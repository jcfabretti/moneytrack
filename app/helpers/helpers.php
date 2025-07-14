<?php

use Illuminate\Support\Number;

// formatar_cpf_cnpj($doc)
// limparCodigoCategoria($codCateg)

 function formatar_cpf_cnpj($doc) {

    $doc = preg_replace("/[^0-9]/", "", $doc);
    $qtd = strlen($doc);

    if($qtd >= 11) {

        if($qtd === 11 ) {

            $docFormatado = substr($doc, 0, 3) . '.' .
                            substr($doc, 3, 3) . '.' .
                            substr($doc, 6, 3) . '-' .
                            substr($doc, 9, 2);
        } else {
            $docFormatado = substr($doc, 0, 2) . '.' .
                            substr($doc, 2, 3) . '.' .
                            substr($doc, 5, 3) . '/' .
                            substr($doc, 8, 4) . '-' .
                            substr($doc, -2);
        }

        return $docFormatado;

    } else {
        return 'Documento invalido';
    }
  }

function limpa_cpf_cnpj($valor){
    $valor = trim($valor);
    $valor = str_replace(array('.','-','/'), "", $valor);
    return $valor;
   }

function removePontosValor($valor){
    $valor = trim($valor);
    if ($valor === null || $valor === 0 || $valor === '0' || $valor === '') {
        $valor = 0;
    } else {
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
    }
    return $valor;
   }   

   function cleanCurrencyValue($valor){
    $valor = trim($valor);
    if ($valor === null || $valor === 0 || $valor === '0' || $valor === '') {
        $valor = 0;
    } else {
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
    }
    return $valor;
   }   



function checaUnidade($unidade){
$unidadeTrimed = trim($unidade); // Remove espaços em branco do início/fim

if (empty($unidadeTrimed)) {
    // Se a unidade estiver vazia (incluindo apenas espaços em branco), defina como 'UND'
    return 'UND';
} else {
    // Caso contrário, use o valor fornecido, em maiúsculas
    return $unidadeTrimed;
}
}

// Função para gerar o número da categoria
// Exemplo: numero_categoria('1', '1.10.05') -> 011005
// Exemplo: numero_categoria('2', '1.10.05') -> 021005
function numero_categoria($codTipoCategoria, $numeroCategoria){
    // 1. Remove os pontos do $numeroCategoria (ex: "1.10.05" -> "11005")
    $numeroCategoriaLimpo = str_replace('.', '', $numeroCategoria);

    // 2. Concatena o codTipoCategoria com o numeroCategoria limpo
    $nrCategoria = $codTipoCategoria . $numeroCategoriaLimpo;

    // 3. Adiciona zero à esquerda se o comprimento total for menor que 7
    // Isso é para garantir que o formato final seja consistente (ex: 011005)
    while (strlen($nrCategoria) < 7) { // Ajuste 7 para o comprimento total desejado (ex: 1 + 5 = 6, então 7 para um zero inicial)
        $nrCategoria = '0' . $nrCategoria;
    }

    // 4. Retorna o valor como um inteiro, pois a coluna categorias_id no DB deve ser numérica
    return (int)$nrCategoria;
}

function numeroConta($numero){
    $numero=substr($numero, -5);
    $pattern='%.%%.%%'; // ['X','X','XXXX','XXX']
    return vsprintf(str_replace('%', '%s', $pattern), str_split($numero));
}

function empresasArray(){
    //$nomes=app(HomeController::class)->listarEmpresa();

    $menu=array('text'=>'ingles', 'icon,' => 'flag-icon flag-icon-us');
    //dd($menu);
    return($menu);
}

function formataValor($valor){
return Number::format($valor, locale: 'pt-br') ;

}

function formatarNumeroCategoria($numero){
    $qtdCaracter = strlen($numero);

    if($qtdCaracter === 6 ) {
    $numeroFormatado = substr($numero, 1, 1) . '.' .
    substr($numero, 2, 2) . '.' .
    substr($numero, 4, 2);

    } else {
        $numeroFormatado = substr($numero, 2, 1) . '.' .
        substr($numero, 3, 2) . '.' .
        substr($numero, 5, 2);
        }
    return $numeroFormatado;

}

function limparCodigoCategoria($codCateg)
{
    //dd('func:'.$codCateg);
    return str_replace('.', '', $codCateg);
}

function updateMessage($data)
{
    // Get the current date
    $todayDate = \Carbon\Carbon::now()->format('Y-m-d');

    // Parse the updated_at field and format it
    $updatedAt = \Carbon\Carbon::parse($data)->format('Y-m-d');

    // Check if the record was updated today
    if ($updatedAt === $todayDate) {
        return '<span style="color: red;">&nbsp;&nbsp;&nbsp;** Alterado</span>';
    }
    // Return an empty string if the record was not updated today
    return "";
}


