<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalles extends Model
{

    protected $table = 'detalles_pedido';

    protected $fillable = [
        'idPedido',
        'idProducto',
        'cantidad_unitaria',
        'precio_compra',
        'total_linea',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'idPedido');
    }

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'idProducto');
    }
}
