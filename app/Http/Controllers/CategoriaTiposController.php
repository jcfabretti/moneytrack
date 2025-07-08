<?php

namespace App\Http\Controllers;

use App\Models\CategoriaTipo;
use App\Models\Categoria;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoriaTiposController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = CategoriaTipo::all();
        return view('categoriaTipos.index-tipos', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // modal carregado via JAVAscript
    }


     // armazenar novo Tipo de Categoria
    public function store(Request $request)
    {
        // Check if the name already exists //
        $checkNome = $request->nome;
        $checkNome = CategoriaTipo::where('nome', $checkNome)->get();
        if (count($checkNome) > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Já existe uma categoria com esse nome.']);
        }

        // Store the new CategoriaTipo //
        try {
            $newTipo = new CategoriaTipo([
                'nome' => $request->nome,
            ]);

            $newTipo->save(); // Salva o novo Tipo de Categoria no banco de dados
            
            // *** Refatoração: Adicionar os 2 registros à tabela Categoria ***
            $tipoCategoriaId = $newTipo->id; // Pega o ID do CategoriaTipo recém-criado

            // Insere registros basicos na tabela Categoria
            // 1. Registro "Sem Categoria"
            $semCategoria = new Categoria([
                'id'                 => (string)$tipoCategoriaId . '99999', // Concatenando id do tipo com codigo da categoria
                'nome'               => 'Sem Categoria',
                'categoria_pai'      => 0,
                'nivel'              => 0,
                'fk_tipocategoria_id' => $tipoCategoriaId,
            ]);
            $semCategoria->save();

            // 2. Registro "ENTRADAS"
            $semCategoria = new Categoria([
                'id'                 => (string)$tipoCategoriaId . '10000', // Concatenando id do tipo com codigo da categoria
                'nome'               => 'ENTRADAS',
                'categoria_pai'      => 0,
                'nivel'              => 1,
                'fk_tipocategoria_id' => $tipoCategoriaId,
            ]);
            $semCategoria->save();

            // 3. Registro "Saldo Inicial"
            $saldoInicial = new Categoria([
                'id'                 => (string)$tipoCategoriaId . '00000',
                'nome'               => 'Saldo Inicial',
                'categoria_pai'      => 0,
                'nivel'              => 0,
                'fk_tipocategoria_id' => $tipoCategoriaId,
            ]);
            $saldoInicial->save();

            return redirect()->route('categoria.tipos.index')
                ->with('success', 'Tipo de Categoria e categorias padrão cadastradas com sucesso.');
        } catch (\Exception $e) {
            // Log the error for debugging (recommended)
            Log::error("Erro ao cadastrar Tipo de Categoria ou categorias padrão: " . $e->getMessage());

            // Return a user-friendly error message
            return redirect()->back() // Redirect back to the form
                ->withInput() // Send the input data back to the form
                ->withErrors(['error' => 'Ocorreu um erro ao cadastrar o tipo de categoria e/ou categorias padrão.']); // Or a more specific message
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
    public function edit(Request $request, string $id)
    {

    }

    // ALTERA O Tipo de Categoria
    public function update(Request $request)
    {
        try {
           // dd($request->id);
            $tipo = CategoriaTipo::find($request->id);
            if (!$tipo) {
                return redirect()->back()
                    ->with('info', $request->nome . '-ALTERAÇAO não efetuda. Tipo Não localizado-tente novamente');
            }
            $tipo->nome   = $request->nome;
            $tipo->update();
            return redirect()->route('categoria.tipos.index')
            ->with('success', $request->nome . '-Alterado com sucesso!');            
       
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()
                ->with('error', '500-Ocorreu um erro ao alterar a categoria.');
        }
    }

    //  EXCLUI Tipo de Categoria.
    public function destroy(Request $request)
    {
        $id = $request->id;
        // Procura o Tipo de Categoria pelo ID
        $categoriaTipo = CategoriaTipo::find($id);
        if (!$categoriaTipo) {
            return redirect()->back()
            ->with('info', $request->nome . '-EXCLUSÃO não efetuda. Tipo Não localizado-tente novamente');
        }
        // Verifica se o Tipo de Categoria está em uso por alguma empresa
        $checkEmpresa = Empresa::where('tipos_planocontas_id', $id)->get();
        if (count($checkEmpresa) > 0) {
            return redirect()->back()
                ->with('info', $request->nome . '-EXCLUSÃO não efetuda. Este Tipo de Categoria está em uso por ' .count($checkEmpresa). ' empresa(s)');
        }

        try {
            // Deleta o Tipo de Categoria
            $categoriaTipo->delete();
            return redirect()->route('categoria.tipos.index')
                ->with('success', $request->nome . '-Excluido com sucesso!');            

            }
        // Se ocorrer um erro, loga o erro e retorna uma mensagem de erro amigável    
        catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()
                ->with('error', '500-Ocorreu um erro ao excluir a categoria.');
        }
    }
}
