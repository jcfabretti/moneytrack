<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PlanoContaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function getCategorias($id)
    {
      $categs = Categoria::get()->where('nivel','=',$id)->toJson();
      // dd($categs);
      
      return $categs;
    }

    public function manageIframeCategory()
    {
        $categories = Category::where('parent_id', '=', 0)->get();
        //dd($categories);
        $allCategories = Category::all();
       // dd($allCategories);
        return view('planoconta.ListTreeView',compact('categories','allCategories'));
    }

   public function addCategory(Request $request){

    dd($request);
   $this->validate($request, [ 'title' => 'required', ]);
   $this->validate($request, [ 'level' => 'required', ]);
   $input = $request->all();
   $input['parent_id'] = empty($input['parent_id']) ? 0 : $input['parent_id'];
   
   Category::create($input);
   return back()->with('success', 'New Category added successfully.');
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
        //
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
    public function edit(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        $categoria->nome   = $request->nome;
        $categoria->nat_jur = $request->nat_jur;
        $categoria->tipo_cliente = $request->tipo_cliente;
        $categoria->cod_fiscal = limpa_cpf_cnpj($request->cod_fiscal);
        $categoria->localidade = $request->localidade;

        $categoria->update();
        return redirect('/home/showParceiro')->with('Success', 'Alteração Efetuada');
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
        //
    }
}
