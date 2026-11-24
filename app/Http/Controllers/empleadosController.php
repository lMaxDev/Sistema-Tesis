<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;

class empleadosController extends Controller
{
    public function index()
    {
        $empleados = User::all();
        $empresa = Empresa::query()->first() ?? new Empresa();

        return view('empleados.index', compact('empleados', 'empresa'));
        
    }

    public function crearEmpleado(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'string|max:255',
            'email' => 'required|string|email',
            'password' => 'required|string|min:4',
            'opcion' => 'required|string|max:100',
            'telefono' => 'string|max:10',
            'direccion' => 'string|max:200',
        ],
        [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
            'opcion.required' => 'El campo cargo es obligatorio.',
        ]);

        $user = new User();

        $user->name = $request->nombre;
        $user->second_name = $request->apellido;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->telefono = $request->telefono;
        $user->direccion = $request->direccion;
        $user->cargo = $request->opcion;
        
        $user->save();
        
        return redirect()->back()->with('success', 'Empleado creado exitosamente.');
    }

    // Guarda o actualiza los datos
    public function empresaUpdate(Request $request)
    {
        // Validamos los datos recibidos
        $request->validate([
            'ruc'       => 'required|string',
            'nombre'    => 'required|string',
            'correo'    => 'required|email',
            'telefono'  => 'nullable|string',
            'direccion' => 'nullable|string',
        ]);

        // Si existe el ID 1, lo actualiza. Si no, lo crea con ID 1.
        Empresa::updateOrCreate(
            ['id' => 1], 
            $request->only(['ruc', 'nombre', 'correo', 'telefono', 'direccion'])
        );

        // Redirecciona de vuelta al formulario con un mensaje de éxito
        return redirect()->back()->with('status', 'Datos de la empresa guardados correctamente.');
    }
}
