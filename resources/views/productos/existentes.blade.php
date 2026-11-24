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
            <h1>Registrar productos existentes</h1>
            <br>
            <p>Registra productos que ya se encuentren en el sistema para rellenar existencias</p>
        </div>
    </header>

    <div class="formulario-contenedor">
        <h2 class="tutulo-seccion">Rellena para registrar un producto existente</h2>
        <form action="{{ route('productos.agregarExistentes') }}" method="POST">
            @csrf
            <div id="campos-formulario-contenedor">
                <div class="detalles-contenedor">
                    <div class="detalles-producto-contenedor productoAutocompletado">
                        <div class="input-contenedor nombre">
                            <div class="input-icono-contenedor">
                                <div class="encabezado-input">
                                    <span class="material-symbols-outlined">deployed_code</span>
                                </div>
                                <label>Producto</label>
                            </div>
                            <input type="text" id="nombre" name="productos[0][producto]" required placeholder="Nombre del producto" autocomplete="off">
                        </div>
                        <div class="input-contenedor">
                            <label>Identificador</label>
                            <input type="number" id="id" name="productos[0][id]" required placeholder="ID Producto" autocomplete="off" readonly>
                        </div>
                        <div class="input-contenedor">
                            <label>Cantidad</label>
                            <input type="number" id="cantidad" name="productos[0][cantidad]" required placeholder="Cantidad a agregar" autocomplete="off">
                        </div>
                    </div>
                    <button type="button" class="boton boton-quitar" onclick="borrarProducto(this)">Borrar</button>
                    
                    <hr>
                </div>
            </div>
            
            <div class="botones-formulario-contenedor">
                <button type="submit" class="boton boton-enviar">Registrar</button>
                <button type="button" class="boton boton-agregar">
                    <span class="material-symbols-outlined">add_2</span>
                </button>
            </div>
        </form>
    </div>

    <div class="seccion-tabla-contenedor">
        <div class="encabezado-tabla-contenedor">
            <h2 class="tutulo-seccion">Productos registrados</h2>
            <form action="{{ route('productos.existentes') }}" class="campo-formulario formulario-buscar" autocomplete="off">
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
                        <td>{{ $producto->precio }}</td>
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





<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>




    <script>
        
        /*Aplicar autocompletado*/

function aplicarAutocompletado(row) {
    row.find('#nombre').autocomplete({
        source: function(request, response){
    $.ajax({
        url: "{{ route('productos.autocompletar') }}",
        dataType: "json",
        data: {
            term: request.term
        },
        success: function(data){
            response(data);
        }
    })},
        select: function(event, ui) {
            $(this).val(ui.item.value);
            row.find('#id').val(ui.item.id);
            return false;
        }
    });
}
        

$(document).ready(function() {
    aplicarAutocompletado($('.productoAutocompletado').first());

    // Evento para agregar una nueva fila
    let index = 1;
    $('.botones-formulario-contenedor').on('click', '.boton-agregar', function() {
        
        const nuevaFila = $(`
                <div class="detalles-contenedor">
                    <div class="detalles-producto-contenedor productoAutocompletado">
                        <div class="input-contenedor nombre">
                            <div class="input-icono-contenedor">
                                <div class="encabezado-input">
                                    <span class="material-symbols-outlined">deployed_code</span>
                                </div>
                                <label>Producto</label>
                            </div>
                            <input type="text" id="nombre" name="productos[${index}][producto]" required placeholder="Nombre del producto" autocomplete="off">
                            </div>
                            <div class="input-contenedor">
                                <label>Identificador</label>
                                <input type="number" id="id" name="productos[${index}][id]" required placeholder="ID Producto" autocomplete="off">
                                </div>
                                <div class="input-contenedor">
                                    <label>Cantidad</label>
                                    <input type="number" id="cantidad" name="productos[${index}][cantidad]" required placeholder="Cantidad a agregar" autocomplete="off">
                                    </div>
                                </div>
                                <button type="button" class="boton boton-quitar" onclick="quitarProducto(this)">Quitar</button>
                    <hr>
                </div>
            `);
        
        index++;
        $('#campos-formulario-contenedor').append(nuevaFila);
        aplicarAutocompletado(nuevaFila);
        
    });
});

// Quitar Campos
        function quitarProducto(boton) {
        const item = boton.closest('.detalles-contenedor');
        item.remove();
        }

// Borrar primer campo
        function borrarProducto(boton) {
        document.getElementById('nombre').value = '';
        document.getElementById('id').value = '';
        document.getElementById('cantidad').value = '';
        }

    </script>

@endsection