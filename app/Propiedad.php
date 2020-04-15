<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    protected $table = 'propiedades';

    protected $fillable = [
        'title', 'content', 'category_id',
    ];

    // Relacion de muchos a unos (Muchas propiedades pueden estar relacionadas a una categoria)

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }

    // Relación de uno a muchos 
    
    public function propiedadesImagenes(){
        return $this->hasMany('App\PropiedadImagen');
    }

    // Scope

    public function scopeOperacion($query, $operacion){
        if($operacion)
        return $query->where('operacion', 'LIKE', "%$operacion%");
    }

    public function scopeTitulo($query, $titulo){
        if($titulo)
        return $query->where('titulo', 'LIKE', "%$titulo%");
    }

    public function scopeCiudad($query, $ciudad){
        if($ciudad)
        return $query->where('ciudad', 'LIKE', "%$ciudad%");
    }
    
    public function scopeCodigo($query, $codigo){
        if($codigo)
        return $query->where('codigo', 'LIKE', "%$codigo%");
    }

}
