<?php

namespace App\Http\Controllers;

use App\Models\Lancamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Empresa;
use App\Models\Categoria;
use App\Models\GrupoEconomico;
use App\Models\Parceiro;
use Illuminate\Support\Facades\Auth; // Importante: Adicione esta linha!
use App\Traits\CreatedByUpdatedBy;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LancamentoController extends Controller
{
    use CreatedByUpdatedBy;
    /**
     * Display a listing of the resource.
     */
    public function index1()
    {
        $totalLinhasPagina = Session::get('app.qtyItemsPerPage');
        $empresa_id = Session::get('app.empresa_id'); // Obtém o ID da empresa da sessão

        if (empty($totalLinhasPagina)) {
            $totalLinhasPagina = 10;
        }

        $lancamentos = Lancamento::with('LctoPartida:id,nome', 'LctoContraPartida:id,nome', 'Categoria:id,nome', 'usuarioQueCriou', 'UsuarioQueAtualizou')
            ->where('empresa_id', $empresa_id) // <-- CORREÇÃO AQUI
            ->orderBy('updated_at', 'DESC')
            ->paginate($totalLinhasPagina);

        return view('lancamento.indexLancamento', compact('lancamentos'));
    }

 public function index(Request $request)
    {
      
        $totalLinhasPagina = Session::get('app.qtyItemsPerPage') ?? 10;

        // 1. Obter o ID da Empresa selecionada
        $empresaId = $request->query('empresaId');
     
        if (empty($empresaId) && Session::has('app.empresa_id')) {
             $empresaId = Session::get('app.empresa_id');
        }

        // 2. Lógica para determinar a data e o estado do checkbox "Todas as Datas"
        $dataSelecionadaParam = $request->query('dataSelecionada');

        $isTodasDatasChecked = empty($dataSelecionadaParam);

        $inputValueData = empty($dataSelecionadaParam) ? Carbon::now()->toDateString() : $dataSelecionadaParam;
   
    $lancamentosQuery = Lancamento::with([
        'LctoPartida:id,nome',
        'LctoContraPartida:id,nome',
        'categoria:numero_categoria,nome',
        'usuarioQueCriou', 
        'usuarioQueAtualizou',
    ]);


        if (!empty($empresaId)) {
            $lancamentosQuery->where('empresa_id', $empresaId);
        }

        if (!empty($dataSelecionadaParam)) {
            $lancamentosQuery->whereDate('data_lcto', $dataSelecionadaParam);
        }
   
        $lancamentos = $lancamentosQuery->orderBy('updated_at', 'DESC')->paginate($totalLinhasPagina);   
        $empresas = Empresa::all();

        // --- CORREÇÃO AQUI: Adicionar 'inputValueData' ao compact() ---
        return view('lancamento.indexLancamento', compact('lancamentos', 'empresas', 'empresaId', 'inputValueData', 'isTodasDatasChecked'));
    }

    public function listIndex()
    {

        return view('lancamento.listLcto');
    }
    /*** Armazena lançamento digitado*/
    public function create()
    {
        $empresas = Empresa::all();
        $categorias = Categoria::all();
        return view('lancamento.createLancamento', compact('empresas', 'categorias'));
    }

    /*** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        try {
            // Garante que o usuário está autenticado
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado.',
                ], 401); // Código 401 para "Unauthorized"
            }

            // Tenta criar o lançamento
            $lancamento = new Lancamento(); //instancia primeiro
            $lancamento->grupo_economico_id = $request->grupo_economico_id;
            $lancamento->empresa_id = $request->empresa_id;
            $lancamento->data_lcto = $request->data_lcto;
            $lancamento->tipo_docto = strtoupper($request->tipo_docto ?? 'LCTO');
            $lancamento->numero_docto = $request->numero_docto;
            $lancamento->tipo_conta = $request->tipo_conta;
            $lancamento->conta_partida = $request->conta_partida;
            $lancamento->conta_contrapartida = $request->conta_contrapartida;
            // $lancamento->plano_contas_conta = numero_categoria($request->codPlanoConta, $request->plano_contas_conta);
            $lancamento->categorias_id = numero_categoria($request->codPlanoCategoria, $request->categorias_id);
            $lancamento->historico = strtoupper($request->historico);
            $lancamento->unidade = strtoupper($request->unidade) ?? 'UND';
            $lancamento->quantidade = removePontosValor($request->quantidade);
            $lancamento->valor = removePontosValor($request->valor);
            $lancamento->centro_custo = $request->centro_custo ?? '00000000000000000000';
            $lancamento->vencimento = $request->vencimento;
            $lancamento->origem = $request->origem;
            $lancamento->created_by = Auth::id();  // Define o created_by
            $lancamento->updated_by = Auth::id();  // Define o updated_by
            $lancamento->save();

            return response()->json(['success' => true, 'message' => 'Lançamento gravado com sucesso!']);
        } catch (\Exception $e) {
            // Captura qualquer exceção
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gravar lançamento: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function storeAjax(Request $request)
    {
        //dd($request);
        return response()->json(['success' => true, 'message' => 'Lançamento gravado com sucesso!']);
    }

    public function show($id)
    {
        $lancamento = Lancamento::with(['LctoPartida', 'LctoContraPartida', 'Categoria'])->find($id);

        if (!$lancamento) {
            return response()->json(['message' => 'Lançamento não encontrado'], 404);
        }

        // Você pode precisar ajustar os nomes das propriedades para corresponder ao que o JS espera
        // Por exemplo, se 'LctoPartida' é um relacionamento e você quer 'nome' dele
        return response()->json([
            'id' => $lancamento->id,
            'empresa_id' => $lancamento->empresa_id,
            'grupo_economico_id' => $lancamento->grupo_economico_id,
            'origem' => $lancamento->origem,
            'data_lcto' => $lancamento->data_lcto, // Formato YYYY-MM-DD para input[type="date"]
            'tipo_docto' => $lancamento->tipo_docto,
            'numero_docto' => $lancamento->numero_docto,
            'tipo_conta' => $lancamento->tipo_conta,
            'conta_partida' => $lancamento->conta_partida,
            'lcto_partida_nome' => $lancamento->LctoPartida ? $lancamento->LctoPartida->nome : '',
            'categorias_id' => $lancamento->categorias_id,
            'categorias_nome' => $lancamento->Categoria ? $lancamento->Categoria->nome : '',
            'cod_plano_categoria' => $lancamento->codPlanoCategoria, // Verifique se este campo existe no seu modelo/tabela
            'conta_contrapartida' => $lancamento->conta_contrapartida,
            'lcto_contra_partida_nome' => $lancamento->LctoContraPartida ? $lancamento->LctoContraPartida->nome : '',
            'historico' => $lancamento->historico,
            'unidade' => $lancamento->unidade,
            'quantidade' => $lancamento->quantidade,
            'deb_cred' => $lancamento->deb_cred,
            'valor' => $lancamento->valor, // O JS fará a formatação para moeda
            'centro_custo' => $lancamento->centro_custo,
            'vencimento' => $lancamento->vencimento, // Formato YYYY-MM-DD para input[type="date"]
        ]);
    }

    public function update(Request $request)
    {

        $id = $request->lcto_id_update;
        $lancamento = Lancamento::find($id);
        if (!$lancamento) {
            return redirect()->back()->with('error', 'Lançamento não encontrado.');
        }

        //  $lancamento->grupo_economico_id = $request->grupo_economico_id_update;
        // $lancamento->empresa_id = $request->empresa_id_update;
        $lancamento->data_lcto = $request->data_lcto_update;
        $lancamento->tipo_docto = strtoupper($request->tipo_docto_update ?? 'LCTO');
        $lancamento->numero_docto = $request->numero_docto_update;
        $lancamento->tipo_conta = $request->tipo_conta_update;
        $lancamento->conta_partida = $request->conta_partida_update;
        $lancamento->conta_contrapartida = $request->conta_contrapartida_update;
        // $lancamento->plano_contas_conta = numero_categoria($request->codPlanoConta, $request->plano_contas_conta);
        $lancamento->categorias_id = numero_categoria($request->codPlanoCategoria_update, $request->categorias_id_update);
        $lancamento->historico = strtoupper($request->historico_update);
        $lancamento->unidade = strtoupper($request->unidade_update) ?? 'UND';
        $lancamento->quantidade = removePontosValor($request->quantidade_update);
        $lancamento->valor = removePontosValor($request->valor_update);
        $lancamento->centro_custo = $request->centro_custo_update ?? '00000000000000000000';
        $lancamento->vencimento = $request->vencimento_update;
        $lancamento->origem = 'Manual'; // Definindo origem como 'Manual'
        $lancamento->updated_by = Auth::id();  // Define o updated_by
        $lancamento->update();

        return redirect()->back()->with('success', 'Doc:' . $request->numero_docto_update . " / " . $request->nomePartida_update . ' - Lançamento Alterado com sucesso!');
    }

    /**
     * Display the specified resource.
     */


    /**;
     * Show the form for editing the specified resource.
     */
    public function edit(lancamento $lancamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request)
    {
        // O ID virá do campo hidden 'lancamento_id' no corpo da requisição POST
        $lancamentoId = $request->input('delete_lacto_id'); // Ou $request->lancamento_id;
        // Agora você pode usar $lancamentoId para encontrar e deletar o lançamento
        $lancamento = Lancamento::find($lancamentoId);
        $nr_docto = $lancamento->numero_docto ?? '';
        $partida_nome = $lancamento->LctoPartida ? $lancamento->LctoPartida->nome : '';
        if ($lancamento) {
            $lancamento->delete();
            return redirect()->back()->with('success', 'Doc:' . $nr_docto . " / " . $partida_nome . ' - Lançamento excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Lançamento não encontrado ou não pôde ser excluído.');
    }

 public function createImportForm()
    {
        return view('lancamento.import');
    }

    /**
     * Processa o arquivo CSV enviado e importa os lançamentos.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048', // Max 2MB
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $rows = [];
        $errors = [];
        $lineNumber = 0;
        $importedCount = 0;

        // Definir o mapeamento dos cabeçalhos do CSV para as colunas do DB.
        // AS CHAVES (à esquerda '=>') DEVEM CORRESPONDER EXATAMENTE aos nomes dos cabeçalhos na sua planilha CSV,
        // APÓS terem sido 'trimados' pelo PHP.
        $csvHeaderMap = [
            'Empresa Nome'           => 'empresa_nome',
            'Grupo Economico Nome'   => 'grupo_economico_nome',
            'Data Lcto'              => 'data_lcto',
            'Tipo Docto'             => 'tipo_docto',
            'Numero Docto'           => 'numero_docto',
            'Tipo Conta'             => 'tipo_conta',
            'Conta Partida ID'       => 'conta_partida',
            'Categoria ID'           => 'categorias_id',
            'Conta Contrapartida ID' => 'conta_contrapartida',
            'Historico'              => 'historico',
            'Unidade'                => 'unidade',
            'Quantidade'             => 'quantidade',
            'Valor'                  => 'valor',
            'Centro Custo'           => 'centro_custo',
            'Vencimento'             => 'vencimento',
        ];

        // Mapeamento de valores de ENUM
        $tipoContaEnumMap = [
            'Banco' => 'Banco',
            'Cliente' => 'Cliente',
            'Fornecedor' => 'Fornecedor',
            'BCO' => 'Banco', // Variações no CSV
            'CLI' => 'Cliente',
            'FORN' => 'Fornecedor',
        ];

        $currentUser = Auth::user();
        if (!$currentUser) {
            Log::warning('Tentativa de importação CSV sem usuário autenticado. Usando ID 1 como fallback para created_by/updated_by.');
            $currentUserId = 1; // Fallback para usuário não autenticado
        } else {
            $currentUserId = $currentUser->id;
        }

        Log::info("ID do usuário para importação: {$currentUserId}");


        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ';'); // Delimitador ponto e vírgula
            $csvColumns = array_map('trim', $header); // Limpa espaços em branco nos nomes das colunas do CSV

            if (isset($csvColumns[0])) {
                $csvColumns[0] = preg_replace('/^\x{FEFF}/u', '', $csvColumns[0]);
            }

            $mappedCsvColumns = [];
            foreach ($csvColumns as $csvColumn) {
                $foundMapping = false;
                foreach ($csvHeaderMap as $csvKey => $dbColumn) {
                    if (strtolower($csvColumn) === strtolower($csvKey)) {
                        $mappedCsvColumns[] = $dbColumn;
                        $foundMapping = true;
                        break;
                    }
                }
                if (!$foundMapping) {
                    $mappedCsvColumns[] = null;
                    Log::warning("Coluna CSV '{$csvColumn}' não mapeada para nenhuma coluna do banco de dados. Linha: {$lineNumber}");
                }
            }

            while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) { // Delimitador ponto e vírgula
                $lineNumber++;
                $rowData = [];
                foreach ($mappedCsvColumns as $index => $dbColumn) {
                    if ($dbColumn !== null && isset($data[$index])) {
                        $rowData[$dbColumn] = trim($data[$index]);
                    }
                }

                try {
                    // Validação e Busca de IDs para Empresa e Grupo Econômico
                    $empresa = Empresa::whereRaw('LOWER(nome) = ?', [strtolower($rowData['empresa_nome'])])->first();
                    if (!$empresa) {
                        throw new \Exception("Empresa '{$rowData['empresa_nome']}' não encontrada.");
                    }
                    $rowData['empresa_id'] = $empresa->id;

                    $grupoEconomico = GrupoEconomico::whereRaw('LOWER(nome) = ?', [strtolower($rowData['grupo_economico_nome'])])->first();
                    if (!$grupoEconomico) {
                        throw new \Exception("Grupo Econômico '{$rowData['grupo_economico_nome']}' não encontrado.");
                    }
                    $rowData['grupo_economico_id'] = $grupoEconomico->id;

                    // Validação de IDs Numéricos do CSV E EXISTÊNCIA NO BANCO DE DADOS
                    // Para conta_partida e conta_contrapartida (referem-se a Parceiros)
                    foreach (['conta_partida', 'conta_contrapartida'] as $field) {
                        if (!isset($rowData[$field]) || !is_numeric($rowData[$field]) || (int)$rowData[$field] <= 0) {
                            throw new \Exception("ID inválido para '{$field}': '{$rowData[$field]}' deve ser um número inteiro positivo.");
                        }
                        $id = (int)$rowData[$field];
                        if (!Parceiro::find($id)) {
                            throw new \Exception("ID de Parceiro '{$id}' para '{$field}' não encontrado na tabela 'parceiros'.");
                        }
                        $rowData[$field] = $id; // Garante que o valor é um inteiro
                    }

                    // Para categorias_id (refere-se a Categoria)
                    if (!isset($rowData['categorias_id']) || !is_numeric($rowData['categorias_id']) || (int)$rowData['categorias_id'] <= 0) {
                        throw new \Exception("ID inválido para 'categorias_id': '{$rowData['categorias_id']}' deve ser um número inteiro positivo.");
                    }
                    $categoriaId = (int)$rowData['categorias_id'];
                    if (!Categoria::find($categoriaId)) {
                        throw new \Exception("ID de Categoria '{$categoriaId}' não encontrado na tabela 'categorias'.");
                    }
                    $rowData['categorias_id'] = $categoriaId; // Garante que o valor é um inteiro


                    // Formatação e Validação de Outros Tipos
                    $rowData['data_lcto'] = Carbon::createFromFormat('d/m/Y', $rowData['data_lcto'])->format('Y-m-d');
                    if (isset($rowData['vencimento']) && !empty($rowData['vencimento'])) {
                        $rowData['vencimento'] = Carbon::createFromFormat('d/m/Y', $rowData['vencimento'])->format('Y-m-d');
                    } else {
                        $rowData['vencimento'] = null;
                    }

                    // Conversão de números com vírgula decimal para ponto decimal
                    $rowData['quantidade'] = (float)str_replace(',', '.', trim($rowData['quantidade']));
                    $rowData['valor'] = (float)str_replace(',', '.', trim($rowData['valor']));

                    $rowData['tipo_conta'] = $tipoContaEnumMap[$rowData['tipo_conta']] ?? null;
                    if (is_null($rowData['tipo_conta'])) {
                         throw new \Exception("Valor de 'Tipo Conta' inválido: '{$rowData['tipo_conta']}'. Valores permitidos: " . implode(', ', array_keys($tipoContaEnumMap)));
                    }

                    // Preenchimento de Campos Padrão/Obrigatórios
                    $rowData['origem'] = 'Import CSV'; // <--- ALTERADO AQUI
                    $rowData['created_by'] = $currentUserId;
                    $rowData['updated_by'] = $currentUserId;

                    Log::info("Dados completos para criação de Lancamento na linha {$lineNumber}:", $rowData);

                    // Criação do Lançamento
                    Lancamento::create([
                        'empresa_id' => $rowData['empresa_id'],
                        'grupo_economico_id' => $rowData['grupo_economico_id'],
                        'data_lcto' => $rowData['data_lcto'],
                        'tipo_docto' => $rowData['tipo_docto'],
                        'numero_docto' => $rowData['numero_docto'],
                        'tipo_conta' => $rowData['tipo_conta'],
                        'conta_partida' => $rowData['conta_partida'], // Já é int aqui
                        'categorias_id' => $rowData['categorias_id'], // Já é int aqui
                        'conta_contrapartida' => $rowData['conta_contrapartida'], // Já é int aqui
                        'historico' => $rowData['historico'],
                        'unidade' => $rowData['unidade'],
                        'quantidade' => $rowData['quantidade'],
                        'valor' => $rowData['valor'],
                        'centro_custo' => $rowData['centro_custo'],
                        'vencimento' => $rowData['vencimento'],
                        'origem' => $rowData['origem'],
                        'created_by' => $rowData['created_by'],
                        'updated_by' => $rowData['updated_by'],
                    ]);
                    $importedCount++;

                } catch (\Exception $e) {
                    Log::error("Erro na importação CSV na linha {$lineNumber}: " . $e->getMessage());
                    $errors[] = [
                        'linha' => $lineNumber,
                        'mensagem' => $e->getMessage(),
                    ];
                }
            }
            fclose($handle);
        } else {
            return redirect()->back()->with('error', 'Não foi possível abrir o arquivo CSV.');
        }

        if (count($errors) > 0) {
            return redirect()->back()->with('error', 'Importação concluída com erros em algumas linhas.')
                                     ->with('errors_details', $errors);
        }

        return redirect()->back()->with('success', "Importação concluída! {$importedCount} lançamentos importados com sucesso.");
    }
    
}