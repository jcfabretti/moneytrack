<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\CategoriaTipo;
use App\Models\Empresa;
use PhpParser\Node\Expr\New_;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idTipoCategParam)
    {
        $id_tipoCategoria = (int) $idTipoCategParam; // Garantir que seja um inteiro
        $tipoPlanos = CategoriaTipo::all();
        if ($id_tipoCategoria == 0 or $id_tipoCategoria == null) {
            $id_tipoCategoria =  $tipoPlanos->first()->id; // Default para o primeiro registro de tipos de categoria
        }
       // $categorias = Categoria::where('fk_tipocategoria_id', $id_tipoCategoria)->get();
          // Carrega as categorias de nível 1 e todos os seus filhos recursivamente
    $categorias = Categoria::with('children')
                             ->where('fk_tipocategoria_id', $id_tipoCategoria)
                             ->orderBy('numero_categoria') // Importante para a ordenação da treeview
                             ->get();
      

        return view('categoria.index', compact('categorias', 'tipoPlanos', 'id_tipoCategoria'));
    }
 
        public function indexTreeView($idTipoCategParam)
    {
        $id_tipoCategoria = (int) $idTipoCategParam; // Garantir que seja um inteiro
        $tipoPlanos = CategoriaTipo::all();
        if ($id_tipoCategoria == 0 or $id_tipoCategoria == null) {
            $id_tipoCategoria =  $tipoPlanos->first()->id; // Default para o primeiro registro de tipos de categoria
        }
        $categorias = Categoria::where('fk_tipocategoria_id', $id_tipoCategoria)->get();

        return view('categoria.indextreeview', compact('categorias', 'tipoPlanos', 'id_tipoCategoria'));
    }
   
    public function manageCategory()
    {
        $categories = Categoria::where('categoria_pai', '=', 0)->get();
        //dd($categories);
        $allCategories = Categoria::get()->where('categoria_pai', '!=', '299999');
        // dd($allCategories);
        return view('planoconta.CategoryTreeview', compact('categories', 'allCategories'));
    }

    public function consultaCategoria()
{
{
    $categorias = \App\Models\Categoria::where('fk_tipocategoria_id', session('app.empresaCodPlanoConta'))
        ->where('nivel', '<=', 3)
        ->orderBy('nivel')
        ->orderBy('nome')
        ->get();

    $codigo_planoCategoria = session('app.empresaCodPlanoConta');
    $dataAtual = \Carbon\Carbon::now()->format('Y-m-d');
    $nivelArray = ['Saldo Inicial', 'Grupo', 'Sub-Totais', 'Movimento'];

    // Carrega a view do modal e a retorna como uma string
    $modalContent = view('categoria.modal.showModalCategoria', compact('categorias', 'codigo_planoCategoria', 'dataAtual', 'nivelArray'))->render();

    return response()->json(['html' => $modalContent]);
}
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        // Validation rules
        $rules = [
            'id' => 'required',
            'nome' => 'required|max:45',
            'categoria_pai' => 'required',
            'nivel' => 'required',
            'tipo_categoria' => 'required',
        ];

        // Custom error messages
        $customMessages = [
            'nome' => 'Informe o nome da Categoria',
            'categoria_pai' => 'Informe a Categoria Pai',
            'nivel.required' => 'Erro ao cadastrar nível, recadastre!',
            'tipo_categoria' => 'Erro ao cadastrar Tipo de Categoria, recadastre!',
        ];

        // Validate the request
        //  $validatedData = $request->validate($rules, $customMessages);
        // $codCateg= $request->tipo_categoria . limparCodigoCategoria($request->categoria_id);
        // dd($codCateg);
        // Create a new Categoria instance and fill its attributes
        $newCateg = new Categoria([
            'numero_categoria' => $request->tipo_categoria . limparCodigoCategoria($request->categoria_id),
            'nome' => strtoupper($request->nome),
            'categoria_pai' => $request->categoria_pai,
            'nivel' => $request->nivel,
            'fk_tipocategoria_id' => $request->tipo_categoria,
        ]);
 
        // Save the Categoria instance to the database
        $newCateg->save();
         
 
    // Redireciona para a rota 'categoria.index' passando o 'tipo_categ' do request
    return redirect()->route('categoria.index', ['tipo_categ' => $request->tipo_categoria])
        ->with('success', 'Categoria cadastrada com sucesso!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id=limparCodigoCategoria($request->categoria_id);
   
        $codigo=$request->tipo_categoria . $id ;        
        $categ = Categoria::find($codigo); 
        if ($categ == null) {
             return redirect()->route('categoria.indextreeview')
            ->with('Erro:', 'Codigo não encontrado!');
        } else {
            $categ->nome = strtoupper($request->categoria_nome);
            $categ->categoria_pai = $request->categoria_pai;
            $categ->update();
            return redirect()->route('categoria.index')
            ->with('Erro:', 'Alteração efetuada !');
            return redirect()->Route('categoria.indextreeview')->with('Errors', 'Alteração Efetuada');
        }
    
    }

    /**
     * Remove the specified resource from storage. */
    public function destroy($id)
    {

        try {
            //Busca no BD a categoria pelo ID
            $categoria = Categoria::find($id);
            if (!$categoria) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Categoria não encontrada.'
                ], 404);
            }
    
            // Se for nivel 1 ou 2 checa se tem filhos / Childreen
            if ($categoria->nivel == 1 || $categoria->nivel == 2) {
                $catPai = Categoria::where('categoria_pai', $id)->get();
                $qtde = $catPai->count();
    
                if ($qtde > 0) {
                    return response()->json([
                        'status' => '424',
                        'message' => 'Existe ' . $qtde . ' categorias cadastradas abaixo desta - Exclua antes.'
                    ], 424); // 424 - Failed Dependency
                }
            }
    
            // Delete the category
 //           $categoria->delete();
    
            // Return a success response
            return response()->json([
                'status' => '200',
                'message' => formatarNumeroCategoria($id) . ' - Categoria excluida com sucesso!'
            ], 200);
    
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => '500',
                'message' => 'Erro ao excluir esta categoria: ' . $e->getMessage()
            ], 500);
        }
    }
 
    public function getConta($id)
    {
     //  $nomeConta = PlanoConta::select('nome')->where('conta', $id)->first();
       //return response()->json($nomeConta);

      if ($nomeConta = Categoria::select('nome')->where('conta', $id)->firstOrFail()) {
       return response()->json($nomeConta);
      }
      return response()->json([
        'status' => 404,
        'message' => 'Conta não Cadastrada!',
        ]);

    }
   
    public function getCategorias($id1, $id2)
    {
        // Try to find the category by $id1
        $categNova = Categoria::find($id1);
    
        // If $categNova is found, return with a 409 status (Conflict)
        if ($categNova) {
            return response()->json([
                'status' => 409,
                'message' => 'Este código de Categoria já está cadastrado.'
            ], 409);
        }
    
        // Check if $id2 is a valid numeric ID
        if (!is_numeric($id2)) {
            return response()->json([
                'status' => 400,
                'message' => 'Formato de ID inválido.'
            ], 400);
        }
    
        // Try to find the category by $id2
        $categPai = Categoria::find($id2);

        // If $categPai is found, return the category data
        if ($categPai) {
            return response()->json($categPai);
        }
    
        // If $categPai is not found, return a 404 response
        return response()->json([
            'status' => 404,
            'message' => 'Categoria Pai NÃO cadastrada!'
        ], 404);
    }

    // Busca o nome dados da categoria codigo id
    public function getNomeCategoria($id)
    {
      $nomeConta = Categoria::find($id);
      
      return response()->json($nomeConta);

    }

    // utilizado por Digitação de Lançamentos //
    public function buscaNomeCategoria($id)
    {

      if ($nomeConta = Categoria::select('nome')->where('numero_categoria', $id)->firstOrFail()) {
       return response()->json($nomeConta);
      }
      return response()->json([
        'status' => 404,
        'message' => 'Conta não Cadastrada!',
        ]);

    }    
}
