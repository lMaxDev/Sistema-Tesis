@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/estadisticas/estadisticas.css') }}">
@endpush

@section('content')

<div class="contendor">
    <header>
        <div>
            <h1>Estadíscticas</h1> 
            <br>
            <p>Monitorea el rendimiento de tus ventas en tiempo real.</p>
        </div>
    </header>

    <div class="datosHoyContenedor">
      <div class="tituloDatoHoy">
          Ventas de hoy
      </div>
      
      @foreach ($ventasHoy as $pedido)
        <div class="cartas-contenedor">
          <div class="cartaInformacion-contenedor">
            <div class="informacion-carta">
              <div class="icono-carta"><span class="material-symbols-outlined">shopping_bag</span></div>
              <div class="titulo-carta"><p>Pedidos realizados</p></div>
            </div>
            <div class="data-carta">
              {{ number_format($pedido->total_pedidos) }}
            </div>
            <div class="imagen-carta">
              <img src="imagenes\bolsa-de-la-compra.png" alt="#">
            </div>
        </div>

        <div class="cartaInformacion-contenedor">
            <div class="informacion-carta">
              <div class="icono-carta carta2"><span class="material-symbols-outlined">attach_money</span></div>
              <div class="titulo-carta"><p>Ingresos generados</p></div>
            </div>
            <div class="data-carta">
                C$ {{ number_format($pedido->total_dinero) }}
            </div>   
            <div class="imagen-carta">
              <img src="imagenes/billete-de-banco.png" alt="#">
            </div>
        </div>
        </div>
      @endforeach
    </div>
  
    <div class="graficasContenedor">
        <div class="graficaContenedor">
            <div class="tituloGrafica">
              <span>Ventas de 
                @if (isset($fechaRequest) || $fechaRequest = $hoy) 
                {{   $fechaRequest->format('d/m/Y') }}
                @endif
              </span>
            
                <form method="GET" action="{{ route('estadstica1.request') }}">
                  <input class="seleccionador" type="text" id="flatpickr-input" name="fecha" placeholder="Selecciona una fecha" onchange="this.form.submit()">
                </form>
            </div>
            <div class="grafica">
                <div id="ventasHoy"></div>
            </div>
        </div>

        <div class="graficaContenedor">
            <div class="tituloGrafica"> 
                Ventas de la semana
            </div>
            <div class="grafica">
                <div id="ventasSemana"></div>
            </div>
        </div>

        <div class="graficaContenedor">
            <div class="tituloGrafica">
                <span>Ventas del mes de 
                  @if (isset($mesRequestNombre) || isset($anioRequest))
                {{ $mesRequestNombre == $mes ? $mes : $mesRequestNombre }}
                {{ $anioRequest == $anio ? $anio : $anioRequest }}
            @else
                {{ $mesTitulo }}    
            @endif
                </span>

                <form method="GET" action="{{ route('estadstica2.request') }}">
                  <input class="seleccionador" type="text" id="flatpickr-input2" name="fecha" placeholder="Selecciona un mes" onchange="this.form.submit()">
                </form>
            </div>
            <div class="grafica">
                <div id="ventasMes"></div>
            </div>
        </div>

        <div class="graficaContenedor">
            <div class="tituloGrafica">
              <span>
                  Ventas del año {{ $ano }}
              </span>
              <form method="GET" action="{{ route('estadstica3.request') }}">
                  <input class="seleccionador" type="text" id="flatpickr-input3" name="fecha" placeholder="Selecciona un año" onchange="this.form.submit()">
              </form>
            </div>
            <div class="grafica">
                <div id="ventasAno"></div>
            </div>
        </div>

        <div class="graficaContenedor graficaProyeccionContenedor">
            <div class="tituloGrafica">
                Proyecciones de ventas.
            </div>
            <div class="grafica graficaProyeccion">
              <div id="Proyecciones"></div>
            </div>
        </div>
    </div>
</div>





<script>

    const ventas = @json(array_values($pedidosHoyData));
    const ganancias = @json(array_values($dineroHoyData));

var options = {
  chart: {
    toolbar: { show: false },
    type: 'line'
  },
  series: [{
    name: 'Ventas',
    data: ventas
  }],
  xaxis: {
    categories: ['0:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00']
  },
  tooltip: {
    custom: function({ series, seriesIndex, dataPointIndex, w }) {
      let ventasDia = series[seriesIndex][dataPointIndex];
      let gananciaDia = ganancias[dataPointIndex];

      return `
        <div style="padding:10px">
          <strong>${w.globals.labels[dataPointIndex] - 1}:00</strong><br><br>
          🛒 Ventas: ${ventasDia}<br>
          💰 Ganancias: C$${gananciaDia}
        </div>
      `;
    }
  },
  xaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    },
    categories: ['0:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00']
  },
  yaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    }
  }
};

var chart = new ApexCharts(document.querySelector("#ventasHoy"), options);
chart.render();

/*
    var options = {
  chart: {
    
    zoom: {
      enabled: false
    }
  },
  series: [{

    name: 'Ventas',
    data: pedidosHoyData
  },
  {
    name: 'Genancias',
    data: dineroHoyData

  }
],
tooltip: {
  shared: true
},
  
  xaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    },
    categories: ['0:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00']
  },
  yaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    }
  }
}

var chart = new ApexCharts(document.querySelector("#ventasHoy"), options);
chart.render();

*/


//Render de ventas de la Semana
    const pedidosSemanaData = @json(array_values($pedidosSemanaData));
    var options2 = {
  chart: {
    toolbar: { show: false },
    type: 'area'
  },
  series: [{
    name: 'Ventas',
    data: pedidosSemanaData
  }],
  
  xaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    },
    categories: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']
  },
  colors: ['#10B981'],
  yaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    }
  }
}

var chart2 = new ApexCharts(document.querySelector("#ventasSemana"), options2);
chart2.render();



//Render de ventas del mes
    const pedidosMesDataDia = @json(array_values($pedidosMesDataDia));
    const pedidosMesDataPedidos = @json(array_values($pedidosMesDataPedidos));
    var options3 = {
  chart: {
    toolbar: { show: false },
    type: 'area'
  },
  series: [{
    name: 'Ventas',
    data: pedidosMesDataPedidos
  }],
  
  xaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    },
    categories: pedidosMesDataDia
  },
  colors: ['#F97316'],
  yaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    }
  },
  
}

var chart3 = new ApexCharts(document.querySelector("#ventasMes"), options3);
chart3.render();



//Render de ventas del año
    const pedidosAnoData = @json(array_values($pedidosAnoData));

    var options4 = {
  chart: {
    type: 'bar',
    toolbar: { show: false },
    zoom: {
      enabled: false
    }
  },
  series: [{
    name: 'Ventas',
    data: pedidosAnoData
  }],
  
  xaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    },
    categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
  },
  colors: ['#905eb5'],
  yaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    }
  }
}

var chart4 = new ApexCharts(document.querySelector("#ventasAno"), options4);
chart4.render();


//Render de Proyecciones de ventas
    const proyeccionVentasData = @json(array_values($proyeccionVentasData));
    const meses = @json(array_values($meses));

    var options5 = {
  chart: {
    type: 'line',
    width: '100%', 
    height: 450,
    toolbar: { show: false },
    zoom: {
      enabled: false
    }
  },
  series: [{
    name: 'Ventas',
    data: proyeccionVentasData
  }],
  
  xaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    },
    categories: meses
  },
  colors: ['#905eb5'],
  yaxis: {
    labels: {
      style: {
        colors: '#fff' // Color para las etiquetas de la izquierda
      }
    }
  }
}

var chart5 = new ApexCharts(document.querySelector("#Proyecciones"), options5);
chart5.render();

</script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

    <script>
    flatpickr("#flatpickr-input", {
        locale: "es",  
        
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