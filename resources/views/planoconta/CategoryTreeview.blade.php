@extends('adminlte::page')
@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'CategoryTreeview')

@section('content_header')

<head>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

@stop

@section('content')
<style>
    /* 
        --ESTAVA SENDO USADO CSS DO BOOTSTRAP NO INPUT--  
            class="form-control rounded col-md-3" 
            class="input-group-text border-0"  
    */
    /* Change background color of <td> tags to light gray */
    .child-row {
        display: none;
    }

    
</style>

<body>
    @php
    $codigo_planoCategoria=session('app.empresaCodPlanoConta');
    setlocale(LC_TIME, 'ptb'); // LC_TIME é formatação de data e hora com strftime()
    $dataAtual = \Carbon\Carbon::now()->format('Y-m-d');
    $nivelArray=['Saldo Inicial','Grupo','Sub-Totais','Movimento'];
    @endphp

<section class="formulario mt-0 pt-0">
  <table class="table mt-1">
      <thead class="table thead tr">
          <tr style="background-color: --primary-color;">
              <th style="width: 500px;">Categoria</th>
              <th>
                  <button type="button" class="btn btn-primary">Incluir</button>
                  <button type="button" class="btn btn-success">Editar</button>
                  <button type="button" class="btn btn-danger">Deletar</button>
              </th>
              <th style="width: 100px;">
              </th>
          </tr>
      </thead>

      <tbody>
          @foreach($categories as $category)
          <tr>
              <td><strong>
                      @if(count($category->children))
                      <span class="material-icons md-45" id="toggle-icon-{{ $category->id }}"
                          style="cursor: pointer;" onclick="toggleChildren({{ $category->id }})"
                          title="Expandir/Contrair">
                          expand_more
                      </span>
                      @endif
                      <span class="custom-checkbox">
                          <input type="checkbox" id="checkbox-{{ $category->id }}" onchange="uncheckOthers(this)"
                              value="1">
                          <label for="checkbox-{{ $category->id }}"></label>
                      </span>
                      {{ formatarNumeroCategoria($category->id) . '-' . $category->nome }}
                  </strong>
              </td>

              <td>
                  <!-- ACTIONS - EDIT / DELETE -->
                  <div style="display: flex; gap: 10px;">
                      <span class="material-icons md-45" style="cursor: pointer;"
                          onclick="loadEditCategoria({{ $category->id }})" title="Editar">
                          edit
                      </span>
                      <span class="material-icons md-45" style="cursor: pointer;"
                          onclick="loadDeleteCategoria({{ $category->id }})" title="Excluir">
                          delete
                      </span>
                  </div>
                  <!--  End of ACTIONS -->
              </td>
              <td> >>>> </td>
          </tr>

          @if(count($category->children))
          <tr class="child-row" id="children-{{ $category->id }}" style="display:none;">
              <td colspan="3">
                  <ul style="list-style: none;">
                      @foreach($category->children as $child)
                      <li>
                          @if ($child->level < 3) <i class="material-icons md-45" title="Expandir/Contrair"
                              style="cursor:pointer;" onclick="toggleChildren({{ $child->id }})">expand_more</i>
                              <?php
                                  $categChildren="children-".$child->id;
                                  $strItem=(formatarNumeroCategoria($child->id) . ' - ' . $child->nome);
                                  $strlen=strlen($strItem);
                                  $strSize=(int)100-$strlen;
                                  $secondString=str_pad($strItem, $strSize, " ", STR_PAD_RIGHT);
                              ?>
                              @endif

                              <strong class="padded-text">{{ formatarNumeroCategoria($child->id) . ' - ' . $child->nome }}</strong>


                                  <span class="material-icons md-45" style="cursor: pointer;"
                                      onclick="loadEditCategoria({{ $category->id }})" title="Editar">
                                      edit
                                  </span>
                                  <span class="material-icons md-45" style="cursor: pointer;"
                                      onclick="loadDeleteCategoria({{ $category->id }})" title="Excluir">
                                      delete
                                  </span>


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
</section>

</body>
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/styleForm.css') }}">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@stop

@section('js')
<script>
    var url = window.location;
    var urlData = JSON.stringify(url);
    console.log(urlData);
    console.log(window.location.href);
    // TOGGLE Children Categories ------------------------------------------    
    function toggleChildren(categoryId) {
        var childrenRow = document.getElementById('children-' + categoryId);
        var icon = document.getElementById('toggle-icon-' + categoryId);
        console.log('Category:', categoryId);
        console.log('childrenRow:', childrenRow);
        console.log('icon:', icon);
        if (childrenRow) {
            // Check the current display state
            if (childrenRow.style.display === "none" || childrenRow.style.display === "") {
                // Show the children
                childrenRow.style.display = "table-row";
                icon.innerText = "expand_less"; // Change icon to collapse
            } else {
                // Hide the children
                childrenRow.style.display = "none";
                icon.innerText = "expand_more"; // Change icon to expand
            }
        } else {
            console.warn("No child row found for category ID " + categoryId);
        }
    }
    // CHECKBOX Selection ------------------------------------------ 
    function uncheckOthers(checkbox) {
        // Get all checkboxes in the table (you can specify a class or selector if needed)
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        // Iterate through all checkboxes
        checkboxes.forEach(function(cb) {
            // If the current checkbox is not the one that was clicked and it is checked, uncheck it
            if (cb !== checkbox && cb.checked) {
                cb.checked = false;
            }
        });
    }




</script>
@stop