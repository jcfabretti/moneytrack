@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="box">
    <FORM name="formCad" id="formCad" method="POST" action="{{url('/parceiros/create')}}" >
        @csrf
        @method('post')
        <fieldset>
            <legend><b>Cadastro de Parceiros</b></legend>
            <br>

            <div class="form-outline w-25">
                <label for="nome">Nome da Empresa:</label>
                <input type="text" name='nome' id="nome" class="form-control" style="text-transform:uppercase"/> <br>
            </div>

            <div class="inputBox">
                <label for="natJur">Natureza Juridica:</label>
                <select class="form-control valid" required data-val="true" data-val-required="Selecione Natureza Juridica" id="natJur" name="natJur">
                    <option selected="selected" value="0">Selecione</option>
                    <option value="1">P.Juridica</option>
                    <option value="2">P.Fisica</option>
                </select><br>
            </div>

            <div class="inputBox">
                </select>
                <label for="tipoCliente">Tipo de Cliente:</label>
                <select class="form-control valid" required data-val="true" data-val-required="Selecione Tipo de Cliente" id="tipoCliente" name="tipoCliente">
                    <option selected="selected" value="0">Selecione</option>
                    <option value="1">Banco</option>
                    <option value="2">Cliente</option>
                    <option value="2">Fornecedor</option>
                </select> <br>
            </div>

            <div class="inputBox">
                <label for="CodFiscal">CNPJ/CPF:</label>
                <input class="form-control" required type="number" name="CodFiscal" id="CodFiscal" /><br>
            </div>

            <div class="inputBox">
                <label for="localidade">Localidade:</label>
                <input class="form-control" required type="text" name="localidade" id="localidade"/>
            </div>

            <br>
            <input type="submit" value="Cadastrar" id="submit"/>
            <br><br>
     </fieldset>

    </form>
</div>
@stop

@section('css')

@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
