<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ValoresMensais;
use App\Models\Empresa;
use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Log; // Removido o import de Log

class ProcessLaunchCountToValoresMensais extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lancamentos:process-monthly
                            {--year= : Ano para processar (padrão: ano atual)}
                            {--empresa= : ID da empresa para processar (opcional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa a contagem de lançamentos mensais por empresa e grava na tabela valores_mensais.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Removido: Log::info('*** INICIANDO COMANDO: lancamentos:process-monthly ***');
        // Removido: $this->info("Iniciando processamento de Contagem de Lançamentos...");

        $targetYear = $this->option('year') ?? Carbon::now()->year;
        $empresaId = $this->option('empresa');
        $tipoDado = 'ContagemLancamentos'; // O tipo de dado para este gráfico

        // Removido: Log::info("Parâmetros de entrada para lancamentos:process-monthly:", [ ... ]);

        $empresas = new Collection();

        if ($empresaId) {
            // Removido: $this->info("Tentando encontrar empresa com ID: {$empresaId}");
            // Removido: Log::info("Tentando encontrar empresa com ID: {$empresaId} para lançamentos.");
            $empresa = Empresa::find($empresaId);
            if ($empresa) {
                $empresas->push($empresa);
                // Removido: $this->info("Empresa {$empresa->nome} (ID: {$empresa->id}) encontrada.");
                // Removido: Log::info("Empresa encontrada para lançamentos: {$empresa->nome} (ID: {$empresa->id}).");
            } else {
                $this->error("Empresa com ID {$empresaId} não encontrada.");
                // Removido: Log::error("Erro: Empresa com ID {$empresaId} não encontrada para lançamentos.");
                return Command::FAILURE;
            }
        } else {
            $empresas = Empresa::all();
            // Removido: Log::info('Processando todas as empresas para contagem de lançamentos.');
        }

        if ($empresas->isEmpty()) {
            $this->warn("Nenhuma empresa encontrada para processar a contagem de lançamentos.");
            // Removido: Log::warning("Nenhuma empresa encontrada para processar a contagem de lançamentos.");
            return Command::SUCCESS;
        }

        foreach ($empresas as $empresa) {
            // Removido: $this->info("Processando Contagem de Lançamentos para a empresa: {$empresa->nome} (ID: {$empresa->id}) para o ano {$targetYear}.");
            // Removido: Log::info("Iniciando processamento de contagem de lançamentos para empresa: {$empresa->nome} (ID: {$empresa->id}).");

            // Limpar dados antigos para este tipo específico antes de inserir
            ValoresMensais::where('empresa_id', $empresa->id)
                          ->where(DB::raw("SUBSTRING_INDEX(mes_ano, '/', -1)"), $targetYear)
                          ->where('tipo_dado', $tipoDado)
                          ->delete();
            // Removido: Log::info("Dados antigos da tabela valores_mensais para empresa {$empresa->id} e ano {$targetYear} (tipo {$tipoDado}) foram excluídos.");
            // Removido: $this->info("Limpando dados antigos para empresa {$empresa->nome} no ano {$targetYear} (apenas Contagem de Lançamentos).");


            for ($month = 1; $month <= 12; $month++) {
                // Removido: Log::info("Executando query de contagem para mês {$month} do ano {$targetYear} para empresa {$empresa->id}.");
                $totalDeLancamentosDoMes = DB::table('lancamentos')
                                                ->where('empresa_id', $empresa->id)
                                                ->whereYear('data_lcto', $targetYear)
                                                ->whereMonth('data_lcto', $month)
                                                ->count();
                // Removido: Log::info("Contagem para mês {$month}: {$totalDeLancamentosDoMes} lançamentos.");

                $mesAnoFormatado = Carbon::createFromDate($targetYear, $month, 1)->format('m/Y');

                $dataToInsert = [
                    'empresa_id' => $empresa->id,
                    'mes_ano' => $mesAnoFormatado,
                    'tipo_dado' => $tipoDado,
                    'item_id' => null,
                    'nome_empresa' => $empresa->nome,
                    'item_nome' => 'Total de Lançamentos',
                    'quantidade_numerica' => $totalDeLancamentosDoMes,
                    'valor_monetario' => null,
                ];

                ValoresMensais::updateOrCreate(
                    [
                        'empresa_id' => $empresa->id,
                        'mes_ano' => $mesAnoFormatado,
                        'tipo_dado' => $tipoDado,
                        'item_id' => null,
                    ],
                    $dataToInsert
                );
                // Removido: Log::info("Inserido/Atualizado Contagem de Lançamentos para {$mesAnoFormatado}: {$totalDeLancamentosDoMes}", $dataToInsert);
                // Removido: $this->info("  -> Gravando Contagem de Lançamentos para {$mesAnoFormatado}: {$totalDeLancamentosDoMes}");
            }
            // Removido: $this->info("Contagem de lançamentos processada para empresa {$empresa->nome}.");
            // Removido: Log::info("Processamento de contagem de lançamentos concluído para empresa {$empresa->id}.");
        }

        // Removido: $this->info("Processamento de Contagem de Lançamentos concluído para o ano {$targetYear}.");
        // Removido: Log::info("Comando lancamentos:process-monthly finalizado.");
        return Command::SUCCESS;
    }
}