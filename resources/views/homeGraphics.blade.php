@extends('adminlte::page')

@section('title', 'Dashboard')

{{-- Início da seção de cabeçalho do conteúdo --}}
@section('content_header')
    <div class="d-flex align-items-center">
        <h1 class="m-0 text-dark mr-3">Dashboard</h1>

        <div class="folder-navigation">
            {{-- FLUXO DE CAIXA AGORA É O PRIMEIRO FOLDER --}}
            <div class="folder active" id="fluxocaixa-folder" onclick="showSection('fluxocaixa')">
                Fluxo de Caixa
            </div>
            {{-- TOTAL DE LANÇAMENTOS AGORA É O SEGUNDO FOLDER --}}
            <div class="folder" id="lancamentos-folder" onclick="showSection('lancamentos')">
                Total de Lançamentos
            </div>
            {{-- Estilos para a navegação de folders --}}
            <style>
                .folder-navigation {
                    display: flex;
                    margin-left: 20px;
                }

                .folder {
                    background-color: #e0e0e0;
                    border: 1px solid #c0c0c0;
                    border-radius: 5px 5px 0 0;
                    padding: 8px 15px;
                    margin-right: 5px;
                    cursor: pointer;
                    font-weight: bold;
                    color: #555;
                    transition: background-color 0.3s ease;
                    white-space: nowrap;
                }

                .folder.active {
                    background-color: #f8f9fa;
                    border-bottom: 1px solid #f8f9fa;
                    color: #333;
                }

                .folder:hover {
                    background-color: #d0d0d0;
                }

                /* Novos estilos para os checkboxes na legenda */
                #checkbox-container-fluxocaixa {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 15px;
                    /* Espaçamento entre os itens da legenda */
                    margin-bottom: 20px;
                }

                #checkbox-container-fluxocaixa label {
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                    font-size: 0.9em;
                }

                #checkbox-container-fluxocaixa label input[type="checkbox"] {
                    margin-right: 8px;
                    /* Espaçamento entre checkbox e color box */
                    transform: scale(1.1);
                    /* Um pouco maior para fácil clique */
                }

                #checkbox-container-fluxocaixa label .color-box {
                    width: 16px;
                    height: 16px;
                    border-radius: 3px;
                    display: inline-block;
                    margin-right: 5px;
                    /* Espaçamento entre color box e texto */
                    border: 1px solid #ccc;
                    /* Borda sutil para caixas de cor */
                }
            </style>
        </div>
    </div>
@stop {{-- Fim da seção de cabeçalho do conteúdo --}}

{{-- Início da seção de conteúdo principal --}}
@section('content')
    <div class="p-6 bg-gray-100 min-h-screen font-inter w-full">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8 space-y-6">

            {{-- Seção para o Gráfico de Fluxo de Caixa (agora visível por padrão) --}}
            <div id="fluxocaixa-content" class="mt-0">
                <div class="flex justify-between items-center mb-6 mt-0">
                    <h1 class="text-1xl font-bold text-gray-800">
                        FLUXO DE CAIXA Mensal
                    </h1>

                    <div class="flex items-center">
                        {{-- NOVO: Seletor de Empresa para Fluxo de Caixa --}}
                        <label for="empresa-select-fluxocaixa" class="mr-3 text-xs text-gray-700 font-normal">
                            Empresa:
                        </label>
                        <select id="empresa-select-fluxocaixa"
                            class="p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            {{-- CORRIGIDO: {{ $empresa->nome_empresa }} para {{ $empresa->nome }} --}}
                            @foreach ($EMPRESAS as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
                            @endforeach
                        </select>

                        <label for="year-select-fluxocaixa" class="ml-4 mr-3 text-xs text-gray-700 font-normal">
                            Selecionar Ano:
                        </label>
                        <select id="year-select-fluxocaixa"
                            class="p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @php
                                $currentYear = \Carbon\Carbon::now()->year;
                                $years = range($currentYear - 5, $currentYear + 5);
                            @endphp
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="chart-container-fluxocaixa" class="chart-container flex justify-center items-center">
                    <canvas id="monthlyFluxoCaixaChart"></canvas>
                </div>
                <div id="checkbox-container-fluxocaixa" class="mt-4"></div>
                <div id="chart-messages-fluxocaixa" class="text-center p-4 rounded-md hidden"></div>
            </div>

            {{-- Seção para o Gráfico de Total de Lançamentos (agora inicialmente escondida) --}}
            <div id="lancamentos-content" style="display: none;">
                <div class="flex justify-between items-center mb-6 mt-0">
                    <h1 class="text-1xl font-bold text-gray-800">
                        TOTAL DE LANÇAMENTOS por Empresa
                    </h1>

                    <div class="flex items-center">
                        {{-- NOVO: Seletor de Empresa para Lançamentos --}}
                        <label for="empresa-select-lancamentos" class="mr-3 text-xs text-gray-700 font-normal">
                            Empresa:
                        </label>
                        <select id="empresa-select-lancamentos"
                            class="p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            {{-- CORRIGIDO: {{ $empresa->nome_empresa }} para {{ $empresa->nome }} --}}
                            @foreach ($EMPRESAS as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
                            @endforeach
                        </select>

                        <label for="year-select-lancamentos" class="ml-4 mr-3 text-xs text-gray-700 font-normal">
                            Selecionar Ano:
                        </label>
                        <select id="year-select-lancamentos"
                            class="p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="chart-container-lancamentos" class="chart-container flex justify-center items-center">
                    <canvas id="monthlyLaunchesChart"></canvas>
                </div>

                <div id="chart-messages-lancamentos" class="text-center p-4 rounded-md hidden"></div>
            </div>
        </div>
    </div>
@stop {{-- Fim da seção de conteúdo principal --}}

{{-- Seção para estilos CSS adicionais --}}
@section('css')
    {{-- CDN para Tailwind CSS (ou inclua via PostCSS se estiver usando localmente) --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Estilos globais ou para o corpo, se necessário */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Estilos para o contêiner do gráfico */
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }
    </style>
@stop {{-- Fim da seção CSS --}}

{{-- Seção para scripts JavaScript adicionais --}}
@section('js')
    {{-- CDN para Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- CDN para Moment.js e chartjs-adapter-moment (essencial para eixos de tempo) --}}
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>

    <script>
        let myChartInstanceLancamentos = null;
        let myChartInstanceFluxoCaixa = null;

        // Função para gerar cores dinâmicas (melhorada para ser mais vibrante)
        function getColors(numColors) {
            const colors = [];
            const baseColors = [
                '#4285F4', '#FBBC05', '#34A853', '#EA4335', '#8D6E63',
                '#607D8B', '#9C27B0', '#00BCD4', '#FF9800', '#CDDC39',
                '#E91E63', '#673AB7', '#FFEB3B', '#795548', '#2196F3'
            ];
            for (let i = 0; i < numColors; i++) {
                colors.push(baseColors[i % baseColors.length]);
            }
            return colors;
        }

        // --- FUNÇÃO PARA O GRÁFICO DE LANÇAMENTOS ---
        async function fetchAndRenderChartLancamentos(year, empresaId) {
            const chartMessages = document.getElementById('chart-messages-lancamentos');
            const chartContainer = document.getElementById('chart-container-lancamentos');
            const canvas = document.getElementById('monthlyLaunchesChart');
            const ctx = canvas.getContext('2d');

            chartMessages.className =
                'text-center p-4 bg-gray-100 border border-gray-400 text-gray-700 rounded-md block';
            chartMessages.innerHTML = `
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mr-3"></div>
                    Carregando dados do gráfico para ${year} (Empresa ID: ${empresaId})...
                </div>`;
            chartContainer.style.display = 'none';

            if (myChartInstanceLancamentos) {
                myChartInstanceLancamentos.destroy();
            }

            try {
                const response = await fetch(`${window.location.origin}/api/monthly-launch-totals?year=${year}&empresa_id=${empresaId}`);
                if (!response.ok) {
                    throw new Error(`Erro HTTP! status: ${response.status}`);
                }
                const result = await response.json();

                console.log('Dados recebidos da API para Lançamentos:', result);

                const chartData = result.data;
                const empresas = result.empresas;

                if (chartData.length === 0) {
                    chartMessages.className =
                        'text-center p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md block';
                    chartMessages.innerHTML = `
                        <p class="font-bold">Dados não encontrados:</p>
                        <p>Nenhum lançamento encontrado para o ano ${year} e empresa ${empresaId}.</p>
                        <p>Certifique-se de que a agregação foi executada para este ano e empresa.</p>`;
                    chartContainer.style.display = 'none';
                    return;
                }

                const labels = chartData.map(item => item.mes_ano);

                const datasets = empresas.map((empresa, index) => {
                    const colors = getColors(empresas.length);
                    return {
                        label: empresa,
                        data: chartData.map(item => item[empresa] || 0),
                        backgroundColor: colors[index % colors.length],
                        borderColor: colors[index % colors.length],
                        borderWidth: 1,
                    };
                });

                chartMessages.style.display = 'none';
                chartContainer.style.display = 'block';

                myChartInstanceLancamentos = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            },
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Mês/Ano'
                                },
                                stacked: false,
                                type: 'time',
                                time: {
                                    unit: 'month',
                                    tooltipFormat: 'MM/YYYY',
                                    displayFormats: {
                                        month: 'MM/YYYY'
                                    },
                                    parser: 'MM/YYYY' // Garante que a data seja interpretada corretamente
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Total de Lançamentos'
                                },
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                stacked: false,
                            }
                        }
                    }
                });

            } catch (e) {
                console.error("Erro ao carregar dados do gráfico de Lançamentos:", e);
                chartMessages.className =
                    'text-center p-4 bg-red-100 border border-red-400 text-red-700 rounded-md block';
                chartMessages.innerHTML = `
                    <p class="font-bold">Erro:</p>
                    <p>Não foi possível carregar os dados do gráfico de Lançamentos. Por favor, tente novamente.</p>
                    <p>Detalhes: ${e.message}</p>`;
                chartContainer.style.display = 'none';
            }
        }

        // --- FUNÇÃO PARA O GRÁFICO DE FLUXO DE CAIXA ---
        async function fetchAndRenderChartFluxoCaixa(year, empresaId) {
            const chartMessages = document.getElementById('chart-messages-fluxocaixa');
            const chartContainer = document.getElementById('chart-container-fluxocaixa');
            const checkboxContainer = document.getElementById('checkbox-container-fluxocaixa');
            const canvas = document.getElementById('monthlyFluxoCaixaChart');
            const ctx = canvas.getContext('2d');

            chartMessages.className =
                'text-center p-4 bg-gray-100 border border-gray-400 text-gray-700 rounded-md block';
            chartMessages.innerHTML = `
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mr-3"></div>
                    Carregando dados do gráfico de Fluxo de Caixa para ${year} (Empresa ID: ${empresaId})...
                </div>`;
            chartContainer.style.display = 'none';
            checkboxContainer.innerHTML = ''; // Limpa checkboxes anteriores

            if (myChartInstanceFluxoCaixa) {
                myChartInstanceFluxoCaixa.destroy();
            }

            try {
                const response = await fetch(`${window.location.origin}/api/monthly-fluxocaixa-totals?year=${year}&empresa_id=${empresaId}`);
                if (!response.ok) throw new Error(`Erro HTTP! status: ${response.status}`);

                const result = await response.json();

                console.log('Dados recebidos da API para Fluxo de Caixa:', result);

                const chartData = result.data;
                const categories = result.categories;

                if (chartData.length === 0) {
                    chartMessages.className =
                        'text-center p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md block';
                    chartMessages.innerHTML = `
                        <p class="font-bold">Dados de Fluxo de Caixa não encontrados:</p>
                        <p>Nenhum dado encontrado para o ano ${year} e empresa ${empresaId}.</p>`;
                    chartContainer.style.display = 'none';
                    return;
                }

                const labels = chartData.map(item => item.mes_ano);
                const colors = getColors(categories.length);

                const allDatasets = categories.map((category, index) => {
                    let datasetColor = colors[index % colors.length];
                    let borderColor = datasetColor;
                    let fill = false;
                    let borderWidth = 2;
                    let pointRadius = 3;
                    let pointBackgroundColor = borderColor;
                    let hidden = false;

                    // Regras específicas para o "Total Geral de Fluxo de Caixa"
                    if (category === 'Total Geral de Fluxo de Caixa' || category === 'Total Geral') {
                        borderColor = 'rgb(75, 192, 192)'; // Cor distinta para o total
                        borderWidth = 3;
                        pointRadius = 5;
                        pointBackgroundColor = borderColor;
                        fill = false;
                    }

                    return {
                        label: category,
                        data: chartData.map(item => item[category] || 0.00),
                        borderColor: borderColor,
                        backgroundColor: borderColor.replace('0.8)', '0.2)'), // Cor de fundo mais clara para área (se fill true)
                        fill: fill,
                        tension: 0.3,
                        pointRadius: pointRadius,
                        pointHoverRadius: 5,
                        borderWidth: borderWidth,
                        hidden: hidden
                    };
                });

                // GERAÇÃO DOS CHECKBOXES COM QUADRADINHO COLORIDO E TEXTO COLORIDO
                allDatasets.forEach((dataset, index) => {
                    const checkboxDiv = document.createElement('div');
                    checkboxDiv.className = 'flex items-center';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `fluxocaixa-checkbox-${index}`;
                    checkbox.checked = !dataset.hidden;
                    checkbox.className = 'h-4 w-4 text-blue-600 rounded mr-2';

                    const colorBox = document.createElement('span');
                    colorBox.className = 'color-box';
                    colorBox.style.backgroundColor = dataset.borderColor;

                    const labelText = document.createElement('span');
                    labelText.textContent = dataset.label;
                    labelText.className = 'text-sm font-medium';
                    labelText.style.color = dataset.borderColor;

                    checkboxDiv.appendChild(checkbox);
                    checkboxDiv.appendChild(colorBox);
                    checkboxDiv.appendChild(labelText);
                    checkboxContainer.appendChild(checkboxDiv);

                    // Adiciona o listener de evento para alternar visibilidade da linha
                    checkbox.addEventListener('change', () => {
                        dataset.hidden = !checkbox.checked;
                        myChartInstanceFluxoCaixa.update();
                    });
                });

                chartMessages.style.display = 'none';
                chartContainer.style.display = 'block';

                myChartInstanceFluxoCaixa = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: allDatasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) label += ': ';
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('pt-BR', {
                                                style: 'currency',
                                                currency: 'BRL'
                                            }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            },
                            legend: {
                                display: false // A legenda é controlada pelos checkboxes agora
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Mês/Ano'
                                },
                                type: 'time',
                                time: {
                                    unit: 'month',
                                    tooltipFormat: 'MM/YYYY',
                                    displayFormats: {
                                        month: 'MM/YYYY'
                                    },
                                    parser: 'MM/YYYY' // Garante que a data seja interpretada corretamente
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Valor Monetário (BRL)'
                                },
                                beginAtZero: false,
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('pt-BR', {
                                            style: 'currency',
                                            currency: 'BRL'
                                        }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });

            } catch (e) {
                console.error("Erro ao carregar dados do gráfico de Fluxo de Caixa:", e);
                chartMessages.className =
                    'text-center p-4 bg-red-100 border border-red-400 text-red-700 rounded-md block';
                chartMessages.innerHTML = `
                    <p class="font-bold">Erro:</p>
                    <p>Não foi possível carregar os dados do gráfico. Por favor, tente novamente.</p>
                    <p>Detalhes: ${e.message}</p>`;
                chartContainer.style.display = 'none';
            }
        }

        // --- FUNÇÃO PARA ALTERNAR SEÇÕES ---
        function showSection(section) {
            const lancamentosContent = document.getElementById('lancamentos-content');
            const fluxocaixaContent = document.getElementById('fluxocaixa-content');
            const lancamentosFolder = document.getElementById('lancamentos-folder');
            const fluxocaixaFolder = document.getElementById('fluxocaixa-folder');

            // Primeiro, oculta todas as seções de conteúdo
            lancamentosContent.style.display = 'none';
            fluxocaixaContent.style.display = 'none';

            // Remove a classe 'active' de todos os folders
            lancamentosFolder.classList.remove('active');
            fluxocaixaFolder.classList.remove('active');

            if (section === 'lancamentos') {
                lancamentosContent.style.display = 'block';
                lancamentosFolder.classList.add('active');
                const yearSelectLancamentos = document.getElementById('year-select-lancamentos');
                const empresaSelectLancamentos = document.getElementById('empresa-select-lancamentos');
                const currentEmpresaId = empresaSelectLancamentos ? empresaSelectLancamentos.value : null;
                const currentYear = yearSelectLancamentos ? yearSelectLancamentos.value : new Date().getFullYear();
                fetchAndRenderChartLancamentos(currentYear, currentEmpresaId);
            } else if (section === 'fluxocaixa') {
                fluxocaixaContent.style.display = 'block';
                fluxocaixaFolder.classList.add('active');
                const yearSelectFluxoCaixa = document.getElementById('year-select-fluxocaixa');
                const empresaSelectFluxoCaixa = document.getElementById('empresa-select-fluxocaixa');
                const currentEmpresaId = empresaSelectFluxoCaixa ? empresaSelectFluxoCaixa.value : null;
                const currentYear = yearSelectFluxoCaixa ? yearSelectFluxoCaixa.value : new Date().getFullYear();
                fetchAndRenderChartFluxoCaixa(currentYear, currentEmpresaId);
            }
        }

        // --- LISTENERS DE EVENTOS ---
        document.addEventListener('DOMContentLoaded', () => {
            // Pegar todos os seletores de empresa e ano
            const empresaSelectFluxoCaixa = document.getElementById('empresa-select-fluxocaixa');
            const yearSelectFluxoCaixa = document.getElementById('year-select-fluxocaixa');
            const empresaSelectLancamentos = document.getElementById('empresa-select-lancamentos');
            const yearSelectLancamentos = document.getElementById('year-select-lancamentos');

            // Definir empresa padrão para ambos os gráficos (a primeira do dropdown)
            // Isso garante que uma empresa esteja selecionada no carregamento inicial
            if (empresaSelectFluxoCaixa && empresaSelectFluxoCaixa.options.length > 0) {
                // Seleciona a primeira empresa do dropdown
                empresaSelectFluxoCaixa.value = empresaSelectFluxoCaixa.options[0].value;
            }
            if (empresaSelectLancamentos && empresaSelectLancamentos.options.length > 0) {
                // Seleciona a primeira empresa do dropdown
                empresaSelectLancamentos.value = empresaSelectLancamentos.options[0].value;
            }

            // Adiciona listeners para Fluxo de Caixa
            if (empresaSelectFluxoCaixa) {
                empresaSelectFluxoCaixa.addEventListener('change', (event) => {
                    const currentYear = yearSelectFluxoCaixa ? yearSelectFluxoCaixa.value : new Date().getFullYear();
                    fetchAndRenderChartFluxoCaixa(currentYear, event.target.value);
                });
            }
            if (yearSelectFluxoCaixa) {
                yearSelectFluxoCaixa.addEventListener('change', (event) => {
                    const currentEmpresaId = empresaSelectFluxoCaixa ? empresaSelectFluxoCaixa.value : null;
                    fetchAndRenderChartFluxoCaixa(event.target.value, currentEmpresaId);
                });
            }

            // Adiciona listeners para Lançamentos
            if (empresaSelectLancamentos) {
                empresaSelectLancamentos.addEventListener('change', (event) => {
                    const currentYear = yearSelectLancamentos ? yearSelectLancamentos.value : new Date().getFullYear();
                    fetchAndRenderChartLancamentos(currentYear, event.target.value);
                });
            }
            if (yearSelectLancamentos) {
                yearSelectLancamentos.addEventListener('change', (event) => {
                    const currentEmpresaId = empresaSelectLancamentos ? empresaSelectLancamentos.value : null;
                    fetchAndRenderChartLancamentos(event.target.value, currentEmpresaId);
                });
            }

            // Garante que o Fluxo de Caixa seja a seção ativa ao carregar a página
            // E carrega os dados para a empresa e ano inicialmente selecionados
            showSection('fluxocaixa');
        });
    </script>
@stop {{-- Fim da seção JS --}}