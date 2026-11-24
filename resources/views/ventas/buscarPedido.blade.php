@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/ventas/ventas.css') }}">
@endpush

@php
    use Carbon\Carbon;
@endphp

@section('content')

<div class="contendor">
    <header>
        <h1>Registro de ventas</h1>
    </header>
    <div class="botonPrincipal-contenedor">
        <button class="botonPrincipal">
            <a href="{{ route('ventas.registrarVenta') }}">Añadir una venta</a>
        </button>
    </div>

    <div class="buscador-contenedor">
        <h2>Buscar pedido</h2>

        <form method="GET" action="{{ route('ventas.buscar') }}" class="campo-formulario formulario-buscar">
            <input class="input-buscar" type="text" name="id" placeholder="ID del pedido" value="{{ request('id') }}">
    
            <input class="input-buscar" type="text" name="vendedor" placeholder="Nombre del vendedor" value="{{ request('vendedor') }}">
    
            <input class="input-buscar" id="flatpickr-input" type="text" name="fecha" value="{{ request('fecha') }}" placeholder="Fecha del pedido 📅">
    
            <button type="submit" class="btn-submit">Buscar</button>
        </form>

        <form class="campo-formulario formulario-verTodos" action="{{ route('ventas.index') }}">
            <button type="submit" class="btn-submit">Ver todos</button>
        </form>
    </div>


    <h2 class="tutulo-seccion">Resultado</h2>

    <div class="tabla-contenedor">
        <table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Vendedor</th>
            <th>Fecha</th>
            <th>Detalles</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pedidos as $pedido)
            <tr>
                <td>{{ $pedido->id }}</td>
                <td>{{ $pedido->user->name }} {{ $pedido->user->second_name }}</td>
                <td>{{ $pedido->created_at->format('d/m/Y') }}</td>
                <td>
                    <a class="detallesBoton" href="{{ route('ventas.detalles', $pedido->id) }}">Ver detalles</a>
                </td>
            </tr>
        @empty
            <td colspan="4">
                No se encontraron pedidos que coincidan con la búsqueda
            </td>
        @endforelse
    </tbody>
</table>
</div>
    
</div>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
    flatpickr("#flatpickr-input", {
        locale: "es",  
        
        locale: {
            firstDayOfWeek: 1,
            dateFormat: "Y-m-d"
        },
    });
    </script>

@endsection