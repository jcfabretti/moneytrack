@extends('adminlte::page')

@section('title', 'Plano de Contas')

@section('content_header')
@stop

@section('content')
   
    <head>
        {{ file_get_contents(public_path('appforms.css')) }}
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        </head>
    <style>
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
                        <tbody>
                            <body>
                                <div class="details">
                                    <div class="recentOrders">
                                        <ul class="tree">
                                            <li>
                                                <details open>
                                                    <summary><b>1.00.00 - ENTRADAS</b></summary>
                                                    <ul>
                                                        <li>
                                                            <details>
                                                            <summary><b>1.10.00 - COMBUSTIVEIS</b></summary>
                                                            <ul>
                                                                <li>1.10.10 - ETANOL</li>
                                                                <li>1.10.20 - GASOLINA</li>
                                                                <li>1.10.30 - DIESEL</li>
                                                            </ul>
                                                            </details>
                                                        </li>
                                                        <li>
                                                            <details>
                                                            <summary><b>1.20.00 - FRETES</b></summary>
                                                            <ul>
                                                                <li>1.20.10 - TRANSPORTE MATERIAIS</li>
                                                                <li>1.20.20 - TRANSPORTE FUNCIONARIOS</li>
                                                            </ul>
                                                            </details>
                                                        </li>
                                                    </ul>
                                                </details>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </body>        
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
    </body>
@stop

@section('css')

@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
