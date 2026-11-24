<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class estadisticasController extends Controller
{
    public function index(){

        Carbon::setLocale('es');
        
        //Pedidos del dia actual agrupados por hora
        $pedidosHoy = Pedidos::select(
             DB::raw('HOUR(created_at) AS hora'),
             DB::raw('COUNT(*) as total_pedidos')
        )
        ->whereDate('created_at', now())
        ->groupBy('hora')
        ->orderBy('hora')
        ->get()
        ->toArray();

        $pedidosHoyData = array_fill(0, 24, 0); // Inicializa un array con 24 horas, todas con valor 0  
        foreach ($pedidosHoy as $pedido) {
            $pedidosHoyData[$pedido['hora']] = $pedido['total_pedidos'];
        }

        //Ganancias del dia actual agrupados por hora
        $dineroHoy = Pedidos::select(
             DB::raw('HOUR(created_at) AS hora'),
             DB::raw('SUM(total_pedido) as total_pedidos')
        )
        ->whereDate('created_at', now())
        ->groupBy('hora')
        ->orderBy('hora')
        ->get()
        ->toArray();

        $dineroHoyData = array_fill(0, 24, 0);
        foreach ($dineroHoy as $pedido) {
            $dineroHoyData[$pedido['hora']] = $pedido['total_pedidos'];
        }


        //Pedidos de la semana actual

        $inicioSemana = Carbon::now('America/Managua')->startOfWeek(); // Lunes
        $finSemana = Carbon::now('America/Managua')->endOfWeek();     // Domingo

        $pedidosSemana = Pedidos::select(
            DB::raw('DAYOFWEEK(created_at)-2 AS dia'),
            DB::raw('COUNT(*) as total_pedidos')
        )
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->groupBy('dia')
        ->orderBy('dia')
        ->get()
        ->toArray();

        $pedidosSemanaData = array_fill(0, 7, 0);
        foreach ($pedidosSemana as $pedido){
            $pedidosSemanaData[$pedido['dia']] = $pedido['total_pedidos'];
        }
        
    
        
        //Pedidos del mes actual agrupados por dia
        
        $mes = Carbon::now()->monthName; // Mes actual
        
        $pedidosMes = Pedidos::select(
            DB::raw("DATE_FORMAT(created_at, '%d/%m') AS dia"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereMonth('created_at', now()->month)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->toArray();
            
            $pedidosMesDataDia = [];
            foreach ($pedidosMes as $pedido){
                $pedidosMesDataDia[] = $pedido['dia'];
            }

            $pedidosMesDataPedidos = [];
            foreach ($pedidosMes as $pedido){
                $pedidosMesDataPedidos[] = $pedido['total_pedidos']; 
            }



            //Pedidos del Año actual agrupados por dia
        
        $ano = Carbon::now()->year; // Año actual
        
        $pedidosAno = Pedidos::select(
            DB::raw("MONTH(created_at)-1 AS mes"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();
            
            $pedidosAnoData = array_fill(0, 12, 0);
            foreach ($pedidosAno as $pedido){
                $pedidosAnoData[$pedido['mes']] = $pedido['total_pedidos'];
            }

            

            //Proyeccion de ventas del siguiente mes

            $desde = Carbon::now()->startOfMonth()->subMonths(3);

            $proyeccionVentas = Pedidos::select(
                DB::raw('YEAR(created_at) as anio'),
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('Count(*) as total_pedidos')
            )
            ->where('created_at', '>=', $desde)
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get()
            ->toArray();

            $nElementos = count($proyeccionVentas);

            $x = [];
            $y = [];

            // Generar array del numero de elementos (Meses) para la regresion lineal
            for ($i=1; $i <= $nElementos ; $i++) { 
                $x[] = $i;
            }

            // Generar array de total de pedidos para cada mes
            foreach ($proyeccionVentas as $venta) {
                $y[] = $venta['total_pedidos'];
            }


    $n = count($x);
    if (count($y) != $n) {
        die("Los elementos en x no coinciden con los elementos en y");
    }
    $sumaX = array_sum($x);
    $sumaY = array_sum($y);

    $sumaXporX = 0;
    $sumaXporY = 0;

    for ($i = 0; $i < $n; $i++) {
        $sumaXporX = $sumaXporX + ($x[$i] * $x[$i]);
        $sumaXporY = $sumaXporY + ($x[$i] * $y[$i]);
    }
    $num = ($n * $sumaXporY) - ($sumaX * $sumaY);
    $denom = ($n * $sumaXporX) - ($sumaX * $sumaX);
    if ($denom == 0) {
        $b = 0; // Evitar división por cero, asignar un valor predeterminado a b
        $w = 0; // Evitar división por cero, asignar un valor predeterminado a w
    }
    else {
        $w = $num / $denom;
        $b = ($sumaY - ($w * $sumaX)) / $n;
    }

            
            $proyeccionVentasData = [];
            foreach ($proyeccionVentas as $ventas){
                $proyeccionVentasData[] = $ventas['total_pedidos'];
            }
            if ($b != 0 && $w != 0) {
                $proyeccionVentasData[] = round($w * ($nElementos + 1) + $b); 
            } 
            
            $meses = [];
            for ($i=0; $i < $nElementos; $i++) { 
                $meses[] = Carbon::createFromDate($proyeccionVentas[$i]['anio'], $proyeccionVentas[$i]['mes'], 1,)->translatedFormat('F Y');
            }
            $meses[] = Carbon::now()->addMonth()->translatedFormat('F Y'); //
            
            



            //Cantidad de pedidos realizados y dinero generado en el dia.


            $ventasHoy = DB::select(
                "
                SELECT 
                    COUNT(*) AS total_pedidos,
                    COALESCE(SUM(total_pedido), 0) AS total_dinero
                FROM pedidos
                WHERE DATE(created_at) = ?
                ",
                [Carbon::now()->toDateString()]
            );

            $hoy = now();

            $mesTitulo = now()->monthName.' '.now()->year;
            $mesTitulo = ucfirst($mesTitulo);

            return view('estadisticas.index', compact('pedidosHoyData', 'pedidosSemanaData', 'pedidosMesDataDia', 'pedidosMesDataPedidos', 'mes', 'ano', 'pedidosAnoData', 'proyeccionVentasData', 'meses', 'ventasHoy', 'hoy', 'dineroHoyData', 'mesTitulo'));
            //return $w * ($nElementos + 1) + $b; // Proyeccion de ventas del siguiente mes
        }



/******************************************************************/
/******************************************************************/
/******************************************************************/
/******************************************************************/


        public function estadistica1(Request $request){
        
        Carbon::setLocale('es');
        $fechaRequest = Carbon::parse($request->input('fecha'));

        
        //Pedidos del dia actual agrupados por hora
        $pedidosHoy = Pedidos::select(
             DB::raw('HOUR(created_at) AS hora'),
             DB::raw('COUNT(*) as total_pedidos')
        )
        ->whereDate('created_at', $fechaRequest)
        ->groupBy('hora')
        ->orderBy('hora')
        ->get()
        ->toArray();

        $pedidosHoyData = array_fill(0, 24, 0); // Inicializa un array con 24 horas, todas con valor 0  
        foreach ($pedidosHoy as $pedido) {
            $pedidosHoyData[$pedido['hora']] = $pedido['total_pedidos'];
        }

        //Ganancias del dia actual agrupados por hora
        $dineroHoy = Pedidos::select(
             DB::raw('HOUR(created_at) AS hora'),
             DB::raw('SUM(total_pedido) as total_pedidos')
        )
        ->whereDate('created_at', $fechaRequest)
        ->groupBy('hora')
        ->orderBy('hora')
        ->get()
        ->toArray();

        $dineroHoyData = array_fill(0, 24, 0);
        foreach ($dineroHoy as $pedido) {
            $dineroHoyData[$pedido['hora']] = $pedido['total_pedidos'];
        }


        //Pedidos de la semana actual

        $inicioSemana = Carbon::now('America/Managua')->startOfWeek(); // Lunes
        $finSemana = Carbon::now('America/Managua')->endOfWeek();     // Domingo

        $pedidosSemana = Pedidos::select(
            DB::raw('DAYOFWEEK(created_at)-2 AS dia'),
            DB::raw('COUNT(*) as total_pedidos')
        )
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->groupBy('dia')
        ->orderBy('dia')
        ->get()
        ->toArray();

        $pedidosSemanaData = array_fill(0, 7, 0);
        foreach ($pedidosSemana as $pedido){
            $pedidosSemanaData[$pedido['dia']] = $pedido['total_pedidos'];
        }
        
    
        
        //Pedidos del mes actual agrupados por dia
        
        $mes = Carbon::now()->monthName; // Mes actual
        
        $pedidosMes = Pedidos::select(
            DB::raw("DATE_FORMAT(created_at, '%d/%m') AS dia"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereMonth('created_at', now()->month)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->toArray();
            
            $pedidosMesDataDia = [];
            foreach ($pedidosMes as $pedido){
                $pedidosMesDataDia[] = $pedido['dia'];
            }

            $pedidosMesDataPedidos = [];
            foreach ($pedidosMes as $pedido){
                $pedidosMesDataPedidos[] = $pedido['total_pedidos']; 
            }



            //Pedidos del Año actual agrupados por dia
        
        $ano = Carbon::now()->year; // Año actual
        
        $pedidosAno = Pedidos::select(
            DB::raw("MONTH(created_at)-1 AS mes"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();
            
            $pedidosAnoData = array_fill(0, 12, 0);
            foreach ($pedidosAno as $pedido){
                $pedidosAnoData[$pedido['mes']] = $pedido['total_pedidos'];
            }

            

            //Proyeccion de ventas del siguiente mes

            $desde = Carbon::now()->startOfMonth()->subMonths(3);

            $proyeccionVentas = Pedidos::select(
                DB::raw('YEAR(created_at) as anio'),
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('Count(*) as total_pedidos')
            )
            ->where('created_at', '>=', $desde)
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get()
            ->toArray();

            $nElementos = count($proyeccionVentas);

            $x = [];
            $y = [];

            // Generar array del numero de elementos (Meses) para la regresion lineal
            for ($i=1; $i <= $nElementos ; $i++) { 
                $x[] = $i;
            }

            // Generar array de total de pedidos para cada mes
            foreach ($proyeccionVentas as $venta) {
                $y[] = $venta['total_pedidos'];
            }


    $n = count($x);
    if (count($y) != $n) {
        die("Los elementos en x no coinciden con los elementos en y");
    }
    $sumaX = array_sum($x);
    $sumaY = array_sum($y);

    $sumaXporX = 0;
    $sumaXporY = 0;

    for ($i = 0; $i < $n; $i++) {
        $sumaXporX = $sumaXporX + ($x[$i] * $x[$i]);
        $sumaXporY = $sumaXporY + ($x[$i] * $y[$i]);
    }
    $num = ($n * $sumaXporY) - ($sumaX * $sumaY);
    $denom = ($n * $sumaXporX) - ($sumaX * $sumaX);
    if ($denom == 0) {
        $b = 0; // Evitar división por cero, asignar un valor predeterminado a b
        $w = 0; // Evitar división por cero, asignar un valor predeterminado a w
    }
    else {
        $w = $num / $denom;
        $b = ($sumaY - ($w * $sumaX)) / $n;
    }

            
            $proyeccionVentasData = [];
            foreach ($proyeccionVentas as $ventas){
                $proyeccionVentasData[] = $ventas['total_pedidos'];
            }
            if ($b != 0 && $w != 0) {
                $proyeccionVentasData[] = round($w * ($nElementos + 1) + $b); 
            } 
            
            $meses = [];
            for ($i=0; $i < $nElementos; $i++) { 
                $meses[] = Carbon::createFromDate($proyeccionVentas[$i]['anio'], $proyeccionVentas[$i]['mes'], 1,)->translatedFormat('F Y');
            }
            $meses[] = Carbon::now()->addMonth()->translatedFormat('F Y'); //
            



            //Cantidad de pedidos realizados y dinero generado en el dia.


            $ventasHoy = DB::select(
                "
                SELECT 
                    COUNT(*) AS total_pedidos,
                    COALESCE(SUM(total_pedido), 0) AS total_dinero
                FROM pedidos
                WHERE DATE(created_at) = ?
                ",
                [Carbon::now()->toDateString()]
                );
                
            $hoy = now();
            
            $mesTitulo = now()->monthName.' '.now()->year;
            $mesTitulo = ucfirst($mesTitulo);

            return view('estadisticas.index', compact('pedidosHoyData', 'pedidosSemanaData', 'pedidosMesDataDia', 'pedidosMesDataPedidos', 'mes', 'ano', 'pedidosAnoData', 'proyeccionVentasData', 'meses', 'ventasHoy', 'fechaRequest', 'hoy', 'dineroHoyData', 'mesTitulo'));            

            //return $fechaRequest;
        }





/******************************************************************/
/******************************************************************/
/******************************************************************/
/******************************************************************/





         public function estadistica2(Request $request){

        Carbon::setLocale('es');

        $fechaRequest = Carbon::parse($request->input('fecha'));
        $mes = Carbon::now()->monthName;
        $anio = Carbon::now()->year; 
        $mesNumero = $fechaRequest->month; 
        $anioNumero = $fechaRequest->year;
        $mesRequestNombre = $fechaRequest->monthName;
        $anioRequest = Carbon::parse($request->input('fecha'))->year;

        $mesString = $request->input('fecha'); 
        $mesReq = (int) $mesString;
        $mesNombre = Carbon::create()->month($mesReq)->locale('es')->monthName; // "mayo"
        
        //Pedidos del dia actual agrupados por hora
        $pedidosHoy = Pedidos::select(
             DB::raw('HOUR(created_at) AS hora'),
             DB::raw('COUNT(*) as total_pedidos')
        )
        ->whereDate('created_at', now())
        ->groupBy('hora')
        ->orderBy('hora')
        ->get()
        ->toArray();

        $pedidosHoyData = array_fill(0, 24, 0); // Inicializa un array con 24 horas, todas con valor 0  
        foreach ($pedidosHoy as $pedido) {
            $pedidosHoyData[$pedido['hora']] = $pedido['total_pedidos'];
        }

        //Ganancias del dia actual agrupados por hora
        $dineroHoy = Pedidos::select(
             DB::raw('HOUR(created_at) AS hora'),
             DB::raw('SUM(total_pedido) as total_pedidos')
        )
        ->whereDate('created_at', now())
        ->groupBy('hora')
        ->orderBy('hora')
        ->get()
        ->toArray();

        $dineroHoyData = array_fill(0, 24, 0);
        foreach ($dineroHoy as $pedido) {
            $dineroHoyData[$pedido['hora']] = $pedido['total_pedidos'];
        }

        //Pedidos de la semana actual

        $inicioSemana = Carbon::now('America/Managua')->startOfWeek(); // Lunes
        $finSemana = Carbon::now('America/Managua')->endOfWeek();     // Domingo

        $pedidosSemana = Pedidos::select(
            DB::raw('DAYOFWEEK(created_at)-2 AS dia'),
            DB::raw('COUNT(*) as total_pedidos')
        )
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->groupBy('dia')
        ->orderBy('dia')
        ->get()
        ->toArray();

        $pedidosSemanaData = array_fill(0, 7, 0);
        foreach ($pedidosSemana as $pedido){
            $pedidosSemanaData[$pedido['dia']] = $pedido['total_pedidos'];
        }
        
    
        
        //Pedidos del mes actual agrupados por dia
        
        $mes = Carbon::now()->monthName; // Mes actual

        $pedidosMes = Pedidos::select(
            DB::raw("DATE_FORMAT(created_at, '%d/%m') AS dia"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereMonth('created_at', $mesNumero)
            ->whereYear('created_at', $anioNumero)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->toArray();
            
            $pedidosMesDataDia = [];
            foreach ($pedidosMes as $pedido){
                $pedidosMesDataDia[] = $pedido['dia'];
            }

            $pedidosMesDataPedidos = [];
            foreach ($pedidosMes as $pedido){
                $pedidosMesDataPedidos[] = $pedido['total_pedidos']; 
            }



            //Pedidos del Año actual agrupados por dia
        
        $ano = Carbon::now()->year; // Año actual
        
        $pedidosAno = Pedidos::select(
            DB::raw("MONTH(created_at)-1 AS mes"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();
            
            $pedidosAnoData = array_fill(0, 12, 0);
            foreach ($pedidosAno as $pedido){
                $pedidosAnoData[$pedido['mes']] = $pedido['total_pedidos'];
            }

            

            //Proyeccion de ventas del siguiente mes

            $desde = Carbon::now()->startOfMonth()->subMonths(3);

            $proyeccionVentas = Pedidos::select(
                DB::raw('YEAR(created_at) as anio'),
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('Count(*) as total_pedidos')
            )
            ->where('created_at', '>=', $desde)
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get()
            ->toArray();

            $nElementos = count($proyeccionVentas);

            $x = [];
            $y = [];

            // Generar array del numero de elementos (Meses) para la regresion lineal
            for ($i=1; $i <= $nElementos ; $i++) { 
                $x[] = $i;
            }

            // Generar array de total de pedidos para cada mes
            foreach ($proyeccionVentas as $venta) {
                $y[] = $venta['total_pedidos'];
            }


    $n = count($x);
    if (count($y) != $n) {
        die("Los elementos en x no coinciden con los elementos en y");
    }
    $sumaX = array_sum($x);
    $sumaY = array_sum($y);

    $sumaXporX = 0;
    $sumaXporY = 0;

    for ($i = 0; $i < $n; $i++) {
        $sumaXporX = $sumaXporX + ($x[$i] * $x[$i]);
        $sumaXporY = $sumaXporY + ($x[$i] * $y[$i]);
    }
    $num = ($n * $sumaXporY) - ($sumaX * $sumaY);
    $denom = ($n * $sumaXporX) - ($sumaX * $sumaX);
    if ($denom == 0) {
        $b = 0; // Evitar división por cero, asignar un valor predeterminado a b
        $w = 0; // Evitar división por cero, asignar un valor predeterminado a w
    }
    else {
        $w = $num / $denom;
        $b = ($sumaY - ($w * $sumaX)) / $n;
    }

            
            $proyeccionVentasData = [];
            foreach ($proyeccionVentas as $ventas){
                $proyeccionVentasData[] = $ventas['total_pedidos'];
            }
            if ($b != 0 && $w != 0) {
                $proyeccionVentasData[] = round($w * ($nElementos + 1) + $b); 
            } 
            
            $meses = [];
            for ($i=0; $i < $nElementos; $i++) { 
                $meses[] = Carbon::createFromDate($proyeccionVentas[$i]['anio'], $proyeccionVentas[$i]['mes'], 1,)->translatedFormat('F Y');
            }
            $meses[] = Carbon::now()->addMonth()->translatedFormat('F Y'); //

            
            



            //Cantidad de pedidos realizados y dinero generado en el dia.


            $ventasHoy = DB::select(
                "
                SELECT 
                    COUNT(*) AS total_pedidos,
                    COALESCE(SUM(total_pedido), 0) AS total_dinero
                FROM pedidos
                WHERE DATE(created_at) = ?
                ",
                [Carbon::now()->toDateString()]
            );

            $hoy = now();
            
            return view('estadisticas.index', compact('pedidosHoyData', 'pedidosSemanaData', 'pedidosMesDataDia', 'pedidosMesDataPedidos', 'mes', 'ano', 'pedidosAnoData', 'proyeccionVentasData', 'meses', 'ventasHoy', 'hoy', 'anio', 'mesRequestNombre', 'anioRequest', 'dineroHoyData'));
        }

    






/******************************************************************/
/******************************************************************/
/******************************************************************/
/******************************************************************/





        public function estadistica3(Request $request){

        Carbon::setLocale('es');
        
        //Pedidos del dia actual agrupados por hora
        $pedidosHoy = Pedidos::select(
             DB::raw('HOUR(created_at) AS hora'),
             DB::raw('COUNT(*) as total_pedidos')
        )
        ->whereDate('created_at', now())
        ->groupBy('hora')
        ->orderBy('hora')
        ->get()
        ->toArray();

        $pedidosHoyData = array_fill(0, 24, 0); // Inicializa un array con 24 horas, todas con valor 0  
        foreach ($pedidosHoy as $pedido) {
            $pedidosHoyData[$pedido['hora']] = $pedido['total_pedidos'];
        }


        //Ganancias del dia actual agrupados por hora
        $dineroHoy = Pedidos::select(
             DB::raw('HOUR(created_at) AS hora'),
             DB::raw('SUM(total_pedido) as total_pedidos')
        )
        ->whereDate('created_at', now())
        ->groupBy('hora')
        ->orderBy('hora')
        ->get()
        ->toArray();

        $dineroHoyData = array_fill(0, 24, 0);
        foreach ($dineroHoy as $pedido) {
            $dineroHoyData[$pedido['hora']] = $pedido['total_pedidos'];
        }

        //Pedidos de la semana actual

        $inicioSemana = Carbon::now('America/Managua')->startOfWeek(); // Lunes
        $finSemana = Carbon::now('America/Managua')->endOfWeek();     // Domingo

        $pedidosSemana = Pedidos::select(
            DB::raw('DAYOFWEEK(created_at)-2 AS dia'),
            DB::raw('COUNT(*) as total_pedidos')
        )
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->groupBy('dia')
        ->orderBy('dia')
        ->get()
        ->toArray();

        $pedidosSemanaData = array_fill(0, 7, 0);
        foreach ($pedidosSemana as $pedido){
            $pedidosSemanaData[$pedido['dia']] = $pedido['total_pedidos'];
        }
        
    
        
        //Pedidos del mes actual agrupados por dia
        
        $mes = Carbon::now()->monthName; // Mes actual
        
        $pedidosMes = Pedidos::select(
            DB::raw("DATE_FORMAT(created_at, '%d/%m') AS dia"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereMonth('created_at', now()->month)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->toArray();
            
            $pedidosMesDataDia = [];
            foreach ($pedidosMes as $pedido){
                $pedidosMesDataDia[] = $pedido['dia'];
            }

            $pedidosMesDataPedidos = [];
            foreach ($pedidosMes as $pedido){
                $pedidosMesDataPedidos[] = $pedido['total_pedidos']; 
            }



            //Pedidos del Año actual agrupados por dia
        
            
        $ano = Carbon::now()->year; // Año actual
        
        $pedidosAno = Pedidos::select(
            DB::raw("MONTH(created_at)-1 AS mes"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereYear('created_at', $request->input('fecha'))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();
            
            $pedidosAnoData = array_fill(0, 12, 0);
            foreach ($pedidosAno as $pedido){
                $pedidosAnoData[$pedido['mes']] = $pedido['total_pedidos'];
            }

            

            //Proyeccion de ventas del siguiente mes

            $desde = Carbon::now()->startOfMonth()->subMonths(3);

            $proyeccionVentas = Pedidos::select(
                DB::raw('YEAR(created_at) as anio'),
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('Count(*) as total_pedidos')
            )
            ->where('created_at', '>=', $desde)
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get()
            ->toArray();

            $nElementos = count($proyeccionVentas);

            $x = [];
            $y = [];

            // Generar array del numero de elementos (Meses) para la regresion lineal
            for ($i=1; $i <= $nElementos ; $i++) { 
                $x[] = $i;
            }

            // Generar array de total de pedidos para cada mes
            foreach ($proyeccionVentas as $venta) {
                $y[] = $venta['total_pedidos'];
            }


    $n = count($x);
    if (count($y) != $n) {
        die("Los elementos en x no coinciden con los elementos en y");
    }
    $sumaX = array_sum($x);
    $sumaY = array_sum($y);

    $sumaXporX = 0;
    $sumaXporY = 0;

    for ($i = 0; $i < $n; $i++) {
        $sumaXporX = $sumaXporX + ($x[$i] * $x[$i]);
        $sumaXporY = $sumaXporY + ($x[$i] * $y[$i]);
    }
    
    $num = ($n * $sumaXporY) - ($sumaX * $sumaY);
    $denom = ($n * $sumaXporX) - ($sumaX * $sumaX);
    if ($denom == 0) {
        $b = 0; // Evitar división por cero, asignar un valor predeterminado a b
        $w = 0; // Evitar división por cero, asignar un valor predeterminado a w
    }
    else {
        $w = $num / $denom;
        $b = ($sumaY - ($w * $sumaX)) / $n;
    }

    

            
            $proyeccionVentasData = [];
            foreach ($proyeccionVentas as $ventas){
                $proyeccionVentasData[] = $ventas['total_pedidos'];
            }
            $proyeccionVentasData[] = round($w * ($nElementos + 1) + $b); 
            
            $meses = [];
            for ($i=0; $i < $nElementos; $i++) { 
                $meses[] = Carbon::createFromDate($proyeccionVentas[$i]['anio'], $proyeccionVentas[$i]['mes'], 1,)->translatedFormat('F Y');
            }
            $meses[] = Carbon::now()->addMonth()->translatedFormat('F Y'); //
            
            

            //Cantidad de pedidos realizados y dinero generado en el dia.


            $ventasHoy = DB::select(
                "
                SELECT 
                    COUNT(*) AS total_pedidos,
                    COALESCE(SUM(total_pedido), 0) AS total_dinero
                FROM pedidos
                WHERE DATE(created_at) = ?
                ",
                [Carbon::now()->toDateString()]
            );

            $mesTitulo = now()->monthName.' '.now()->year;
            $mesTitulo = ucfirst($mesTitulo);

            $ano = $request->fecha;

            $hoy = now();
            return view('estadisticas.index', compact('pedidosHoyData', 'pedidosSemanaData', 'pedidosMesDataDia', 'pedidosMesDataPedidos', 'mes', 'ano', 'pedidosAnoData', 'proyeccionVentasData', 'meses', 'ventasHoy', 'hoy', 'dineroHoyData', 'mesTitulo'));
            //return $anio;
        }
}
