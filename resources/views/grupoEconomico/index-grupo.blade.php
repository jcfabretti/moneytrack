@extends('adminlte::page')
@php
    use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Dashboard')

@section('content_header')

    <head>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    </head>

@stop

@section('content')
    <style>
        .form-select {
            border-color: rgb(225, 220, 220);
            height: 40px;
        }
    </style>

    <body>
        <section class="formulario mt-0 pt-0">
            {{-- Display general success or error messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table class="table mt-1">
                <thead class="table thead tr">
                    <tr>
                        <!-- *********** LIST HEADER ********* -->
                        <th>Id #</th>
                        <th>Grupo Economico</th>
                        <th>Localidade</th>
                        <th>Data Criado</th>
                        <th>açoes &nbsp &nbsp<span class="material-icons md-45" style="cursor: pointer;"
                                onclick="loadCreateGrupo()" title="Incluir">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" color="#86e676" viewBox="0 0 24 24"
                                    width="24">
                                    <path d="M0 0h24v24H0z" fill="#86e676" />
                                    <path
                                        d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                                </svg>
                            </span>
                        </th>
                    </tr>
                </thead>
                <!-- *********** LIST BODY ********* here -->
                <tbody>
                    @forelse ($grupos as $grupo)
                        <tr>
                            <td>{{ $grupo->id }}</td>
                            <td>{{ $grupo->nome }}</td>
                            <td>{{ $grupo->localidade }}</td>
                            <td>{{ $grupo->created_at->format('d-m-Y') }}</td>
                            <td>
                                <a href="#" class="edit-grupo"
                                    onclick="loadEditGrupo({{ $grupo->id }}, `{{ $grupo->nome }}`,`{{ $grupo->localidade }}`)"
                                    data-toggle="modal" id="editarGrupoEcon"><i class="material-icons" style="color: orange;" data-toggle="tooltip"
                                        title="Edit">&#xE254;</i></a>

                                <div class="btn-group btn-group-sm">
                                    <a href="" class="delete-grupo"
                                        onclick="loadDeleteGrupo({{ $grupo->id }}, `{{ $grupo->nome }}`)"
                                        data-toggle="modal"><i class="material-icons" style="color: #F44336;"data-toggle="tooltip"
                                            title="Delete">&#xE872;</i></a>
                            </td>
                        </tr>
                    @empty
                        <p>Nada Cadastrado</p>
                    @endforelse
                </tbody>

            </table>

            <div class="tableFooter">

            </div>
        </section>
        @include('grupoEconomico.createModalGrupo')
        @include('grupoEconomico.editModalGrupo')
        @include('grupoEconomico.deleteModalGrupo')
    </body>
@stop

<!--######################### CSS / JS Section  ##################################### -->
@section('css')

@stop

@section('js')

    <script type="text/javascript">
        // ######################################
        //  CREATE PARCEIRO MODAL
        function loadCreateGrupo() {
            console.log("loadCreateGrupo called");
            $('#nome').val("");
            $('#localidade').val("");
            $('#addModalGrupo').modal('show');
            $('#addModalGrupo').on('shown.bs.modal', function() {
                $('.modal-body #createGrupoNome').focus();
            });

        };
        // ######################################
        //  EDIT MODAL
        function loadEditGrupo(id, nome, localidade) {
            $('#editGrupoEconomicoId').val(id); // Matches the new ID for the hidden input
            $('#editNomeGrupoEconomico').val(nome); // Matches the new ID for "Nome Grupo Economico"
            $('#editLocalidade').val(localidade); // Matches the new ID for "Localidade"
            $('#editModalGrupo').modal('show');
            $('#editModalGrupo').on('shown.bs.modal', function() {
                $('.modal-body #editNomeGrupoEconomico').focus();
            });
        };

        function loadDeleteGrupo(id, nome) {
            $('#deleteGrupo_id').val(id);
            $('#deleteGrupo_nome_display').text(nome);
            $('#deleteGrupo_nome').val(nome);
            // Show the delete confirmation modal
            $('#deleteGrupoEconomico').modal('show');
        }

        function capitalizeInput(input) {
            // const input = document.getElementById("nome");
            input.value = input.value.toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(
                ' ');
        }
    </script>
@stop
