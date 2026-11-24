<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

    protected $fillable = [
        'idUsuario',
        'cliente',
        'cajero',
        'total_pedido',
        'subtotal_pedido',
        'tipo_descuento',
        'valor_descuento',
    ];

    public function detalles()
    {
        return $this->hasMany(Detalles::class, 'idPedido');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'idUsuario'); 
}
    
}
