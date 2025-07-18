<?php

namespace App\Http\Controllers;

use App\Models\Lancamento;
use App\Models\Empresa;
use App\Models\Categoria;
use App\Models\ResumoFluxoCaixa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FluxoCaixaController extends Controller
{
   public function index()
    {
        // Exemplo de como você passaria as empresas para a view (ajuste conforme seu modelo)
        $empresas = [
            (object)['id' => 1, 'nome' => 'Empresa Alpha'],
            (object)['id' => 2, 'nome' => 'Empresa Beta'],
            (object)['id' => 3, 'nome' => 'Empresa Gamma'], // Usamos a empresa 3 nos exemplos da SP
        ];

        // Você pode adicionar um campo para fk_tipocategoria_id ou deixá-lo fixo por enquanto
        // $tiposCategorias = [...];

        return view('relatorios.fluxocaixa', compact('empresas'));
    }

    /**
     * Processa a requisição para gerar o relatório de fluxo de caixa.
     */
    public function gerar(Request $request)
    {
         // Valida os dados da requisição com os nomes de input corretos
        $request->validate([
            'empresa_select' => 'required|integer',
            'lcto_dataInicial' => 'required|date',
            'lcto_dataFinal' => 'required|date',
            'colecaoCategoria_id' => 'required|integer',
            'empresa_nome' => 'nullable|string', // Adicionado para validar e capturar o nome da empresa
        ]);

        // Atribui os valores dos inputs da requisição às variáveis
        $empresaId = $request->input('empresa_select');
        $dataInicial = $request->input('lcto_dataInicial');
        $dataFinal = $request->input('lcto_dataFinal');
        $fkTipoCategoriaId = $request->input('colecaoCategoria_id');
        $empresaNome = $request->input('empresa_nome'); // Captura o nome da empresa

        try {
            // Chama a stored procedure diretamente usando DB::select
            $resultados = DB::select('CALL sp_gerar_fluxo_caixa_agregado(?, ?, ?, ?)', [
                $empresaId,
                $dataInicial,
                $dataFinal,
                $fkTipoCategoriaId
            ]);

            // Mapeia os nomes das colunas de meses (display_mes_X) para usar como cabeçalhos na tabela
            $mesesDisplay = [];
            if (!empty($resultados)) {
                $primeiroResultado = (array) $resultados[0];
                for ($i = 1; $i <= 12; $i++) {
                    $key = 'display_mes_' . $i;
                    if (isset($primeiroResultado[$key])) {
                        $mesesDisplay[] = $primeiroResultado[$key];
                    }
                }
            }

            // Retorna a view com os resultados, os meses, as empresas E O NOME DA EMPRESA
            // Você deve buscar as empresas do banco de dados para que seja dinâmico
            // Exemplo: $empresas = \App\Models\Empresa::all();
            $empresas = [
                (object)['id' => 1, 'nome' => 'Empresa Alpha', 'tipos_planocontas_id' => 1],
                (object)['id' => 2, 'nome' => 'Empresa Beta', 'tipos_planocontas_id' => 2],
                (object)['id' => 3, 'nome' => 'Empresa Gamma', 'tipos_planocontas_id' => 3],
            ];

            return view('relatorios.fluxocaixa', compact('resultados', 'mesesDisplay', 'empresas', 'empresaNome', 'dataInicial', 'dataFinal'));

        } catch (\Exception $e) {
            // Em caso de erro, redireciona de volta com uma mensagem de erro
            return redirect()->back()->withInput($request->all())->with('error', 'Erro ao gerar o fluxo de caixa: ' . $e->getMessage());
        }
    }
}
