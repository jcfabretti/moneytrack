@extends('adminlte::page')
@php
    use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Usuarios')

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
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Criado em</th>
                        <th>açoes &nbsp &nbsp<span button type="button" class="btn btn-success" onclick="loadRegisterUser()">Novo Usuário</button>
                            
                            </span>
                        </th>
                    </tr>
                </thead>
                <!-- *********** LIST BODY ********* here -->
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d-m-Y') }}</td>

                        </tr>
                    @empty
                        <p>Nada Cadastrado</p>
                    @endforelse
                </tbody>

            </table>

            <div class="tableFooter">

            </div>
        </section>

    </body>
@stop

<!--######################### CSS / JS Section  ##################################### -->
@section('css')

@stop

@section('js')

    <script type="text/javascript">
        // ######################################
        //  CREATE PARCEIRO MODAL
        function loadRegisterUser() {
            console.log("loadCreateGrupo called");
            $('#name').val("");
            $('#email').val("");
            $('#password').val("");
            $('#password_confirmation').val("");
            $('#registerUserModal').modal('show');
            $('#registerUserModal').on('shown.bs.modal', function() {
                $('.modal-body #name').focus();
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


<!--######################### CSS / JS Section  ##################################### -->
@section('css')

@stop

@section('js')
@stop