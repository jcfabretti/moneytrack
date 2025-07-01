@extends('adminlte::page')

@section('title', 'Plano de Contas')
<?php
$companyNum=session()->get('app.company');
?>

@section('content_header')

@stop

@section('content')

    {{--  IMPORTA ARQUIVO CSS --}}
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
        <div class="container">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row" id="rowTitle">
                            <div class="col-xs-6 ">
                                <h2>Plano de <b>Contas</b></h2>
                            </div>
                            <div class="col-xs-6 ">
                                <a href="#addEmpresaModal" class="btn btn-success" data-toggle="modal"><i
                                        class="material-icons">&#xE147;</i> <span>Incluir nova Empresa</span></a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Numero<i class="fa fa-sort-alpha-asc"
                                            aria-hidden="true"></i></a> </th>
                                <th>Nome Conta</th>
                                <th>Nível</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($contas as $conta)
                                <tr>
                                    <td>{{ numeroConta($conta->conta) }}</td>
                                    <td>{{$conta->nome }}</td>
                                    <td>{{ $conta->nivel}}</td>
                                    <td>
                                        <a href="#" class="edit"
                                            onclick="loadEditModal({{ $conta->id }}, `{{ $conta->numero_conta }}`, `{{ $conta->nome }}`)"
                                            data-toggle="modal" id="editplanoBtn"><i class="material-icons"
                                                data-toggle="tooltip" title="Edit">&#xE254;</i></f=>

                                            <div class="btn-group btn-group-sm">
                                                <a href="" class="delete"
                                                    onclick="loadDeleteModal({{ $conta->id }}, `{{ $conta->nome }}`)"
                                                    data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                                        title="Delete">&#xE872;</i></a>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ############################################################################################# --}}
        <!-- Create Modal HTML -->
        <div id="addEmpresaModal" class="modal fade">
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
                                <input type="text" class="form-control" name="nome" id="nome" value=""
                                    required>
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
                        action="{{ route('planocontas.update', $conta->id) }}">
                        @csrf
                        @method('put')

                        <div class="modal-header">
                            <h4 class="modal-title">Alterar Empresa</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>

                        <input type="hidden" id="M_Id" name="M_Id" />
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nome da Empresa</label> <strong> <span id="M_Idmodal-category_name"></span>
                                </strong>
                                <input type="text" class="form-control" name="Mnome" id="Mnome" required>
                            </div>
                            <div class="form-group">
                                <label>CNPJ/CPF</label>
                                <input type="text" class="form-control" name="McodFiscal" id="McodFiscal" required>
                            </div>
                            <div class="form-group">
                                <label>Localidade</label>
                                <input type="text" class="form-control" name="Mlocalidade" id="Mlocalidade" required>
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
                            <button type="button" a href="{{ route('planoconta.destroy', $conta->id) }}"
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
        console.log("Hi, I'm using the Laravel-AdminLTE package!"); <
        /scrip>
    @stop
