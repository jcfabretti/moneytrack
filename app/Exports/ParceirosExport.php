<?php

namespace App\Exports;

use App\Models\Parceiro; // Certifique-se de que este namespace está correto para o seu modelo
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParceirosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Retorna a coleção de dados que será exportada.
    * Você pode adicionar lógica de filtragem ou ordenação aqui, se necessário.
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Parceiro::all(); // Busca todos os parceiros do banco de dados
    }

    /**
     * Define os cabeçalhos das colunas no arquivo Excel.
     * A ordem deve corresponder à ordem dos dados retornados no método map().
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nome',
            'Natureza Jurídica',
            'Tipo de Cliente',
            'Código Fiscal',
            'Localidade',
            'Status',
            'Criado Em',
            'Atualizado Em',
        ];
    }

    /**
     * Mapeia cada item da coleção (um objeto Parceiro) para uma linha no Excel.
     * Permite formatar os dados antes da exportação.
     * @param mixed $parceiro O modelo Parceiro para a linha atual
     * @return array
     */
    public function map($parceiro): array
    {
        return [
            $parceiro->id,
            $parceiro->nome,
            $parceiro->nat_jur,
            $parceiro->tipo_cliente,
            $parceiro->cod_fiscal,
            $parceiro->localidade,
            // Converte o tinyint(1) 'status' para uma string mais legível
            $parceiro->status ? 'Ativo' : 'Inativo',
            // Formata as colunas de data/hora para um formato mais legível no Excel
            $parceiro->created_at ? $parceiro->created_at->format('d/m/Y H:i:s') : null,
            $parceiro->updated_at ? $parceiro->updated_at->format('d/m/Y H:i:s') : null,
        ];
    }
}