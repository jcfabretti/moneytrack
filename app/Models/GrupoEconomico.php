<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrupoEconomico extends Model
{
    use HasFactory;

    protected $table='grupo_economicos';
    protected $fillable=[ 'id','nome','localidade'];

        public function hasManyEmpresa()
        {
            return $this->hasMany(Empresa::class,'grupo_economico_id','id');
        }

}
