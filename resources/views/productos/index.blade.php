@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/productos/productos.css') }}">
@endpush

@php
    use Carbon\Carbon;
@endphp

@section('content')

<div class="contendor">
    <header>
        <div>
            <h1>Registro de productos</h1>
            <br>
            <p>Registra productos nuevos para tu negocio o agrega mas productos ya existentes en el sistema</p>
        </div>
        <div class="botonPrincipal-contenedor">
            <button class="botonPrincipal">
                <span class="material-symbols-outlined">add</span>
                <a href="{{ route('productos.nuevo') }}">Añadir producto nuevo</a>
            </button>
            <br><br>
            <button class="botonPrincipal">
                <span class="material-symbols-outlined">add_shopping_cart</span>
                <a href="{{ route('productos.existentes') }}">Agregar producto existente</a>
            </button>
        </div>
    </header>
    
    
    
    <div class="buscador-contenedor">
        <h2 class="tutulo-seccion">Busqueda de productos</h2>
        <form action="{{ route('productos.index') }}" class="campo-formulario formulario-buscar" autocomplete="off">
                <input type="search" name="buscar" class="input-buscar" placeholder="Buscar producto...">
                <div class="btn-buscar">
                     <button type="submit" ><span class="material-symbols-outlined">search</span></button>
                </div>
                <form class="campo-formulario formulario-verTodos" action="{{ route('producto.refrescar') }}">
                    <input type="submit" class="btn-submit" name="refrescar" value="Ver todos" id="input-verTodos">
                </form>
        </form>
    </div>
    
<div class="tabla">
    <div class="tutulo-seccion">
        <span class="material-symbols-outlined">inventory_2</span>
        <h4>Productos registrados</h4>
    </div>
    <div class="tabla-contenedor">
        <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Unidades</th>
                        <th>Descripcion</th>
                        <th>Fecha de ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td class="alineadoIzquierda nombre">{{ $producto->nombre }}</td>
                        <td class="dinero">C$ {{ number_format($producto->precio) }}</td>
                        @if ($producto->cantidad == 0)
                            <td class="cantidad-tabla" style="color: rgb(250, 76, 76)">{{$producto->cantidad}}</td>
                            
                        @else
                             <td class="cantidad-tabla">{{$producto->cantidad}}</td>
                        @endif
                        {{-- <td class="cantidad-tabla">{{ $producto->cantidad }}</td> --}}
                        <td class="alineadoIzquierda">{{ $producto->descripcion }}</td>
                        <td>{{ carbon::parse($producto->created_at)->format('d/m/y')}}</td>
                        <td class="accionesTabla">
                                <a href="{{ route('productos.editar', $producto->id) }}" type="submit" class="botonTabla boton-editar">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>

                                @if (Auth::User()->cargo == 'Administrador')
                                <form action="{{ route('productos.eliminar', $producto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar el producto {{ $producto->nombre }}? Se eliminaran de forma permanente');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="botonTabla boton-eliminar">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                                 @else
                                           
                                 @endif
                            
                        </td>
                    </tr>
                @endforeach
                </tbody>
        </table>
    </div>
</div>
@endsection