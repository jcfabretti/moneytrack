<?php

namespace App\Http\Controllers\Api; // ✅ Namespace corrigido para Api

use App\Http\Controllers\Controller; // Use o Controller base do Laravel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ValoresMensais;
use App\Models\Empresa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log; // Importar a fachada Log

class ChartDataController extends Controller // ✅ Nome da classe corrigido
{
    /**
     * Retorna os totais de lançamentos por mês e empresa para um dado ano.
     * Inclui logs da query SQL para depuração.
     */
    public function getMonthlyLaunchTotals(Request $request)
    {
        $tipoDadoParaGrafico = 'ContagemLancamentos';
        $year = $request->input('year', Carbon::now()->year);
        $empresaId = $request->input('empresa_id');

        if (!$empresaId) {
            Log::warning("getMonthlyLaunchTotals: empresa_id não fornecido na requisição.");
            return response()->json(['data' => [], 'empresas' => []]);
        }

        // Início da construção da query
        $query = ValoresMensais::where('tipo_dado', $tipoDadoParaGrafico)
            ->where(DB::raw('SUBSTRING(mes_ano, 4, 4)'), (string) $year)
            ->where('empresa_id', $empresaId)
            ->orderBy(DB::raw("STR_TO_DATE(mes_ano, '%m/%Y')"));

        // ✅ Log da query SQL gerada para depuração

        $totals = $query->get();

        $chartData = [];
        $empresas = [];

        // Inicializa o chartData com todos os meses do ano
        for ($m = 1; $m <= 12; $m++) {
            $mesAnoFormatado = Carbon::createFromDate($year, $m, 1)->format('m/Y');
            $chartData[$mesAnoFormatado] = ['mes_ano' => $mesAnoFormatado];
        }

        // Popula o chartData com os totais obtidos do banco de dados
        foreach ($totals as $total) {
            $mesAno = $total->mes_ano;
            $nomeEmpresa = $total->nome_empresa;
            $quantidadeTotal = (int) $total->quantidade_numerica;

            $empresas[$nomeEmpresa] = true; // Marca a empresa como existente nos dados

            $chartData[$mesAno][$nomeEmpresa] = $quantidadeTotal;
        }

        // Garante que todas as empresas encontradas tenham um valor para cada mês (0 se não houver)
        $uniqueEmpresas = array_keys($empresas);
        foreach ($chartData as $mesAno => &$dataRow) {
            foreach ($uniqueEmpresas as $empresaNome) {
                if (!isset($dataRow[$empresaNome])) {
                    $dataRow[$empresaNome] = 0;
                }
            }
        }
        unset($dataRow); // Desreferencia a variável para evitar efeitos colaterais em loops futuros

        $formattedChartData = array_values($chartData); // Converte o array associativo em array indexado

        return response()->json([
            'data' => $formattedChartData,
            'empresas' => $uniqueEmpresas
        ]);
    }

    /**
     * Retorna os totais de fluxo de caixa por categoria/total e mês para um dado ano.
     * Inclui logs da query SQL para depuração.
     */
    public function getMonthlyFluxoCaixaTotals(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $empresaId = $request->input('empresa_id');

            Log::warning("Fluxo caixa executado".now());

        Log::info("Chamada para getMonthlyFluxoCaixaTotals. Ano: {$year}, Empresa ID: {$empresaId}");

        if (!$empresaId) {
            Log::warning("getMonthlyFluxoCaixaTotals: empresa_id não fornecido na requisição.");
            return response()->json(['data' => [], 'categories' => []]);
        }

        // Query para categorias de Fluxo de Caixa (e.g., Entradas, Saídas)
        $categoriaQuery = ValoresMensais::where('tipo_dado', 'FluxoCaixaCategoria')
            ->where(DB::raw('SUBSTRING(mes_ano, 4, 4)'), (string) $year)
            ->where('empresa_id', $empresaId)
            ->orderBy(DB::raw("STR_TO_DATE(mes_ano, '%m/%Y')"));

        // ✅ Log da query SQL gerada para depuração

        $categoriaTotals = $categoriaQuery->get();

        // Query para o Total Geral de Fluxo de Caixa
        $overallQuery = ValoresMensais::where('tipo_dado', 'FluxoCaixaTotal')
            ->where(DB::raw('SUBSTRING(mes_ano, 4, 4)'), (string) $year)
            ->where('empresa_id', $empresaId)
            ->orderBy(DB::raw("STR_TO_DATE(mes_ano, '%m/%Y')"));

        $overallTotals = $overallQuery->get();


        $chartData = [];
        $categories = []; 

        // Inicializa o chartData com todos os meses do ano
        for ($m = 1; $m <= 12; $m++) {
            $mesAnoFormatado = Carbon::createFromDate($year, $m, 1)->format('m/Y');
            $chartData[$mesAnoFormatado] = ['mes_ano' => $mesAnoFormatado];
        }

        // Popula o chartData com os totais de categorias
        foreach ($categoriaTotals as $item) {
            $mesAno = $item->mes_ano;
            $categoryName = $item->item_nome; 
            $value = (float) $item->valor_monetario;

            $chartData[$mesAno][$categoryName] = $value;

            if (!in_array($categoryName, $categories)) {
                $categories[] = $categoryName;
            }
        }

        // Popula o chartData com os totais gerais (se houver)
        foreach ($overallTotals as $item) {
            $mesAno = $item->mes_ano;
            $categoryName = $item->item_nome; 
            $value = (float) $item->valor_monetario;

            $chartData[$mesAno][$categoryName] = $value;

            if (!in_array($categoryName, $categories)) {
                $categories[] = $categoryName;
            }
        }

        // Garante que todas as categorias tenham um valor para cada mês (0.00 se não houver)
        foreach ($chartData as $mesAno => &$dataRow) {
            foreach ($categories as $cat) {
                if (!isset($dataRow[$cat])) {
                    $dataRow[$cat] = 0.00;
                }
            }
        }
        unset($dataRow); // Desreferencia a variável

        // Ordena as categorias, colocando "Total Geral de Fluxo de Caixa" por último
        usort($categories, function($a, $b) {
            if ($a === 'Total Geral de Fluxo de Caixa') return 1; 
            if ($b === 'Total Geral de Fluxo de Caixa') return -1;
            return strnatcmp($a, $b); 
        });

        $formattedChartData = array_values($chartData); // Converte o array associativo em array indexado

        return response()->json([
            'data' => $formattedChartData,
            'categories' => $categories
        ]);
    }
}

