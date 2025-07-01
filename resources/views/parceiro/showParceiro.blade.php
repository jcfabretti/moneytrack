@extends('adminlte::page')
@php
use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Dashboard')

@section('content_header')

<head>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</head>

@stop

@section('content')
<style>
    /* 
        --ESTAVA SENDO USADO CSS DO BOOTSTRAP NO INPUT--  
            class="form-control rounded col-md-3" 
            class="input-group-text border-0"  
    */
    footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background-color: #f8f9fa;
    border-top: 1px solid #ddd;
  }



  .pagination-wrapper {
    display: flex;
    justify-content: center;
    flex-grow: 1;
  }


</style>

<body>
    @php
    $selectedValue = session('app.qtyItemsPerPage'); // Get preseted value to list items in page
    @endphp

    <section class="formulario mt-0 pt-0">  <!-- mt-0 pt-0 reduce margin top and padding top to zero -->
        <table class="table mt-1">
            <thead class="table thead tr">
                <tr style="background-color: --primary-color;">
                    <!-- *********** Listing HEADER ********* -->
                    <th>Id</th>
                    <th>Nome do Parceiro</th>
                    <th>Tipo Cliente</th>
                    <th>Nat Juridica</th>
                    <th>CNPJ/CPF</th>
                    <th>Localidade</th>
                    <th>Status</th>
                    <th >açoes &nbsp &nbsp<span class="material-icons md-45" style="cursor: pointer;" onclick="loadCreateParceiro()" title="Incluir">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" color="#86e676" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="#86e676"/><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                        
                        
                    </span>
                </tr>
                
            </thead>
            <!-- *********** LIST BODY ********* here -->
            <tbody>
                @forelse ($parceiros as $parceiro)
                <tr class="table tr">
                    <td scope="row">{{ $parceiro->id }} </td>
                    <td>{{ $parceiro->nome }} </td>
                    <td>{{ $parceiro->tipo_cliente }}</td>
                    <td>{{ $parceiro->nat_jur }}</td>
                    <td>
                        <!-- format codigo fiscal -->
                        <script> 
                            document.write(formatCnpjCpf('{{$parceiro->cod_fiscal}}'))
                        </script>
                    </td> 
                        <!-- localidade -->
                    <td>{{ $parceiro->localidade }}</td>
                    <td>
                        <span style="color: {{ $parceiro->status == '1' ? 'green' : 'red'}}">
                            <b>{{ $parceiro->status == '1' ? 'Ativo' : 'Inativo' }}</b>
                        </span>
                    <td>
                        <!-- Action Buttons -->
                        <a href="" class="edit"
                            onclick="loadEditParceiro({{ $parceiro->id }}, `{{ $parceiro->nome }}`, `{{ $parceiro->nat_jur }}`,
                                   `{{ $parceiro->tipo_cliente }}`, 
                                   `{{ $parceiro->cod_fiscal }}`,`{{ $parceiro->localidade}}`,`{{ $parceiro->status}}`)"
                            data-id="{{ $parceiro->id }}" data-toggle="modal" id="editParceiroBtn">
                            <i class="material-icons" data-toggle="tooltip" title="Alterar">&#xE254;</i></a>

                        <div class="btn-group btn-group-sm">
                            <a href="" class="delete"
                                onclick="loadDeleteModal({{ $parceiro->id }}, `{{ $parceiro->nome }}`)"
                                data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                    title="Delete">&#xE872;</i></a>
                        </div>
                    </td>
                </tr>
                @empty
                    <!-- If no records in DB table show message -->
                    <th></th>
                    <th><b>Não Cadastrado !</b></th>
                @endforelse
            </tbody>

        </table>

        <footer class="pt-0 mt-0">
            <div class="selectpage" style="font-size: 15px !important;">
              <label for="entries">Qtde por página:</label>
              <select name="qtyPerPage" id="qtyPerPage" onchange="saveQtyPerPage(this.value)">
                <option value="10" {{ $selectedValue == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ $selectedValue == 15 ? 'selected' : '' }}>15</option>
                <option value="20" {{ $selectedValue == 20 ? 'selected' : '' }}>20</option>
                <option value="30" {{ $selectedValue == 30 ? 'selected' : '' }}>30</option>
                <option value="40" {{ $selectedValue == 40 ? 'selected' : '' }}>40</option>
              </select>
            </div>
            <div class="pagination-wrapper" style="font-size: 15px !important;">
              {{ $parceiros->onEachSide(5)->links() }}
            </div>
          </footer>
    </section>
    @include('parceiro.modal.createModalParceiro')
    @include('parceiro.modal.editModalParceiro')
    @include('parceiro.modal.deleteModalParceiro')
</body>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/styleForm.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@stop

@section('js')
<script>
    // Load modals and format data
    function loadCreateParceiro() {
        $('#addParceiroModal').modal('show');
    }

    function loadEditParceiro(id, nome, nat_jur, tipo_cliente, cod_fiscal, localidade, status) {
        $('#editFormParceiro #id').val(id);
        $('#editFormParceiro #nome').val(nome);
        $('#editFormParceiro #nat_jur').val(nat_jur);
        $('#editFormParceiro #tipo_cliente').val(tipo_cliente);
        $('#editFormParceiro #edit_cod_fiscal').val(formatCnpjCpf(cod_fiscal));
        $('#editFormParceiro #localidade').val(localidade);
        $('#editFormParceiro #status').val(status);
        $('#editModal').modal('show');
    }

    function loadDeleteModal(id, nome) {
        $('#modal-category_name').text(nome);
        $('#modal-confirm_delete').attr('onclick', `confirmDelete(${id})`);
        $('#deleteCategory').modal('show');
    }

    function confirmDelete(id) {
        $.ajax({
            url: `/parceiros/destroy/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            }
        });
    }
        function capitalizeInput(input) {
            // const input = document.getElementById("nome");
            input.value = input.value.toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(
                ' ');
        }

</script>
@stop