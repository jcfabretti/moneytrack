<?php

namespace App\Http\Controllers;

use App\Models\CategoriaTipo;
use App\Models\TiposPlanoConta;
use Illuminate\Http\Request;

class CategoriaTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(TiposPlanoConta $tiposPlanoConta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TiposPlanoConta $tiposPlanoConta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TiposPlanoConta $tiposPlanoConta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TiposPlanoConta $tiposPlanoConta)
    {
        //
    }

    public function getTiposPlanoContas()
    {
        // Fetch all TiposPlanoConta records
        $tiposPlanoContas = CategoriaTipo::all();

        // Return the records as JSON
        return response()->json($tiposPlanoContas);
    }
}
