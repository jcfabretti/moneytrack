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

</head>

<body>
    <style>
        /* Apply the same font family and size as the Navbar */
        .child-row ul,
        .child-row li {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
        }
    </style>
    <section class="formulario mt-0 pt-0">
        <table class="table mt-1" style="background-color: var(--primary-color);">
            <table class="table mt-1">

                <thead class="table thead tr">
                    <tr style="background-color: var(--primary-color);">
                        <th style="width: 45ch;">Categoria</th>
                        <th>Descrição</th>
                        <th>Ações &nbsp; &nbsp;
                            <span class="material-icons md-45" style="cursor: pointer;" onclick="loadCreateCategoria()"
                                title="Incluir">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" color="#86e676" viewBox="0 0 24 24"
                                    width="24">
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                                </svg>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ formatarNumeroCategoria($category->id) . '-' . $category->nome }}</td>
                        <td>{{ $category->descricao }}</td>
                        <td>
                            <span class="material-icons md-45" style="cursor: pointer;"
                                onclick="loadEditCategoria({{ $category->id }})" title="Editar">
                                edit
                            </span>
                            <span class="material-icons md-45" style="cursor: pointer;"
                                onclick="loadDeleteCategoria({{ $category->id }})" title="Excluir">
                                delete
                            </span>
                            @if(count($category->children))
                            <span class="material-icons md-45" style="cursor: pointer;"
                                onclick="toggleChildren({{ $category->id }})" title="Expandir/Contrair">
                                expand_more
                            </span>
                            @endif
                        </td>
                    </tr>

                    @if(count($category->children))
                    <tr class="child-row" id="children-{{ $category->id }}" style="display:none;">
                        <td colspan="3">
                            <ul>
                                @foreach($category->children as $child)
                                <li>
                                    @if ($child->level < 3) <i class="fa fa-plus-circle" style="cursor:pointer;"
                                        onclick="toggleChildren({{ $child->id }})"></i>
                                        <!-- keep Child id -->
                                        <?php
                                                $categChildren="children-".$child->id;
                                                ?>
                                        <!-- eof php -->
                                        @endif
                                        {{ formatarNumeroCategoria($child->id) . ' - ' . $child->nome }}
                                        @if(count($child->children))
                                        @include('planoconta.manageChild', ['childs' => $child->children])
                                        @endif
                                </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>

            <script>
                function toggleChildren(categoryId) {
                    var childrenRow = document.getElementById('children-' + categoryId);
                    // Ensure that the childrenRow exists before toggling
                    if (childrenRow) {
                        // Toggle the display between 'none' and 'table-row'
                        if (childrenRow.style.display === "none" || childrenRow.style.display === "") {
                            childrenRow.style.display = "table-row"; // Show the children
                        } else {
                            childrenRow.style.display = "none"; // Hide the children
                        }
                    } else {
                        console.warn("No child row found for category ID " + categoryId);
                    }
                }
            </script>
</section>
</body>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/styleForm.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@stop

@section('js')
<script>
    function loadCreateModal() {
        console.log('load');
        $('#addCategoria1').modal('show');
    };
</script>
@stop