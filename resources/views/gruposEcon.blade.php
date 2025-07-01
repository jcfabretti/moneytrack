@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>GRUPO</h1>
@stop

@section('content')
    <form>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">First name</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">Surname</label>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-12">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">Adress 1</label>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-12">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">Adress 2</label>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">City</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">Zip code</label>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="email" id="typeEmail" class="form-control" />
                    <label class="form-label" for="typeEmail">Email</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="tel" id="typePhone" class="form-control" />
                    <label class="form-label" for="typePhone">Phone number </label>
                </div>
            </div>
        </div>

        <td>
            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#ModalEdit">{{__('Edit')}}</a>/>
        </td>
            <!--
        <a href="{{route('parceiro.edit',$partner->id)}}">{{__('Show')}} class="edit" data-toggle="modal">
            <i class="material-icons" data-toggle="tooltip"
                title="Edit">&#xE254;</i></a>

                <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-primary">Sign up</button>
        </div>
    -->
      @include('parceiro.edit')
</form>


    <!-- Edit Modal HTML -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>ALTERAR EMPRESA</strong> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="Nome" class="col-form-label">Nome da Empresa:</label>
                            <input type="text" class="form-control" id="nome" size = "45"
                            maxlength = "45" style='text-transform:capitalize'>
                        </div>

                        <div class="form-group">
                            </select>
                            <label>Tipo de Cliente</label>
                            <select class="form-control valid" name="tipoCliente" required data-val="true"
                                data-val-required="Selecione Tipo de Cliente" id="tipoCliente" name="tipoCliente">
                                <option selected="selected" value="0">Selecione</option>
                                <option value="1">Banco</option>
                                <option value="2">Cliente</option>
                                <option value="2">Fornecedor</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Natureza Jurídica:</label>
                            <select class="form-control valid" name="natJur" required data-val="true"
                                data-val-required="Selecione Natureza Juridica" id="natJur" name="natJur">
                                <option selected="selected" value="0">Selecione</option>
                                <option value="1">P.Juridica</option>
                                <option value="2">P.Fisica</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="CodFiscal" class="col-form-label">CNPJ / CPF:</label>
                            <input type="text" class="form-control" id="CodFiscal">
                        </div>
                        <div class="form-group">
                            <label for="localidade" class="col-form-label">Localdade:</label>
                            <input type="text" class="form-control" id="localidade">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success">Incluir</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal HTML -->

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
