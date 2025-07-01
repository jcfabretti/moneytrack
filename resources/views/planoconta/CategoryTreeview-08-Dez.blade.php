@extends('adminlte::page')

@section('title', 'Plano de Contas')
<?php
$companyNum=session()->get('app.company');
?>

@section('content_header')

@stop

@section('content')

<head>
    <title>Laravel Category Treeview Example</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="/css/treeview.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <table class="table mt-1">
            <thead class="thead-dark">
                <tr style="background-color: var(--primary-color);">
                    <th style="width: 45ch;">Categoria</th>
                    <th>Descrição</th>
                    <th>Ações &nbsp; &nbsp;
                        <span class="material-icons md-45" style="cursor: pointer;" onclick="loadCreateCategoria()" title="Incluir">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" color="#86e676" viewBox="0 0 24 24" width="24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                            </svg>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul id="tree1">
                                            @foreach($categories as $category)
                                                <li>
                                                    <i class="fa fa-plus-circle"></i>
                                                    {{ formatarNumeroCategoria($category->id) . '-' . $category->nome }}
                                                    @if(count($category->children))
                                                        @include('planoconta.manageChild', ['childs' => $category->children])
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <script src="/js/treeview.js"></script>
    
</body>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}

@stop

@section('js')
<script>
    function loadCreateModal() {
        console.log('load');
        $('#addCategoria1').modal('show');
    };
</script>
@stop