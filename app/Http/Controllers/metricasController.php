<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pedidos;
use App\Models\Productos;
use App\Models\User;
use Carbon\Carbon;

class metricasController extends Controller
{
    
    public function index(){
        Carbon::setLocale('es');

        $mes = Carbon::now()->monthName; // Mes actual
        $anio = Carbon::now()->year; // Año actual
        
        $fechaActual = now()->month; // Obtiene la fecha actual en formato 'Y-m-d'

        //Pedidos realizados en el mes actual

        $pedidosMesActual = DB::select(
            "
            SELECT 
    COUNT(*) AS total_pedidos,
    COALESCE(SUM(total_pedido),0) AS total_dinero
FROM pedidos
WHERE MONTH(created_at) = ?;
            ",
            [$fechaActual]
        );

        //Productos vendidos en el mes actual
        $productosMesActual = DB::select(
            "
            SELECT 
    COALESCE(SUM(cantidad_unitaria),0) AS total_productos_vendidos
FROM detalles_pedido dp
JOIN pedidos p ON p.id = dp.idPedido
WHERE MONTH(p.created_at) = ?;
            ",
            [$fechaActual]
        );

        //metricas mensuales de ventas por vendedor
        $metricasVentasMensuales = DB::select(
            "
            SELECT 
    u.id,
    u.name,
    u.second_name,
    
    -- Cantidad de pedidos
    (
        SELECT COUNT(*)
        FROM pedidos p
        WHERE p.idUsuario = u.id
        AND MONTH(p.created_at) = ?
    ) AS total_pedidos,
    
        -- Total productos vendidos
    (
        SELECT COALESCE(SUM(dp.cantidad_unitaria),0)
        FROM pedidos p
        JOIN detalles_pedido dp ON dp.idPedido = p.id
        WHERE p.idUsuario = u.id
        AND MONTH(p.created_at) = ?
    ) AS total_productos_vendidos,
    
    -- Total dinero vendido
    (
        SELECT COALESCE(SUM(p.total_pedido),0)
        FROM pedidos p
        WHERE p.idUsuario = u.id
        AND MONTH(p.created_at) = ?
    ) AS total_dinero_vendido
    
    
FROM users u
ORDER BY u.name;
            ",
            [$fechaActual, $fechaActual, $fechaActual]
        );


        $metricasProductosConPocoStock = DB::select(
            "
            SELECT
id, nombre, cantidad, descripcion 
FROM productos
WHERE cantidad < 5
ORDER BY cantidad
ASC
LIMIT 10;
            "
        );
    

        $productosMasVendidos = DB::select(
            "
            SELECT 
    pr.descripcion,
    pr.nombre AS producto,
    COUNT(DISTINCT dp.idPedido) AS cantidad_pedidos,
    SUM(dp.cantidad_unitaria) AS unidades_vendidas,
    SUM(dp.total_linea) AS dinero_generado
FROM detalles_pedido dp
JOIN productos pr 
    ON pr.id = dp.idProducto
JOIN pedidos p 
    ON p.id = dp.idPedido
WHERE 
    p.created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
    AND p.created_at < DATE_FORMAT(CURDATE() + INTERVAL 1 MONTH, '%Y-%m-01')
GROUP BY pr.id, pr.nombre
ORDER BY unidades_vendidas DESC
LIMIT 10;
            "
        );


        return view('metricas.index', compact('metricasVentasMensuales', 'metricasProductosConPocoStock', 'productosMasVendidos', 'mes', 'anio', 'pedidosMesActual', 'productosMesActual'));
        }








    public function metrica1(Request $request){
        Carbon::setLocale('es');

        $fechaRequest = Carbon::parse($request->input('fecha'));
        $mes = Carbon::now()->monthName;
        $anio = Carbon::now()->year; 
        $mesNumero = $fechaRequest->month; 
        $mesRequestNombre = $fechaRequest->monthName;
        $anioRequest = Carbon::parse($request->input('fecha'))->year; 

        $fechaActual = now()->month; // Obtiene la fecha actual en formato 'Y-m-d'

        //Pedidos realizados en el mes actual

        $pedidosMesActual = DB::select(
            "
            SELECT 
    COUNT(*) AS total_pedidos,
    COALESCE(SUM(total_pedido),0) AS total_dinero
FROM pedidos
WHERE MONTH(created_at) = ?;
            ",
            [$fechaActual]
        );

        //Productos vendidos en el mes actual
        $productosMesActual = DB::select(
            "
            SELECT 
    COALESCE(SUM(cantidad_unitaria),0) AS total_productos_vendidos
FROM detalles_pedido dp
JOIN pedidos p ON p.id = dp.idPedido
WHERE MONTH(p.created_at) = ?;
            ",
            [$fechaActual]
        );

        $metricasVentasMensuales = DB::select(
            "
            SELECT 
    u.id,
    u.name,
    u.second_name,
    
    -- Cantidad de pedidos
    (
        SELECT COUNT(*)
        FROM pedidos p
        WHERE p.idUsuario = u.id
        AND Month(p.created_at) = ?
        AND Year(p.created_at) = ?
    ) AS total_pedidos,
    
        -- Total productos vendidos
    (
        SELECT COALESCE(SUM(dp.cantidad_unitaria),0)
        FROM pedidos p
        JOIN detalles_pedido dp ON dp.idPedido = p.id
        WHERE p.idUsuario = u.id
        AND Month(p.created_at) = ?
        AND Year(p.created_at) = ?
    ) AS total_productos_vendidos,
    
    -- Total dinero vendido
    (
        SELECT COALESCE(SUM(p.total_pedido),0)
        FROM pedidos p
        WHERE p.idUsuario = u.id
        AND Month(p.created_at) = ?
        AND Year(p.created_at) = ?
    ) AS total_dinero_vendido
    
    
FROM users u
ORDER BY u.name;
            ",
            [$mesNumero, $anioRequest, $mesNumero, $anioRequest, $mesNumero, $anioRequest,]
        );


        /*------------------*/


        $metricasProductosConPocoStock = DB::select(
            "
            SELECT
id, nombre, cantidad, descripcion 
FROM productos
WHERE cantidad < 5
ORDER BY cantidad
ASC
LIMIT 10;
            "
        );
    

        $productosMasVendidos = DB::select(
            "
            SELECT 
    pr.descripcion,
    pr.nombre AS producto,
    COUNT(DISTINCT dp.idPedido) AS cantidad_pedidos,
    SUM(dp.cantidad_unitaria) AS unidades_vendidas,
    SUM(dp.total_linea) AS dinero_generado
FROM detalles_pedido dp
JOIN productos pr 
    ON pr.id = dp.idProducto
JOIN pedidos p 
    ON p.id = dp.idPedido
WHERE 
    p.created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
    AND p.created_at < DATE_FORMAT(CURDATE() + INTERVAL 1 MONTH, '%Y-%m-01')
GROUP BY pr.id, pr.nombre
ORDER BY unidades_vendidas DESC
LIMIT 10;
            "
        );

        return view('metricas.index', compact('metricasVentasMensuales', 'metricasProductosConPocoStock', 'productosMasVendidos', 'mes', 'anio', 'mesRequestNombre', 'anioRequest', 'pedidosMesActual', 'productosMesActual'));
        //return $mes;
    }




    public function metrica2(Request $request){
        Carbon::setLocale('es');

        $fechaActual = now()->month; 
        $mes = Carbon::now()->monthName;  
        $anio = Carbon::now()->year;  

        $fechaActual = now()->month; // Obtiene la fecha actual en formato 'Y-m-d'

        //Pedidos realizados en el mes actual

        $pedidosMesActual = DB::select(
            "
            SELECT 
    COUNT(*) AS total_pedidos,
    COALESCE(SUM(total_pedido),0) AS total_dinero
FROM pedidos
WHERE MONTH(created_at) = ?;
            ",
            [$fechaActual]
        );

        //Productos vendidos en el mes actual
        $productosMesActual = DB::select(
            "
            SELECT 
    COALESCE(SUM(cantidad_unitaria),0) AS total_productos_vendidos
FROM detalles_pedido dp
JOIN pedidos p ON p.id = dp.idPedido
WHERE MONTH(p.created_at) = ?;
            ",
            [$fechaActual]
        );

        $metricasVentasMensuales = DB::select(
            "
            SELECT 
    u.id,
    u.name,
    u.second_name,
    
    -- Cantidad de pedidos
    (
        SELECT COUNT(*)
        FROM pedidos p
        WHERE p.idUsuario = u.id
        AND MONTH(p.created_at) = ?
    ) AS total_pedidos,
    
        -- Total productos vendidos
    (
        SELECT COALESCE(SUM(dp.cantidad_unitaria),0)
        FROM pedidos p
        JOIN detalles_pedido dp ON dp.idPedido = p.id
        WHERE p.idUsuario = u.id
        AND MONTH(p.created_at) = ?
    ) AS total_productos_vendidos,
    
    -- Total dinero vendido
    (
        SELECT COALESCE(SUM(p.total_pedido),0)
        FROM pedidos p
        WHERE p.idUsuario = u.id
        AND MONTH(p.created_at) = ?
    ) AS total_dinero_vendido
    
    
FROM users u
ORDER BY u.name;
            ",
            [$fechaActual, $fechaActual, $fechaActual]
        );


        $metricasProductosConPocoStock = DB::select(
            "
            SELECT
id, nombre, cantidad, descripcion 
FROM productos
WHERE cantidad < 5
ORDER BY cantidad
ASC
LIMIT 10;
            "
        );
    

        $fechaRequest = Carbon::parse($request->input('fecha'));

        $mesRequestNombre2 = $fechaRequest->monthName;
        $anioRequest2 = Carbon::parse($request->input('fecha'))->year; 

        $mesRequest = $fechaRequest->month;
        $anioRequest = $fechaRequest->year;

        $productosMasVendidos = DB::select(
            "
            SELECT 
    pr.descripcion,
    pr.nombre AS producto,
    COUNT(DISTINCT dp.idPedido) AS cantidad_pedidos,
    SUM(dp.cantidad_unitaria) AS unidades_vendidas,
    SUM(dp.total_linea) AS dinero_generado
FROM detalles_pedido dp
JOIN productos pr 
    ON pr.id = dp.idProducto
JOIN pedidos p 
    ON p.id = dp.idPedido
WHERE 
    MONTH(p.created_at) = ?
    AND YEAR(p.created_at) = ?
GROUP BY pr.id, pr.nombre
ORDER BY unidades_vendidas DESC
LIMIT 10;
            ",
            [$mesRequest, $anioRequest]
        );


        
        return view('metricas.index', compact('metricasVentasMensuales', 'metricasProductosConPocoStock', 'productosMasVendidos', 'mes', 'anio', 'mesRequestNombre2', 'anioRequest2', 'pedidosMesActual', 'productosMesActual'));
        //$fechaRequest = Carbon::parse($request->input('fecha'));
        
    }
    












/* SECCION METRICAS ------  VENDEDORES ------  */
/* SECCION METRICAS ------  VENDEDORES ------  */
/* SECCION METRICAS ------  VENDEDORES ------  */
/* SECCION METRICAS ------  VENDEDORES ------  */
/* SECCION METRICAS ------  VENDEDORES ------  */
/* SECCION METRICAS ------  VENDEDORES ------  */
/* SECCION METRICAS ------  VENDEDORES ------  */
/* SECCION METRICAS ------  VENDEDORES ------  */
/* SECCION METRICAS ------  VENDEDORES ------  */



    public function metricaVendedor($id){

        /* Para la grafica */

        $anios = Pedidos::selectRaw('YEAR(created_at) as anio')
        ->distinct()
        ->orderBy('anio', 'desc')
        ->pluck('anio');

        $infoFecha = now()->year;

        /*Para las fechas de los datos */
        Carbon::setLocale('es');
        $fechaActual = now()->year;
        $xd = now();
        $mesNombre = $xd->monthName;
        $year = $xd->year;

        $mesFecha = ucfirst($mesNombre.' '.$year);

        // Vendedor
        $vendedor = User::FindOrFail($id);

        // Tabla todos los pedidos vendidos en el mes acutal
        $metricasVendedor = DB::select(
            "
            SELECT 
    u.name AS vendedor,
    u.second_name AS segundo_nombre,
    p.id AS id_pedido,
    p.cajero,
    p.cliente,
    p.total_pedido,
    p.created_at,
    (SELECT COUNT(*)
FROM detalles_pedido dp
WHERE dp.idPedido = p.id) AS cantidad_productos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND YEAR(p.created_at) = ? 
ORDER BY p.created_at DESC;
            ",
            [$id, $fechaActual]);

        //Numero de peddidos vendidos por el vendedor en el mes actual
        $numeroPedidos = DB::select(
            "
            SELECT 
    COUNT(*) AS total_pedidos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND YEAR(p.created_at) = ?;
            ",
            [$id, $fechaActual]
        );

        //cantidad de dinero generado en un mes especifico 
        $dineroGenerado = DB::select(
            "
            SELECT 
    COALESCE(SUM(p.total_pedido),0) AS total_dinero_vendido
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND YEAR(p.created_at) = ?;
            ",
            [$id, $fechaActual]
        );

        //cantidad de productos vendidos en un mes especifico 
        $productosVendidos = DB::select(
            "
            SELECT 
    COALESCE(SUM(dp.cantidad_unitaria),0) AS total_productos_vendidos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
JOIN detalles_pedido dp ON dp.idPedido = p.id
WHERE u.id = ? AND YEAR(p.created_at) = ?;
            ",
            [$id, $fechaActual]
        );


        $anio = Carbon::now()->year;
        // dinero mensuales a lo largo del año
$ventasVendedorMes = Pedidos::select(
            DB::raw("MONTH(created_at)-1 AS mes"),
            DB::raw('COALESCE(SUM(total_pedido),0) as total_pedidos')
            )
            ->whereYear('created_at', $anio)
            ->Where('idUsuario', $id)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();
        
        // ventas mensuales a lo largo del año
$pedidosVendedorMeses = Pedidos::select(
            DB::raw("MONTH(created_at)-1 AS mes"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereYear('created_at', $anio)
            ->Where('idUsuario', $id)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();


$pedidosAnoData = array_fill(0, 12, 0);
            foreach ($pedidosVendedorMeses as $pedido){
                $pedidosAnoData[$pedido['mes']] = $pedido['total_pedidos'];
            }

$ventasAnoData = array_fill(0, 12, 0);
            foreach ($ventasVendedorMes as $pedido){
                $ventasAnoData[$pedido['mes']] = number_format($pedido['total_pedidos']);
            }
$mesesData = ['Enero', 'Febro', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        return view('metricas.metricaVendedor', compact('fechaActual', 'mesFecha', 'metricasVendedor', 'numeroPedidos', 'dineroGenerado', 'pedidosVendedorMeses', 'productosVendidos', 'pedidosAnoData', 'ventasAnoData', 'vendedor', 'anios', 'infoFecha', 'mesesData')); 
        //return $anios; 
        }







            public function graficaVendedorMes(Request $request, $id){
        Carbon::setLocale('es');


        $fechaActual = now()->month;
        $nombreMesActual = now()->monthName;
        $anioActual = now()->year;
        $fechaRequest = Carbon::parse($request->input('fecha'));
        $mesRequest = $fechaRequest->month;
        $mesNombre = $fechaRequest->monthName;
        $year = $fechaRequest->year; 

        $anios = Pedidos::selectRaw('YEAR(created_at) as anio')
        ->distinct()
        ->orderBy('anio', 'desc')
        ->pluck('anio');
        
        
        $mesFecha = ucfirst($nombreMesActual.' '.$anioActual);
        $infoFecha = ucfirst( $mesNombre.' '.$year);

        $vendedor = User::FindOrFail($id);

        // Tabla todos los pedidos vendidos en el mes acutal
        $metricasVendedor = DB::select(
            "
            SELECT 
    u.name AS vendedor,
    u.second_name AS segundo_nombre,
    p.id AS id_pedido,
    p.cajero,
    p.cliente,
    p.total_pedido,
    p.created_at,
    (SELECT COUNT(*)
FROM detalles_pedido dp
WHERE dp.idPedido = p.id) AS cantidad_productos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND MONTH(p.created_at) = ? 
ORDER BY p.created_at DESC;
            ",
            [$id, $mesRequest]);

        //Numero de peddidos vendidos por el vendedor en el mes actual
        $numeroPedidos = DB::select(
            "
            SELECT 
    COUNT(*) AS total_pedidos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND MONTH(p.created_at) = ?;
            ",
            [$id, $mesRequest]
        );

        //cantidad de dinero generado en un mes especifico 
        $dineroGenerado = DB::select(
            "
            SELECT 
    COALESCE(SUM(p.total_pedido),0) AS total_dinero_vendido
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND MONTH(p.created_at) = ?;
            ",
            [$id, $mesRequest]
        );

        //cantidad de productos vendidos en un mes especifico 
        $productosVendidos = DB::select(
            "
            SELECT 
    COALESCE(SUM(dp.cantidad_unitaria),0) AS total_productos_vendidos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
JOIN detalles_pedido dp ON dp.idPedido = p.id
WHERE u.id = ? AND MONTH(p.created_at) = ?;
            ",
            [$id, $mesRequest]
        );


        $anio = Carbon::now()->year;
        // dinero diarioo a lo largo del mes


$pedidosMes = Pedidos::select(
            DB::raw("DAY(created_at) AS dia"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereMonth('created_at', $mesRequest)
            ->whereYear('created_at', $year)
            ->where('idUsuario', $id)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->toArray();
            
$dineroMes = Pedidos::select(
            DB::raw("DATE_FORMAT(created_at, '%e') AS dia"),
            DB::raw('COALESCE(SUM(total_pedido),0) as total_pedidos')
            )
            ->whereMonth('created_at', $mesRequest)
            ->whereYear('created_at', $year)
            ->where('idUsuario', $id)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->toArray();


$mesesData = Carbon::createFromDate($year, $mesRequest)->daysInMonth;

        $pedidosAnoData = array_fill(0, $mesesData, 0);
            foreach ($pedidosMes as $pedido){
                $pedidosAnoData[$pedido['dia']] = number_format($pedido['total_pedidos']);
            }

        $ventasAnoData = array_fill(0, $mesesData, 0);
            foreach ($dineroMes as $pedido){
                $ventasAnoData[$pedido['dia']] = number_format($pedido['total_pedidos']);
            }

        return view('metricas.metricaVendedor', compact('fechaActual', 'mesFecha', 'metricasVendedor', 'numeroPedidos', 'dineroGenerado',  'productosVendidos', 'pedidosAnoData', 'ventasAnoData', 'vendedor', 'mesesData', 'infoFecha', 'anios')); 
        //return $fechaActual;
        }



        public function graficaVendedorAnual(Request $request, $id){
            
        /* Para la grafica */

        $anios = Pedidos::selectRaw('YEAR(created_at) as anio')
        ->distinct()
        ->orderBy('anio', 'desc')
        ->pluck('anio');

        $infoFecha = now()->year;

        $fechaRequest = Carbon::parse($request->input('fecha'));

        $fechaRequest = $fechaRequest->year;

        /*Para las fechas de los datos */
        Carbon::setLocale('es');
        $fechaActual = now()->year;
        $xd = now();
        $mesNombre = $xd->monthName;
        $year = $xd->year;

        $mesFecha = ucfirst($mesNombre.' '.$year);

        // Vendedor
        $vendedor = User::FindOrFail($id);

        // Tabla todos los pedidos vendidos en el mes acutal
        $metricasVendedor = DB::select(
            "
            SELECT 
    u.name AS vendedor,
    u.second_name AS segundo_nombre,
    p.id AS id_pedido,
    p.cajero,
    p.cliente,
    p.total_pedido,
    p.created_at,
    (SELECT COUNT(*)
FROM detalles_pedido dp
WHERE dp.idPedido = p.id) AS cantidad_productos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND YEAR(p.created_at) = ? 
ORDER BY p.created_at DESC;
            ",
            [$id, $fechaRequest]);

        //Numero de peddidos vendidos por el vendedor en el mes actual
        $numeroPedidos = DB::select(
            "
            SELECT 
    COUNT(*) AS total_pedidos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND YEAR(p.created_at) = ?;
            ",
            [$id, $fechaRequest]
        );

        //cantidad de dinero generado en un mes especifico 
        $dineroGenerado = DB::select(
            "
            SELECT 
    COALESCE(SUM(p.total_pedido),0) AS total_dinero_vendido
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
WHERE u.id = ? AND YEAR(p.created_at) = ?;
            ",
            [$id, $fechaRequest]
        );

        //cantidad de productos vendidos en un mes especifico 
        $productosVendidos = DB::select(
            "
            SELECT 
    COALESCE(SUM(dp.cantidad_unitaria),0) AS total_productos_vendidos
FROM pedidos p
JOIN users u ON u.id = p.idUsuario
JOIN detalles_pedido dp ON dp.idPedido = p.id
WHERE u.id = ? AND YEAR(p.created_at) = ?;
            ",
            [$id, $fechaRequest]
        );


        $anio = Carbon::now()->year;
        // dinero mensuales a lo largo del año
$ventasVendedorMes = Pedidos::select(
            DB::raw("MONTH(created_at)-1 AS mes"),
            DB::raw('COALESCE(SUM(total_pedido),0) as total_pedidos')
            )
            ->whereYear('created_at', $anio)
            ->Where('idUsuario', $id)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();
        
        // ventas mensuales a lo largo del año
$pedidosVendedorMeses = Pedidos::select(
            DB::raw("MONTH(created_at)-1 AS mes"),
            DB::raw('COUNT(*) as total_pedidos')
            )
            ->whereYear('created_at', $anio)
            ->Where('idUsuario', $id)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();


$pedidosAnoData = array_fill(0, 12, 0);
            foreach ($pedidosVendedorMeses as $pedido){
                $pedidosAnoData[$pedido['mes']] = $pedido['total_pedidos'];
            }

$ventasAnoData = array_fill(0, 12, 0);
            foreach ($ventasVendedorMes as $pedido){
                $ventasAnoData[$pedido['mes']] = number_format($pedido['total_pedidos']);
            }
$mesesData = ['Enero', 'Febro', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        return view('metricas.metricaVendedor', compact('fechaActual', 'mesFecha', 'metricasVendedor', 'numeroPedidos', 'dineroGenerado', 'pedidosVendedorMeses', 'productosVendidos', 'pedidosAnoData', 'ventasAnoData', 'vendedor', 'anios', 'infoFecha', 'mesesData')); 
        //return $fechaRequest; 
        }
}


