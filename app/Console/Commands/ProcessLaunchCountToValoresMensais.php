<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ValoresMensais;
use App\Models\Empresa;
use Illuminate\Support\Collection;

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
        $targetYear = $this->option('year') ?? Carbon::now()->year;
        $empresaId = $this->option('empresa');
        $tipoDado = 'ContagemLancamentos';

        $this->info("Iniciando processamento de Contagem de Lançamentos para o ano {$targetYear}...");

        $empresas = new Collection(); // Inicializa como uma coleção vazia

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
            $this->warn("Nenhuma empresa para processar a contagem de lançamentos.");
            return Command::SUCCESS;
        }

        foreach ($empresas as $empresa) {
            for ($month = 1; $month <= 12; $month++) {
                // Lógica para calcular o total de lançamentos do mês
                $totalDeLancamentosDoMes = DB::table('lancamentos') // <<== Confirme o nome da tabela 'lancamentos'
                                            ->where('empresa_id', $empresa->id)
                                            ->whereYear('data_lcto', $targetYear) // <<== Usando 'data_lcto'
                                            ->whereMonth('data_lcto', $month)    // <<== Usando 'data_lcto'
                                            ->count();

                $mesAnoFormatado = Carbon::createFromDate($targetYear, $month, 1)->format('m/Y');

                ValoresMensais::updateOrCreate(
                    [
                        'empresa_id' => $empresa->id,
                        'mes_ano' => $mesAnoFormatado,
                        'tipo_dado' => $tipoDado,
                        'item_id' => null, // 'item_id' é nulo para este tipo de dado de total
                    ],
                    [
                        'nome_empresa' => $empresa->nome,
                        'item_nome' => 'Total de Lançamentos', // 'item_nome' descreve o total
                        'quantidade_numerica' => $totalDeLancamentosDoMes,
                        'valor_monetario' => null,
                    ]
                );
            }
            $this->info("Contagem de lançamentos processada para empresa {$empresa->nome}.");
        }

        $this->info("Processamento de Contagem de Lançamentos concluído para o ano {$targetYear}.");
        return Command::SUCCESS;
    }
}