<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;
    protected $table='categories';
    public $fillable = ['title','parent_id','level'];

    public function childs() {
        return $this->hasMany('App\Models\Category','parent_id','id') ;

    }    
}
