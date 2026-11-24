<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Productos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'cantidad',
    ];

    protected function nombre(): Attribute
    {
        return Attribute::make(
            set: function($value){
                return ucfirst($value);
            },
            get: function($value){
                return ucfirst($value);
            }
        );
    }
}


