@extends('adminlte::page')
@php
    use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Dashboard')

@section('content_header')

<h1></h1>
@stop

@section('content')

    {{--  IMPORTA ARQUIVO CSS --}}
    <style>
     {{ file_get_contents(public_path('appforms.css'))}}
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
                                <h2><STRONG>Grupos Economico</STRONG></b></h2>
                            </div>
                            <div class="col-xs-6">
                                <a href="#addGrupoEconModal" class="btn btn-success" data-toggle="modal"><i
                                        class="material-icons">&#xE147;</i> <span>Adicionar novo Parceiro</span></a>
                                <a href="#deleteGrupoEconModal" class="btn btn-danger" data-toggle="modal"><i
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
                            @foreach ($grupos as $grupo)
                                <tr>
                                    <td>
                                        <span class="custom-checkbox">
                                            <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                            <label for="checkbox1"></label>
                                        </span>
                                    </td>
                                    <th scope="row">{{ $grupo->id }} </th>
                                    <td>{{ $grupo->nome }}</td>
                                    <td>{{ $grupo->localidade }}</td>


                                    <td>
                                        <!--  <a href="#editEmpresaModal" class="edit"  data-toggle="modal" data-id="{{ $grupo->id }}">
                                          <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                     -->
                                     @include("grupos.edit")

                                        <!-- EDITAR -->
                                     <a class="btn btn-secondary" href="{{route('grupos.edit', $grupo->id)}}">{{('__Show')}}
                                      <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#ModalEdit">{{('__Edit')}}

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
        <!-- Create Modal HTML -->
        <div class="modal fade" id="addGrupoEconModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form name="formCad" id="formCad" method="POST" action="{{ url('/parceiros/create') }}">
                        @csrf
                        @method('post')
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong>CADASTRAR GRUPO</strong> </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nome dO Grupo</label>
                                <input type="text" name="nome" autofocus="autofocus" size = "45" maxlength = "45"
                                    style='text-transform:capitalize' class="form-control" required>
                            </div>

                        <div class="form-group">
                                <label for="localidade" class="col-form-label">Localdade:</label>
                                <input type="text" name="localidade" class="form-control" id="localidade">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-success" value="Add">Incluir</button>
                            </div>
                    </form>
                </div>

            </div>
        </div>
        </div>


        <!-- Delete Modal HTML -->
        <div id="deleteGrupoEcon" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <h4 class="modal-title">Delete Employee</h4>
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
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

    <scrip> console.log("Hi, I'm using the Laravel-AdminLTE package!");


    @stop
