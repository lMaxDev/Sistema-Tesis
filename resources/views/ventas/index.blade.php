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
        <div>
            <h1>Registro de ventas</h1> 
            <br>
            <p>Añade ventas, busca y gestiona todos los pedidos de tu negocio.</p>
        </div>
        <div class="botonPrincipal-contenedor">
            <button class="botonPrincipal">
                <span class="material-symbols-outlined">add</span>
                <a href="{{ route('ventas.registrarVenta') }}">Añadir una venta</a>
            </button>
        </div>
    </header>

    <div class="buscador-contenedor">
        <div class="tutulo-seccion">
            <span class="material-symbols-outlined">search</span>
            <h4>Buscar pedido</h4>
        </div>

        <form method="GET" action="{{ route('ventas.buscar') }}" class="campo-formulario formulario-buscar">
            <input class="input-buscar" type="text" name="id" placeholder="ID del pedido" value="{{ request('id') }}">
    
            <input class="input-buscar" type="text" name="vendedor" placeholder="Nombre del vendedor" value="{{ request('vendedor') }}">
    
            <input class="input-buscar" id="flatpickr-input" type="text" name="fecha" value="{{ request('fecha') }}" placeholder="Fecha del pedido">
    
            <button type="submit" class="btn-submit">Buscar</button>
        </form>
    </div>

    <div class="tabla">
        <div class="tabla-contenedor">
        <div class="tutulo-seccion">
            <span class="material-symbols-outlined">list_alt</span>
            <h4>Ventas recientes</h4>
        </div>
        <table class ="table">
        <thead>
            <tr>
                <th>Pedido ID</th>
                <th>Cliente</th>
                <th>Total</th> 
                <th>Fecha</th>
                <th>Vendedor</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
                @foreach ($ventas as $venta)
                @php
                    $ventaFormat = $venta->created_at;
                    $ventaFormat = Carbon::parse($ventaFormat)->format('d/m/y');
                    $horaFormat = Carbon::parse($venta->created_at)->format('H:i');
                @endphp
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ $venta->cliente }}</td>
                    <td class="dinero">C$ {{ number_format($venta->total_pedido) }}</td>
                    <td>
                        {{ $ventaFormat }} <span class="horaVenta">{{ $horaFormat }}</span>
                    </td>
                    <td>{{ $venta->name }} {{ $venta->second_name }}</td>
                    <td>
                        <a class="detallesBoton" href="{{ route('ventas.detalles', $venta->id) }}">Ver detalles <span class="material-symbols-outlined">keyboard_arrow_right</span></a> 
                    </td>
                </tr>
            @endforeach
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