<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @stack('styles')
    <title>Proyecto</title>
</head>
<body>
    <div class="layout-superior">
        <div class="contenedor-superior">
            <div class="titulo-contenedor">
                <img src="/imagenes/estadistica.png" alt="#">
                <span class="titulo">Bienvenido!</span>
            </div>
            <div class="perfil">
                <a href="{{ route('perfil.index') }}" class="foto-perfil-contenedor">
                    <img src="/imagenes/perfil.png" alt="#">
                    <span>{{ Auth::User()->name }}</span>
                    <span class="material-symbols-outlined">stat_minus_1</span>
                </a>
            </div>
        </div>
    </div>
    <div class="layout-lateral">
        <ul class="menu-lateral">
            <li class="botonLateral1"><a class="enlaceLateral" href="/"><span class="material-symbols-outlined">sell</span> Ventas</a></li>
            
            <li class="botonLateral2"><a class="enlaceLateral" href="/productos"><span class="material-symbols-outlined">shopping_cart</span> Productos</a></li>
            
            @if (Auth::User()->cargo == 'Administrador')
            <li class="botonLateral3"><a class="enlaceLateral" href="/estadisticas"><span class="material-symbols-outlined">bar_chart_4_bars</span> Estadisticas</a></li>
            
            <li class="botonLateral4"><a class="enlaceLateral" href="/metricas"><span class="material-symbols-outlined">table_chart_view</span> Metricas</a></li>
            
            <li class="botonLateral5"><a class="enlaceLateral" href="/empleados"><span class="material-symbols-outlined">group</span> Empresa</a></li>
            @else
                
            @endif
            
        </ul>
        <div class="contenido">
            @yield('content')
        </div>
    </div>
</body>
</html>