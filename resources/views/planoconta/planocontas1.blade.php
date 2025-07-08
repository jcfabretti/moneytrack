@extends('adminlte::page')

@section('title', 'Plano de Contas')
<?php
$companyNum=session()->get('app.company');
?>

@section('content_header')

@stop

@section('content')

{{-- IMPORTA ARQUIVO CSS --}}
<style>
{{ file_get_contents(public_path('appforms.css')) }}
</style>

<head>
    <!-- Latest compiled and minified CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style>
    .set-width {
        width: 220px;
    }
</style>

<body>
    <div class="table-responsive mt-0">
        <div class="table-wrapper">

            <table class="table table-bordered table-striped table-hover P-3">
                <thead>
                    <tr style="background-color:#1824c2; color:#ffffff;">
                        <th>Categoria</th>
                        
                        <th>Nome Conta</th>
                        <th>Nivel</th>
                        <th>Conta Pai</th>
                        <th style="width:10%" valign="middle">
                            Ações
                      
                            <a href="#" class="edit"
                            onclick="loadCreateModal()"
                            data-toggle="modal" id="editPlano1Btn">                                
                            <i class="material-icons outlined" valign="middle"
                                    style="color:rgb(243, 248, 243); font-size: 26px">add_box</i>
                            </a>
                      
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($planoContas as $conta)
                    <tr>
                        <td>{{ $conta->numero_categoria }}</td>
                        <td>{{ $conta->nome }}</td>
                        <td>{{ $conta->nivel}}</td>
                        <td>{{ $conta->categoria_pai}}</td>
                        <td>
                            <a href="#" class="edit" data-id="{{ $conta->numero_categoria}}"
                                onclick="loadEditModal({{ $conta->numero_categoria}}, `{{ $conta->nome }}`, `{{ $conta->nivel}}`, `{{ $conta->categoria_pai}}`)"
                                data-toggle="modal" id="editPlano2Btn"><i class="material-icons" data-toggle="tooltip"
                                    title="Edit">&#xE254;</i></f=>

                           <a href="#" class="delete" data-id="{{ $conta->numero_categoria}}"
                                        onclick="loadDeleteModal({{ $conta->numero_categoria }}, `{{ $conta->nome }}`)"
                                        data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                            title="Delete">&#xE872;</i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                       <td></td>
                       <td><b>Não possui categorias cadastradas!</b></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
 
    {{-- ############################################################################################# --}}
    <!-- Create Modal HTML -->
    <div id="addCategoriaModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form name="createEmpresa" method="POST" action="{{ url('/empresas') }}">
                    @method('post')
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Incluir Empresa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id" />
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value="" required>
                            @error('nome')
                            <span class="fs-6 text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>CNPJ/CPF</label>
                            <input type="text" class="form-control" name="codFiscal" id="codFiscal" required>
                            @error('codFiscal')
                            <span class="fs-6 text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Localidade</label>
                            <input type="text" class="form-control" name="localidade" id="localidade" required>
                            @error('localidade')
                            <span class="fs-6 text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-success" value="Gravar">

                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ############################################################################################# --}}
    <!-- Edit Modal HTML -->
    <div id="editModalForm" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">

                <form name="formEdit" id='editFormID' method="Post"
                    action="{{ route('planocontas.update', '1')}}">
                    @csrf
                    @method('put')

                    <div class="modal-header">
                        <h4 class="modal-title">Alterar Empresa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <input type="hidden" id="M_Id" name="M_Id" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nome da Conta</label> <strong> <span id="M_Idmodal-category_name"></span>
                            </strong>
                            <input type="text" class="form-control" name="Mnome" id="Mnome" required>
                        </div>
                        <div class="form-group">
                            <label>Nivel</label>
                            <input type="text" class="form-control" name="Mnivel" id="Mnivel" required>
                        </div>
                        <div class="form-group">
                            <label>Conta Pai</label>
                            <input type="text" class="form-control" name="Mcategoria_pai" id="Mcategoria_pai" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-info" value="Alterar">
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ############################################################################################# --}}
    <!-- Delete Modal HTML -->
    <div class="modal fade" id="deleteCategory" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="deleteCategory" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form name="deleteEmpresa" method="POST" action="{{ url('/planoconta/destroy') }}">
                    @method('post')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">EXCLUSÃO DE EMPRESA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Confirma Exclusão: <strong> <span id="modal-category_name"></span> </strong>
                        <input type="hidden" id="category" name="category_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-white" data-dismiss="modal">Cancelar</button>
                        <button type="button" a href="{{ route('planoconta.destroy', '1') }}"
                            class="btn btn-danger" id="modal-confirm_delete">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
    console.log("Hi, I'm using the Laravel-AdminLTE package!"); 

        // #########################################################################################################
        //  CREATE/ADD MODAL
        function loadCreateModal() {
            $('#addCategoriaModal').modal('show');
        };
        // #########################################################################################################
        //  EDIT MODAL
        function loadEditModal(id, nome, nivel,categoria_pai) {
            $('#M_Id').val(id);
            $('#Mnome').val(nome);
            $('#Mnivel').val(nivel);
            $('#Mcategoria_pai').val(categoria_pai);
            $('#editModalForm').modal('show');
        };

        function loadDeleteModal(id, nome) {
            $('#modal-category_name').html(nome);
            $('#modal-confirm_delete').attr('onclick', `confirmDelete(${id})`);
            $('#deleteCategory').modal('show');
        }
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
        }
    </script>    
@stop