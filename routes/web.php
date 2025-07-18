<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController; 

// Importar todos os Controllers personalizados que você usa
use App\Http\Controllers\HomeController;
use App\Http\Controllers\grupoEconomicoController;
use App\Http\Controllers\PlanoContaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaTiposController;
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\LancamentoController;
use App\Http\Controllers\FluxoCaixaController;
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

// Rota da homepage principal (pode ser a welcome ou sua customizada)
Route::get('/', [HomeController::class, 'index'])->name('index'); // Usando o Controller, e nomeando 'index'

// Rotas de autenticação do Breeze (MUITO IMPORTANTE manter esta linha)
require __DIR__.'/auth.php';

// Rota do Dashboard (pós-login)
// Aponta para sua view personalizada 'app.dashboard'
Route::get('/dashboard', function () {
    return view('app.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas que precisam de autenticação (middleware 'auth')
Route::middleware('auth')->group(function () {

    // Rotas de Perfil (do Breeze) - SÓ SE ProfileController EXISTIR
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do HomeController que precisam de autenticação
    Route::post('/home/saveQtyPerPage', [HomeController::class, 'saveQtyPerPage'])->name('saveQtyPerPage');
    Route::get('notifications/get', [HomeController::class, 'getNotificationsData'])->name('notifications.get');

    //#### GRUPO ECONOMICO #############################################################################################################
    Route::get('/grupoeconomico/index',[grupoEconomicoController::class, 'index'])->name('index.grupoeconomico');
    Route::post('/grupoeconomico/store', [grupoEconomicoController::class, 'store'])->name('grupoeconomico.store');
    Route::post('/grupoEconomico/edit',[grupoEconomicoController::class, 'edit'])->name('grupoeconomico.edit');
    Route::post('/grupoeconomico/destroy', [grupoEconomicoController::class, 'destroy'])->name('grupoeconomico.destroy');

    //#### CATEGORIAS #############################################################################################################
    Route::get('/categoria/index/{tipo_categ}', [CategoriaController::class, 'index'])->name('categoria.index');
    Route::get('/categoria/treeview-ajax/{idTipoCategParam}', [CategoriaController::class, 'indexTreeView'])->name('categorias.treeview.ajax');
    Route::post('/categoria/store', [CategoriaController::class, 'store'])->name('categoria.store');
    Route::get('/categoria/edit/{id}', [CategoriaController::class, 'edit'])->name('edit.categoria');
    Route::post('/categoria/update', [CategoriaController::class, 'update'])->name('categoria.update');
    Route::delete('/categoria/destroy/{id}', [CategoriaController::class, 'destroy'])->name('categoria.destroy');
    Route::get('/categoria/getNomeCategoria/{id}',[CategoriaController::class, 'getNomeCategoria'])->name('getNomeCategoria');
    Route::get('/categoria/getcategorias/{id1}/{id2}', [CategoriaController::class, 'getCategorias'])->name('getcategorias');

    // -------------- Tipos Categoria ---------------------//
    Route::get('/categoria/tipos/index', [CategoriaTiposController::class, 'index'])->name('categoria.tipos.index');
    Route::post('/categoria/tipos/store', [CategoriaTiposController::class, 'store'])->name('categoria.tipos.store');
    Route::post('/categoria/tipos/update', [CategoriaTiposController::class, 'update'])->name('categoria.tipos.update');
    Route::post('/categoria/tipos/destroy/{id}', [CategoriaTiposController::class, 'destroy'])->name('categoria.tipos.destroy');
    Route::get('/categoria/consulta', [CategoriaController::class, 'consultaCategoria'])->name('categoria.consulta');

    //#### PARCEIROS #################################################################################################
    Route::get('/home/showParceiro', [ParceiroController::class, 'index'])->name('parceiro.index');
    Route::get('/home/listaParceiros', [ParceiroController::class, 'listaParceiros'])->name('listaParceiros');
    Route::get('/home/searchParceiro', [HomeController::class, 'searchParceiro'])->name('parceiro.search');
    Route::get('/parceiros/create', [ParceiroController::class, 'store']); // Provavelmente deveria ser 'create' e não 'store'
    Route::post('/parceiros', [ParceiroController::class, 'store'])->name('parceiroCreate');
    Route::get('/parceiro/edit/{parceiro}', [ParceiroController::class, 'editParceiro'])->name('parceiro.edit');
    Route::POST('/parceiro/update/{parceiro}', [ParceiroController::class, 'update'])->name('parceiro.update');
    Route::delete('/parceiro/destroy/{id}', [ParceiroController::class, 'destroy'])->name('parceiro.destroy');
    Route::GET('/parceiro/getparceiro/{id}', [HomeController::class,'getParceiro']);
    Route::get('/parceiro/fetchparceiro/{id}',[HomeController::class, 'fetchParceiro']);
    Route::get('/parceiros/search', [ParceiroController::class, 'sarchParceiro']);
    Route::get('/parceiros/export/excel', [ParceiroController::class, 'exportExcel'])->name('parceiros.export.excel');
    Route::get('/parceiros/export/csv', [ParceiroController::class, 'exportCsv'])->name('parceiros.export.csv');


    //#### EMPRESAS #############################################################################################################//
    Route::get('/home/modalSelecioneEmpresa', [EmpresaController::class, 'storeEmpresaSelecionada'])->name('selecioneEmpresa1');
    Route::get('/empresa/empresaselecionada/{id}', [EmpresaController::class, 'storeEmpresaSelecionada1'])->name('empresa.Selecionada1');
    Route::get('/home/empresa/listaempresamodal', [EmpresaController::class, 'listaEmpresaModal'])->name('empresa.listaModal');
    Route::get('/home/empresa/index', [EmpresaController::class, 'index'])->name('empresa.index');
    Route::post('/empresa/store', [EmpresaController::class, 'store'])->name('storeEmpresa');
    Route::put('/empresas/update/{empresa}', [EmpresaController::class, 'updateEmpresa'])->name('empresa.update');
    Route::delete('/empresas/destroy/{id}', [EmpresaController::class, 'destroyEmpresa'])->name('empresa.destroy');
    // Route::get('/home/empresa/edit/{id}', [HomeController::class, 'editEmpresa']); // Comentado, pois há um put abaixo
    Route::put('/empresa/{id}', [EmpresaController::class, 'editEmpresa']); // Cuidado: PUT em GET URL

    //#### LANCAMENTOS #############################################################################################################
    Route::get('/home/showlancamento', [LancamentoController::class, 'index'])->name('showlancamento');
    Route::get('/home/lancamento/listLcto', [LancamentoController::class, 'listindex'])->name('listLcto');
    Route::put('lancamento/update', [LancamentoController::class, 'update'])->name('lancamento.update');
    Route::get('/home/lancamento/create', [LancamentoController::class, 'create'])->name('lancamento.create');
    Route::post('/lancamento/store', [LancamentoController::class, 'store'])->name('lancamento.store');
    Route::get('/api/lancamentos/{id}', [LancamentoController::class, 'show']);
    Route::put('/lancamentos/{id}', [LancamentoController::class, 'update']);
    Route::delete('/lancamento/destroy', [LancamentoController::class, 'destroy'])->name('lancamento.destroy');
    Route::get('/lancamentos/import', [LancamentoController::class, 'createImportForm'])->name('lancamento.import.form');
    Route::post('/lancamentos/import', [LancamentoController::class, 'processImport'])->name('lancamento.import.process');
    Route::post('/category/store', [LancamentoController::class, 'storeAjax']);

    //#### FLUXO DE CAIXA #############################################################################################################
    Route::get('/fluxo-caixa', [FluxoCaixaController::class, 'index'])->name('fluxocaixa.index');
    Route::post('/fluxo-caixa/gerar', [ReportController::class, 'gerar_fluxo_caixa'])->name('fluxocaixa.gerar');


    //#### RELATORIOS #############################################################################################################
    Route::post('/relatorios/gerar', [ReportController::class, 'generateReport'])->name('relatorios.gerar');
    Route::get('/createrelatorios/{reportName}', [ReportController::class, 'createReport'])->name('create.relatorio');
    Route::get('/relatorios/index', [ReportController::class, 'index'])->name('index.report');

}); // Fim do grupo de middleware 'auth'