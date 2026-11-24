<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Detalles;
use App\Models\Empresa;
use App\Models\Pedidos;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Pdf;

class ventasController extends Controller
{
    public function index()
    {
        $ventas = DB::select('
            	SELECT 
            	p.id,
                u.name,
                u.second_name,
                p.total_pedido,
                p.cliente,
                p.created_at,
                p.subtotal_pedido,
                p.tipo_descuento,
                p.valor_descuento
            FROM
            	Pedidos as p
            JOIN
            	users as u ON u.id = p.idUsuario
            ORDER BY 
            p.id DESC
            LIMIT 8;
        ');

        
        return view('ventas.index', compact('ventas'));
    }



    public function registrarVenta()
    {
        $ventas = DB::select('
            	SELECT 
            	p.id,
                u.name,
                u.second_name,
                p.total_pedido,
                p.created_at,
                p.subtotal_pedido,
                p.tipo_descuento,
                p.valor_descuento
            FROM
            	Pedidos as p
            JOIN
            	users as u ON u.id = p.idUsuario
            ORDER BY 
            p.id DESC
            LIMIT 8;
        ');

        return view('ventas.registrarVenta', compact('ventas'));
    }

    public function ventasAutocompletar(Request $request)
    {
        $term = $request->get('term');

        $querys = Productos::where('nombre', 'LIKE', '%'.$term.'%')->get();

        $data = [];

        foreach ($querys as $query) {
            $data[] = [
                'label' => '#' . $query->id .' '. $query->nombre . ' - ' . $query->descripcion,
                'value' => $query->nombre,
                'precio' => $query->precio,
                'descripcion' => $query->descripcion,
                'id' => $query->id,
            ];
        };

        return $data;
    }

    public function vendedorAutocompletar(Request $request)
    {
        $term = $request->get('term');

        $querys = DB::table('users')
            ->where('name', 'LIKE', '%' . $term . '%')
            ->get();

        $data = [];

        foreach ($querys as $query) {
            $data[] = [
                'label' => '#' . $query->id . ' ' . $query->name . ' ' . $query->second_name,
                'value' => $query->name. ' ' . $query->second_name,
                'id' => $query->id,
            ];
        }

        return $data;
    }

    public function crearPedido(Request $request)
    {
        $pedido = Pedidos::create([
            'idUsuario' => $request->input('idvendedor'),
            'cajero' => $request->input('cajero'),
            'cliente' => $request->input('cliente'),
            'total_pedido' => $request->input('total'),
            'subtotal_pedido' => $request->input('subtotal'),
            'tipo_descuento' => $request->input('opcion'),
            'valor_descuento' => $request->input('descuento'),  
        ]);

        $detalles = $request->input('detalles');
        $idPedido = $pedido->id;

        foreach ($detalles as $detalle) {
            Detalles::create([
                'idPedido' => $idPedido,
                'idProducto' => $detalle['id'],
                'cantidad_unitaria' => $detalle['cantidad'],
                'precio_compra' => $detalle['precioU'],
                'total_linea' => $detalle['precioT'],
            ]);

            $producto = Productos::find($detalle['id']);

            if ($producto) {
                if ($producto->cantidad - $detalle['cantidad'] <= 0) {
                    $producto->cantidad = 0;
                } else {
                    $producto->cantidad -= $detalle['cantidad'];
                }
                $producto->save();
            }
        };

        return back()->with('success', 'exito');
    }

    public function detalles($id)
    {
        $pedido = Pedidos::find($id);
        $cajero = Pedidos::find('cajero');

        //$detalles = Detalles::where('idPedido', $id)->get();


        $detalles = DB::select('
            SELECT 
                dp.id,
                p.nombre,
                p.descripcion,
                dp.cantidad_unitaria,
                dp.precio_compra,
                dp.total_linea
            FROM 
                Detalles_pedido AS dp
            JOIN 
                Productos AS p ON dp.idProducto = p.id
            WHERE 
                dp.idPedido = ?
        ', [$id]);

        $fecha = Carbon::parse($pedido->created_at)->format('d/m/Y');

        return view('ventas.detalles', compact('pedido', 'detalles', 'fecha'));
    }


    public function buscarPedido(Request $request)
    {
        $pedidos = Pedidos::with('user') 
        ->when($request->id, function ($query, $id) {
            return $query->where('id', $id);
        })
        ->when($request->vendedor, function ($query, $vendedor) {
            // Buscamos en la tabla relacionada 'users'
            return $query->whereHas('user', function ($q) use ($vendedor) {
                $q->where('name', 'LIKE', "%$vendedor%");
            });
        })
        ->when($request->fecha, function ($query, $fecha) {
            return $query->whereDate('created_at', $fecha);
        })
        ->paginate(20)
        ->withQueryString();

    return view('ventas.buscarPedido', compact('pedidos'));
    //return $request->all();

    }


        public function factura($id)
    {
        $pedido = Pedidos::find($id);
        $empresa = Empresa::find(1);

        //$detalles = Detalles::where('idPedido', $id)->get();


        $detalles = DB::select('
            SELECT 
                dp.id,
                p.nombre,
                p.descripcion,
                dp.cantidad_unitaria,
                dp.precio_compra,
                dp.total_linea
            FROM 
                Detalles_pedido AS dp
            JOIN 
                Productos AS p ON dp.idProducto = p.id
            WHERE 
                dp.idPedido = ?
        ', [$id]);

        $fecha = Carbon::parse($pedido->created_at)->format('d/m/Y');

        //return view('ventas.factura', compact('pedido', 'detalles', 'fecha', 'empresa'));
        //return $empresa;    

        $pdf = Pdf::loadView('ventas.factura', compact('pedido', 'detalles', 'fecha', 'empresa'));
        return $pdf->download('Factura_'.$id.'.pdf');
    }

}

