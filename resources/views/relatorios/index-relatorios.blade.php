@extends('adminlte::page')

@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Relatórios')
<link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
@section('content_header')

@stop

@section('content')
    <div class="container">
        <div class="table-responsive">
            <div class="table-wrapper formulario-list mx-auto my-4" style="max-width: 800px;">

                <form action="{{ route('relatorios.gerar') }}" method="POST">
                    @csrf

                    {{-- SELECT para Empresas --}}
                    <input type="hidden" id="colecaoCategoria_id" name="colecaoCategoria_id">
                    <input type="hidden" id="empresa_nome" name="empresa_nome">

                    <div class="form-group mb-3">
                        <label for="empresa_select">Selecione a Empresa:</label>
                        <select class="form-control" id="empresa_select" name="empresa_select"
                            onchange="jsSetColecaoCategoria()">
                            <option value="">Todas as Empresas</option>
                            @foreach ($EMPRESAS as $empresa)
                                <option value="{{ $empresa->id }}"
                                    data-tipos-categoria-id="{{ $empresa->tipos_planocontas_id }}">{{ $empresa->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SELECT para RELATÓRIOS --}}
                    <div class="form-group mb-3">
                        <label for="relatorio_select">Selecione Relatório:</label>
                        <select class="form-control" id="relatorio_select" name="relatorio_select"
                            onchange='jsOnSelectRelatorio()' disabled>
                            <option value="0">Selecione um Relatório</option>
                            <option value="1">1.Fluxo de Caixa</option>
                            <option value="2">2.Lançamentos por Data</option>
                            <option value="3">3.Lançamento por Categoria</option>
                            <option value="4">4.Movimentação por Categoria-Banco</option>
                        </select>
                    </div>

                    {{-- Containers dos parâmetros --}}
                    <div class="col-md-6 form-group param-numeroConta d-none">
                        <label for="conta_partida">Nº da Conta</label>
                        <div class="input-group">
                            <input type="number" class="form-control col-md-4" name="conta_partida" id="conta_partida"
                                onchange="jsGetParceiro(this.value)" maxlength="4" value="">
                            <input type="text" class="form-control" id="nomePartida" name="nomePartida" value=""
                                style="font-size: 0.9em; font-weight: bold;" readonly>
                        </div>
                    </div>

                    {{-- CONTAINER DA CATEGORIA (COM CHECKBOX) --}}
                    <div class="form-group mb-3 param-categoria d-none">
                        <label for="categorias_id">Categoria:</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="all_categories_checkbox" id="all_categories_checkbox"
                                   class="form-check-input" onchange="toggleCategoryInput()">
                            <label class="form-check-label" for="all_categories_checkbox">Listar Todas as Categorias</label>
                        </div>
                        <div class="input-group mt-2">
                            <input type="number" class="form-control" name="categorias_id" id="categorias_id"
                                onchange="jsGetCategoria(this.value)" maxlength="5">
                            <input type="text" class="form-control" name="nomeConta" id="nomeConta" maxlength="6"
                                readonly style="font-size: 0.9em; font-weight: bold;">
                        </div>
                    </div>

                    <div class="row param-periodo d-none">
                        <div class="col">
                            <label for="lcto_dataInicial">Data Inicial:</label><br>
                            <input type="date" class="form-control form-control-sm report-data-inicial"
                                name="lcto_dataInicial" value="{{ date('Y-01-01') }}" required>
                        </div>
                        <div class="col">
                            <label for="lcto_dataFinal">Data Final:</label><br>
                            <input type="date" class="form-control form-control-sm report-data-final"
                                name="lcto_dataFinal" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    {{-- Botão "Executar" --}}
                    <div class="row pt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 w-md-auto" id="execute_button" disabled>Executar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
@stop

@section('js')
    <script>
        // Função para verificar e controlar o estado do botão "Executar"
        function checkExecuteButtonState() {
            const selectEmpresa = document.getElementById('empresa_select');
            const selectRelatorio = document.getElementById('relatorio_select');
            const executeButton = document.getElementById('execute_button');

            if (!selectEmpresa || !selectRelatorio || !executeButton) {
                console.error("Erro: Elementos HTML 'empresa_select', 'relatorio_select' ou 'execute_button' não encontrados.");
                return;
            }

            // Verifica se uma empresa foi selecionada (value não é vazio)
            const isEmpresaSelected = selectEmpresa.value !== "";
            // Verifica se um relatório foi selecionado (value não é "0")
            const isRelatorioSelected = selectRelatorio.value !== "0";

            // Habilita o botão se ambos forem verdadeiros, caso contrário, desabilita
            if (isEmpresaSelected && isRelatorioSelected) {
                executeButton.removeAttribute('disabled');
            } else {
                executeButton.setAttribute('disabled', 'true');
            }
        }

        function jsSetColecaoCategoria() {
            const selectEmpresa = document.getElementById('empresa_select');
            const selectedOption = selectEmpresa.options[selectEmpresa.selectedIndex];
            const colecaoCategoriaInput = document.getElementById('colecaoCategoria_id');
            const selectRelatorio = document.getElementById('relatorio_select');
            const empresaNomeInput = document.getElementById('empresa_nome');

            if (!selectEmpresa || !colecaoCategoriaInput || !selectRelatorio || !empresaNomeInput) {
                console.error(
                    "Erro: Elementos HTML 'empresa_select', 'colecaoCategoria_id', 'relatorio_select' ou 'empresa_nome' não encontrados."
                );
                return;
            }

            let tiposCategoriaId = '';
            let empresaNome = '';

            if (selectedOption && selectedOption.value !== "") {
                tiposCategoriaId = selectedOption.dataset.tiposCategoriaId;
                empresaNome = selectedOption.textContent.trim();
                selectRelatorio.removeAttribute('disabled'); // Habilita o select de relatório
            } else {
                empresaNome = 'Todas as Empresas';
                selectRelatorio.setAttribute('disabled', 'true'); // Desabilita o select de relatório
                selectRelatorio.value = '0'; // Reseta o valor do select de relatório
                jsOnSelectRelatorio(); // Chama para redefinir os parâmetros visíveis
            }

            colecaoCategoriaInput.value = tiposCategoriaId || '';
            empresaNomeInput.value = empresaNome;

            // Sempre verifica o estado do botão após a mudança da empresa
            checkExecuteButtonState();
        }

        function jsGetCategoria(categ_id) {
            const colecaoCategoriaInput = document.getElementById('colecaoCategoria_id');
            const codPlanoCategoria = colecaoCategoriaInput.value;
            const nomeContaInput = document.getElementById('nomeConta');

            if (!codPlanoCategoria) {
                console.warn("codPlanoCategoria não definido. Não é possível buscar o nome da categoria.");
                nomeContaInput.value = '';
                return;
            }

            const codCategoria = codPlanoCategoria + categ_id;
            const nrCategoria = categ_id;

            $.ajax({
                url: '/categoria/getNomeCategoria/' + codCategoria,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: "application/json",
                dataType: 'text',
                success: function (response) {
                    var objJSON = JSON.parse(response);
                    nomeContaInput.value = objJSON.nome;
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 404) {
                        if (typeof displayMessage === 'function') {
                            displayMessage('Erro:' + formatCodigoCategoria(nrCategoria) + '- Categoria não cadastrada!', false);
                        } else {
                            console.error('Erro: Categoria não cadastrada ou displayMessage não definido.');
                        }
                        nomeContaInput.value = 'Não Cadastrado';
                        document.getElementById('categorias_id').focus();
                        document.getElementById('categorias_id').select();
                    } else {
                        console.error('Erro na requisição AJAX para categoria:', thrownError);
                        nomeContaInput.value = 'Erro na Busca';
                    }
                }
            });
        };

        function toggleCategoryInput() {
            const categoryInput = document.getElementById('categorias_id');
            const categoryNameInput = document.getElementById('nomeConta');
            const allCategoriesCheckbox = document.getElementById('all_categories_checkbox');

            if (allCategoriesCheckbox.checked) {
                categoryInput.disabled = true;
                categoryInput.value = '';
                categoryNameInput.value = 'Todas as Categorias';
                categoryInput.removeAttribute('required');
            } else {
                categoryInput.disabled = false;
                categoryNameInput.value = '';

                if (categoryInput.value.trim() !== '') {
                    jsGetCategoria(categoryInput.value);
                } else {
                    categoryInput.focus();
                }
            }
        }

        function jsOnSelectRelatorio() {
            const selectRelatorio = document.getElementById('relatorio_select');
            const selectedOption = selectRelatorio.options[selectRelatorio.selectedIndex];

            const numeroContaContainer = $('.param-numeroConta');
            const categoriaContainer = $('.param-categoria');
            const periodoContainer = $('.param-periodo');

            const contaPartidaInput = $('#conta_partida');
            const categoriasIdInput = $('#categorias_id');
            const dataInicialInput = $('.report-data-inicial');
            const dataFinalInput = $('.report-data-final');
            const allCategoriesCheckbox = $('#all_categories_checkbox');

            numeroContaContainer.addClass('d-none');
            categoriaContainer.addClass('d-none');
            periodoContainer.addClass('d-none');

            contaPartidaInput.prop('disabled', true).removeAttr('required');
            categoriasIdInput.prop('disabled', true).removeAttr('required');
            dataInicialInput.prop('disabled', true).removeAttr('required');
            dataFinalInput.prop('disabled', true).removeAttr('required');
            allCategoriesCheckbox.prop('checked', false);
            toggleCategoryInput();

            const selectedReportValue = selectedOption.value;

            switch (selectedReportValue) {
                case '0':
                    break;
                case '1':
                case '2':
                    periodoContainer.removeClass('d-none');
                    dataInicialInput.prop('disabled', false).attr('required', true);
                    dataFinalInput.prop('disabled', false).attr('required', true);
                    break;
                case '3':
                    categoriaContainer.removeClass('d-none');
                    periodoContainer.removeClass('d-none');
                    dataInicialInput.prop('disabled', false).attr('required', true);
                    dataFinalInput.prop('disabled', false).attr('required', true);
                    break;
                case '4':
                    numeroContaContainer.removeClass('d-none');
                    categoriaContainer.removeClass('d-none');
                    periodoContainer.removeClass('d-none');
                    contaPartidaInput.prop('disabled', false).attr('required', true);
                    dataInicialInput.prop('disabled', false).attr('required', true);
                    dataFinalInput.prop('disabled', false).attr('required', true);
                    break;
                default:
                    break;
            }

            // Sempre verifica o estado do botão após a mudança do relatório
            checkExecuteButtonState();
        }

        $(document).ready(function() {
            const selectRelatorio = document.getElementById('relatorio_select');
            const executeButton = document.getElementById('execute_button');

            if (selectRelatorio) {
                selectRelatorio.setAttribute('disabled', 'true');
                selectRelatorio.value = '0';
                // Não chame jsOnSelectRelatorio aqui diretamente, pois checkExecuteButtonState será chamado no final
            }

            // Define o estado inicial do botão ao carregar a página
            checkExecuteButtonState();
        });
    </script>
@stop
