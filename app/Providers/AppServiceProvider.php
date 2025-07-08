<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Schema;
use App\Models\Empresa;
use App\Services\GlobalEmpresaService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GlobalEmpresaService::class, function ($app) { // <--- Use o nome CORRETO aqui
                    return new GlobalEmpresaService( // <--- E aqui
                        $app->make(SessionManager::class) // <--- Use a classe importada SessionManager
                    );
                });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env(key: 'APP_ENV')!=='local') {
           URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();
        
        $today=date('Y-m-d');
        Session::put('app.dataLcto', $today );        // set data lancamento default

        Session::put('app.qtyItemsPerPage','10');   // set quantidade default de itens por pagina a listar = 10

        if (!Schema::hasTable('empresas')) {
            // Se a tabela não existe (provavelmente durante a primeira migração),
            // defina valores padrão ou vazios para evitar erros nas views.
            view()->share('EMPRESAS', collect());
            view()->share('EMPRESA_ID', null);
            view()->share('EMPRESA_NOME', null);

            return;
        }

        // 2. Carregue os dados da empresa (agora seguro para fazer, pois a tabela existe)
  $empresas = Empresa::all();

view()->share('EMPRESAS', $empresas); 

if ($empresas->isNotEmpty()) {
    view()->share('EMPRESA_ID', $empresas->first()->id);
    view()->share('EMPRESA_NOME', $empresas->first()->nome);

    $empresaSel = $empresas->first();

    Session::put('app.empresaId', $empresaSel->id);
    Session::put('app.empresaNome', $empresaSel->nome);
    Session::put('app.grupoEmpresarial', $empresaSel->grupo_economico_id);
    Session::put('app.empresaCodPlanoConta', $empresaSel->tipos_planocontas_id);
} else {
    view()->share('EMPRESA_ID', null);
    view()->share('EMPRESA_NOME', null);

    Session::put('app.empresaId', null);
    Session::put('app.empresaNome', null);
    Session::put('app.grupoEmpresarial', null);
    Session::put('app.empresaCodPlanoConta', null);
}

    }
}
