@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="{{ asset('js/categoria.js') }}"></script>
    <style>
        .modal-footer {
            background-color: #92AFC2;
        }

        .modal-header {
            background-color: #92AFC2;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background-color: #143f6b;
            color: #fff;
            text-align: left;
        }

        .level-1 {
            padding-left: 10px;
        }

        .level-2 {
            padding-left: 30px;
        }

        .level-3 {
            padding-left: 50px;
        }

        .action-icons {
            cursor: pointer;
            margin-right: 10px;
            font-size: 18px;
        }

        .content-wrapper {
            background-color: #E4EBF3 !important;
        }

        .action-icons.delete {
            color: red;
        }

        .action-icons.edit {
            color: orange;
        }
    </style>
@stop

@section('content')
    @php
        setlocale(LC_TIME, 'ptb'); // LC_TIME é formatação de data e hora com strftime()
        $dataAtual = \Carbon\Carbon::now()->format('Y-m-d');
        $nivelArray = ['Saldo Inicial', 'Grupo', 'Sub-Totais', 'Movimento'];
    @endphp
    {{-- Selecionar o tipo de categoria --}}
    <div class="form-group mb-3">
        <label for="tipoCategoria_select">Selecione Tabela de tipo de categoria:</label>
        <select class="form-control" id="tipoCategoria_select" name="tipoCategoria_select"
            onchange="recarregarTreeView(this.value)"> {{-- Removido o value="{{ $id_tipoCategoria }}" daqui --}}
            <option value="">Selecionar Tipo de Categoria</option>
            @foreach ($tipoPlanos as $tipoCateg)
                <option value="{{ $tipoCateg->id }}" {{ ($tipoCateg->id == $id_tipoCategoria) ? 'selected' : '' }}>
                    {{ $tipoCateg->id . '-' . $tipoCateg->nome }}
                </option>
            @endforeach
        </select>
    </div>
    <table id="treeViewContainer">
        <thead>
            <tr>
                <th>Categorias</th>
                <th>Alterado em</th>
                <th>Ações
                    {{-- /* Botão para incluir nova categoria e passa parametro de id e nome do tipo de categoria */ --}}
                    <button style="background: green; border: none; color: #fff; cursor: pointer;"
                        onclick="loadAddCategoria(
                                document.getElementById('tipoCategoria_select').value,
                                document.getElementById('tipoCategoria_select').options[document.getElementById('tipoCategoria_select').selectedIndex].text
                            )">
                        <i class="fas fa-plus-circle"></i> Incluir
                    </button>

                </th>
            </tr>
        </thead>
        {{-- CONTAINER PARA A TREE VIEW (O TBODY) --}}
        <tbody id="categoria-table">
            {{-- CORREÇÃO AQUI: Passa as variáveis $categorias e $id_tipoCategoria como são definidas no controller --}}
            @include('categoria.indextreeview', [
                'categorias' => $categorias,
                'tipoPlanos' => $tipoPlanos,
                'id_tipoCategoria' => $id_tipoCategoria,
            ])
        </tbody>
    </table>
    @include('categoria.modal.createModalCategoria')
    @include('categoria.modal.editModalCategoria')
    @include('categoria.modal.deleteModalCategoria')



    </html>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
