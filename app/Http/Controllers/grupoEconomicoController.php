<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\GrupoEconomico;
use Illuminate\Support\Facades\Log;

class grupoEconomicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupos = GrupoEconomico::get();
        return view('grupoEconomico.index-grupo', compact('grupos'));
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
        try {
            GrupoEconomico::create([
            'nome' => $request->nome,
            'localidade' => $request->localidade
        ]);
            return redirect()->back()->with('success', $request->nome . ' - Grupo cadastrado com sucesso!');

        } catch (\Exception $e) {
            // Catch any other unexpected errors
            Log::error('Error storing GrupoEconomico: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao cadastrar o grupo. Por favor, tente novamente.');
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
    public function edit(Request $request)
    {
        $id = $request->editGrupoEconomicoId;
         try {
            GrupoEconomico::where('id', $id)->update([
                'nome' => $request->nome_grupo_economico,
                'localidade' => $request->editLocalidade
            ]);
            return redirect()->back()->with('success', $request->nome_grupo_economico . ' - Grupo atualizado com sucesso!');

        } catch (\Exception $e) {
            // Catch any other unexpected errors
            Log::error('Error storing GrupoEconomico: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao cadastrar o grupo. Por favor, tente novamente.');
        }

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
    public function destroy(Request $request)
    {
       
        $temEmpresas = Empresa::where('grupo_economico_id', $request->deleteGrupo_id)->count();
        if ($temEmpresas > 0) {
            // Se o grupo econômico tem empresas associadas, não é possível excluí-lo
            return redirect()->route('index.grupoeconomico')->with('error', $request->deleteGrupo_nome . ' - Não é possível excluir o grupo econômico porque ele possui empresas associadas.');
        }

        $grupo = GrupoEconomico::findOrFail($request->deleteGrupo_id);
        if (!$grupo) {
            // Se o grupo econômico não for encontrado, retornar uma mensagem de erro
            return redirect()->route('index.grupoeconomico')->with('error', $request->deleteGrupo_nome . '- Grupo Econômico não encontrado.');
        }       
       
       $grupo->delete();
        
        // Retornar uma resposta adequada, como um redirecionamento ou uma mensagem de sucesso
        return redirect()->route('index.grupoeconomico')->with('success', $request->deleteGrupo_nome . ' - Grupo Econômico excluído com sucesso.');

    }
}
