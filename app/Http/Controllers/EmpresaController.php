<?php

namespace App\Http\Controllers;

use App\Models\CategoriaTipo;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\GrupoEconomico;
use App\Models\TipoConta;
use App\Models\TiposPlanoConta;
use Illuminate\Support\Facades\Log;
use App\Services\GlobalEmpresaService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmpresaController extends Controller
{

    private $objGrupos;
    private $objEmpresas;
    private $objTiposPlanoConta;
    private $globalEmpresaService; // <--- DECLARE A PROPRIEDADE AQUI

    public function __construct(GlobalEmpresaService $globalEmpresaService) // <--- INJETE O SERVIÇO AQUI
    {
        $this->objEmpresas = new Empresa(); // Isso é ok se você preferir instanciar assim
        $this->objGrupos = new GrupoEconomico();
        $this->objTiposPlanoConta = new TiposPlanoConta();
        $this->globalEmpresaService = $globalEmpresaService; 
    }

    public function index()
    {
        $grupoEcons = GrupoEconomico::all();
        $tiposPlanos = CategoriaTipo::all();
        $empresas = Empresa::with('grupoEconomico', 'tiposPlanoConta')
                            ->where('id', '>', 0) // Adiciona a condição id > 0
                            ->get()
                            ->sortByDesc('created_at');

        return view('empresa.index', compact('empresas', 'grupoEcons', 'tiposPlanos'));
    }


    public function storeEmpresaSelecionada($id, $nome)
    {
        // Atualiza as variáveis de sessão com o ID e o nome da empresa selecionada
        session(['app.empresaId' => $id]);
        session(['app.empresaNome' => $nome]);

        // Retorna uma resposta JSON com o status e a mensagem
        return response()->json([
            'status' => 200,
            'message' => 'Empresa selecionada com sucesso!',
        ]);

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  dd($request);
        try {
            // Process and save the data
            Empresa::create([
                'nome' => $request['nome'],
                'grupo_economico_id' => $request['grupo_economico_id'],
                'cod_fiscal' => limpa_cpf_cnpj($request['cod_fiscal']),
                'localidade' => $request['localidade'] ?? null,
                'tipos_planocontas_id' => $request['tipos_planocontas_id'],
            ]);

            // Redirect to the desired page with a success message
            return redirect('/home/empresa/index')->with('success', 'Empresa criada com sucesso!');
        } catch (\Exception $e) {
            dd($e);
            // Log the exception and redirect with an error message
            Log::error('Error creating Empresa: ' . $e->getMessage());
            return redirect('/home/empresa/index')->with('error', 'Erro ao criar a Empresa. Tente novamente mais tarde.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      

    }

    // ###################################################################
    /* EMPRESA */

     public function StoreEmpresaSelecionada1($id) // Mantenha o $id, pois vem da rota
    {
        try {
            $empresaSel = Empresa::findOrFail($id);

            // Usa o serviço para setar a empresa na sessão
            $this->globalEmpresaService->setEmpresa( // <--- AGORA ESTARÁ DISPONÍVEL
                $empresaSel->id,
                $empresaSel->nome,
                $empresaSel->grupo_economico_id,
                $empresaSel->tipos_planocontas_id
            );

            Log::info('Empresa selecionada e armazenada na sessão: ' . $empresaSel->nome);

            return response()->json([
                'status' => 'success',
                'message' => 'Empresa selecionada e salva na sessão com sucesso!',
                'empresa' => [
                    'id' => $empresaSel->id,
                    'nome' => $empresaSel->nome,
                    'grupo_economico_id' => $empresaSel->grupo_economico_id,
                    'cod_plano_categoria' => $empresaSel->tipos_planocontas_id,
                ]
            ], 200);

        } catch (ModelNotFoundException $e) {
            Log::error('Empresa não encontrada com ID: ' . $id);
            return response()->json([
                'status' => 'error',
                'message' => 'Empresa não encontrada.'
            ], 404);
        } catch (Exception $e) {
            Log::error('Erro ao selecionar empresa: ' . $e->getMessage() . ' no ID: ' . $id);
            return response()->json([
                'status' => 'error',
                'message' => 'Ocorreu um erro interno ao selecionar a empresa.'
            ], 500);
        }
    }

    public function listaEmpresaModal()
    {
        // Obtém todas as empresas do banco de dados usando o Eloquent ORM.
        $empresas = Empresa::all();
        // Retorna as empresas como JSON.  O Laravel converte automaticamente
        // a coleção do Eloquent para JSON.
        return response()->json($empresas);
    }

    //* Get first Empresa and keep in Session */
    public function defineEmpresaDefault()
    {
        $empresaSel = Empresa::FindFirst();
        view()->share('EMPRESA_ID', $empresaSel->id);
        view()->share('EMPRESA_NOME', $empresaSel->nome);
        view()->share('GRUPO_ECONOMICO_ID', $empresaSel->grupo_economico_id);
        view()->share('EMPRESA_COD_PLANO_CATEGORIA', $empresaSel->tipos_planocontas_id);

        return;
    }

    public function StoreSelectedEmpresa1(Request $request)
    {
        $codEmpresa = $request->empresa_id;
        $dadosEmpresa = Empresa::where('id', $codEmpresa)->first();
        view()->share('EMPRESA_ID', $dadosEmpresa->id);
        view()->share('EMPRESA_NOME', $dadosEmpresa->nome);

        $grupos = GrupoEconomico::all();
        $empresas = Empresa::all();
        return view('lancamento.createLancamentov3', compact('empresas', 'grupos'));
    }

    public function listarEmpresa()
    {
        $empresas = Empresa::get()->sortby('nome');
        // dd($empresas);
        return ($empresas);
    }

    public function storeEmpresa(Request $request)
    {

        // dd($request);
        $request->validate([
            'nome' => 'required|max:45|unique:parceiros,nome',
            'grupo_economico_id' => 'required|not_in:0',
            'cod_fiscal' => 'required|unique:parceiros,cod_fiscal',
            'localidade' => 'required',
            'tipo_conta_id' => 'required',
            'tipos_planocontas_id' => 'required|not_in:0',
        ]);

        $this->objEmpresas->create([
            'nome' => ucfirst($request->nome),
            'grupo_economico_id' => $request->grupo_economico_id,
            'cod_fiscal' => $request->cod_fiscal,
            'localidade' => $request->localidade,
            'tipo_conta_id' => $request->tipo_conta_id,
            'tipos_planocontas_id' => $request->tipos_planocontas_id,
        ]);

        return redirect('/home/showEmpresa/1-9');
    }

    /* public function editEmpresa(Empresa $empresa, $id) {
        $empresa = $this->objEmpresas->find($id);
        return view('empresa.edit', ['empresa' => $empresa]);
    }
*/
    public function updateEmpresa(Request $request)
    {
        //dd($request);
        $id   = $request->id;
        $empresa = Empresa::find($id);
        $empresa->nome   = $request->nome;
        $empresa->grupo_economico_id = $request->grupoEcon;
        $empresa->cod_fiscal = limpa_cpf_cnpj($request->cod_fiscal);
        $empresa->localidade = $request->localidade;
        $empresa->update();
        return redirect('/home/empresa/index');
    }

    public function destroyEmpresa($id)
    {
        $empresa = Empresa::find($id);
        $empresa->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Empresa excluida com sucesso!',
        ]);
    }
}
