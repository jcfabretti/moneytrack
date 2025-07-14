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
                            {--data_inicial= : Data inicial no formatoYYYY-MM-DD (padrão: 1º dia do ano atual)}
                            {--data_final= : Data final no formatoYYYY-MM-DD (padrão: último dia do ano atual)}';

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
        // Removido: Log::info('Iniciando o comando fluxocaixa:process-monthly (Detalhado - tipos_planocontas_id dinâmico).');
        // Removido: $this->info("Iniciando processamento de Fluxo de Caixa (Detalhado - tipos_planocontas_id dinâmico)...");

        $tipoDadoFluxoCaixa = 'FluxoCaixaCategoria';
        $tipoDadoFluxoCaixaTotal = 'FluxoCaixaTotal';

        // Parâmetros do comando
        $empresaId = $this->option('empresa');
        $dataInicialInput = $this->option('data_inicial');
        $dataFinalInput = $this->option('data_final');

        // Removido: Log::info("Parâmetros de entrada: Empresa ID: " . ($empresaId ?? 'Todas'), [ ... ]);

        $empresas = new Collection();

        if ($empresaId) {
            // Removido: Log::info("Tentando encontrar empresa com ID: {$empresaId}");
            $empresa = Empresa::find($empresaId);
            if ($empresa) {
                $empresas->push($empresa);
                // Removido: $this->info("Empresa {$empresa->nome} (ID: {$empresa->id}) encontrada.");
                // Removido: Log::info("Empresa encontrada: {$empresa->nome} (ID: {$empresa->id}).");
            } else {
                $this->error("Empresa com ID {$empresaId} não encontrada.");
                Log::error("Erro: Empresa com ID {$empresaId} não encontrada.");
                return Command::FAILURE;
            }
        } else {
            $empresas = Empresa::all();
            // Removido: Log::info('Processando todas as empresas.');
        }

        if ($empresas->isEmpty()) {
            $this->error('Nenhuma empresa encontrada para processar.');
            Log::warning('Nenhuma empresa encontrada para processar.');
            return Command::FAILURE;
        }

        foreach ($empresas as $empresa) {
            // AQUI ESTÁ A MUDANÇA CRUCIAL: PEGAR O tipos_planocontas_id DA EMPRESA
            $fkTipoCategoriaIdDaEmpresa = $empresa->tipos_planocontas_id;

            if (is_null($fkTipoCategoriaIdDaEmpresa)) {
                $this->warn("Empresa {$empresa->nome} (ID: {$empresa->id}) não possui tipos_planocontas_id definido. Pulando.");
                Log::warning("Empresa {$empresa->id} não possui tipos_planocontas_id definido. Pulando.");
                continue; // Pula para a próxima empresa
            }

            // Removido: $this->info("Processando Fluxo de Caixa para a empresa: {$empresa->nome} (ID: {$empresa->id}) com tipos_planocontas_id: {$fkTipoCategoriaIdDaEmpresa}");
            // Removido: Log::info("Iniciando processamento para empresa: {$empresa->nome} (ID: {$empresa->id}) com tipos_planocontas_id: {$fkTipoCategoriaIdDaEmpresa}.");

            // Define o período: se não for passado, usa o ano atual completo
            $dataInicial = $dataInicialInput ? Carbon::parse($dataInicialInput) : Carbon::now()->startOfYear();
            $dataFinal = $dataFinalInput ? Carbon::parse($dataFinalInput) : Carbon::now()->endOfYear();

            // Ajuste para garantir que a data inicial e final estejam dentro do mesmo ano fiscal
            $dataInicial = $dataInicial->startOfYear();
            $dataFinal = $dataFinal->endOfYear();

            // Removido: Log::info("Período de processamento: Data Inicial: {$dataInicial->toDateString()}, Data Final: {$dataFinal->toDateString()}");

            try {
                // Chama a stored procedure
                // Removido: $this->info("Chamando stored procedure com Empresa ID: {$empresa->id}, Data Inicial: {$dataInicial->toDateString()}, Data Final: {$dataFinal->toDateString()}, Tipo Categoria ID: {$fkTipoCategoriaIdDaEmpresa}");
                // Removido: Log::info("Chamando SP 'sp_gerar_fluxo_caixa_agregado' com parâmetros:", [ ... ]);

                $results = DB::select("CALL sp_gerar_fluxo_caixa_agregado(?, ?, ?, ?)", [
                    $empresa->id,
                    $dataInicial->toDateString(),
                    $dataFinal->toDateString(),
                    $fkTipoCategoriaIdDaEmpresa
                ]);

                // Removido: Log::info('Resultado da stored procedure sp_gerar_fluxo_caixa_agregado:', ['count' => count($results), 'first_row_sample' => collect($results)->first()]);

                if (empty($results)) {
                    $this->warn("Nenhum dado retornado pela stored procedure para empresa {$empresa->nome} no período {$dataInicial->format('Y-m-d')} a {$dataFinal->format('Y-m-d')} com tipos_planocontas_id {$fkTipoCategoriaIdDaEmpresa}.");
                    Log::info("SP retornou 0 resultados para empresa {$empresa->id} com tipos_planocontas_id {$fkTipoCategoriaIdDaEmpresa}.");
                    continue; // Pula para a próxima empresa
                }

                // Arrays para armazenar os totais mensais para cálculo do Total Geral
                $monthlyTotals = array_fill(1, 12, 0.00); // Indexado de 1 a 12 para os meses

                // Limpar a tabela ValoresMensais para esta empresa e período antes de inserir novos dados
                ValoresMensais::where('empresa_id', $empresa->id)
                                  ->where(DB::raw("SUBSTRING_INDEX(mes_ano, '/', -1)"), $dataInicial->year)
                                  ->whereIn('tipo_dado', [$tipoDadoFluxoCaixa, $tipoDadoFluxoCaixaTotal])
                                  ->delete();
                // Removido: Log::info('Dados antigos da tabela valores_mensais para a empresa ' . $empresa->id . ' e ano ' . $dataInicial->year . ' (apenas tipos Fluxo de Caixa) foram excluídos.');
                // Removido: $this->info("Limpando dados antigos para empresa {$empresa->nome} no ano {$dataInicial->year} (apenas tipos Fluxo de Caixa).");


                foreach ($results as $row) {
                    $nivel = $row->nivel;
                    $categoriaId = property_exists($row, 'id') ? (string)$row->id : '';
                    $nomeCategoria = $row->nome_categoria;

                    // Itera sobre os 12 meses retornados pela SP
                    for ($i = 1; $i <= 12; $i++) {
                        $mesFieldName = "mes_" . $i;
                        if (!property_exists($row, $mesFieldName)) {
                            Log::warning("Coluna {$mesFieldName} não encontrada na linha da SP para categoria {$categoriaId}.");
                            continue;
                        }
                        $valor = (float) $row->$mesFieldName; // Converte para float

                        // Calcula o mes_ano a partir da data inicial e do índice do mês
                        $currentMonth = Carbon::createFromDate($dataInicial->year, $i, 1);
                        $mesAnoFormatado = $currentMonth->format('m/Y');

                        // Lógica para extrair o 5º dígito da direita para a esquerda (posição -5, comprimento 1)
                        $posicaoX = 5;
                        $digitoNaPosicaoX = substr($categoriaId, -$posicaoX, 1);
                        if (empty($digitoNaPosicaoX) || !is_numeric($digitoNaPosicaoX)) {
                            $digitoNaPosicaoX = '0';
                        }

                        // NOVO FORMATO PARA item_nome: 'X - Nome da Categoria'
                        $itemNomeFormatado = "{$digitoNaPosicaoX} - {$nomeCategoria}";

                        // Grava apenas as linhas de nível 1 (categorias principais)
                        if ($nivel === 1) {
                            $dataToInsert = [
                                'empresa_id' => $empresa->id,
                                'mes_ano' => $mesAnoFormatado,
                                'tipo_dado' => $tipoDadoFluxoCaixa,
                                'item_id' => $categoriaId,
                                'nome_empresa' => $empresa->nome,
                                'item_nome' => $itemNomeFormatado,
                                'quantidade_numerica' => null,
                                'valor_monetario' => $valor,
                            ];
                            ValoresMensais::updateOrCreate(
                                ['empresa_id' => $empresa->id, 'mes_ano' => $mesAnoFormatado, 'tipo_dado' => $tipoDadoFluxoCaixa, 'item_id' => $categoriaId],
                                $dataToInsert
                            );
                            // Removido: Log::info("Inserido/Atualizado Categoria '{$itemNomeFormatado}' para {$mesAnoFormatado}: {$valor}", $dataToInsert);
                            // Removido: $this->info("  -> Gravando Categoria '{$itemNomeFormatado}' para {$mesAnoFormatado}: {$valor}");

                            // Soma para o Total Geral do mês (apenas categorias de nível 1 para evitar duplicidade de soma)
                            $monthlyTotals[$i] += $valor;
                        }
                    }
                }

                // Após processar todas as linhas, salve o Total Geral para cada mês
                foreach ($monthlyTotals as $i => $totalGeralDoMes) {
                    $currentMonth = Carbon::createFromDate($dataInicial->year, $i, 1);
                    $mesAnoFormatado = $currentMonth->format('m/Y');

                    $dataToInsertTotal = [
                        'empresa_id' => $empresa->id,
                        'mes_ano' => $mesAnoFormatado,
                        'tipo_dado' => $tipoDadoFluxoCaixaTotal,
                        'item_id' => null,
                        'nome_empresa' => $empresa->nome,
                        'item_nome' => 'Total Geral de Fluxo de Caixa',
                        'quantidade_numerica' => null,
                        'valor_monetario' => $totalGeralDoMes,
                    ];
                    ValoresMensais::updateOrCreate(
                        ['empresa_id' => $empresa->id, 'mes_ano' => $mesAnoFormatado, 'tipo_dado' => $tipoDadoFluxoCaixaTotal, 'item_id' => null],
                        $dataToInsertTotal
                    );
                    // Removido: Log::info("Inserido/Atualizado Total Geral de Fluxo de Caixa para {$mesAnoFormatado}: {$totalGeralDoMes}", $dataToInsertTotal);
                    // Removido: $this->info("  -> Gravando Total Geral de Fluxo de Caixa para {$mesAnoFormatado}: {$totalGeralDoMes}");
                }

                // Removido: $this->info("Dados de Fluxo de Caixa processados e gravados para {$empresa->nome}.");
                // Removido: Log::info("Processamento de Fluxo de Caixa concluído para empresa {$empresa->id}.");

            } catch (\Exception $e) {
                $this->error("Erro ao processar Fluxo de Caixa para empresa {$empresa->nome}: " . $e->getMessage());
                Log::error("Erro em ProcessFluxoCaixaToValoresMensais para empresa {$empresa->nome}: " . $e->getMessage(), [
                    'exception' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    // 'data' => $request->all(), // <-- LINHA REMOVIDA/COMENTADA
                ]);
                return Command::FAILURE;
            }
        }
        // Removido: $this->info("Processamento de Fluxo de Caixa concluído para todas as empresas.");
        // Removido: Log::info("Comando fluxocaixa:process-monthly finalizado.");
        return Command::SUCCESS;
    }
}

