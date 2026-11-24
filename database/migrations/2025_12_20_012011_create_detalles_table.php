<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_pedido', function (Blueprint $table) {
             $table->id(); // PRIMARY KEY AUTO_INCREMENT

            // Claves foráneas
            $table->unsignedBigInteger('idPedido');
            $table->unsignedBigInteger('idProducto');

            // Otros campos
            $table->integer('cantidad_unitaria');
            $table->integer('precio_compra');
            $table->integer('total_linea');

            // Definir claves foráneas
            $table->foreign('idPedido')->references('id')->on('pedidos')->onDelete('cascade'); 
            $table->foreign('idProducto')->references('id')->on('productos')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles');
    }
};
