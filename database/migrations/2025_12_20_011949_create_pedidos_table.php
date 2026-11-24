<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY AUTO_INCREMENT
            $table->unsignedBigInteger('idUsuario'); // clave foránea

            $table->timestamps(); // created_at y updated_at
            $table->integer('total_pedido');
            $table->string('cajero');
            $table->string('cliente');
            $table->integer('subtotal_pedido');
            $table->string('tipo_descuento', 50);
            $table->integer('valor_descuento');

            // Definir clave foránea
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
