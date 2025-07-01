<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
    use HasFactory;
    protected $table = 'empresas';
    protected $fillable = ['id', 'nome', 'grupo_economico_id', 'cod_fiscal', 'localidade','tipos_planocontas_id'];

    public function grupoEconomico(): BelongsTo
    {
        return $this->belongsTo(GrupoEconomico::class,'grupo_economico_id','id');
    }

    public function tiposPlanoConta(): BelongsTo
    {
        return $this->belongsTo(CategoriaTipo::class,'tipos_planocontas_id','id');
    }    

}
