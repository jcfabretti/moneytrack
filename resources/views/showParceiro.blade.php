@extends('adminlte::page')
@php
    use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Dashboard')
@stop

@section('content_header')
<h1>TIMEZONE {{Config::get('app.globalNomeEmpresa')}};</h1>
@stop

@section('content')

    {{--  IMPORTA ARQUIVO CSS --}}
    <style>
        {{ file_get_contents(public_path('appforms.css')) }}
    </style>

    <head>
    </head>

    <body>
        <div class="container">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row" id="rowTitle">
                            <div class="col-xs-6">
                                <h2><STRONG>Cadastro de Empresas</STRONG></b></h2>
                            </div>
                            <div class="col-xs-6">
                                <a href="#addEmpresaModal" class="btn btn-success" data-toggle="modal"><i
                                        class="material-icons">&#xE147;</i> <span>Adicionar novo Parceiro</span></a>
                                <a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i
                                        class="material-icons">&#xE15C;</i> <span>Deletar</span></a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="custom-checkbox">
                                        <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                        <label for="checkbox1"></label>
                                    </span>
                                </td>
                                <!-- *********** LISTIN HEADER ********* -->
                                <th>Id#</th>
                                <th>Empresa</th>
                                <th>Tipo Cliente</th>
                                <th>Nat.Jurídica</th>
                                <th>CNPJ/CPF</th>
                                <th>Localidade</th>
                                <th>Ações</th>
                            </tr>
                            </thead>

                            <!-- -----------------------------------  -->
                            <!-- *********** LIST BODY ********* here -->
                            <!-- -----------------------------------  -->
                        <tbody>
                            @foreach ($parceiros as $parceiro)
                                <tr>
                                    <td>
                                        <span class="custom-checkbox">
                                            <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                            <label for="checkbox1"></label>
                                        </span>
                                    </td>
                                    <th scope="row">{{ $parceiro->id }} </th>
                                    <td>{{ $parceiro->nome }}</td>
                                    <td>{{ $parceiro->tipoCliente }}</td>
                                    <td>{{ $parceiro->natJur }}</td>
                                    <td>{{ $parceiro->CodFiscal }}</td>
                                    <td>{{ $parceiro->localidade }}</td>


                                    <td>
                                        @include('empresa.edit')
                                        <a href="{{ url('empresa.edit', ('partner')->$parceiros) }}"
                                            style="text-align:center" data-target="#editEmpresaModal">Edit</a>

                                        <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i
                                                class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                        <ul class="pagination">
                            <li class="page-item disabled"><a href="#">Previous</a></li>
                            <li class="page-item"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                            <li class="page-item active"><a href="#" class="page-link">3</a></li>
                            <li class="page-item"><a href="#" class="page-link">4</a></li>
                            <li class="page-item"><a href="#" class="page-link">5</a></li>
                            <li class="page-item"><a href="#" class="page-link">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- ############################################################################################### -->
        <!-- Delete Modal HTML -->
        <div id="deleteEmployeeModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <h4 class="modal-title">Delete Employee</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete these Records?</p>
                            <p class="text-warning"><small>This action cannot be undone.</small></p>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <input type="submit" class="btn btn-danger" value="Delete">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

    </html>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@stop

@section('js')

    <scrip> console.log("Hi, I'm using the Laravel-AdminLTE!-em uso");


    @stop
