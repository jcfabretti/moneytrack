<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChartDataController; // Certifique-se que esta linha está presente!

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Suas rotas para os gráficos devem estar aqui dentro do grupo 'api'
Route::get('/monthly-fluxocaixa-totals', [ChartDataController::class, 'getMonthlyFluxoCaixaTotals']);
Route::get('/monthly-launch-totals', [ChartDataController::class, 'getMonthlyLaunchTotals']);