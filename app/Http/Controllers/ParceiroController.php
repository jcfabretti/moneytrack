<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parceiro;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParceirosExport;

class ParceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qtyPerPage=session('app.qtyItemsPerPage');
        if (request()->has('adminlteSearch')) {
          $searchstr = request()->get('adminlteSearch');
          $parceiros = Parceiro::where('nome', 'like', '%' . $searchstr . '%')->paginate($qtyPerPage);
      } else {

        $parceiros = Parceiro::where('id', '>', 0) // Adiciona a condição id > 0
                       ->orderBy('updated_at', 'DESC')
                       ->paginate($qtyPerPage);

      }
      return view('parceiro.showParceiro', compact('parceiros'));
    }

    public function listaParceiros()
    {
        $qtyPerPage=session('app.qtyItemsPerPage');
        if (request()->has('adminlteSearch')) {
          $searchstr = request()->get('adminlteSearch');
          $parceiros = Parceiro::where('nome', 'like', '%' . $searchstr . '%')->paginate($qtyPerPage);
      } else {
          $parceiros = Parceiro::orderBy('updated_at', 'DESC')->paginate($qtyPerPage);
      }
      return view('relatorios.listaParceiros', compact('parceiros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function searchParceiro() {
         


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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:45|unique:parceiros,nome',
            'tipo_cliente' => 'required|not_in:0',
            'nat_jur' => 'required|not_in:0',
            'cod_fiscal' => 'required|unique:parceiros,cod_fiscal',
            'localidade' => 'required',
            'status'     => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
        // Create the parceiro record in the database
        Parceiro::create([
            'nome' => $request->nome,
            'nat_jur' => $request->nat_jur,
            'tipo_cliente' => $request->tipo_cliente,
            'cod_fiscal' => limpa_cpf_cnpj($request->cod_fiscal),
            'localidade' => $request->localidade,
            'status' => $request->status
        ]);
    
        // Redirect to the specified route with a success message
        return redirect()->route('parceiro.index') // Use named route for better maintainability
                         ->with('success', 'Parceiro cadastrado com sucesso!');
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
    
  
        $parceiro = Parceiro::find($request->id);
        $parceiro->nome   = $request->nome;
        $parceiro->nat_jur = $request->nat_jur;
        $parceiro->tipo_cliente = $request->tipo_cliente;
        $parceiro->cod_fiscal = limpa_cpf_cnpj($request->cod_fiscal);
        $parceiro->localidade = $request->localidade;
        $parceiro->status = $request->status;    //1-Parceiro ativo 0- Parceiro Inativo
        $parceiro->update();

        return redirect('/home/showParceiro')->with('Success', 'Alteração Efetuada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    public function exportExcel()
    {
        // Define o nome do arquivo com um timestamp para torná-lo único
        $fileName = 'parceiros_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Usa a fachada Excel para iniciar o download como XLSX.
        return Excel::download(new ParceirosExport, $fileName);
    }

    /**
     * Exporta os dados da tabela 'parceiros' para um arquivo CSV.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCsv()
    {
        // Define o nome do arquivo com um timestamp para torná-lo único
        $fileName = 'parceiros_' . date('Y-m-d_H-i-s') . '.csv';

        // Usa a fachada Excel para iniciar o download como CSV.
        return Excel::download(new ParceirosExport, $fileName, \Maatwebsite\Excel\Excel::CSV);
    }



}
