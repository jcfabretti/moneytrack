@extends('adminlte::page')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                {{-- NOVO CABEÇALHO DO RELATÓRIO --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-left" style="font-size: 1.2em; font-weight: bold;">Fluxo de Caixa</div>
                    <div class="text-center" style="font-size: 1.5em; font-weight: bold;">{{ $empresaNome }}</div>
                    {{-- Substitua "Nome da Empresa" pelo nome real --}}
                    <div class="text-right" style="font-size: 0.9em;">
                        Período de {{ $dataInicial }} a {{ $dataFinal }}
                    </div>
                </div>
                {{-- FIM DO NOVO CABEÇALHO --}}

                @isset($resultados)
                    <div class="table-responsive" style="font-size: 11px;">
                        <table class="table table-bordered table-striped table-hover mt-0" style="width: 100%;">
                            <thead style="background-color: #495E8A;" class="text-white">
                                <tr>
                                    <th style="min-width: 250px;">Categoria</th>
                                    @php
                                        // Inicializa um array para armazenar os totais de cada mês.
                                        $totaisMensais = array_fill(0, 12, 0);
                                    @endphp
                                    @foreach ($mesesDisplay as $index => $mes)
                                        <th class="text-right">{{ $mes }}</th>
                                    @endforeach
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resultados as $resultado)
                                    @php
                                        $rowClass = '';
                                        // Aplica a classe 'font-weight-bold' (Bootstrap 4) para Nível 1 e 2.
                                        if ($resultado->nivel == 1 || $resultado->nivel == 2) {
                                            $rowClass = 'font-weight-bold';
                                        }
                                        $codCategoria = formatarNumeroCategoria($resultado->id);
                                        $totalLinha = 0; // Inicializa o total da linha para cada iteração.

                                        $indentClass = '';
                                        // Adiciona classes de padding (Bootstrap 4) para indentação.
                                        // 'pl-3' para Nível 2 e 'pl-5' para Nível 3.
                                        if ($resultado->nivel == 2) {
                                            $indentClass = 'pl-3';
                                        } elseif ($resultado->nivel == 3) {
                                            $indentClass = 'pl-4';
                                        }
                                    @endphp

                                    <tr class="{{ $rowClass }}">
                                        {{-- Aplica a classe de indentação à célula da Categoria --}}
                                        <td class="{{ $indentClass }}">{{ $codCategoria }}- {{ $resultado->nome_categoria }}
                                        </td>
                                        {{-- A célula do Nível foi removida --}}
                                        @for ($i = 1; $i <= 12; $i++)
                                            @php
                                                $valorMes = $resultado->{'mes_' . $i};
                                                $totalLinha += $valorMes; // Soma o valor do mês ao total da linha.

                                                // APENAS SOMA AO TOTAL GERAL SE O NÍVEL FOR 1
                                                if ($resultado->nivel == 1) {
                                                    $totaisMensais[$i - 1] += $valorMes;
                                                }
                                            @endphp
                                            <td class="text-right">{{ number_format($valorMes, 2, ',', '.') }}</td>
                                        @endfor
                                        <td class="text-right">{{ number_format($totalLinha, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- Ajusta o colspan para cobrir as colunas restantes após a remoção de 'Nivel' --}}
                                        <td colspan="{{ 2 + count($mesesDisplay) }}" class="text-center">Nenhum lançamento
                                            encontrado para o período e filtros selecionados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot> {{-- Seção de rodapé para os totais --}}
                                <tr class="bg-light font-weight-bold"> {{-- Estilo para a linha de total geral --}}
                                    {{-- Colspan ajustado: agora cobre apenas a coluna 'Categoria' --}}
                                    <td colspan="1" class="text-right">Total Geral:</td>
                                    @foreach ($totaisMensais as $totalMes)
                                        <td class="text-right">{{ number_format($totalMes, 2, ',', '.') }}</td>
                                    @endforeach
                                    @php
                                        // Calcula o total geral de todo o relatório somando os totais mensais.
                                        $totalGeralRelatorio = array_sum($totaisMensais);
                                    @endphp
                                    <td class="text-right">{{ number_format($totalGeralRelatorio, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endisset
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- Scripts JavaScript adicionais, se necessário --}}
@stop
