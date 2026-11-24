@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/productos/productos.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/productos/nuevo.css') }}">
@endpush

@php
    use Carbon\Carbon;
@endphp


@section('content')

<div class="contendor">
    <header>
        <div>
            <h1>Registrar productos nuevos</h1>
            <br>
            <p>Registra productos nuevos para tu negocio añadiendo todos sus detalles</p>
        </div>
    </header>

    <div class="formulario-contenedor">
        <h2 class="tutulo-seccion">Informacion del producto</h2>
        <form action="{{ route('productos.crearNuevo') }}" method="POST">
            @csrf
            <div id="campos-formulario-contenedor">
                <div class="detalles-contenedor">
                    <div class="detalles-producto-contenedor">
                        <div class="input-contenedor nombre">
                            <div class="input-icono-contenedor">
                                <div class="encabezado-input">
                                    <span class="material-symbols-outlined">deployed_code</span>
                                </div>
                                <label>Nombre</label>
                            </div>
                            <input type="text" id="nombre" name="productos[0][producto]" required placeholder="Nombre del producto" autocomplete="off">
                        </div>
                        <div class="input-contenedor">
                            <label>Cantidad</label>
                            <input type="number" id="cantidad" name="productos[0][cantidad]" required placeholder="Unidades" autocomplete="off">
                        </div>
                        <div class="input-contenedor">
                            <label>Precio unitario</label>
                            <input type="number" id="precio" name="productos[0][precio]" required placeholder="Precio unitario" autocomplete="off">
                        </div>
                    </div>

                    <div class="descripcion-producto-contenedor">
                        <div class="input-contenedor">
                            <label>Descripcion</label>
                            <input type="text" id="descripcion" name="productos[0][desc]" required placeholder="Descripción del producto" autocomplete="off">
                        </div>
                    </div>
                    <hr>
                </div>
            </div>

            <div class="botones-formulario-contenedor">
                <button type="submit" class="boton boton-enviar">Registrar</button>
                <button type="button" class="boton boton-añadir" onclick="AgregarProducto()">
                    <span class="material-symbols-outlined">add_2</span>
                </button>
            </div>
        </form>
    </div>

    <div class="seccion-tabla-contenedor">
        <div class="encabezado-tabla-contenedor">
            <h2 class="tutulo-seccion">Productos registrados</h2>
            <form action="{{ route('productos.nuevo') }}" class="campo-formulario formulario-buscar" autocomplete="off">
                    <input type="search" name="buscar" class="input-buscar" placeholder="Buscar producto...">
                    <div class="btn-buscar">
                         <button type="submit" ><span class="material-symbols-outlined">search</span></button>
                    </div>
               </form>
               <form class="campo-formulario formulario-verTodos" action="{{ route('producto.refrescar') }}">
                    <input type="submit" name="refrescar" value="Ver todos" id="input-verTodos">
            </form>
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
                        <td class="alineadoIzquierda">{{ $producto->nombre }}</td>
                        <td class="dinero">C$ {{ number_format($producto->precio) }}</td>
                        <td>{{ $producto->cantidad }}</td>
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



<script>
        let index = 1; 
        function AgregarProducto() {
            const contenedor = document.getElementById('campos-formulario-contenedor');
            let item = document.createElement('div');
            item.classList.add('detalles-contenedor');
            item.innerHTML += `
                <div class="detalles-producto-contenedor">
                    <div class="input-contenedor nombre">
                        <div class="input-icono-contenedor">
                                <div class="encabezado-input">
                                    <span class="material-symbols-outlined">deployed_code</span>
                                </div>
                                <label>Nombre</label>
                            </div>
                        <input type="text" id="nombre" name="productos[${index}][producto]" required placeholder="Nombre del producto">
                    </div>
                    <div class="input-contenedor">
                        <label>Cantidad</label>
                        <input type="number" id="cantidad" name="productos[${index}][cantidad]" required placeholder="Unidades">
                    </div>
                    <div class="input-contenedor">
                        <label>Precio unitario</label>
                        <input type="number" id="precio" name="productos[${index}][precio]" required placeholder="Precio unitario">
                    </div>
                </div>

                <div class="descripcion-producto-contenedor">
                    <div class="input-contenedor">
                        <label>Descripcion</label>
                        <input type="text" id="descripcion" name="productos[${index}][desc]" required placeholder="Descripción del producto">
                    </div>
                    <button type="button" class="boton boton-quitar" onclick="quitarProducto(this)">Quitar</button>
                </div>
                <hr>
                `;
            contenedor.appendChild(item);
            index++;
        }

        function quitarProducto(boton) {
        const item = boton.closest('.detalles-contenedor');
        item.remove();
    }
    </script>

@endsection