<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ValoresMensais;
use App\Models\Empresa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log; // Garante que Log está importado

class ProcessFluxoCaixaToValoresMensais extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fluxocaixa:process-monthly
                            {--empresa= : ID da empresa para processar (opcional)}
                            {--data_inicial= : Data inicial no formato YYYY-MM-DD (padrão: 1º dia do ano atual)}
                            {--data_final= : Data final no formato YYYY-MM-DD (padrão: último dia do ano atual)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa os dados de fluxo de caixa da stored procedure e grava na tabela valores_mensais.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tipoDadoFluxoCaixa = 'FluxoCaixaCategoria'; // Para linhas de nível de categoria
        $tipoDadoFluxoCaixaTotal = 'FluxoCaixaTotal'; // Para o total geral do mês

        // Define o fk_tipocategoria_id para o Fluxo de Caixa (ajuste conforme necessário, ex: 2 para despesas/receitas)
        // ESTE É O QUARTO PARÂMETRO DA SUA STORED PROCEDURE. AJUSTE PARA O VALOR CORRETO SE NECESSÁRIO!
        $fkTipoCategoriaId = 2; // Exemplo: ID que representa "Receitas e Despesas"

        // Parâmetros do comando
        $empresaId = $this->option('empresa');
        $dataInicialInput = $this->option('data_inicial');
        $dataFinalInput = $this->option('data_final');

        $this->info("Iniciando processamento de Fluxo de Caixa...");

        $empresas = new Collection();

        if ($empresaId) {
            $this->info("Tentando encontrar empresa com ID: {$empresaId}");
            $empresa = Empresa::find($empresaId);
            if ($empresa) {
                $empresas->push($empresa);
                $this->info("Empresa {$empresa->nome} (ID: {$empresa->id}) encontrada.");
            } else {
                $this->error("Empresa com ID {$empresaId} não encontrada.");
                return Command::FAILURE;
            }
        } else {
            $empresas = Empresa::all();
        }

        if ($empresas->isEmpty()) {
            $this->error('Nenhuma empresa encontrada para processar.');
            return Command::FAILURE;
        }

        foreach ($empresas as $empresa) {
            $this->info("Processando Fluxo de Caixa para a empresa: {$empresa->nome} (ID: {$empresa->id})");

            // Define o período: se não for passado, usa o ano atual completo
            $dataInicial = $dataInicialInput ? Carbon::parse($dataInicialInput) : Carbon::now()->startOfYear();
            $dataFinal = $dataFinalInput ? Carbon::parse($dataFinalInput) : Carbon::now()->endOfYear();

            // Ajuste para garantir que a data inicial e final estejam dentro do mesmo ano fiscal
            // Se a SP espera um ano completo, ajuste as datas de entrada para abranger o ano
            $dataInicial = $dataInicial->startOfYear();
            $dataFinal = $dataFinal->endOfYear();


            try {
                // Chama a stored procedure
                $this->info("Chamando stored procedure com Empresa ID: {$empresa->id}, Data Inicial: {$dataInicial->toDateString()}, Data Final: {$dataFinal->toDateString()}, Tipo Categoria ID: {$fkTipoCategoriaId}");
                $results = DB::select("CALL sp_gerar_fluxo_caixa_agregado(?, ?, ?, ?)", [
                    $empresa->id,
                    $dataInicial->toDateString(),
                    $dataFinal->toDateString(),
                    $fkTipoCategoriaId
                ]);

                if (empty($results)) {
                    $this->warn("Nenhum dado retornado pela stored procedure para empresa {$empresa->nome} no período {$dataInicial->format('Y-m-d')} a {$dataFinal->format('Y-m-d')}.");
                    continue; // Pula para a próxima empresa
                }

                // Arrays para armazenar os totais mensais para cálculo do Total Geral
                $monthlyTotals = array_fill(1, 12, 0.00); // Indexado de 1 a 12 para os meses

                foreach ($results as $row) {
                    $nivel = $row->nivel;
                    // Certifique-se de que 'id' existe no objeto $row e converta para string se necessário
                    $categoriaId = property_exists($row, 'id') ? (string)$row->id : ''; 
                    $nomeCategoria = $row->nome_categoria;

                    // Itera sobre os 12 meses retornados pela SP
                    for ($i = 1; $i <= 12; $i++) {
                        $mesFieldName = "mes_" . $i;
                        if (!property_exists($row, $mesFieldName)) {
                            // Se a SP não retornar todos os meses (mes_1, mes_2, etc.), pular
                            continue;
                        }
                        $valor = (float) $row->$mesFieldName; // Converte para float

                        // Calcula o mes_ano a partir da data inicial e do índice do mês
                        $currentMonth = Carbon::createFromDate($dataInicial->year, $i, 1); // Garante o ano correto e o mês atual
                        $mesAnoFormatado = $currentMonth->format('m/Y');

                        // Lógica para extrair o 5º dígito da direita para a esquerda (posição -5, comprimento 1)
                        $posicaoX = 5; 
                        $digitoNaPosicaoX = substr($categoriaId, -$posicaoX, 1);
                        // Se o categoriaId for muito curto, o substr pode retornar vazio, então garantimos que seja um dígito
                        if (empty($digitoNaPosicaoX) || !is_numeric($digitoNaPosicaoX)) {
                             $digitoNaPosicaoX = '0'; // Valor padrão se não for um dígito válido ou string muito curta
                        }


                        // NOVO FORMATO PARA item_nome: 'X - Nome da Categoria'
                        $itemNomeFormatado = "{$digitoNaPosicaoX} - {$nomeCategoria}";

                        // Grava apenas as linhas de nível 1 (categorias principais)
                        if ($nivel === 1) {
                            ValoresMensais::updateOrCreate(
                                [
                                    'empresa_id' => $empresa->id,
                                    'mes_ano' => $mesAnoFormatado,
                                    'tipo_dado' => $tipoDadoFluxoCaixa, // 'FluxoCaixaCategoria'
                                    'item_id' => $categoriaId, // ID da categoria da SP
                                ],
                                [
                                    'nome_empresa' => $empresa->nome,
                                    'item_nome' => $itemNomeFormatado, // ATUALIZADO AQUI!
                                    'quantidade_numerica' => null,
                                    'valor_monetario' => $valor,
                                ]
                            );
                            $this->info("  -> Gravando Categoria '{$itemNomeFormatado}' para {$mesAnoFormatado}: {$valor}");

                            // Soma para o Total Geral do mês (apenas categorias de nível 1 para evitar duplicidade de soma)
                            $monthlyTotals[$i] += $valor;
                        }
                    }
                }

                // Após processar todas as linhas, salve o Total Geral para cada mês
                foreach ($monthlyTotals as $i => $totalGeralDoMes) {
                    $currentMonth = Carbon::createFromDate($dataInicial->year, $i, 1);
                    $mesAnoFormatado = $currentMonth->format('m/Y');

                    ValoresMensais::updateOrCreate(
                        [
                            'empresa_id' => $empresa->id,
                            'mes_ano' => $mesAnoFormatado,
                            'tipo_dado' => $tipoDadoFluxoCaixaTotal, // 'FluxoCaixaTotal'
                            'item_id' => null, // Não se aplica a um total geral
                        ],
                        [
                            'nome_empresa' => $empresa->nome,
                            'item_nome' => 'Total Geral de Fluxo de Caixa', // Nome para o item total
                            'quantidade_numerica' => null,
                            'valor_monetario' => $totalGeralDoMes,
                        ]
                    );
                    $this->info("  -> Gravando Total Geral de Fluxo de Caixa para {$mesAnoFormatado}: {$totalGeralDoMes}");
                }

                $this->info("Dados de Fluxo de Caixa processados e gravados para {$empresa->nome}.");
            } catch (\Exception $e) {
                $this->error("Erro ao processar Fluxo de Caixa para empresa {$empresa->nome}: " . $e->getMessage());
                Log::error("Erro em ProcessFluxoCaixaToValoresMensais para empresa {$empresa->nome}: " . $e->getMessage(), [
                        'exception' => $e
                    ]);
                return Command::FAILURE;
            }
        }
        Log::info("Process Fluxo Caixa executado às: " . Carbon::now());
        $this->info("Processamento de Fluxo de Caixa concluído.");
        return Command::SUCCESS;
    }
}
