<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaTipo extends Model
{
    use HasFactory;

    protected $table='colecao_categorias';
    protected $fillable=[ 'id','nome'];

    public function hasManyEmpresa()
    {
        return $this->hasMany(Empresa::class,'tipos_planocontas_id','id');
    }

    public function hasManyCategoria()
    {
        return $this->hasMany(Categoria::class,'fk_tipocategoria_id','id');
    }
}
