<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPJasper\PHPJasper;
use Illuminate\Support\Facades\Log; // Importe a classe Log
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException; // Importe a classe ValidationException


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

   /**
     * Gera o relatório com base no tipo selecionado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function generateReport(Request $request)
    {
        // Valide os dados da requisição
        $request->validate([
            'relatorio_select' => 'required|numeric|min:1|max:4',
            'empresa_select' => 'nullable|numeric',
            'lcto_dataInicial' => 'nullable|date',
            'lcto_dataFinal' => 'nullable|date|after_or_equal:lcto_dataInicial',
            'categorias_id' => 'nullable|numeric', // Pode ser nulo se 'Todos' for marcado
            'all_categories_checkbox' => 'nullable|string', // Checkbox envia 'on' ou nada
            'conta_partida' => 'nullable|numeric',
            'colecaoCategoria_id' => 'nullable|string', // Adicionado para capturar o codPlanoCategoria
        ]);

        $reportType = $request->input('relatorio_select');
        $reportParams = []; // Inicializa o array de parâmetros aqui
        $reportName = '';   // Inicializa o nome do relatório aqui

        // Parâmetros comuns a todos os relatórios
        if ($request->filled('empresa_select')) {
            $reportParams['REPORT_EMPRESA'] = $request->input('empresa_select');
        }
        $reportParams['REPORT_IMAGE_DIR'] = str_replace('\\', '/', public_path('images')) . '/';


        switch ($reportType) {
            case '1': // 1. Fluxo de Caixa
                $fluxoCaixaController = new FluxoCaixaController();
                return $fluxoCaixaController->gerar($request);
                break;

            case '2': // 2. Lançamentos por Data
                $reportName = 'lancamentos_por_data';
                // Mapeamento: Parameter2=Data Inicial, Parameter1=Data Final
                if ($request->filled('lcto_dataInicial')) {
                    $reportParams['Parameter1'] = $request->input('lcto_dataInicial');
                }
                if ($request->filled('lcto_dataFinal')) {
                    $reportParams['Parameter2'] = $request->input('lcto_dataFinal');
                }
                break;

            case '3': // 3. Lançamento por Categoria
                // Lógica para escolher entre "uma categoria" ou "todas as categorias"
                if ($request->has('all_categories_checkbox') && $request->input('all_categories_checkbox') === 'on') {
                    $reportName = 'lancamentos_por_todas_categoria'; // JRXML para todas as categorias
                    // Este JRXML não usa Parameter3, então não o adicionamos a $reportParams
                } else {
                    $reportName = 'lancamentos_por_uma_categoria'; // JRXML para uma categoria
                    // Se o checkbox NÃO foi marcado, a categoria_id é obrigatória
                    if (!$request->filled('categorias_id')) {
                        throw ValidationException::withMessages([
                            'categorias_id' => 'O ID da categoria é obrigatório quando "Todas as Categorias" não está marcado.'
                        ]);
                    }

                    // *** LÓGICA DE CONCATENAÇÃO AQUI ***
                    $codPlanoCategoria = $request->input('colecaoCategoria_id');
                    $categoriaDigitada = $request->input('categorias_id');

                    // Concatena codPlanoCategoria com o valor digitado da categoria
                    $reportParams['Parameter3'] = $codPlanoCategoria . $categoriaDigitada;
                }

                // Parâmetros de data para ambos os relatórios de categoria
                if ($request->filled('lcto_dataInicial')) {
                    $reportParams['Parameter1'] = $request->input('lcto_dataInicial');
                }
                if ($request->filled('lcto_dataFinal')) {
                    $reportParams['Parameter2'] = $request->input('lcto_dataFinal');
                }
                break;

            case '4': // 4. Movimentação por Categoria-Banco
                $reportName = 'movimentacao_por_categoria_banco';
                // Você precisará definir os parâmetros para este relatório
                if ($request->filled('lcto_dataInicial')) {
                    $reportParams['PARAM_DATA_INICIAL'] = $request->input('lcto_dataInicial');
                }
                if ($request->filled('lcto_dataFinal')) {
                    $reportParams['PARAM_DATA_FINAL'] = $request->input('lcto_dataFinal');
                }
                if ($request->filled('categorias_id')) {
                    // Se Movimentacao_por_categoria_banco também usa a concatenação,
                    // você precisará replicar a lógica de concatenação aqui.
                    $codPlanoCategoria = $request->input('colecaoCategoria_id');
                    $categoriaDigitada = $request->input('categorias_id');
                    $reportParams['PARAM_CATEGORIA'] = $codPlanoCategoria . $categoriaDigitada;
                }
                if ($request->filled('conta_partida')) {
                    $reportParams['PARAM_CONTA'] = $request->input('conta_partida');
                }
                break;

            default:
                return back()->withErrors(['relatorio' => 'Tipo de relatório inválido.']);
                break;
        }

        // Se um reportName foi definido, chame generateJasperReport
        if (!empty($reportName)) {
            return $this->generateJasperReport($reportName, $reportParams);
        } else {
            // Caso nenhum relatório válido tenha sido selecionado/processado
            return back()->withErrors(['relatorio' => 'Não foi possível determinar o relatório a ser gerado.']);
        }
    }


    public function gerar_fluxo_caixa(Request $request)
    {
        $empresaId = $request->input('empresa_select');
        $empresaNome=$request->input('empresa_nome');
        $dataInicial = Carbon::parse($request->input('lcto_dataInicial'))->format('Y-m-d'); // Formato Y-m-d para DB
        $dataFinal = Carbon::parse($request->input('lcto_dataFinal'))->format('Y-m-d');     // Formato Y-m-d para DB
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
        $dataInicialDisplay =Carbon::parse($request->input('lcto_dataInicial'))->format('d/m/y');
        $dataFinalDisplay =Carbon::parse($request->input('lcto_dataFinal'))->format('d/m/y');

            return view('relatorios.fluxocaixa', compact('resultados', 'mesesDisplay','empresaNome','dataInicialDisplay', 'dataFinalDisplay'));
        } catch (\Exception $e) {
            // Em caso de erro, redireciona de volta com uma mensagem de erro
            return redirect()->back()->withInput($request->all())->with('error', 'Erro ao gerar o fluxo de caixa: ' . $e->getMessage());
        }
    }

    protected function generateJasperReport(string $reportName, array $reportParams)
    {
        $fullReportNameJrxml = preg_replace('/\.jrxml$/i', '', $reportName) . '.jrxml';
        $baseReportName = pathinfo($reportName, PATHINFO_FILENAME);
        $timestamp = date('dmY_His');
        $uniquePdfFileName = $baseReportName . '_' . $timestamp;

        $outputDirectory = storage_path('app/public/reports_temp');
        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }
        $fullOutputPdfPath = $outputDirectory . '/' . $uniquePdfFileName;
        $finalPdfPath = $fullOutputPdfPath . '.pdf';
        Log::info('Parametros do relatorio a serem usados:', $reportParams);


        $report = new PHPJasper();

        try {
            $dbConnectionConfig = $this->getDatabaseConfig();

            // Caminho do executável jasperstarter (isso está correto)
            $jasperExecutablePath = base_path('vendor/lavela/phpjasper/bin/jasperstarter/bin/jasperstarter');

            $options = [
                'format' => ['pdf'], // A chave deve ser 'format', não '-f'
                'params' => $reportParams,
                'db_connection' => [
                    'driver'        => $dbConnectionConfig['driver'],
                    'host'          => $dbConnectionConfig['host'],
                    'port'          => $dbConnectionConfig['port'],
                    'database'      => $dbConnectionConfig['database'],
                    'username'      => $dbConnectionConfig['username'],
                    'password'      => $dbConnectionConfig['password'],
                    'jdbc_driver'   => $dbConnectionConfig['jdbc_driver'],
                    'jdbc_url'      => $dbConnectionConfig['jdbc_url'],
                    'jdbc_dir'      => $dbConnectionConfig['jdbc_dir'],
                ],
                // A chave 'executable_path' não é uma opção padrão para o método process().
                // O PHPJasper já sabe onde está o executável por seu construtor ou por padrão.
                // Remova esta linha:
                // 'executable_path' => $jasperExecutablePath,
            ];

            // Log das opções para depuração (este log é útil!)
            Log::info('Opcoes do JasperStarter a serem usadas:', $options);

            // --- ESTE É O PONTO CRÍTICO: CHAME PROCESS() PRIMEIRO ---
            $report->process(
                public_path('reports') . '/' . $fullReportNameJrxml,
                $fullOutputPdfPath,
                $options
            );

            // --- AGORA VOCÊ PODE CHAMAR output() PARA VER O COMANDO COMPLETO ---
            Log::info("Comando FINAL do JasperStarter (para debug): " . $report->output());

            $report->execute();

            // Lógica de sucesso aqui
            Log::info('Report generated successfully.', [
                'jasper_command_output' => $report->output(), // Pode logar aqui também, se desejar
                'generated_pdf_at' => $finalPdfPath
            ]);
            return response()->download($finalPdfPath, $uniquePdfFileName . '.pdf')->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Se o erro acontecer AQUI, o output() já terá o comando que causou o problema
            $rawOutput = $report->output();
            $cleanedOutput = mb_convert_encoding($rawOutput, 'UTF-8', 'UTF-8');
            $cleanedOutput = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $cleanedOutput);

            if (str_contains($e->getMessage(), 'WARN: Establishing SSL connection')) {
                Log::warning('Jasper report generated with SSL warning.');
            } else {
                // Logue a exceção original e a saída do comando aqui
                Log::error('JasperStarter processing failed (Exception): ' . $e->getMessage(), [
                    'command_output' => $rawOutput,
                    'cleaned_output' => $cleanedOutput,
                    'exception_trace' => $e->getTraceAsString()
                ]);
            }
            // Retorne a resposta de erro
            return response()->json([
                'message' => 'Erro ao gerar o relatório: ' . $e->getMessage(),
                'details' => $cleanedOutput
            ], 500);
        } catch (\Throwable $e) {
            // Este catch de Throwable é para erros mais graves que não são Exceptions
            // Aqui, o output() também deve ter o comando que causou o problema
            $rawOutput = $report->output();
            $cleanedOutput = mb_convert_encoding($rawOutput, 'UTF-8', 'UTF-8');
            $cleanedOutput = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $cleanedOutput);

            Log::error('JasperStarter processing failed (Throwable): ' . $e->getMessage(), [
                'command_output' => $rawOutput,
                'cleaned_output' => $cleanedOutput,
                'exception_trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erro crítico ao gerar o relatório: ' . $e->getMessage(),
                'details' => $cleanedOutput
            ], 500);
        }

        // Este bloco é para verificar se o arquivo PDF existe após a execução bem-sucedida,
        // mas antes do download.

        if (!file_exists($finalPdfPath)) {
            $rawOutput = $report->output(); // O output() ainda estará disponível
            $cleanedOutput = mb_convert_encoding($rawOutput, 'UTF-8', 'UTF-8');
            $cleanedOutput = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $cleanedOutput);

            Log::error('JasperStarter failed to create the final PDF file at the expected path (after process call).', [
                'expected_path' => $finalPdfPath,
                'jasper_command_output_on_fail' => $rawOutput,
                'cleaned_output_on_fail' => $cleanedOutput
            ]);
            return response()->json([
                'message' => 'O arquivo PDF final não foi gerado pelo JasperStarter no caminho esperado.',
                'esperado_em' => $finalPdfPath,
                'jasper_output' => $cleanedOutput
            ], 500);
        }

        Log::info('Report generated successfully.', [
            'jasper_command_output' => $report->output(),
            'generated_pdf_at' => $finalPdfPath
        ]);

        return response()->download($finalPdfPath, $uniquePdfFileName . '.pdf')->deleteFileAfterSend(true);
    }

    /**
     * Retorna a configuração do banco de dados para o JasperReports.
     * @return array
     */
    public function getDatabaseConfig(): array
    {
        // Define o nome do banco de dados, usando 'mtrack' como padrão se não estiver no .env
        $databaseName = env('DB_DATABASE', 'mtrack');
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        // Define o nome de usuário do banco de dados, usando 'fabrettidev' como padrão
        $dbUsername = env('DB_USERNAME', 'fabrettidev');
        // Define a senha do banco de dados, obtendo do .env (pode ser vazia)
        $dbPassword = env('DB_PASSWORD', ''); 

        // Constrói a URL JDBC completa para a conexão MySQL.
        // Inclui parâmetros importantes para compatibilidade e codificação de caracteres.
        // - useUnicode=true: Habilita o uso de caracteres Unicode.
        // - characterEncoding=UTF-8: Define a codificação de caracteres para UTF-8.
        // - useSSL=false: Desabilita SSL (muitas vezes necessário em ambientes de desenvolvimento/locais).
        // - serverTimezone=UTC: Define o fuso horário do servidor, importante para consistência de datas.
        $jdbcUrl = 'jdbc:mysql://' . $dbHost . ':' . $dbPort . '/' . $databaseName ;

        // Garante que o caminho do diretório JDBC use barras normais (/)
        // para compatibilidade entre sistemas operacionais (Windows/Linux).
        // Este diretório deve conter o arquivo JAR do driver JDBC do MySQL (ex: mysql-connector-j-X.X.X.jar).
        $jdbcDir = str_replace('\\', '/', base_path() . '/vendor/lavela/phpjasper/bin/jasperstarter/jdbc');
                                
        // A lógica de escape para Windows (`if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')`)
        // foi removida. A biblioteca PHPJasper deve lidar com o escape de argumentos
        // para o shell usando `escapeshellarg()` internamente. Se o problema de
        // interpretação de caracteres especiais persistir no servidor Linux,
        // isso indica uma limitação do ambiente do shell ou da versão do jasperstarter
        // que precisará de uma solução mais avançada (ex: arquivo de propriedades do Jasper).

        // Configuração da conexão para a biblioteca PHPJasper.
        // O 'driver' deve ser 'generic' quando você está fornecendo uma 'jdbc_url' completa,
        // pois isso dá ao jasperstarter mais controle sobre a conexão.
        // 'jdbc_driver' é o nome da classe Java do driver JDBC (o novo driver recomendado).
        // 'jdbc_url' é a string de conexão completa para o banco de dados.
        // 'jdbc_dir' é o caminho para o diretório que contém o driver JDBC.
        $db_connection = [
            'driver' => 'generic', // Tipo de driver para o PHPJasper (funciona com o comando manual)
            'host' => $dbHost,
            'port' => $dbPort,
            'database' => $databaseName,
            'username' => $dbUsername,
            'password' => $dbPassword, // A biblioteca PHPJasper espera esta chave para a senha
            'jdbc_driver' => 'com.mysql.cj.jdbc.Driver', // Driver JDBC Java (explicitamente)
            'jdbc_url' => $jdbcUrl, // URL JDBC completa
            'jdbc_dir' => $jdbcDir, // Diretório onde o driver .jar está
        ];

        // O bloco de `unset($db_connection['password'])` foi removido.
        // A chave 'password' deve sempre existir no array, mesmo que seu valor seja uma string vazia.
        // A biblioteca PHPJasper e o jasperstarter lidam corretamente com senhas vazias dessa forma.

        return $db_connection;
    }
}
