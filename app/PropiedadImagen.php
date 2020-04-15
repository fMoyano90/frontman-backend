<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropiedadImagen extends Model
{
    protected $table = 'propiedades_imagenes';

    protected $fillable = [
        'file_name', 'propiedad_id',
    ];

    // Relacion de muchos a unos (Muchas imagenes pueden estar relacionadas a una propiedad)

    public function propiedad(){
        return $this->belongsTo('App\Propiedad', 'propiedad_id');
    }

}