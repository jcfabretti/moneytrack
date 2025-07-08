<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposPlanoConta extends Model
{
    use HasFactory;
    protected $table='tipos_plano_contas';
    protected $fillable=[ 'id','nome'];
    
    public function hasManyTipoPlanoContas()
    {
        return $this->hasMany(Empresa::class,'tipos_planocontas_id','id');
    }

}
