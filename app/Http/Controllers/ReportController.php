<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPJasper\PHPJasper;
use Illuminate\Support\Facades\Log; // Importe a classe Log
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // utiliza Empresas da Shared View EMPRESAS
        return view('relatorios.index-relatorios');
    }

    public function gerar_fluxo_caixa(Request $request)
    {

        // dd($request);

        $empresaId = $request->input('empresa_select');
        $empresaNome=$request->input('empresa_nome');
        $dataInicial = $request->input('lcto_dataInicial');
        $dataFinal = $request->input('lcto_dataFinal');
        $fkTipoCategoriaId = $request->input('colecaoCategoria_id');

        try {
            // Chama a stored procedure diretamente usando DB::select
            // DB::select retorna um array de objetos ou um array vazio
            $resultados = DB::select('CALL sp_gerar_fluxo_caixa_agregado(?, ?, ?, ?)', [
                $empresaId,
                $dataInicial,
                $dataFinal,
                $fkTipoCategoriaId
            ]);

            // Mapeia os nomes das colunas de meses (display_mes_X) para usar como cabeçalhos na tabela
            // Pega o primeiro resultado (se houver) para obter os nomes dos meses
            $mesesDisplay = [];
            if (!empty($resultados)) {
                // Não precisamos mais do $primeiroResultado['display_mes_X'] se vamos gerar aqui
                // Apenas precisamos de um ano de referência para construir as datas
                $anoDeReferencia = 2025; // **ATENÇÃO: Substitua isso pelo ano correto do seu relatório!**

                for ($i = 1; $i <= 12; $i++) {
                    // Cria uma data para o mês e ano, define o locale para pt_BR
                    // e formata para "Mês/Ano" (ex: "Jan/2025")
                    $mesesDisplay[] = Carbon::createFromDate($anoDeReferencia, $i, 1)
                        ->locale('pt_BR')
                        ->isoFormat('MMM/YYYY');
                    // Se preferir apenas "Jan", "Fev", use: ->locale('pt_BR')->shortMonthName;
                }
            }
        // Formate as datas para o formato desejado antes de passá-las para a view
        $dataInicial =Carbon::parse($request->input('lcto_dataInicial'))->format('d/m/y');
        $dataFinal =Carbon::parse($request->input('lcto_dataFinal'))->format('d/m/y');

            return view('relatorios.fluxocaixa', compact('resultados', 'mesesDisplay','empresaNome','dataInicial', 'dataFinal'));
        } catch (\Exception $e) {
            // Em caso de erro, redireciona de volta com uma mensagem de erro
            return redirect()->back()->withInput($request->all())->with('error', 'Erro ao gerar o fluxo de caixa: ' . $e->getMessage());
        }
    }



    public function createReport(Request $request, string $reportName)
    {
        // Garante que o nome do arquivo JRXML é completo
        $fullReportNameJrxml = preg_replace('/\.jrxml$/i', '', $reportName) . '.jrxml';

        // Extrai o nome base do relatório, removendo qualquer extensão que possa existir
        $baseReportName = pathinfo($reportName, PATHINFO_FILENAME);

        // Constrói o nome de arquivo único com timestamp e UMA ÚNICA extensão .pdf
        $timestamp = date('dmY_His'); // Ex: 20250606_154821
        $uniquePdfFileName = $baseReportName . '_' . $timestamp;
        //  $uniquePdfFileName = time() . '_' . $baseReportName;

        // Define o diretório completo onde o PDF será salvo dentro de storage/app/public
        $outputDirectory = storage_path('app/public/reports_temp');

        // Define o caminho completo do arquivo PDF de saída que será passado para PHPJasper
        $fullOutputPdfPath = $outputDirectory . '/' . $uniquePdfFileName;

        // --- Ajuste 1: Popular $reportParams dinamicamente ---
        $reportParams = [];

        // Verifica se há parâmetros na query string.
        // Se a URL for tipo: /createrelatorios/LancamentoPorData?Parameter1=valor1&Parameter2=valor2
        // ou se for: /createrelatorios/LancamentoPorData?data_inicial=valor&data_final=valor
        // Ajuste conforme os nomes dos seus parâmetros na URL
        foreach ($request->query() as $key => $value) {
            // Você pode adicionar uma lógica aqui para mapear nomes de parâmetros da URL para nomes de parâmetros do JasperReports
            // Por exemplo, se a URL usa 'data_inicial' e o JRXML usa 'Parameter2'
            if ($key === 'data_inicial') {
                $reportParams['Parameter2'] = $value;
            } elseif ($key === 'data_final') {
                $reportParams['Parameter1'] = $value;
            } else {
                // Se o nome na URL for o mesmo que no JRXML, apenas adicione
                $reportParams[$key] = $value;
            }
        }
        $reportParams['REPORT_IMAGE_DIR'] = str_replace('\\', '/', public_path('images')) . '/';
        // $reportParams['REPORT_IMAGE_DIR'] = public_path('images') . '/';
        //       $reportParams['REPORT_EMPRESA'] = request()->empresa_id ?? '1'; // Use 'default' ou outro valor padrão se não houver empresa na requisição

        Log::info('Parâmetros do relatório a serem usados:', $reportParams);
        // --- Fim do Ajuste 1 ---


        // Garante que o diretório temporário exista
        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }

        $report = new PHPJasper();
        try {
            $report->process(
                public_path('reports') . '/' . $fullReportNameJrxml, // Caminho do arquivo JRXML
                $fullOutputPdfPath, // Passamos o CAMINHO COMPLETO DO ARQUIVO de saída
                [
                    'format' => ['pdf'],
                    'params' => $reportParams, // Agora $reportParams será [] ou conterá os parâmetros da URL
                    'db_connection' => $this->getDatabaseConfig()
                ]
            )->execute(); // Execute o comando após o processamento

        } catch (\Exception $e) {
            Log::error('JasperStarter processing failed (Exception): ' . $e->getMessage(), [
                'command_output' => $report->output(),
                'exception_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Erro ao gerar o relatório: ' . $e->getMessage(),
                'details' => $report->output()
            ], 500);
        } catch (\Throwable $e) {
            Log::error('JasperStarter processing failed (Throwable): ' . $e->getMessage(), [
                'command_output' => $report->output(),
                'exception_trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Erro crítico ao gerar o relatório: ' . $e->getMessage(),
                'details' => $report->output()
            ], 500);
        }

        // --- VERIFICAÇÃO PÓS-PROCESSAMENTO ---
        $finalPdfPath = $fullOutputPdfPath . '.pdf'; // PHPJasper adiciona .pdf automaticamente

        if (!file_exists($finalPdfPath)) {
            Log::error('JasperStarter failed to create the final PDF file at the expected path (after process call).', [
                'expected_path' => $finalPdfPath,
                'jasper_command_output_on_fail' => $report->output()
            ]);
            return response()->json([
                'message' => 'O arquivo PDF final não foi gerado pelo JasperStarter no caminho esperado.',
                'esperado_em' => $finalPdfPath,
                'jasper_output' => $report->output()
            ], 500);
        }

        // --- Ajuste 2: Alterar a exibição para download ---
        Log::info('Report generated successfully.', [
            'jasper_command_output' => $report->output(), // output() contém o comando gerado
            'generated_pdf_at' => $finalPdfPath
        ]);

        return response()->download($finalPdfPath, $uniquePdfFileName . '.pdf')->deleteFileAfterSend(true);
        // --- Fim do Ajuste 2 ---
    }

    /**
     * Retorna a configuração do banco de dados para o JasperReports.
     * @return array
     */
    public function getDatabaseConfig(): array
    {
        $db_connection = [
            'driver' => 'mysql', // Esta chave 'driver' aqui não é usada pelo PHPJasper para o argumento '-t', mas pode ser para referência interna.
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'financv2',
            'username' => 'root',
            'password' => '', // Mantenha vazio se não houver senha
            'jdbc_driver' => 'com.mysql.cj.jdbc.Driver',
            'jdbc_url' => 'jdbc:mysql://127.0.0.1:3306/financv2', // Verifique se o nome do banco está correto (financv2 ou financ_v2)
            'jdbc_dir' => base_path() . '/vendor/lavela/phpjasper/src/JasperStarter/jdbc',
        ];

        if (isset($db_connection['password']) && $db_connection['password'] === '') {
            unset($db_connection['password']);
        }

        return $db_connection;
    }
}

/*
jasperstarter process \
"C:\laragon\www\moneytrackrep\public\reports/LancamentoPorData.jrxml" \
-f pdf \
-o "C:\laragon\www\moneytrackrep\storage\app/public/reports_temp/1749045362_LancamentoPorData.pdf" \
-P Parameter1="2025-06-04" \
-P Parameter2="2025-05-02" \
-t com.mysql.cj.jdbc.Driver \
-H 127.0.0.1 \
--db-port 3306 \
-n financv2 \
-u root \
--pass SUA_SENHA_AQUI \
--db-driver com.mysql.cj.jdbc.Driver \
--db-url "jdbc:mysql://127.0.0.1:3306/financv2?useUnicode=true&characterEncoding=UTF-8&useSSL=false&serverTimezone=UTC" \
--jdbc-dir "C:\laragon\www\moneytrackrep/vendor/lavela/phpjasper/src/JasperStarter/jdbc"


$jasper = new JasperPHP;

$input =  preg_replace('/\.jrxml$/i', '', $reportName) . '.jrxml'; 
$output = '/your_output_path';
$extension= 'pdf';
$locale = 'pt_BR';

$jasper->process(
    $input,
    $output,
    $extension, 
    [
        'parameter_1' => 'title',
        'parameter_2' => 'name',
    ],
    [
        'driver' => 'postgres',
        'username' => 'DB_USERNAME',
        'password' => 'DB_PASSWORD',
        'host' => 'DB_HOST',
        'database' => 'DB_DATABASE',
        'schema' => 'DB_SCHEMA',
        'port' => '5432'
     ],
    $locale
)->execute();


https://github.com/PHPJasper/phpjasper/issues/3
 
*/