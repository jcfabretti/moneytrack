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

                <form action="{{ route('fluxocaixa.gerar') }}" method="POST">
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
                            {{-- REMOVA 'required' DO HTML AQUI, VAMOS GERENCIAR VIA JS --}}
                            <input type="number" class="form-control col-md-4" name="conta_partida" id="conta_partida"
                                onchange="jsGetParceiro(this.value)" maxlength="4" value="">
                            <input type="text" class="form-control" id="nomePartida" name="nomePartida" value=""
                                style="font-size: 0.9em; font-weight: bold;" readonly>
                        </div>
                    </div>

                    <div class="form-group mb-3 param-categoria d-none">
                        <label for="categorias_id">Categoria:</label>
                        <div class="input-group">
                            {{-- REMOVA 'required' DO HTML AQUI, VAMOS GERENCIAR VIA JS --}}
                            <input type="number" class="form-control" name="categorias_id" id="categorias_id"
                                onchange="jsGetCategoria(this.value)" maxlength="5">
                            <input type="text" class="form-control" name="nomeConta" id="nomeConta" maxlength="6"
                                readonly style="font-size: 0.9em; font-weight: bold;">
                        </div>
                    </div>

                    <div class="row param-periodo d-none">
                        <div class="col">
                            <label for="lcto_dataInicial">Data Inicial:</label><br>
                            {{-- Mantenha 'required' aqui se for SEMPRE obrigatório quando o período estiver visível --}}
                            <input type="date" class="form-control form-control-sm report-data-inicial"
                                name="lcto_dataInicial" value="2025-05-01" required>
                        </div>
                        <div class="col">
                            <label for="lcto_dataFinal">Data Final:</label><br>
                            {{-- Mantenha 'required' aqui se for SEMPRE obrigatório quando o período estiver visível --}}
                            <input type="date" class="form-control form-control-sm report-data-final"
                                name="lcto_dataFinal" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    {{-- Botão "Executar" --}}
                    <div class="row pt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 w-md-auto">Executar</button>
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
        function jsSetColecaoCategoria() {
            const selectEmpresa = document.getElementById('empresa_select');
            const selectedOption = selectEmpresa.options[selectEmpresa.selectedIndex];
            const colecaoCategoriaInput = document.getElementById('colecaoCategoria_id');
            const selectRelatorio = document.getElementById('relatorio_select');
            // Get the new hidden input for the company name
            const empresaNomeInput = document.getElementById('empresa_nome');

            if (!selectEmpresa || !colecaoCategoriaInput || !selectRelatorio || !empresaNomeInput) {
                console.error(
                    "Erro: Elementos HTML 'empresa_select', 'colecaoCategoria_id', 'relatorio_select' ou 'empresa_nome' não encontrados."
                );
                return;
            }

            let tiposCategoriaId = '';
            let empresaNome = ''; // Initialize variable for the company name

            if (selectedOption && selectedOption.value !== "") {
                tiposCategoriaId = selectedOption.dataset.tiposCategoriaId;
                // Get the text content of the selected option (which is the company name)
                empresaNome = selectedOption.textContent.trim();
                selectRelatorio.removeAttribute('disabled');
            } else {
                // If "Todas as Empresas" is selected, set name to an empty string or a default
                empresaNome = 'Todas as Empresas';
                selectRelatorio.setAttribute('disabled', 'true');
                selectRelatorio.value = '0';
                jsOnSelectRelatorio(); // Chama para esconder os campos de relatório novamente
            }

            colecaoCategoriaInput.value = tiposCategoriaId || '';
            // Set the value of the hidden input
            empresaNomeInput.value = empresaNome;
        }

        // ---

        function jsOnSelectRelatorio() {
            const selectRelatorio = document.getElementById('relatorio_select');
            const selectedOption = selectRelatorio.options[selectRelatorio.selectedIndex];

            const numeroContaContainer = $('.param-numeroConta');
            const categoriaContainer = $('.param-categoria');
            const periodoContainer = $('.param-periodo');

            // Campos específicos que podem ser 'required' e precisam ser gerenciados
            const contaPartidaInput = $('#conta_partida');
            const categoriasIdInput = $('#categorias_id');
            const dataInicialInput = $('.report-data-inicial');
            const dataFinalInput = $('.report-data-final');

            // 1. Esconder todos os containers e desabilitar TODOS os inputs neles
            numeroContaContainer.addClass('d-none');
            categoriaContainer.addClass('d-none');
            periodoContainer.addClass('d-none');

            // Desabilita todos os inputs relevantes, removendo o atributo 'required' se existir
            // Usamos .prop('disabled', true) para desabilitar o campo.
            // Para 'required', removemos o atributo para não causar problemas de validação quando o campo está oculto.
            contaPartidaInput.prop('disabled', true).removeAttr('required');
            categoriasIdInput.prop('disabled', true).removeAttr('required');
            dataInicialInput.prop('disabled', true).removeAttr('required');
            dataFinalInput.prop('disabled', true).removeAttr('required');


            const selectedReportValue = selectedOption.value;

            console.log("Relatório selecionado (value):", selectedReportValue);

            // 2. Mostrar os containers e habilitar/adicionar 'required' conforme a necessidade
            switch (selectedReportValue) {
                case '0': // "Selecione um Relatório" - Oculta tudo, todos desabilitados
                    break;
                case '1': // 1. Fluxo de Caixa - Utiliza somente o periodoContainer
                    periodoContainer.removeClass('d-none');
                    dataInicialInput.prop('disabled', false).attr('required', true); // Habilita e torna obrigatório
                    dataFinalInput.prop('disabled', false).attr('required', true); // Habilita e torna obrigatório
                    break;
                case '2': // 2. Lançamentos por Data - Utiliza somente o periodoContainer
                    periodoContainer.removeClass('d-none');
                    dataInicialInput.prop('disabled', false).attr('required', true);
                    dataFinalInput.prop('disabled', false).attr('required', true);
                    break;
                case '3': // 3. Lançamento por Categoria - Utiliza categoriaContainer e periodoContainer
                    categoriaContainer.removeClass('d-none');
                    periodoContainer.removeClass('d-none');
                    categoriasIdInput.prop('disabled', false).attr('required', true); // Habilita e torna obrigatório
                    dataInicialInput.prop('disabled', false).attr('required', true);
                    dataFinalInput.prop('disabled', false).attr('required', true);
                    break;
                case '4': // 4. Movimentação por Categoria-Banco - Utiliza numeroContaContainer, categoriaContainer e periodoContainer
                    numeroContaContainer.removeClass('d-none');
                    categoriaContainer.removeClass('d-none');
                    periodoContainer.removeClass('d-none');
                    contaPartidaInput.prop('disabled', false).attr('required', true); // Habilita e torna obrigatório
                    categoriasIdInput.prop('disabled', false).attr('required', true);
                    dataInicialInput.prop('disabled', false).attr('required', true);
                    dataFinalInput.prop('disabled', false).attr('required', true);
                    break;
                default:
                    // Caso um valor inesperado seja selecionado, oculta tudo
                    break;
            }
        }

        // ---

        $(document).ready(function() {
            // Garante que o select de relatório esteja desabilitado e os containers ocultos ao carregar a página.
            // Também reseta a seleção do relatório para a opção padrão.
            const selectRelatorio = document.getElementById('relatorio_select');
            if (selectRelatorio) {
                selectRelatorio.setAttribute('disabled', 'true');
                selectRelatorio.value = '0';
                jsOnSelectRelatorio
            (); // Chama para garantir que os containers e seus inputs estejam ocultos/desabilitados
            }
        });

        // Funções jsGetParceiro e jsGetCategoria (Mantenha-as se estiverem definidas em outro lugar)
        // Se elas não existirem, você verá erros no console.
        // function jsGetParceiro(value) { console.log('Obtendo parceiro para:', value); /* Implemente sua lógica AJAX aqui */ }
        // function jsGetCategoria(value) { console.log('Obtendo categoria para:', value); /* Implemente sua lógica AJAX aqui */ }
    </script>
@stop

<!--
        $(document).ready(function() {

            // Select/Deselect checkboxes (mantido como estava)
            var checkbox = $('table tbody input[type="checkbox"]');
            $("#selectAll").click(function() {
                if (this.checked) {
                    checkbox.each(function() {
                        this.checked = true;
                    });
                } else {
                    checkbox.each(function() {
                        this.checked = false;
                    });
                }
            });
            checkbox.click(function() {
                if (!this.checked) {
                    $("#selectAll").prop("checked", false);
                }
            });

            // Lógica para gerar relatórios com base nos dados da linha
            $('.btn-gerar-relatorio').on('click', function() {
                var row = $(this).closest('tr');
                var reportName = row.data('report-name');
                var dataInicial = row.find('.report-data-inicial').val();
                var dataFinal = row.find('.report-data-final').val();
                var empresaId = $('#empresa_select').val(); // Pega o ID da empresa selecionada

                // --- VALIDAÇÃO ADICIONADA AQUI ---
                if (!
                    empresaId
                ) { // Se empresaId for vazio (nenhuma empresa selecionada ou "Todas as Empresas")
                    // Você pode usar uma mensagem mais sofisticada (modal Bootstrap)
                    // Por simplicidade, vou usar um alert, mas evite em produção.
                    // Idealmente, você mostraria uma mensagem em um div de alerta na página.
                    alert('Por favor, selecione uma empresa antes de gerar o relatório.');
                    return; // Interrompe a execução da função
                }
                // --- FIM DA VALIDAÇÃO ---

                // Passa o reportName como um parâmetro de rota para a função route()
                var url = "{{ route('create.relatorio', ['reportName' => ':reportNamePlaceholder']) }}";
                url = url.replace(':reportNamePlaceholder', reportName);

                var params = [];

                // Adiciona o ID da empresa selecionada
                params.push(`REPORT_EMPRESA=${empresaId}`);


                // Se houver inputs de data na linha e tiverem valores, adicione-os com os nomes originais
                if (dataInicial && dataInicial.length > 0 && dataFinal && dataFinal.length > 0) {
                    params.push(`Parameter2=${dataInicial}`);
                    params.push(`Parameter1=${dataFinal}`);
                }

                if (params.length > 0) {
                    url += `?${params.join('&')}`;
                }

                window.location.href = url; // Redireciona para a URL construída
            });

            // Lógica para desabilitar campos de data se o relatório não precisar
            // Adicionado para "RelatorioSimples" ou ajuste o nome de acordo com seu data-report-name
            $('tr[data-report-name="Lancamentos"] .report-data-inicial, tr[data-report-name="Lancamentos"] .report-data-final')
                .prop('disabled', true).val('');
        });
    </script>
-->
