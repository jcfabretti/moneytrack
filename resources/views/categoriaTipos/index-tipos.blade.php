@extends('adminlte::page')

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
                        <th>Nome do Conjunto de Categoria</th>
                        <th>Data Alterado</th>
                        <th>açoes &nbsp &nbsp<span class="material-icons md-45" style="cursor: pointer;"
                                onclick="loadCreateTipoCateg()" title="Incluir">
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
                    @forelse ($tipos as $tipo)
                        <tr id="row-{{ $tipo->id }}">
                            <td>{{ $tipo->id }}</td>

                            <td>
                                {{ $tipo->nome }}
                            </td>

                            <td>{{ optional($tipo->updated_at)->format('d-m-Y') ?? 'N/A' }}
                                {!! updateMessage($tipo->updated_at) !!}
                            </td>

                            <td>
                                <a href="#" class="edit"
                                    onclick="loadEditCategTipo({{ $tipo->id }}, `{{ $tipo->nome }}`, `{{ $tipo->tipo_economico_id }}`,`{{ $tipo->cod_fiscal }}`,`{{ $tipo->localidade }}`)"
                                    data-toggle="modal" id="editButton"><i class="material-icons" data-toggle="tooltip"
                                        title="Edit">&#xE254;</i></a>

                                <div class="btn-group btn-group-sm">
                                    <a href="" class="delete" id="deleteBtn"
                                        onclick="loadDeleteCategTipo({{ $tipo->id }}, `{{ $tipo->nome }}`)"
                                        data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                            title="Delete">&#xE872;</i></a>
                            </td>
                        </tr>
                    @empty
                        <p>Nada Cadastrado</p>
                    @endforelse

                </tbody>

            </table>

            <div class="tableFooter">
                {{-- table footer --}}
            </div>
        </section>
        @include('categoriaTipos.createModal')
        @include('categoriaTipos.editModal')
        @include('categoriaTipos.deleteModal')

    </body>
@stop

<!--######################### CSS / JS Section  ##################################### -->
@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
@stop

@section('js')

    <script type="text/javascript">
        // ######################################
        //  CREATE PARCEIRO MODAL
        function loadCreateTipoCateg() {
            $('.modal-body #create_nome').val('');
            $('#addTipoCategoriaModal').modal('show');
            $('#addTipoCategoriaModal').on('shown.bs.modal', function() {
                $('.modal-body #create_nome').focus();
            });
        };
        // ######################################
        //  EDIT MODAL
        function loadEditCategTipo(id, nome) {
            $('.modal-body #edit_id').val(id);
            $('.modal-body #edit_nome').val(nome);
            $('#editTiposModal').modal('show');
        };

        function loadDeleteCategTipo(id, nome) {
            $('.modal-body #delete_id').val(id);
            $('.modal-body #delete_nome').val(nome);
            $('#deleteCategoriaTipos').modal('show');
        };
    </script>
@stop
