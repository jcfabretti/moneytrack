@extends('adminlte::page')
@php
    use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')

    {{--  IMPORTA ARQUIVO CSS --}}
     
    <head>
    </head>

    <body>
        <div class="container">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row" id="rowTitle">
                            <div class="col-xs-6">
                                <h2><STRONG>Index de Parceiros</STRONG></b></h2>
                            </div>
                            <div class="col-xs-6">
                                <a href="{{ url('parceiros/create') }}" class="btn btn-success"><i
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
                                <th>Id</th>
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
                            @foreach ($partners as $partner)
                                <tr>
                                    <td>
                                        <span class="custom-checkbox">
                                            <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                            <label for="checkbox1"></label>
                                        </span>
                                    </td>

                                    <td scope="row">{{ $partner->id }} </td>
                                    <td>{{ $partner->nome }}</td>
                                    <td>{{ $partner->tipoCliente }}</td>
                                    <td>{{ $partner->natJur }}</td>
                                    <td>{{ $partner->CodFiscal }}</td>
                                    <td>{{ $partner->localidade }}</td>

                                    <td>
                                        <a href="{{ route('parceiro.edit',$partner->id) }}" class="edit" data-id="{{ $partner->id }}">
                                            <i class="material-icons" data-toggle="tooltip">&#xE254;</i></a>


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
