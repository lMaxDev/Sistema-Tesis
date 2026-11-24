@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/productos/nuevo.css') }}">
<link rel="stylesheet" href="{{ asset('css/productos/editar.css') }}">
@endpush


@section('content')

<div class="contendor">
    <header>
        <h1>Editar Producto</h1>
    </header>

    <h2 class="tutulo-seccion">Rellena para Editar el producto "{{$producto->nombre}}"</h2>
    <div class="formulario-contenedor">
        <form action="{{ route('productos.actualizar', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div id="campos-formulario-contenedor">
                <div class="detalles-contenedor">
                    <div class="detalles-producto-contenedor">
                        <div class="input-contenedor nombre">
                            <span>Nombre</span>
                            <input type="text" id="nombre" name="nombre" required placeholder="Nombre del producto" autocomplete="off" value="{{ $producto->nombre }}">
                        </div>
                        <div class="input-contenedor">
                            <span>Cantidad</span>
                            <input type="number" id="cantidad" name="cantidad" required placeholder="Unidades" autocomplete="off" value="{{ $producto->cantidad }}">
                        </div>
                        <div class="input-contenedor">
                            <span>Precio</span>
                            <input type="number" id="precio" name="precio" required placeholder="Precio unitario" autocomplete="off" value="{{ $producto->precio }}">
                        </div>
                    </div>

                    <div class="descripcion-producto-contenedor">
                        <div class="input-contenedor">
                            <span>Descripción</span>
                            <input type="text" id="descripcion" name="descripcion" required placeholder="Descripción del producto" autocomplete="off" value="{{ $producto->descripcion }}">
                        </div>
                    </div>
                    <hr>
                </div>
            </div>

            <div class="botones-formulario-contenedor">
                <button type="submit" class="boton boton-enviar">Actualizar</button>
                <button type="button" class="boton boton-quitar">
                    <a href="{{ route('productos.index') }}">Regresar</a>
                </button>
            </div>
        </form>
    </div>
@endsection