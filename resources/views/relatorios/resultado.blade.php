@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Fluxo de Caixa - {{ $empresaNome }}</h3>
            <p>Período: {{ $dataInicial }} a {{ $dataFinal }}</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            @foreach($mesesExibicao as $mes)
                                <th class="text-end">{{ $mes }}</th>
                            @endforeach
                        </tr>
                    </thead>
<tbody>
    @php
        $sortedFluxoCaixa = collect($fluxoCaixa)->sortBy(function($item, $key) {
            return ($item['nivel'] ?? 99) . '-' . $key;
        });
    @endphp

    @foreach($sortedFluxoCaixa as $id => $data)
        @php
            $has_values = false;
            // Determine qual array de meses usar para a verificação de valores
            $tempMesesParaChecagem = $data['meses_depois_agregacao'] ?? $data['meses'] ?? [];

            // Verifica se algum valor de mês é diferente de zero
            foreach ($tempMesesParaChecagem as $valorMes) {
                if ($valorMes != 0) {
                    $has_values = true;
                    break; // Sai do loop assim que encontrar um valor diferente de zero
                }
            }
        @endphp

        {{-- Mostra a linha apenas se tiver valores ou for uma categoria de nível superior (1 ou 2) --}}
        {{-- ou se for SALDO INICIAL ou SEM CATEGORIA, mesmo que zeradas, elas devem aparecer --}}
        @if($has_values || in_array($data['nivel'] ?? null, [1, 2]) || $id == 200000 || $id == 299999)
            <tr>
                <td>
                    @php
                        $nomeExibicao = '';
                        $nivel = $data['nivel'] ?? null; // Obtém o nível ou null se não existir

                        if (isset($data['nome_categoria'])) {
                            $nomeExibicao = $data['nome_categoria'];
                        } elseif ($id == 200000) {
                            $nomeExibicao = 'SALDO INICIAL';
                            $nivel = 0; // Força o nível para Saldo Inicial
                        } elseif ($id == 299999) {
                            $nomeExibicao = 'SEM CATEGORIA';
                            $nivel = 0; // Força o nível para Sem Categoria
                        } else {
                            $nomeExibicao = $id; // Fallback se não encontrar nome
                        }

                        // Aplica a indentação e negrito com base no nível
                        if ($nivel == 0) { // Nível 0: Saldo Inicial, Sem Categoria
                            echo "<strong>{$nomeExibicao}</strong>";
                        } elseif ($nivel == 1) { // Nível 1: Entradas, Saídas, etc.
                            echo "<strong>{$nomeExibicao}</strong>";
                        } elseif ($nivel == 2) { // Nível 2
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;{$nomeExibicao}";
                        } elseif ($nivel == 3) { // Nível 3
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$nomeExibicao}";
                        } else { // Outros casos ou se o nível não for definido
                            echo $nomeExibicao;
                        }

                        // Esta variável já está definida e será usada pelo foreach dos meses
                        $mesesParaExibir = $data['meses_depois_agregacao'] ?? $data['meses'] ?? [];
                    @endphp
                </td>
                {{-- O loop @foreach dos meses agora pode vir diretamente aqui --}}
                @foreach($mesesParaExibir as $valorMes)
                    <td class="text-end">{{ number_format($valorMes, 2, ',', '.') }}</td>
                @endforeach
            </tr>
        @endif
    @endforeach
</tbody>

                </table>
            </div>
            <a href="{{ route('fluxocaixa.parametros') }}" class="btn btn-secondary mt-3">Voltar</a>
        </div>
    </div>
</div>
@endsection