<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    // Relaciona todos los post de la categoria 
    // Relación de uno a muchos 
    
    public function propiedades(){
        return $this->hasMany('App\Propiedad');
    }
}
