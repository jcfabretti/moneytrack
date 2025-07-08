@extends('adminlte::page')
@php
    use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Dashboard')

@section('content_header')

    <head>
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="{{ asset('js/lancamento.js') }}"></script>
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
                                <option value="" {{ ($empresaId == null) ? 'selected' : '' }}>Todas as Empresas</option>
                                {{-- Itera sobre as empresas e marca a selecionada --}}
                                @foreach ($empresas as $empresa) {{-- Variável $empresas (minúsculas) --}}
                                    <option value="{{ $empresa->id }}" {{ ($empresaId == $empresa->id) ? 'selected' : '' }}>
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
                                <input class="form-check-input" type="checkbox" id="todas_datas_checkbox" {{ $isTodasDatasChecked ? 'checked' : '' }}>
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

        <section class="formulario mt-0 pt-0"> <!-- mt-0 pt-0 reduce margin top and padding top to zero -->
            <div class="row mb-3">
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
                                // Removido o dd() de depuração
                                // dd($lancamento->categoria?->numero_categoria);

                                // Use o operador null-safe para evitar erro se $lancamento->categoria for null
                                // E passe o numero_categoria para a função
                                // Se a sua versão do PHP for < 8.0, use:
                                // $numeroCategoria = $lancamento->categoria ? $lancamento->categoria->numero_categoria : null;
                                // $cod_categoria = formatarNumeroCategoria($numeroCategoria);
                                $cod_categoria = formatarNumeroCategoria($lancamento->categoria?->numero_categoria);
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
                                $codigoNomeCategoria = ($lancamento->categoria?->numero_categoria ?? '') . ' - ' . ($lancamento->categoria?->nome ?? '');
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
                                    data-target="#viewModalLancamento" data-toggle="modal"
                                    id="openViewModalBtn">
                                    <i class="far fa-eye" title="Consultar" style="color:blue"></i>
                                </a>

                                {{-- ALTERADO: O botão de editar agora chama loadEditModal com apenas o ID --}}
                                <a href="#" class="edit" onclick="loadEditModal({{ $lancamento->id }})"
                                    data-toggle="modal" id="openEditModalBtn">
                                    <i class="material-icons" style="color:green" data-toggle="tooltip"
                                        title="Alterar">&#xE254;</i>
                                </a>

                                <a href="#loadDeleteLancamento" class="delete"
                                    onclick="loadDeleteLancamento('{{ $lancamento->id }}', '{{ $lancamento->numero_docto }}', '{{ $lancamento->LctoPartida?->nome ?? '' }}')" {{-- Use null-safe --}}
                                    data-toggle="modal" id="openDeleteModalBtn">
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

        // EDIT MODAL
        function loadEditModal(id) {
            // Limpar mensagens anteriores
            $('#message_update').text('');
            // Create a new formatter without a currency symbol
            const numberFormatter = new Intl.NumberFormat('pt-BR', {
                // You might want to specify minimum/maximum fraction digits
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
                useGrouping: true // This will add thousands separators (e.g., 1.000.000)
            });

            // Fazer uma requisição AJAX para buscar os dados do lançamento
            $.ajax({
                url: `/api/lancamentos/${id}`, // Ajuste esta URL para sua rota de API
                method: 'GET',
                success: function(response) {
                    var valor = response.valor; // Pega o valor do lançamento (deve ser numérico)
                    let isNegative = valor.startsWith('-');
                    var valorFormatado = valor ? numberFormatter.format(valor) : '';
                    $('#valor_update').val(valorFormatado);
                    if (isNegative) {
                        $('#valor_update').addClass('valor-negativo');
                    } else {
                        $('#valor_update').removeClass('valor-negativo');
                    }

                    // Ajusta o campo Categoria com por apenas os últimos 5 dígitos do ID formatado.
                    var categoriaIdCompleta = String(response.categorias_id).slice(-
                        5); // Garante que o valor é uma string
                    var codCategoria_Fmt = formatCodigoCategoria(categoriaIdCompleta);

                    // Preencher os campos do formulário no modal
                    $('#lcto_id_update').val(response.id);
                    $('#empresa_nome_update').val(response
                        .empresa_nome); // Certifique-se de que sua API retorna isso
                    $('#empresa_id_update').val(response.empresa_id);
                    $('#grupo_economico_id_update').val(response.grupo_economico_id);
                    $('#origem_update').val(response.origem);
                    $('#data_lcto_update').val(response.data_lcto); // Datas em formato 'YYYY-MM-DD'
                    $('#tipo_docto_update').val(response.tipo_docto);
                    $('#numero_docto_update').val(response.numero_docto);
                    $('#tipo_conta_update').val(response.tipo_conta);

                    // Trigger the onchange event if there are dependent fields
                    // This is important if 'jsOcultaCategoriaContraPartida' needs to run
                    // based on the pre-selected 'tipo_conta'
                    $('#tipo_conta_update').trigger('change');

                    $('#conta_partida_update').val(response.conta_partida);
                    $('#nomePartida_update').val(response
                        .lcto_partida_nome); // para exibir o nome da conta de partida a ser alterada
                    $('#categorias_id_update').val(
                        codCategoria_Fmt); // Atribui ao campo a categoria sem o codigo do plano de categoria
                    $('#nomeConta_update').val(response
                        .categorias_nome); // Assumindo que sua API retorna o nome da categoria
                    $('#codPlanoCategoria_update').val(response.cod_plano_categoria);
                    $('#conta_contrapartida_update').val(response.conta_contrapartida);
                    $('#nomeContraPartida_update').val(response
                        .lcto_contra_partida_nome); // Assumindo que sua API retorna o nome
                    $('#historico_update').val(response.historico);
                    $('#unidade_update').val(response.unidade);
                    $('#quantidade_update').val(response.quantidade);
                    // $('#deb_cred_update').val(response.deb_cred);


                    $('#valor_update').val(valorFormatado); // Formatar para exibição


                    $('#centro_custo_update').val(response.centro_custo);
                    $('#vencimento_update').val(response.vencimento);

                    // Ajustar visibilidade da categoria/contrapartida, se necessário
                    // jsOcultaCategoriaContraPartida($('#tipo_conta_update')[0]);
                    // Exibir o modal
                    $('#updateModalLancamento').modal('show');
                },
                error: function(xhr) {
                    console.error("Erro ao carregar lançamento:", xhr.responseText);
                    $('#message_update').text('Erro ao carregar dados do lançamento.');
                }
            });
        }
    </script>
@stop
