<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class productosController extends Controller
{
    public function index(Request $request)
    {
        $productos = DB::select('
        Select * from productos
        Order by id DESC
        Limit 20
        ');

        $busqueda = $request->input('buscar');
        $productos = Productos::where('nombre', 'like', '%'.$busqueda.'%')->paginate(20);

        return view('productos.index', compact('productos'));
    }

    public function refrescar()
    {
        $productos = Productos::all();
        $productos = Productos::paginate(20);

        return view('productos.index', compact('productos'));
    }

    public function nuevo(Request $request)
    {

        $productos = DB::select('
        Select * from productos
        Order by id DESC
        Limit 10
        ');

        $busqueda = $request->input('buscar');
        $productos = Productos::where('nombre', 'like', '%'.$busqueda.'%')->paginate(20);

        return view('productos.nuevo', compact('productos'));
    }

    public function crearNuevo(Request $request)
    {
        $productos = $request->input('productos');

        foreach ($productos as $producto) {
            Productos::create([
                'nombre' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad'],
                'descripcion' => $producto['desc'],
            ]);
        }

        return redirect()->route('productos.index')->with('success', 'Productos guardados exitosamente.');
    }

    public function existentes(Request $request)
    {
        $productos = DB::select('
        Select * from productos
        Order by id DESC
        Limit 10
        ');

        $busqueda = $request->input('buscar');
        $productos = Productos::where('nombre', 'like', '%'.$busqueda.'%')->paginate(20);

        return view('productos.existentes', compact('productos'));
    }

    public function agregarExistentes(Request $request)
    {
        $productos = $request->input('productos');

        foreach ($productos as $producto) {
            $productoExistente = Productos::find($producto['id']);
            if ($productoExistente) {
                $productoExistente->cantidad += $producto['cantidad'];
                $productoExistente->save();
            }
        }

        return redirect()->back()->with('success', 'Productos existentes actualizados exitosamente.');
    }

    public function productosAutocompletar(Request $request)
    {
        $term = $request->get('term');

        $querys = Productos::where('nombre', 'LIKE', '%'.$term.'%',)->get();

        $data = [];

        foreach ($querys as $query) {
            $data[] = [
                'label' => '#' . $query->id .' '. $query->nombre . ' - ' . $query->descripcion,
                'value' => $query->nombre,
                'id' => $query->id,
            ];
        };

        return $data;
    } 

    public function editar($id){

        $producto = Productos::findOrFail($id);
        return view('productos.editar', compact('producto'));
    }

    public function actualizar(Request $request, $id){
        
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'cantidad' => 'required|integer',
            'precio' => 'required|numeric',
            'descripcion' => 'required|string|max:255',
        ]);

        $productos = Productos::findOrFail($id);
        $productos->update($validatedData);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente!');
    }

    public function eliminar($id){
        
        $producto = Productos::findOrFail($id);
        $producto->delete();
        return redirect()->back()->with('success', 'Producto Eliminado.');
    }
    
}
