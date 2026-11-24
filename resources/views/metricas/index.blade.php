@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/metricas/metricas.css') }}">

@endpush

@section('content')

<header>
        <div>
            <h1>Métricas</h1> 
            <br>
            <p>Resumen de ventas por empleados, productos e informacion relevante</p>
        </div>
</header>

<div>
    <h2 class="tutulo-seccion">Metricas mensuales - <span>{{ucfirst($mes)}} {{ $anio }}</span></h2>
</div>
<div class="cartas-contenedor">
    <div class="cartaInformacion-contenedor">
        <div class="informacion-carta">
          <div class="icono-carta carta3"><span class="material-symbols-outlined">receipt</span></div>
          <div class="titulo-carta"><p>Pedidos realizados</p></div>
        </div>
        <div class="data-carta">
          {{ number_format($pedidosMesActual[0]->total_pedidos) }}
        </div>
        <div class="imagen-carta">
          <img src="imagenes/entrega-de-pedidos.png" alt="#">
        </div>
    </div>

          

        <div class="cartaInformacion-contenedor">
            <div class="informacion-carta">
              <div class="icono-carta carta4"><span class="material-symbols-outlined">attach_money</span></div>
              <div class="titulo-carta"><p>Dinero generado</p></div>
            </div>
            <div class="data-carta">
              C$ {{ number_format($pedidosMesActual[0]->total_dinero) }}
            </div>
            <div class="imagen-carta">
              <img src="imagenes\billete-de-banco (1).png" alt="#">
            </div>
        </div>

        <div class="cartaInformacion-contenedor">
            <div class="informacion-carta">
              <div class="icono-carta"><span class="material-symbols-outlined">shopping_bag</span></div>
              <div class="titulo-carta"><p>Productos vendidos</p></div>
            </div>
            <div class="data-carta">
              {{ number_format($productosMesActual[0]->total_productos_vendidos) }}
            </div>
            <div class="imagen-carta"> 
              <img src="imagenes\bolsa-de-la-compra.png" alt="#">
            </div>
        </div>

</div>

<div class="contendorPrncipal">
<div class="contenedorMetricas metrica1">
    <div class="contenedorMetricas-encabezado">
        <h2 class="tutulo-seccion icono"> <span class="material-symbols-outlined">group</span>Metricas por empleado -  
            @if (isset($mesRequestNombre) || isset($anioRequest))
                {{ $mesRequestNombre == $mes ? $mes : ucfirst($mesRequestNombre) }}
                {{ $anioRequest == $anio ? $anio : $anioRequest }}
            @else
                {{ ucfirst($mes).' '.$anio }}    
            @endif

        </h2>
        <form method="GET" action="{{ route('metrica1.request') }}">
            <input class="seleccionador" type="text" id="flatpickr-input" name="fecha" placeholder="Selecciona una fecha" onchange="this.form.submit()">
        </form>
    </div>

    <div class="tabla-contenedor">
        <table class ="table">
        <thead>
            <tr>
                <th>Nombres</th>
                <th>Ventas realizadas</th>
                <th>Cantidad de productos</th>
                <th>Dinero generado</th>

            </tr>
        </thead>
        <tbody>
                @foreach ($metricasVentasMensuales as $metrica)
                    <tr>
                        <td><a class="enlaceTabla" href="{{ route('metricaVendedor', $metrica->id) }}">{{ $metrica->name }} {{ $metrica->second_name }}</a></td>
                        <td>{{ $metrica->total_pedidos }}</td>
                        <td>{{ $metrica->total_productos_vendidos }}</td>
                        <td class="dinero">C$ {{ number_format($metrica->total_dinero_vendido) }}</td>
                    </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>


<div class="contenedorMetricas metrica2">
    <h2 class="tutulo-seccion">Productos con poco Stock</h2>
    <div class="tabla-contenedor ">
        <table class ="table">
        <thead>
            <tr>
                <th class="separacionTabla">Producto</th>
                <th>Unidades disponibles</th>
            </tr>
        </thead>
        <tbody>
                @foreach ($metricasProductosConPocoStock as $metrica)
                    <tr>
                        <td >{{ ucfirst($metrica->nombre) }}
                            <br>
                            <span class="descripcion-producto">{{ ucfirst($metrica->descripcion) }}</span>
                        </td>
                        <td><sapn class="metricaTablaCantidad">{{ $metrica->cantidad }}</sapn></td>
                    </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
</div>

<div class="contenedorMetricas metrica1 metrica3">
<div class="contenedorMetricas-encabezado">
    <h2 class="tutulo-seccion">Productos mas vendidos - 
            @if (isset($mesRequestNombre2) || isset($anioRequest2))
                {{ $mesRequestNombre2 == $mes ? $mes : ucfirst($mesRequestNombre2) }}
                {{ $anioRequest2 == $anio ? $anio : ucfirst($anioRequest2) }}
            @else
                {{ ucfirst($mes).' '.$anio }}    
            @endif</h2>
        <form method="GET" action="{{ route('metrica2.request') }}">
            <input class="seleccionador" type="text" id="flatpickr-input" name="fecha" placeholder="Selecciona una fecha" onchange="this.form.submit()">
        </form>
</div>    
    <div class="tabla-contenedor productosTabla">
        <table class ="table ">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Unidades vendidas</th>
                <th>Aparicion en pedidos</th>
                <th>Dinero generado</th>

            </tr>
        </thead>
        <tbody>
                @foreach ($productosMasVendidos as $producto)
                    <tr>
                        <td>{{ ucfirst($producto->producto) }}
                            <br>
                            <span class="descripcion-producto">{{ ucfirst($producto->descripcion) }}</span> 
                        </td>
                        <td>{{ $producto->unidades_vendidas }}</td>
                        <td>{{ $producto->cantidad_pedidos }}</td>
                        <td class="dinero">C$ {{ number_format($producto->dinero_generado) }}</td>
                    </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>




    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <script>
    flatpickr("#flatpickr-input", {
        locale: "es",  
        plugins: [
        new monthSelectPlugin({
            shorthand: true,
            dateFormat: "Y-m", // formato que se guarda
            altFormat: "F Y"   // formato visible (Ej: Enero 2026)
        })
    ],
        locale: {
            firstDayOfWeek: 1,// start week on Monday
        },
    });
    </script>

@endsection