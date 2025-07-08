<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Categoria extends Model
    {
        use HasFactory;
        protected $table='categorias';

        // Definir 'numero_categoria' como chave primária
        protected $primaryKey = 'numero_categoria'; // <<< ADICIONE ESTA LINHA
        public $incrementing = false; // Mantenha, pois não é auto-incrementada
        protected $keyType = 'string'; // <<< ADICIONE ESTA LINHA se numero_categoria for string

        protected $fillable = ['numero_categoria','nome', 'categoria_pai','nivel','fk_tipocategoria_id'];
        
        protected $casts = [
            'created_at' => 'datetime', // Corrigido de 'create_at' para 'created_at'
            'updated_at' => 'datetime', // Adicionado, se você usa timestamps
        ];

        public function children() {
            // Se a chave primária é 'numero_categoria', a relação deve usar isso
            return $this->hasMany('App\Models\Categoria','categoria_pai','numero_categoria') ; // <<< ATENÇÃO AQUI
        } 
        
        public function categoriaTipo(): BelongsTo
        {
            return $this->belongsTo(CategoriaTipo::class,'fk_tipocategoria_id','id');
        }
    }