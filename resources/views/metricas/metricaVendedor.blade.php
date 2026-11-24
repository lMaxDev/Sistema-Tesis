@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/perfil/perfil.css') }}">
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/metricas/metricas.css') }}">
<link rel="stylesheet" href="{{ asset('css/metricas/vendedores.css') }}">
@endpush

@php
    use Carbon\Carbon;
@endphp

@section('content')

    <header>
        <div >
            <h1>Metricas de <span class="texto-gradiente">{{ $vendedor->name }} {{ $vendedor->second_name }}</span></h1> 
            <p>Resumen general de ventas </p>
        </div>
        <div class="botonPrincipal-contenedor">
            <form class="SeleccionadorFecha" method="GET" action="{{ route('metricasVendedorMes.request', $vendedor->id) }}">
              <span class="material-symbols-outlined">calendar_month</span>
              <input  class="botonPrincipal"  class="seleccionador" type="text" id="flatpickr-input2" name="fecha" placeholder="{{ $infoFecha }}" disabled>
            </form>
        </div>
    </header>

    
    {{-- Tarjetas --}}
<div class="cartas-contenedor">
    <div class="cartaInformacion-contenedor">
        <div class="informacion-carta">
          <div class="icono-carta"><span class="material-symbols-outlined">receipt</span></div>
          <div class="titulo-carta"><p>Pedidos realizados</p></div>
        </div>
        <div class="data-carta">
          {{ $numeroPedidos[0]->total_pedidos }}
        </div>
        <div class="imagen-carta">
              <span class="material-symbols-outlined icono-inferior color1">shopping_bag</span>
        </div>
    </div>

          
    <div class="cartaInformacion-contenedor">
      <div class="informacion-carta">
        <div class="icono-carta carta4"><span class="material-symbols-outlined">attach_money</span></div>
        <div class="titulo-carta"><p>Dinero Generado</p></div>
      </div>
      <div class="data-carta">
        C$ {{ number_format($dineroGenerado[0]->total_dinero_vendido) }}
      </div>
      <div class="imagen-carta"> 
          <span class="material-symbols-outlined icono-inferior color2">paid</span>
      </div>
  </div>

    <div class="cartaInformacion-contenedor">
            <div class="informacion-carta">
              <div class="icono-carta carta3"><span class="material-symbols-outlined">shopping_bag</span></div>
              <div class="titulo-carta"><p>Productos vendidos</p></div>
            </div>
            <div class="data-carta">
              {{ $productosVendidos[0]->total_productos_vendidos }}
            </div>
            <div class="imagen-carta">
              <span class="material-symbols-outlined icono-inferior color3">shoppingmode</span>
            </div>
      </div>       
</div>


    {{-- Grafica --}}

  <div class="graficaContenedor">
    <div class="graficaSubContenedor">
        <div class="texto-grafica">
          <span class="material-symbols-outlined">monitoring</span>
          <span class="nombre">Ventas de {{$infoFecha}}</span>
        </div>
        <div class="xd">
          <span class="graficaSeleccionadorTitulo">Selecciona</span>
          <div class="seleccionadoresGrafica-contenedor">
              <form class="SeleccionadorFecha" method="GET" action="{{ route('graficaVendedorMes.request', $vendedor->id) }}">
                  <input  class="botonPrincipal2"  class="seleccionador" type="text" id="flatpickr-input2" name="fecha" onchange="this.form.submit()" placeholder="Por mes">
              </form>
              <form action="{{ route('graficaVendedorAnual.request', $vendedor->id) }}">
                  <select id="opcion" name="anio" onchange="if(this.value) this.form.submit()">
                      <option class="option" value="">Por año</option>
                      @foreach($anios as $anio)
                          <option class="option" value="{{ $anio }}">
                              {{ $anio }}
                          </option>
                      @endforeach
                  </select>
              </form>
          </div>
        </div>
    </div>
    <div class="grafica">
      <div id="ventasMensuales"></div>
    </div>
  </div>


      {{-- Tabla --}}
<div class="seccion-tabla-contenedor">
        <div class="encabezado-tabla-contenedor">
            <span class="material-symbols-outlined">list_alt</span>
            <span class="tutulo-seccion">Productos registrados <span class="fechaTabla">{{ $infoFecha }}</span></span>
        </div>

        <div class="tabla-contenedor">
            <table class ="table">
                <thead>
                    <tr>
                        <th>Pedido ID</th>
                        <th>Fecha</th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th>Cajero</th>
                        <th>Total</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($metricasVendedor as $venta)
                        @php
                            $ventaFormat = $venta->created_at;
                            $ventaFormat = Carbon::parse($ventaFormat)->format('d/m/y');
                            $horaFormat = Carbon::parse($venta->created_at)->format('H:i');
                        @endphp
                        <tr>
                            <td>#{{ $venta->id_pedido }}</td>
                            <td>
                                {{ $ventaFormat }}  <span class="horaVenta">{{ $horaFormat }}</span>
                            </td>
                            <td>{{ $venta->vendedor }} {{ $venta->segundo_nombre }}</td>
                            <td>{{ $venta->cliente }}</td>
                            <td>{{ $venta->cantidad_productos }} Productos</td>
                            <td>{{ $venta->cajero }}</td>
                            <td class="dinero">C$ {{ number_format($venta->total_pedido) }}</td>
                            <td>
                              <a class="detallesBoton" href="{{ route('ventas.detalles', $venta->id_pedido) }}">Ver detalles<span class="material-symbols-outlined">keyboard_arrow_right</span></a> 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>





  <script>
    const pedidosAnoData = @json($pedidosAnoData);
    const ventasAnoData = @json($ventasAnoData);
    const mesesData = @json($mesesData)


    var options = {
  chart: {
    type: 'area',
    height: 450,
    width: '100%', 
    toolbar: { show: false },
    background: 'transparent',
    foreColor: '#64748b'

  },
  series: [{
    name: 'Ventas',
    data: pedidosAnoData
  }],

  fill: {
    type: 'gradient',
    gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.45, // Opacidad arriba
        opacityTo: 0.05,   // Opacidad abajo
        stops: [0, 100]
    }
},
// 4. Configuración de los Marcadores (Puntos en las curvas)
markers: {
    size: 4,
    colors: ['#7c3aed'],
    strokeColors: '#ffffff',
    strokeWidth: 2,
    hover: { size: 7 }
},

grid: {
    borderColor: '#233d66',
    strokeDashArray: 5, // Convierte las líneas continuas en punteadas [ej: 5px línea, 5px espacio]
    xaxis: { lines: { show: false } }, // Quitamos líneas verticales
    yaxis: { lines: { show: true } }   // Dejamos solo las horizontales
},
  tooltip: {
    backgroundColor: '#10295a',
    custom: function({ series, seriesIndex, dataPointIndex, w }) {
      let ventasDia = series[seriesIndex][dataPointIndex];
      let gananciaDia = ventasAnoData[dataPointIndex];

      return `
        <div style="padding:10px; background-color: #10295a; color: #fff; border:none; border: 2px solid #394f75; border-radius: 10px;">
            <span style="color: #b6caec; font-weight: 600;">Detalles</span><br>
            <strong><span class="puntoEstadistica">o  </span> Ventas:</strong> ${ventasDia}</strong><br>
            <strong><span class="puntoEstadistica2">o  </span> Ingresos:</strong> C$ ${gananciaDia}
        </di
      `;
    }
  },
  xaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    },
    categories: mesesData
  },
  colors: ['#7c3aed'],
  yaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    }
  },

}

var chart = new ApexCharts(document.querySelector("#ventasMensuales"), options);
chart.render();
</script>



<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

    <script>
    flatpickr("#flatpickr-input1", {
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

    <script>
    flatpickr("#flatpickr-input2", {
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

    <script>
flatpickr("#flatpickr-input3", {
    dateFormat: "Y",
    altInput: true,
    altFormat: "Y",
    plugins: [
        new monthSelectPlugin({
            shorthand: true,
            dateFormat: "Y",
            altFormat: "Y"
        })
    ]
});
</script>

@endsection