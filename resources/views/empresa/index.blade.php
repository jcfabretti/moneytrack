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

        <script src="{{ asset('js/script.js') }}"></script>
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
        <!-- Display Laravel error message at the top -->
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @elseif ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table mt-1">
            <thead class="table thead tr">
                <tr>
                    <!-- *********** LIST HEADER ********* -->
                    <th>Id #</th>
                    <th>Nome da Empresa</th>
                    <th>Grupo Economico</th>
                    <th>CNPJ/CPF</th>
                    <th>Localidade</th>
                    <th>Tabela de Categorias</th>
                    <th>Data Criado</th>
                    <th>açoes &nbsp &nbsp<span class="material-icons md-45" style="cursor: pointer;"
                            onclick="loadCreateEmpresa()" title="Incluir">
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
                @forelse ($empresas as $empresa)
                    <tr>
                        <td>{{ $empresa->id }}</td>
                        <td>{{ $empresa->nome }}</td>
                        <td>{{ $empresa->grupoEconomico->nome }}</td>
                        <td>{{ formatar_cpf_cnpj(intval($empresa->cod_fiscal), 2) }}</td>
                        <td>{{ $empresa->localidade }}</td>
                        <td>{{ $empresa->tiposPlanoConta->nome }}</td>
                        <td>{{ $empresa->created_at->format('d-m-Y') }}
                            {!! updateMessage($empresa->updated_at) !!}
                        </td>
                        <td>
                            <a href="#" class="edit"
                                onclick="loadEditEmpresa({{ $empresa->id }}, `{{ $empresa->nome }}`, `{{ $empresa->grupo_economico_id }}`,`{{ $empresa->cod_fiscal }}`,`{{ $empresa->localidade }}`)"
                                data-toggle="modal" id="editarEmpresaBtn"><i class="material-icons" data-toggle="tooltip"
                                    title="Edit">&#xE254;</i></a>

                            <div class="btn-group btn-group-sm">
                                <a href="" class="delete"
                                    onclick="loadDeleteEmpresa({{ $empresa->id }}, `{{ $empresa->nome }}`)"
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

        </div>
    </section>
    @include('empresa.modal.createEmpresaModal')
    @include('empresa.modal.editEmpresaModal')
    @include('empresa.modal.deleteEmpresaModal')

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
        function loadCreateEmpresa() {
            const textInputs = document.querySelectorAll('.modal-body input[type="text"], .modal-body input[type="date"], .modal-body input[type="hidden"]');
            textInputs.forEach(input => {
            input.value = '';
        });
            $('#addEmpresaModal').modal('show');
        };
        // ######################################
        //  EDIT MODAL
        function loadEditEmpresa(id, nome, grupo_econ, cod_fiscal, localidade, status) {
            console.log(nome);
            var codfiscalformatado = formatCnpjCpf(cod_fiscal);
            $('.modal-body #id').val(id);
            $('.modal-body #nome').val(nome);
            $('.modal-body #grupoEcon').val(grupo_econ);
            $('.modal-body #cod_fiscal').val(codfiscalformatado);
            $('.modal-body #localidade').val(localidade);
            $('#editEmpresaModal').modal('show');
        };

        function loadDeleteEmpresa(id, nome) {
            $('#modal-category_name').html(nome);
            $('#modal-confirm_delete').attr('onclick', `confirmDelete(${id})`);
            $('#deleteCategory').modal('show');
        };
        // #########################################################################################################
        //  DELETE MODAL
        function confirmDelete(id) {
            $.ajax({
                url: '/empresas/destroy/' + id,
                type: 'delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#success_message').addClass('alert alert-sucess');
                    $('success_message').text(response.message);
                    $('#deleteCategory').modal('hide');
                    location.reload();
                }
            });
        };
        // #########################################################################################################
        // FORMAT CNPJ / CPF create / edit
document.addEventListener('DOMContentLoaded', function() {
    // Get the element by its ID
    const codFiscalInput = document.getElementById('cod_fiscal');

    // Check if the element was found before trying to add an event listener
    if (codFiscalInput) {
        // Use 'input' event for real-time formatting as the user types
        // Or 'keyup' if 'input' causes issues with cursor position
        codFiscalInput.addEventListener('input', function(e) { 
            let value = e.target.value.replace(/\D/g, ''); // Remove all non-digits

            // Assuming this is for CNPJ (14 digits)
            if (value.length > 11) { // CNPJ format (e.g., 00.000.000/0000-00)
                let x = value.match(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/);
                if (x) { // Check if the match was successful
                    e.target.value = `${x[1]}.${x[2]}.${x[3]}/${x[4]}-${x[5]}`;
                } else { // Handle cases where it doesn't fit the exact CNPJ pattern yet
                    x = value.match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})/);
                    e.target.value = !x[2] ? x[1] : x[1] + '.' + x[2] + (x[3] ? '.' : '') + x[3] + (x[4] ? '/' : '') + x[4] + (x[5] ? '-' + x[5] : '');
                }
            } else { // Assuming this is for CPF (11 digits)
                let x = value.match(/^(\d{3})(\d{3})(\d{3})(\d{2})$/);
                if (x) { // Check if the match was successful
                    e.target.value = `${x[1]}.${x[2]}.${x[3]}-${x[4]}`;
                } else { // Handle cases where it doesn't fit the exact CPF pattern yet
                    x = value.match(/(\d{0,3})(\d{0,3})(\d{0,3})(\d{0,2})/);
                    e.target.value = !x[2] ? x[1] : x[1] + '.' + x[2] + (x[3] ? '.' : '') + x[3] + (x[4] ? '-' + x[4] : '');
                }
            }
        });
    }
});


    </script>
@stop
