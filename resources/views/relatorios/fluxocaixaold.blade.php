@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Gerar Fluxo de Caixa</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('fluxocaixa.gerar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="empresa_id" class="form-label">Empresa:</label>
                    <select class="form-select" id="empresa_id" name="empresa_id" required>
                        <option value="">Selecione uma empresa</option>
                        {{-- Supondo que $empresas seja passado do controller --}}
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}">{{ $empresa->id }}-{{ $empresa->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="data_inicial" class="form-label">Data Inicial:</label>
                    <input type="date" class="form-control" id="data_inicial" name="data_inicial" VALUE="2025-01-01" required>
                </div>

                <div class="mb-3">
                    <label for="data_final" class="form-label">Data Final:</label>
                    <input type="date" class="form-control" id="data_final" name="data_final"  VALUE="2025-12-31" required>
                </div>

                <button type="submit" class="btn btn-primary">Gerar Fluxo de Caixa</button>
            </form>
        </div>
    </div>
</div>
@endsection