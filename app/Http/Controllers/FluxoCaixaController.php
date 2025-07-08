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
       
        dd($request);
        // Valida os dados da requisição
        $request->validate([
            'empresa_id' => 'required|integer',
            'data_inicial' => 'required|date',
            'data_final' => 'required|date',
            'fk_tipocategoria_id' => 'required|integer',
        ]);

        $empresaId = $request->input('empresa_id');
        $dataInicial = $request->input('data_inicial');
        $dataFinal = $request->input('data_final');
        $fkTipoCategoriaId = $request->input('fk_tipocategoria_id');

        try {
            // Chama a stored procedure diretamente usando DB::select
            // DB::select retorna um array de objetos ou um array vazio
            $resultados = DB::select('CALL sp_gerar_fluxo_caixa_agregado(?, ?, ?, ?)', [
                $empresaId,
                $dataInicial,
                $dataFinal,
                $fkTipoCategoriaId
            ]);

            // Mapeia os nomes das colunas de meses (display_mes_X) para usar como cabeçalhos na tabela
            // Pega o primeiro resultado (se houver) para obter os nomes dos meses
            $mesesDisplay = [];
            if (!empty($resultados)) {
                $primeiroResultado = (array) $resultados[0]; // Converte para array para acessar as chaves
                for ($i = 1; $i <= 12; $i++) {
                    $key = 'display_mes_' . $i;
                    if (isset($primeiroResultado[$key])) {
                        $mesesDisplay[] = $primeiroResultado[$key];
                    }
                }
            }

            // Retorna a view com os resultados e as empresas (para manter o select preenchido)
            // É importante passar $empresas novamente para que o dropdown continue funcionando
            $empresas = [
                (object)['id' => 1, 'nome' => 'Empresa Alpha'],
                (object)['id' => 2, 'nome' => 'Empresa Beta'],
                (object)['id' => 3, 'nome' => 'Empresa Gamma'],
            ]; // Ou buscar do banco de dados novamente se preferir

            return view('relatorios.fluxocaixa', compact('resultados', 'mesesDisplay', 'empresas'));

        } catch (\Exception $e) {
            // Em caso de erro, redireciona de volta com uma mensagem de erro
            return redirect()->back()->withInput($request->all())->with('error', 'Erro ao gerar o fluxo de caixa: ' . $e->getMessage());
        }
    }
}
