@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/ventas/detalles.css') }}">
@endpush


@php
    if ($pedido->tipo_descuento == 'Porcentual') {
        $signo = '%';
    } elseif ($pedido->tipo_descuento == 'Monto fijo') {
        $signo = 'C$';
    } else {
        $signo = '';
    }

    $descontado = $pedido->total_pedido - $pedido->subtotal_pedido;
@endphp


@section('content')


    <header>
        <div class="titulo-detallesPedido">
            <h1>Detalles del pedido #{{ $pedido->id }}</h1> 
            <div class="">
                <span class="estado">Completado</span>
            </div>
        </div>
        <div class="botonPrincipal-contenedor">
            <button class="botonPrincipal">
                <span class="material-symbols-outlined">Print</span>
                <a href="{{ route('ventas.factura', $pedido->id) }}">Imprimir</a> 
            </button>
        </div>
    </header>

    {{-- Tarjetas --}}
    <div class="card-contenedor">
        <div class="card">
            <span class="material-symbols-outlined">receipt_long</span> 
            <div class="card-detalles">
                <span>Numero de pedido</span>
                <p>#{{ $pedido->id }}</p>
            </div>
        </div>

        <div class="card">
            <span class="material-symbols-outlined">calendar_month</span> 
            <div class="card-detalles">
                <span>Fecha del pedido</span>
                <p>{{ $fecha }}</p>
            </div>
        </div>

        <div class="card">
            <span class="material-symbols-outlined">Person</span> 
            <div class="card-detalles">
                <span>Cliente</span>
                <p>{{ $pedido->cliente }}</p>
            </div>
        </div>

        <div class="card">
            <span class="material-symbols-outlined">Person_3</span> 
            <div class="card-detalles">
                <span>Vendedor</span>
                <p>{{$pedido->user->name}} {{$pedido->user->second_name}}</p>
            </div>
        </div>

        <div class="card">
            <span class="material-symbols-outlined">point_of_sale</span>
            <div class="card-detalles">
                <span>Cajero</span>
                <p>{{ $pedido->cajero }}</p>
            </div>
        </div>

    </div>


    {{-- tabla --}}
    <div class="tabla">
        <div class="tutulo-seccion">
            <span class="material-symbols-outlined">list_alt</span>
            <h4>Detalles de los productos</h4>
        </div>
        <div class="tabla-contenedor">
            <table class ="table">
                <thead>
                    <tr>
                        <th class="th-producto">Producto</th>
                        <th>Unidades</th>
                        <th>Precio Unitario</th> 
                        <th>Total de linea</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $detalle)
                        <tr>
                            <td class="th-producto">
                                {{ ucfirst($detalle->nombre) }}
                                <br>
                                <span class="descripcion">{{ ucfirst($detalle->descripcion) }}</span>
                            </td>
                            <td>{{ $detalle->cantidad_unitaria }}</td>
                            <td class="dinero">C$ {{ number_format($detalle->precio_compra) }}</td>
                            <td>C$ {{ number_format($detalle->total_linea) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Detalles Dashbord --}}
    <div class="detalles-contenedor">

        <div class="detalles-contenedor-card card-descuento">
            <div class="tutulo-descuento">
                <span class="material-symbols-outlined ico">sell</span>
                <span>Descuento aplicado</span>
                    @if ($signo == '')
                        <span class="descuento-toggle no">No</span>
                    @else
                        <span class="descuento-toggle si">Si</span>
                    @endif
            </div>

            <div class="descripcion-descuento">
                <span class="descuento-titulo">Descuento por promocion</span>
                <span class="descuento">{{ $pedido->valor_descuento }} {{$signo}}</span>
            </div>
        </div>

        <div class="detalles-contenedor-card">
            <div class="subtotal-contenedor">
                <div class="subtotal">
                    <span>Subtotal</span>
                    <span class="monto">C$ {{ number_format($pedido->subtotal_pedido) }}</span>
                </div>
                <div class="subtotal Descuento">
                    <span>Descuento</span>
                    <span class="monto descontado">C$ {{$descontado}}</span>
                </div>
            </div>

            <div class="totalFinal-contenedor">
                <span>Total final</span>
                <span class="monto-total">C$ {{ number_format($pedido->total_pedido) }}</span>
            </div>
        </div>

    </div>





{{-- 
<header>
        <h1>Pedido #{{ $pedido->id }}</h1>
        <sapan class="datos-detallePedido" >Fecha: {{ $fecha }}</sapan><br>
        <sapan class="datos-detallePedido" >Vendedor: {{$pedido->user->name}} {{$pedido->user->second_name}}</sapan>
</header>

<h2 class="tutulo-seccion">Detalles del pedido</h2>
    <div class="tabla-contenedor">
        <table class ="table">
        <thead>
            <tr>
                <th class="separacionTablas">Producto</th>
                <th class="separacionTablas">Cantidad</th>
                <th class="separacionTablas">Precio Unitario</th>
                <th>Total</th>

            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
                <tr>
                    <td class="separacionTablas">
                        {{ ucfirst($detalle->nombre) }}
                        <br>
                        <span class="descripcion-producto">{{ ucfirst($detalle->descripcion) }}</span>
                    </td>
                    <td class="separacionTablas">{{ $detalle->cantidad_unitaria }}</td>
                    <td class="separacionTablas">C$ {{ $detalle->precio_compra }}</td>
                    <td>C$ {{ $detalle->total_linea }}</td>
                </tr>
            @endforeach
                <tr class="trPedido">
                    <td >Subtotal</td>
                    <td ></td>
                    <td ></td>
                    <td >C$ {{ $pedido->subtotal_pedido }}</td>
                </tr>
                <tr class="trPedido">
                    <td>Descuento</td>
                    @if ( $pedido->tipo_descuento === 'Ninguno')
                        <td style="color: rgba(255, 255, 255, 0.349)">
                            {{ $pedido->tipo_descuento }}
                        </td>
                    @else
                        <td>{{ $pedido->tipo_descuento }}</td>
                    @endif
                    <td></td>
                    @if ($pedido->tipo_descuento === 'Porcentual')
                        <td>{{ $pedido->valor_descuento }}%</td>
                    @endif
                    @if ($pedido->tipo_descuento === 'Monto fijo')
                        <td>C$ {{ $pedido->valor_descuento }}</td>
                    @endif
                    @if ($pedido->tipo_descuento === 'Ninguno')
                        <td>-</td>
                    @endif
                </tr>
                <tr class="trPedido">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>C$ {{ $pedido->total_pedido }}</td>
                </tr>
        </tbody>
        </table>
    </div> --}}

@endsection