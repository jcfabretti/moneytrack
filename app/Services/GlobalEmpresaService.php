<?php

namespace App\Services; // Exemplo de namespace, ajuste conforme sua pasta de services

use Illuminate\Session\SessionManager;
use App\Models\Empresa; // Importe o model Empresa

class GlobalEmpresaService
{
    /**
     * @var SessionManager
     */
    protected $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Define os dados da empresa selecionada na sessão.
     *
     * @param int $empresaId
     * @param string $empresaNome
     * @param int $grupoEconomicoId
     * @param int $empresaCodPlanoCategoria
     * @return void
     */
    public function setEmpresa(int $empresaId, string $empresaNome, int $grupoEconomicoId, int $empresaCodPlanoCategoria)
    {
        $this->session->put('app.empresa_id', $empresaId);
        $this->session->put('app.empresa_nome', $empresaNome);
        $this->session->put('app.grupo_economico_id', $grupoEconomicoId);
        $this->session->put('app.empresa_cod_plano_categoria', $empresaCodPlanoCategoria);
    }

    /**
     * Retorna o ID da empresa da sessão.
     *
     * @return int|null
     */
    public function getEmpresaId(): ?int
    {
        // CORREÇÃO AQUI: Usar a chave completa 'app.empresa_id'
        return $this->session->get('app.empresa_id');
    }

    /**
     * Retorna o nome da empresa da sessão.
     *
     * @return string|null
     */
    public function getEmpresaNome(): ?string
    {
        // CORREÇÃO AQUI: Usar a chave completa 'app.empresa_nome'
        return $this->session->get('app.empresa_nome');
    }

    /**
     * Retorna o ID do grupo econômico da sessão.
     *
     * @return int|null
     */
    public function getGrupoEconomicoId(): ?int
    {
        // CORREÇÃO AQUI: Usar a chave completa 'app.grupo_economico_id'
        return $this->session->get('app.grupo_economico_id');
    }

    /**
     * Retorna o código do plano de categoria da empresa da sessão.
     *
     * @return int|null
     */
    public function getEmpresaCodPlanoCategoria(): ?int
    {
        // CORREÇÃO AQUI: Usar a chave completa 'app.empresa_cod_plano_categoria'
        return $this->session->get('app.empresa_cod_plano_categoria');
    }

    /**
     * Limpa todos os dados da empresa da sessão.
     *
     * @return void
     */
    public function limparEmpresa(): void
    {
        $this->session->forget('app.empresa_id');
        $this->session->forget('app.empresa_nome');
        $this->session->forget('app.grupo_economico_id');
        $this->session->forget('app.empresa_cod_plano_categoria');
    }

    /**
     * Retorna a primeira empresa do banco de dados.
     *
     * @return Empresa|null
     */
    public function getPrimeiraEmpresa(): ?Empresa
    {
        return Empresa::first();
    }
}