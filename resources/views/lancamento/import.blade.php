@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Importar Lançamentos via CSV</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                            @if (session('errors_details'))
                                <ul>
                                    @foreach (session('errors_details') as $error_detail)
                                        <li>Linha {{ $error_detail['linha'] }}: {{ $error_detail['mensagem'] }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('lancamento.import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="csv_file" class="form-label">Selecione o arquivo CSV:</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                            @error('csv_file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Importar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection