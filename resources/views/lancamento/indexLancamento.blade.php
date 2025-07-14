@extends('adminlte::page')
@php
    use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Dashboard')

@section('content_header')

    <head>
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="{{ asset('js/lancamento.js') }}"></script>
        <script src="{{ asset('js/categoria.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    </head>

@stop

@section('content')
    <style>
        table.myFormat tr td {
            font-size: 13px;
        }

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

        .valor-negativo {
            color: red;
        }
    </style>

    <body>
        @php
            $selectedValue = session('app.qtyItemsPerPage'); // Get preseted value to list items in page
        @endphp

        {{-- Linha principal para os filtros e o botão --}}
        <div class="row align-items-end">
            {{-- Coluna para o seletor de empresa --}}
            <div class="col-md-4">
                <div class="form-group mb-0"> {{-- Removida a margem inferior --}}
                    {{-- Linha interna para alinhar Label e Select --}}
                    <div class="row align-items-center">
                        <div class="col-auto"> {{-- Coluna para a label --}}
                            <label for="empresa_nome" class="col-form-label">Selecione Empresa:</label>
                        </div>
                        <div class="col"> {{-- Coluna para o select, ocupando o espaço restante --}}
                            <select class="form-control" id="empresa_nome" name="empresa_nome">
                                {{-- Opção "Todas as Empresas" --}}
                                <option value="" {{ $empresaId == null ? 'selected' : '' }}>Todas as Empresas
                                </option>
                                {{-- Itera sobre as empresas e marca a selecionada --}}
                                @foreach ($empresas as $empresa)
                                    {{-- Variável $empresas (minúsculas) --}}
                                    <option value="{{ $empresa->id }}" {{ $empresaId == $empresa->id ? 'selected' : '' }}>
                                        {{ $empresa->id }}-{{ $empresa->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna para o seletor de data e o checkbox "Todas as Datas" --}}
            <div class="col-md-5">
                <div class="form-group mb-0"> {{-- Removida a margem inferior --}}
                    {{-- Linha interna para alinhar Label e Input de Data --}}
                    <div class="row align-items-center">
                        <div class="col-auto"> {{-- Coluna para a label --}}
                            <label for="data_selecionada" class="col-form-label">Data:</label>
                        </div>
                        <div class="col-6"> {{-- Coluna para o input de data --}}
                            <input type="date" class="form-control" id="data_selecionada" name="data_selecionada"
                                value="{{ $inputValueData }}" {{ $isTodasDatasChecked ? 'disabled' : '' }}>
                        </div>
                        <div class="col-auto"> {{-- Coluna para o checkbox --}}
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="todas_datas_checkbox"
                                    {{ $isTodasDatasChecked ? 'checked' : '' }}>
                                <label class="form-check-label" for="todas_datas_checkbox">Todas as Datas</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna para o botão Filtrar --}}
            <div class="col-md-3">
                <div class="form-group mb-0"> {{-- Removida a margem inferior --}}
                    <button type="button" class="btn btn-primary btn-block" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </div>
        </div> {{-- Fim da linha para os filtros --}}

        <section class="formulario mt-4 pt-0">
            <div class="row mb-3">
                <div class="col-12">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <table class="table mt-1 myFormat">
            <thead class="table thead tr">
                <tr style="background-color: #1e585br;">
                    <th hidden>Id</th>
                    <th>Nº Docto</th>
                    <th>Data</th>
                    <th>tipo</th>
                    <th>Categoria</th>
                    <th>Partida</th>
                    <th>Contra Partida</th>
                    <th>Historico</th>
                    <th>Valor</th>
                    <th>açoes &nbsp &nbsp<span class="material-icons md-45" style="cursor: pointer;"
                            onclick="loadCreateParceiro()" title="Incluir">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" color="#86e676" viewBox="0 0 24 24"
                                width="24">
                                <path d="M0 0h24v24H0z" fill="#86e676" />
                                <path
                                    d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                            </svg>


                        </span>
                </tr>

            </thead>
            <!-- *********** LIST BODY ********* here -->
            <tbody class="table-body-lcto">

                @forelse ($lancamentos as $lancamento)
                    <tr>
                        @php
                            $cod_categoria = formatarNumeroCategoria($lancamento->categoria?->numero_categoria);
                            if ($cod_categoria == '9.99.99') {
                                $cod_categoria = '######'; // Se não houver categoria, define como N/A
                            }
                        @endphp

                        <td hidden>{{ $lancamento->id }}</td>
                        <td>{{ $lancamento->tipo_docto }}-{{ $lancamento->numero_docto }}</td>
                        <td>{{ \Carbon\Carbon::parse($lancamento->data_lcto)->format('d/m/Y') }}</td>
                        <td>{{ $lancamento->tipo_conta }}</td>
                        <td>{{ $cod_categoria }}</td>
                        {{-- Use null-safe operator para LctoPartida e LctoContraPartida --}}
                        <td style="max-width: 190px;">{{ $lancamento->LctoPartida?->nome ?? 'N/A' }}</td>
                        <td style="max-width: 190px;">{{ $lancamento->LctoContraPartida?->nome ?? 'N/A' }}</td>
                        <td>{{ $lancamento->historico }}</td>
                        @php
                            // Formata o valor para o padrão brasileiro (R$ 1.234,56)
                            $valorFormatado = number_format($lancamento->valor, 2, ',', '.');

                            // CORREÇÃO: Use a relação 'categoria' e o operador null-safe
                            // Se a sua versão do PHP for < 8.0, use:
                            // $codigoNomeCategoria = ($lancamento->categoria ? $lancamento->categoria->numero_categoria . ' - ' . $lancamento->categoria->nome : 'N/A');
                            $codigoNomeCategoria =
                                ($lancamento->categoria?->numero_categoria ?? '') .
                                ' - ' .
                                ($lancamento->categoria?->nome ?? '');
                        @endphp

                        @if ($valorFormatado < 0)
                            <td align="right" style="color: red;">{{ $valorFormatado }}</td>
                        @else
                            <td align="right" style="color: black;">{{ $valorFormatado }}</td>
                        @endif
                        <td style="white-space: nowrap;">

                            <a href="#" class="view"
                                onclick="loadViewModal(
                                        '{{ $lancamento->id }}',
                                        '{{ $lancamento->tipo_docto }}',
                                        '{{ $lancamento->numero_docto }}',
                                        '{{ $lancamento->data_lcto ?? '' }}',
                                        '{{ $lancamento->LctoPartida?->nome ?? '' }}', {{-- Use null-safe --}}
                                        '{{ $lancamento->LctoContraPartida?->nome ?? '' }}', {{-- Use null-safe --}}
                                        '{{ $codigoNomeCategoria ?? '' }}',
                                        '{{ $lancamento->historico ?? '' }}',
                                        '{{ $lancamento->unidade ?? '' }}',
                                        '{{ $lancamento->quantidade ?? '' }}',
                                        '{{ $lancamento->valor ?? '' }}',
                                        '{{ $lancamento->vencimento ?? '' }}',
                                        '{{ $lancamento->centro_custo ?? '' }}',
                                        '{{ $lancamento->created_at ?? '' }}',
                                        '{{ $lancamento->usuarioQueCriou?->name ?? '' }}', {{-- Use null-safe --}}
                                        '{{ $lancamento->updated_at ?? '' }}',
                                        '{{ $lancamento->usuarioQueAtualizou?->name ?? '' }}', {{-- Use null-safe --}}
                                        '{{ $lancamento->origem ?? '' }}'
                                    )"
                                data-target="#viewModalLancamento" data-toggle="modal" id="openViewModalBtn">
                                <i class="far fa-eye" title="Consultar" style="color:blue"></i>
                            </a>

                            {{-- ALTERADO: O botão de editar agora chama loadEditModal com apenas o ID --}}
                            <a href="#" class="edit" onclick="loadEditModal({{ $lancamento->id }})"
                                data-toggle="modal" id="openEditModalBtn">
                                <i class="material-icons" style="color:green" data-toggle="tooltip"
                                    title="Alterar">&#xE254;</i>
                            </a>

                            <a href="#loadDeleteLcto" class="delete"
                                onclick="loadDeleteLcto('{{ $lancamento->id }}', '{{ $lancamento->tipo_docto }}','{{ $lancamento->numero_docto }}', '{{ $lancamento->valor ?? '' }}')"
                                {{-- Use null-safe --}} data-toggle="modal" id="openDeleteModalBtn">
                                <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                            </a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Nenhum lançamento encontrado.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        @include('lancamento.viewLancamento')
        @include('lancamento.updateModalLancamento')
        @include('lancamento.deleteModalLancamento')

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
                {{ $lancamentos->onEachSide(5)->links() }}
            </div>
        </footer>

        </section>
    </body>
@stop


@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">



    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

@stop

@section('js')
    <script type="text/javascript">
        // Funções auxiliares e de modal que precisam ser globais para serem acessíveis via onclick
        // VIEW MODAL - Function to load data into a view modal
        function loadViewModal(id, tipo_docto, numero_docto, data_lcto, partida_nome, contraPartida_nome, categoria,
            historico, unidade, qtde, valor, vencimento, centro_de_custo, created_at, quemCriou,
            updated_at, quemAtualizou, origem) {
            $('#viewModalLancamento #viewModal_id').text(id);
            $('#viewModalLancamento #viewModal_numero_docto').text(tipo_docto + '-' + numero_docto);
            $('#viewModalLancamento #viewModal_data').text(new Date(data_lcto).toLocaleDateString('pt-BR'));
            $('#viewModalLancamento #viewModal_partida').text(partida_nome ? partida_nome : 'N/A');
            $('#viewModalLancamento #viewModal_contrapartida').text(contraPartida_nome ? contraPartida_nome : 'N/A');
            $('#viewModalLancamento #viewModal_categoria').text(categoria ? categoria : 'N/A');
            $('#viewModalLancamento #viewModal_historico').text(historico);
            $('#viewModalLancamento #viewModal_unidade').text(unidade);
            $('#viewModalLancamento #viewModal_quantidade').text(qtde);
            $('#viewModalLancamento #viewModal_origem').text(origem);


            const valorNumerico = parseFloat(valor);
            const valorFormatado = valorNumerico.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
            $('#viewModalLancamento #viewModal_valor').text(valorFormatado);
            if (valorNumerico < 0) {
                $('#viewModalLancamento #viewModal_valor').css('color', 'red');
            } else {
                $('#viewModalLancamento #viewModal_valor').css('color', 'black');
            }
            // Format the date to Brazilian format
            $('#viewModalLancamento #viewModal_vencimento').text(vencimento ? new Date(vencimento).toLocaleDateString(
                'pt-BR') : 'Sem Vencimento');
            $('#viewModalLancamento #viewModal_centro_de_custo').text(centro_de_custo ? centro_de_custo : 'N/A');
            $('#viewModalLancamento #viewModal_date_created').text(new Date(created_at).toLocaleDateString('pt-BR'));
            $('#viewModalLancamento #viewModal_date_updated').text(new Date(updated_at).toLocaleDateString('pt-BR'));
            $('#viewModalLancamento #viewModal_quemCriou').text(quemCriou ? quemCriou : 'N/A');
            $('#viewModalLancamento #viewModal_quemAtualizou').text(quemAtualizou ? quemAtualizou : 'N/A');
            // Show the modal
            $('#viewModalLancamento').modal('show');
        }

        // Função auxiliar para formatar o código da categoria (se necessário)
        function getOnlyCodigoCategoria(id) {
            // No seu código, está apenas pegando os últimos 5 dígitos.
            return String(id).slice(-5);
        }

        // Função auxiliar para converter o valor numérico para o formato de exibição (sem símbolo de moeda)
        function formatNumberForDisplay(value) {
            if (typeof value !== 'number') {
                value = parseFloat(value); // Tenta converter para número
            }
            if (isNaN(value)) {
                return ''; // Retorna vazio se não for um número válido
            }
            // Formata com separador de milhares e duas casas decimais
            return value.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
                useGrouping: true
            });
        }

        // Função para exibir mensagens de feedback (agora pode lidar com arrays de mensagens)
        function displayMessage(message, isSuccess) {
            const messageElement = $('#message_update');
            let messageText = '';

            if (Array.isArray(message)) {
                // Se for um array, crie uma lista HTML
                messageText = '<ul>';
                message.forEach(msg => {
                    messageText += `<li>${msg}</li>`;
                });
                messageText += '</ul>';
            } else {
                // Se for uma string simples
                messageText = message;
            }

            messageElement.html(messageText); // Use .html() para renderizar a lista
            messageElement.css('background-color', isSuccess ? 'lightgreen' : 'red');
            setTimeout(() => {
                messageElement.html(''); // Use .html() para limpar
                messageElement.css('background-color', '');
            }, 5000); // Aumentei o tempo para 5 segundos para mensagens de erro
        }


        // Função para carregar os dados no modal de edição
        function loadEditModal(id) {
            // Limpar mensagens anteriores
            $('#message_update').text('');
            $('#message_update').css('background-color', ''); // Limpa a cor de fundo

            // Fazer uma requisição AJAX para buscar os dados do lançamento
            $.ajax({
                url: `/api/lancamentos/${id}`, // Esta URL deve retornar os dados do lançamento
                method: 'GET',
                success: function(response) {
                    // Verifique a estrutura da resposta no console do navegador
                    //console.log('Dados do lançamento recebidos:', response);

                    // Preencher os campos do formulário no modal
                    $('#lcto_id_update').val(response.id);
                    $('#empresa_id_update').val(response.empresa_id);
                    $('#grupo_economico_id_update').val(response.grupo_economico_id);
                    $('#origem_update').val(response.origem);

                    // --- TRATAMENTO DE DATAS ---
                    // Extrai apenas a parte da data (YYYY-MM-DD) da string ISO 8601
                    let dataLctoFormatted = response.data_lcto ? response.data_lcto.split('T')[0] : '';
                    let vencimentoFormatted = response.vencimento ? response.vencimento.split('T')[0] : '';

                    $('#data_lcto_update').val(dataLctoFormatted);
                    $('#vencimento_update').val(vencimentoFormatted);
                    // --- FIM DO TRATAMENTO DE DATAS ---

                    $('#tipo_docto_update').val(response.tipo_docto);
                    $('#numero_docto_update').val(response.numero_docto);
                    $('#historico_update').val(response.historico);
                    $('#unidade_update').val(response.unidade);
                    $('#quantidade_update').val(response.quantidade);
                    $('#centro_custo_update').val(response.centro_custo);


                    // Campo Valor: Formatar para exibição e aplicar classe de negativo
                    var valorNumerico = parseFloat(response.valor); // Garante que é um número
                    var valorFormatado = formatNumberForDisplay(valorNumerico);
                    $('#valor_update').val(valorFormatado);

                    if (valorNumerico < 0) { // Verifica se o número é negativo
                        $('#valor_update').addClass('valor-negativo');
                    } else {
                        $('#valor_update').removeClass('valor-negativo');
                    }

                    // Campos de seleção (dropdowns)
                    $('#tipo_conta_update').val(response.tipo_conta);
                    // Dispara o evento change para que a lógica de ocultar/exibir funcione
                    $('#tipo_conta_update').trigger('change');

                    // Campos de conta e categoria
                    $('#conta_partida_update').val(response.conta_partida);
                    $('#nomePartida').val(response
                        .lcto_partida_nome); // Certifique-se de que a API retorna 'lcto_partida_nome'

                    // Categoria: Ajusta o campo Categoria com por apenas os últimos 5 dígitos do ID formatado.
                    var categoriaIdCompleta = String(response.categorias_id);
                    var codCategoria_Fmt = getOnlyCodigoCategoria(categoriaIdCompleta);
                    $('#categorias_id').val(
                        codCategoria_Fmt); // usar o mesmo id do create sem update para manter a consistência
                    $('#nomeConta').val(response
                        .categorias_nome); // Assumindo que sua API retorna o nome da categoria

                    // --- NOVO: Derivar codPlanoCategoria a partir de categorias_id ---
                    let codPlanoCategoriaDerivado = '';
                    if (categoriaIdCompleta && categoriaIdCompleta.length > 5) {
                        // Remove os últimos 5 dígitos para obter o codPlanoCategoria
                        codPlanoCategoriaDerivado = categoriaIdCompleta.substring(0, categoriaIdCompleta
                            .length - 5);
                    }
                    // Preenche o campo hidden com o valor derivado
                    $('#codPlanoCategoria_update').val(codPlanoCategoriaDerivado);
                    // --- FIM NOVO ---

                    $('#conta_contrapartida_update').val(response.conta_contrapartida);
                    $('#nomeContraPartida').val(response
                        .lcto_contra_partida_nome); // Assumindo que sua API retorna o nome

                    // Exibir o modal
                    $('#updateModalLancamento').modal('show');
                },
                error: function(xhr) {
                    console.error("Erro ao carregar lançamento:", xhr.responseText);
                    displayMessage('Erro ao carregar dados do lançamento.', false);
                }
            });
        }

        function loadDeleteLcto(id, tipoDocto, nrDocto, valor) {
            // Converte o valor para número, caso ele venha como string
            const valorNumerico = parseFloat(valor);

            var docto_id = tipoDocto + '-' + nrDocto;

            // Seleciona os elementos do modal uma única vez para melhor performance
            const lctoIdInput = $('#lctoId_delete');
            const nrDoctoDisplay = $('#lcto_nrDocto_delete_display');
            const valorDisplay = $('#lcto_valor_delete_display');

            // Preenche os dados do documento
            lctoIdInput.val(id);
            nrDoctoDisplay.text(docto_id);

            // 1. Formata o número para o padrão brasileiro (###.###,##)
            // O 'pt-BR' garante o uso de '.' para milhar e ',' para decimal.
            const valorFormatado = new Intl.NumberFormat('pt-BR', {
                minimumFractionDigits: 2, // Garante sempre duas casas decimais
                maximumFractionDigits: 2
            }).format(valorNumerico);

            // 2. Aplica a cor com base no valor ser positivo ou negativo
            if (valorNumerico >= 0) {
                valorDisplay.css('color', 'black');
            } else {
                valorDisplay.css('color', 'red');
            }

            // 3. Exibe o valor já formatado no elemento span
            valorDisplay.text(valorFormatado);

            // Mostrar o modal de confirmação de exclusão
            $('#deleteModalLancamento').modal('show');
        }

        // Funções de formatação de valor
        function formatValueOnInput(input) {
            let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            if (value.length === 0) {
                input.value = '';
                return;
            }

            let isNegative = false;
            if (value.startsWith('-')) {
                isNegative = true;
                value = value.substring(1);
            }

            // Adiciona zeros à esquerda para garantir pelo menos 2 casas decimais
            while (value.length < 3) {
                value = '0' + value;
            }

            let integerPart = value.substring(0, value.length - 2);
            let decimalPart = value.substring(value.length - 2);

            // Adiciona separador de milhares
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            input.value = (isNegative ? '-' : '') + integerPart + ',' + decimalPart;
        }

        function formatAndValidateValor(input) {
            let value = input.value;
            let cleanedValue = value.replace(/\./g, '').replace(',', '.'); // Remove pontos e troca vírgula por ponto

            let numericValue = parseFloat(cleanedValue);

            if (isNaN(numericValue)) {
                input.value = ''; // Limpa se não for um número válido
                displayMessage('O campo "Valor" deve ser um número válido.', false);
                return;
            }

            if (numericValue === 0) {
                displayMessage('O campo "Valor" não pode ser zero ou nulo.', false);
                input.value = '0,00';
                return;
            }

            // Formata para exibição final
            input.value = formatNumberForDisplay(numericValue);

            // Aplica/remove a classe de negativo
            if (numericValue < 0) {
                $(input).addClass('valor-negativo');
            } else {
                $(input).removeClass('valor-negativo');
            }
        }

        function handleDeleteClear(event) {
            if (event.key === 'Delete' || event.key === 'Del') {
                event.target.value = '';
                event.preventDefault(); // Previne o comportamento padrão da tecla Delete
            }
        }

        function jsOcultaCategoriaContraPartida_update() {
            var tipoConta = $('#tipo_conta_update').val();
            var codPlanoCategoria = $('#codPlanoCategoria').val(); // Certifique-se de que este ID existe no seu HTML
            var semCodCategoria = '99999';

            // Seleciona o elemento que contém a categoria de contrapartida (assumindo que tem a classe 'categoria-contrapartida')
            var categoriaContrapartidaContainer = $(
                '.categoria-contrapartida'); // Certifique-se de que este seletor está correto para o seu container

            // Se o tipo de conta for 'banco' (agora em minúsculas), exibe a seção
            if (tipoConta === 'banco') {
                categoriaContrapartidaContainer.show(); // jQuery: exibe o elemento (display: block)

            } else {
                // Se não for 'banco', oculta a seção
                $('#categorias_id').val(semCodCategoria); // Define um valor padrão
                $('#conta_contrapartida_update').val('1');
                $('#nomeConta').val('SEM CATEGORIA'); // Limpa o campo nomeConta// Define um valor padrão
                $('#nomeContraPartida').val('SEM CONTRAPARTIDA'); // Limpa o campo nomeContraPartida
                categoriaContrapartidaContainer.hide(); // jQuery: oculta o elemento (display: none)
                // $('#nomePartida').val('');
            }
        }
        // Garante que os event listeners são anexados após o DOM estar pronto
        $(document).ready(function() {
            // Lidar com o envio do formulário de atualização via AJAX
            $('#updateLancamentoForm').on('submit', function(e) {
                e.preventDefault(); // ESSENCIAL: Previne a submissão padrão do formulário

                var lancamentoId = $('#lcto_id_update').val();
                var formData = $(this).serialize();

                $.ajax({
                    url: `/lancamento/update`,
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        console.log('Resposta do servidor (sucesso):', response);
                        displayMessage(response.message, response.success);

                        if (response.success) {
                            setTimeout(function() {
                                $('#updateModalLancamento').modal('hide');
                                window.location.reload();
                            }, 500);
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao atualizar lançamento (AJAX):", xhr.status, xhr
                            .responseText, xhr.responseJSON);

                        let errorMessage = 'Erro ao atualizar: Verifique os dados.';
                        // Se houver um array de erros no responseJSON, use-o
                        if (xhr.responseJSON && xhr.responseJSON.errors && Array.isArray(xhr
                                .responseJSON.errors)) {
                            errorMessage = xhr.responseJSON.errors; // Passa o array de erros
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            errorMessage = 'Erro desconhecido ao atualizar: ' + xhr.responseText
                                .substring(0, 100) + '...';
                        }
                        displayMessage(errorMessage,
                            false); // displayMessage agora pode lidar com array
                    }
                });
            });

            // Adicione os event listeners para os campos de valor
            $('#valor_update').on('input', function() {
                formatValueOnInput(this);
            });
            $('#valor_update').on('blur', function() {
                formatAndValidateValor(this);
            });
            $('#valor_update').on('keydown', handleDeleteClear);

            // Adicione o event listener para o tipo de conta (para ocultar/exibir categoria/contrapartida)
            $('#tipo_conta_update').on('change', function() {
                jsOcultaCategoriaContraPartida_update();
            });

            // Chame jsOcultaCategoriaContraPartida no carregamento do modal para garantir o estado correto
            $('#updateModalLancamento').on('shown.bs.modal', function() {
                jsOcultaCategoriaContraPartida_update();
            });

        });

        // Fim do $(document).ready
    </script>
@stop
