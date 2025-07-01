<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Parceiro;
use Illuminate\Http\Request;
use App\Models\GrupoEconomico;
use App\Models\CategoriaTipo;
use App\Models\Lancamento;
use App\Models\User;
use App\Models\Empresa;
use App\Models\ValoresMensais;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log; // Importe a facade Log
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('homeGraphics');
    }

    public function indexuser()
    {
        // Fetch all TiposPlanoConta records
        $users = User::all();
        return view('auth.index', compact('users'));
    }

    public function languageEn()
    {
        App::setLocale('en');
        config(['app.locale' => 'en']);
        return view('home');
    }

    public function homeCalendar()
    {
        return view('calendar');
    }

    /* REGISTRA novo usuario */
    public function registerUser()
    {
        return view('auth.register');
    }

    /* CADASTRA Grupo Economico */
    public function gruposEcon()
    {
        $grupos = GrupoEconomico::all();
        return view('gruposEcon', compact('partners'));
    }

    public function saveQtyPerPage(Request $request)
    {
        // Como o JavaScript envia JSON.stringify({ qty: qty }), use $request->json('qty')
        $qty = $request->json('qty');

        // Se por algum motivo o JavaScript enviar form-urlencoded (e não JSON.stringify),
        // usaria $request->input('qty'); mas o seu refatoramento atual usa JSON.stringify
        // $qty = $request->input('qty');

        if (is_null($qty)) {
            return response()->json(['message' => 'Quantidade não fornecida.'], 400);
        }

        // Exemplo: Salvar na sessão
        Session::put('app.qtyItemsPerPage', $qty);

        return response()->json(['message' => 'Quantidade por página salva com sucesso.', 'qty' => $qty]);
    }


    // ###################################################################    
    //* CADASTRA Plano de Contas */

    public function planoContas()
    {
        $categorias = Categoria::with('categoriaTipo')->where('nivel', '!=', 0)->paginate(Session('app.qtyItemsPerPage'));
        $categoriaSelect   = Categoria::get();
        $tipoPlanos = CategoriaTipo::all();
        //dd($categorias);
        return view('categoria.index', compact('categorias', 'tipoPlanos', 'categoriaSelect'));
    }


    // ###################################################################
    // LANCAMENTOS

    public function showlancamento()
    {
        //  $parceiros=$this->objParceiros->all()->sortDesc()->paginate(12);
        $parceiros = Lancamento::orderBy('updated_at', 'DESC')->paginate(Session('app.qtyItemsPerPage'));

        return view('lancamento.showLancamentos', compact('parceiros'));
    }


    // ###################################################################
    // PARCEIROS.
    public function showParceiro(Request $request)
    {
        $qtyPerPage = session('app.qtyItemsPerPage');
        if (request()->has('adminlteSearch')) {
            $searchstr = request()->get('adminlteSearch');
            $parceiros = Parceiro::where('nome', 'like', '%' . $searchstr . '%')->paginate($qtyPerPage);
        } else {
            $parceiros = Parceiro::orderBy('updated_at', 'DESC')->paginate($qtyPerPage);
        }
        return view('parceiro.showParceiro', compact('parceiros'));
    }

    public function searchParceiro()
    {
        $searchstr = request()->get('adminlteSearch');
        //  $parceiros=$this->objParceiros->all()->sortDesc()->paginate(12);
        $parceiros = Parceiro::where('nome', 'like', '%' . $searchstr . '%')->paginate(9);
        return view('parceiro.showParceiro', compact('parceiros'));
    }

    /**
     * Retorna o nome de um parceiro em formato JSON.
     *
     * @param  int  $id O ID do parceiro.
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchParceiro($id)
    {
        // dd($id); // REMOVA OU COMENTE ESTA LINHA! Ela está a causar o erro.

        try {
            $nomeParc = Parceiro::select('nome')->where('id', $id)->firstOrFail();
            // dd($nomeParc); // Se quiser depurar, use \Log::debug($nomeParc); ou depure no navegador
            return response()->json($nomeParc);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Se o parceiro não for encontrado, retorne um erro 404 JSON
            return response()->json(['error' => 'Parceiro não encontrado.'], 404);
        } catch (\Exception $e) {
            // Para outros erros, retorne um erro 500 JSON
            Log::error('Erro ao buscar parceiro: ' . $e->getMessage());
            return response()->json(['error' => 'Ocorreu um erro interno ao buscar o parceiro.'], 500);
        }
    }

    // Store a newly created resource in storage.
    public function createParceiro()
    {
        return view('parceiro.createParceiro');
    }

    public function storeParceiro(Request $request)
    {
        $rules = [
            'nome' => 'required|max:45|unique:parceiros,nome',
            'tipo_cliente' => 'required|not_in:0',
            'nat_jur' => 'required|not_in:0',
            'cod_fiscal' => 'required|unique:parceiros,cod_fiscal',
            'localidade' => 'required',
            'status' => 'required',
        ];

        $customMessages = [
            'nome.required' => 'Informe o nome da empresa',
            'nome.unique'   => 'Este nome já está cadastrado',
            'tipo_cliente.required' => 'Selecione um tipo de cliente',
            'nat_jur.required > 0' => 'Selecione uma Natureza Juridica',
            'cod_fiscal.required' => 'Informe o CNPJ ou CPF',
            'cod_fiscal.unique'   => 'Este CNPJ/CPF ja esta cadastrado',
            'localidade.required' => 'Informe o nome da cidade',
        ];

        $validator = $this->validate($request, $rules, $customMessages);

        // if($validator->fails()) return redirect()->back()->withInput($request->all())->withErrors($validator);

        Parceiro::create([
            'nome' => $request->nome,
            'nat_jur' => $request->natJur,
            'tipo_cliente' => $request->tipo_cliente,
            'cod_fiscal' => $request->cod_fiscal,
            'localidade' => $request->localidade
        ]);

        return redirect('/home/showParceiro')->with('Sucess', 'Cadastrada com Sucesso');
    }

    public function editParceiro(Parceiro $parceiro)
    {
        return view('parceiro.editParceiro', ['parceiro' => $parceiro]);
    }

    public function updateParceiro(Request $request, $id)
    {

        $parceiro = Parceiro::find($id);
        $parceiro->nome   = $request->nome;
        $parceiro->nat_jur = $request->nat_jur;
        $parceiro->tipo_cliente = $request->tipo_cliente;
        $parceiro->cod_fiscal = limpa_cpf_cnpj($request->cod_fiscal);
        $parceiro->localidade = $request->localidade;

        $parceiro->update();
        return redirect('/home/showParceiro')->with('Success', 'Alteração Efetuada');
    }

    public function destroyParceiro($id)
    {
        $parceiro = Parceiro::find($id);
        $parceiro->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Parceiro excluido com sucesso!',
        ]);
    }

    public function getParceiro($id)
    {
        dd($id);
        $nomeParc = Parceiro::where('username', $id)->first();
        //return $nomeParc;

        return response()->json([
            'status' => 200,
            'message' => 'Busca  com sucesso!',
        ]);


        //  return response()->json([$parceiro]);
    }


}
