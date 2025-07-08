<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Importar todos os Controllers personalizados que você usa
use App\Http\Controllers\HomeController;
use App\Http\Controllers\grupoEconomicoController;
use App\Http\Controllers\PlanoContaController; // Note: Esta estava faltando um 'use'
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaTiposController;
use App\Http\Controllers\CategoriaTipoController; // Note: Esta estava faltando um 'use'
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\LancamentoController;
use App\Http\Controllers\FluxoCaixaController; // Note: Esta estava faltando um 'use'
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rotas públicas (não exigem autenticação)
Route::get('/', function () {
    return view('welcome');
});


// Rotas de autenticação do Breeze (MUITO IMPORTANTE manter esta linha)
require __DIR__.'/auth.php';


// GRUPO DE ROTAS QUE EXIGEM AUTENTICAÇÃO
// A maioria das suas rotas deve estar aqui dentro, pois são funcionalidades do sistema
Route::middleware('auth')->group(function () {

    // Rotas padrão do Breeze para perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rota padrão do Breeze para dashboard (manter se o dashboard é a página inicial pós-login)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard'); // Removi o 'verified' temporariamente se estiver causando problemas, mas pode recolocá-lo.


Route::get('/',                  [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/auth/index',        [App\Http\Controllers\HomeController::class, 'indexuser'])->name('auth.indexusers');


//Route::get('/home/en',           [App\Http\Controllers\HomeController::class, 'languageEn'])->name('home');
Route::get('/home',              [App\Http\Controllers\HomeController::class, 'index'])->name('index');

Route::get('/home/registeruser', [App\Http\Controllers\HomeController::class, 'registerUser'])->name('registerUser');

Route::post('/home/saveQtyPerPage', [App\Http\Controllers\HomeController::class, 'saveQtyPerPage'])->name('saveQtyPerPage');

//#### GRUPO ECONOMICO #############################################################################################################
Route::get('/grupoeconomico/index',[App\Http\Controllers\grupoEconomicoController::class, 'index'])->name('index.grupoeconomico');
Route::post('/grupoeconomico/store',  [App\Http\Controllers\grupoEconomicoController::class, 'store'])->name('grupoeconomico.store');
Route::post('/grupoEconomico/edit',[App\Http\Controllers\grupoEconomicoController::class, 'edit'])->name('grupoeconomico.edit');
Route::post('/grupoeconomico/destroy',  [App\Http\Controllers\grupoEconomicoController::class, 'destroy'])->name('grupoeconomico.destroy');

//#### PLANO CONTAS #############################################################################################################
Route::get('/home/planoContas',             [App\Http\Controllers\HomeController::class, 'planoContas'])->name('planoContas');

Route::get('/home/loadIframeTreeview',             [PlanoContaController::class, 'manageIframeCategory'])->name('manageIframeCategory');
Route::post('/home/add-category',['as'=>'add.category','uses'=>'PlanoContaController@addCategory']);
Route::post('/home/add-category', [PlanoContaController::class, 'addCategory'])->name('addCategory');

//#### CATEGORIAS #############################################################################################################
Route::get('/categoria/index/{tipo_categ}',        [App\Http\Controllers\CategoriaController::class, 'index'])->name('categoria.index');
// Rota para a requisição AJAX (que retorna apenas o HTML da Tree View)
Route::get('/categoria/treeview-ajax/{idTipoCategParam}', [CategoriaController::class, 'indexTreeView'])->name('categorias.treeview.ajax');

Route::post('/categoria/store', [App\Http\Controllers\CategoriaController::class, 'store'])->name('categoria.store');
Route::get('/categoria/edit/{id}',        [App\Http\Controllers\CategoriaController::class, 'edit'])->name('edit.categoria');
Route::pOST('/categoria/update',      [App\Http\Controllers\CategoriaController::class, 'update'])->name('categoria.update');
Route::delete('/categoria/destroy/{id}',  [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('categoria.destroy');

Route::get('/categoria/index/getNomeCategoria/{id}',[CategoriaController::class, 'getNomeCategoria']);
Route::get('/categoria/getcategorias/{id1}/{id2}', [CategoriaController::class, 'getCategorias'])->name('getcategorias');
Route::get('/categoria/buscaNomeCategoria/{id}',[CategoriaController::class, 'buscaNomeCategoria']);

// --------------  Tipos Categoria ---------------------//
Route::get('/categoria/tipos/index',        [App\Http\Controllers\CategoriaTiposController::class, 'index'])->name('categoria.tipos.index');
Route::post('/categoria/tipos/store',        [App\Http\Controllers\CategoriaTiposController::class, 'store'])->name('categoria.tipos.store');
Route::post('/categoria/tipos/update',        [App\Http\Controllers\CategoriaTiposController::class, 'update'])->name('categoria.tipos.update');
Route::post('/categoria/tipos/destroy/{id}',  [App\Http\Controllers\CategoriaTiposController::class, 'destroy'])->name('categoria.tipos.destroy');
Route::get('/categoria/consulta',        [App\Http\Controllers\CategoriaController::class, 'consultaCategoria'])->name('categoria.consulta');
//#### TIPOS TABELAS DE CATEGORIAS ###############################################################################
Route::get('/tipos-plano-contas', [CategoriaTipoController::class, 'getTiposPlanoContas'])->name('tipos.plano.contas');

//#### PARCEIROS #################################################################################################
Route::get('/home/showParceiro', [App\Http\Controllers\ParceiroController::class, 'index'])->name('parceiro.index');
Route::get('/home/listaParceiros', [App\Http\Controllers\ParceiroController::class, 'listaParceiros'])->name('listaParceiros');

Route::get('/home/searchParceiro', [App\Http\Controllers\HomeController::class, 'searchParceiro'])->name('parceiro.search');
Route::get('/parceiros/create', [App\Http\Controllers\ParceiroController::class, 'store']);

Route::post('/parceiros', [App\Http\Controllers\ParceiroController::class, 'store'])->name('parceiroCreate');

Route::get('/parceiro/edit/{parceiro}', [App\Http\Controllers\ParceiroController::class, 'editParceiro'])->name('parceiro.edit');

Route::POST('/parceiro/update/{parceiro}', [App\Http\Controllers\ParceiroController::class, 'update'])->name('parceiro.update');

Route::delete('/parceiro/destroy/{id}', [App\Http\Controllers\ParceiroController::class, 'destroy'])->name('parceiro.destroy');
Route::GET('/parceiro/getparceiro/{id}', [App\Http\Controllers\HomeController::class,'getParceiro']);

Route::get('/parceiro/fetchparceiro/{id}',[HomeController::class, 'fetchParceiro']);

Route::get('/parceiros/search', [App\Http\Controllers\ParceiroController::class, 'sarchParceiro']);


// Rota para exportar os parceiros para Excel
Route::get('/parceiros/export/excel', [App\Http\Controllers\ParceiroController::class, 'exportExcel'])->name('parceiros.export.excel');
// Rota para exportar os parceiros para CSV
Route::get('/parceiros/export/csv', [App\Http\Controllers\ParceiroController::class, 'exportCsv'])->name('parceiros.export.csv');


//#### EMPRESAS #############################################################################################################//

Route::get('/home/modalSelecioneEmpresa', [App\Http\Controllers\EmpresaController::class, 'storeEmpresaSelecionada'])->name('selecioneEmpresa1');
Route::get('/empresa/empresaselecionada/{id}', [App\Http\Controllers\EmpresaController::class, 'storeEmpresaSelecionada1'])->name('empresa.Selecionada1');

Route::get('/home/empresa/listaempresamodal', [App\Http\Controllers\EmpresaController::class, 'listaEmpresaModal'])->name('empresa.listaModal');

Route::get('/home/empresa/index', [App\Http\Controllers\EmpresaController::class, 'index'])->name('empresa.index');

Route::post('/empresa/store', [App\Http\Controllers\EmpresaController::class, 'store'])->name('storeEmpresa');

Route::put('/empresas/update/{empresa}', [App\Http\Controllers\EmpresaController::class, 'updateEmpresa'])->name('empresa.update');

Route::delete('/empresas/destroy/{id}', [App\Http\Controllers\EmpresaController::class, 'destroyEmpresa'])->name('empresa.destroy');

//Route::get('/home/empresa/edit/{id}', [App\Http\Controllers\HomeController::class, 'editEmpresa']);

Route::put('/empresa/{id}', [App\Http\Controllers\EmpresaController::class, 'editEmpresa']);

//#### LANCAMENTOS #############################################################################################################

Route::get('/home/showlancamento', [App\Http\Controllers\LancamentoController::class, 'index'])->middleware(['auth', 'verified'])->name('showlancamento');

Route::get('/home/lancamento/listLcto', [App\Http\Controllers\LancamentoController::class, 'listindex'])->name('listLcto');

Route::put('lancamento/update', [App\Http\Controllers\LancamentoController::class, 'update'])->middleware(['auth', 'verified'])->name('lancamento.update');
Route::get('/home/lancamento/create', [App\Http\Controllers\LancamentoController::class, 'create'])->middleware(['auth', 'verified'])->name('lancamento.create');
Route::post('/lancamento/store', [App\Http\Controllers\LancamentoController::class, 'store'])->name('lancamento.store');
Route::get('/api/lancamentos/{id}', [App\Http\Controllers\LancamentoController::class, 'show']);
Route::put('/lancamentos/{id}', [App\Http\Controllers\LancamentoController::class, 'update']);
Route::delete('/lancamento/destroy', [App\Http\Controllers\LancamentoController::class, 'destroy'])->name('lancamento.destroy');

Route::get('/lancamentos/import', [LancamentoController::class, 'createImportForm'])->name('lancamento.import.form');
Route::post('/lancamentos/import', [LancamentoController::class, 'processImport'])->name('lancamento.import.process');

Route::get('/fluxo-caixa', [FluxoCaixaController::class, 'index'])->name('fluxocaixa.index');
Route::post('/fluxo-caixa/gerar', [ReportController::class, 'gerar_fluxo_caixa'])->name('fluxocaixa.gerar');


Route::post('/category/store', [LancamentoController::class, 'storeAjax']);

//#### RELATORIOS #############################################################################################################

Route::get('/createrelatorios/{reportName}', [ReportController::class, 'createReport'])->middleware(['auth', 'verified'])->name('create.relatorio');
Route::get('/relatorios/index', [ReportController::class, 'index'])->middleware(['auth', 'verified'])->name('index.report');

}); // Fim do grupo de middleware 'auth'
