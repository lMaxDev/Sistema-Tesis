@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/empleados/empleados.css') }}">
@endpush

@section('content')

<div class="contendor">
    <header>
        <div>
            <h1>Empresa</h1> 
            <br>
            <p>Gestiona al personal y los datos empresariales</p>
        </div>
    </header>
    
<div class="seccion1">
    <div class="formulario-contenedor">
        <div class="encabezado">
            <span class="material-symbols-outlined">person_add</span>
            <h2 class="tutulo-seccion">Ingresar un nuevo usuario</span></h2>
        </div>
        <form action="{{ route('empleados.crearEmpleado') }}" method="POST">
            @csrf
            <div id="campos-formulario-contenedor">
                <div class="detalles-contenedor">
                    <div class="detalles-producto-contenedor">
                        <div class="input-contenedor">
                            <label>Nombre</label>
                            <input type="text" id="pnombreroducto" name="nombre" required placeholder="Nombre del empleado">                        
                        </div>
                        <div class="input-contenedor">
                            <label>Apellido</label>
                            <input type="text" id="apellido" name="apellido" required placeholder="Apellido del empleado">                        
                        </div>
                        <div class="input-contenedor">
                            <label>Email</label>
                            <input type="email" id="email" name="email" required placeholder="Email">                        
                        </div>
                        <div class="input-contenedor">
                            <label>Contraseña</label>
                            <input type="password" id="password" name="password" required placeholder="Contraseña">                        
                        </div>
                        <div class="input-contenedor">
                            <label>Telefono</label>
                            <input type="number" id="telefono" name="telefono" required placeholder="Numero de telefono">                        
                        </div>
                        <div class="input-contenedor">
                            <label>Direccion</label>
                            <input type="text" id="direccion" name="direccion" required placeholder="Domicilio">                        
                        </div>
                        <div class="input-contenedor">
                            <label>Cargo</label>
                            <select name="opcion" id="opcion" class="">
                                <option onclick="seleccionCargo('Administrador')" value="Administrador">Administrador</option>
                                <option onclick="seleccionCargo('Cajero')" value="Cajero">Cajero</option>
                                <option onclick="seleccionCargo('Vendedor')" value="Vendedor">Vendedor</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="boton boton-enviar">Registrar</button>
        </form>
    </div>
    
    
    
    <div class="tabla-contenedor">
        <h2 class="tutulo-seccion titulo-seccion-tabla"><span class="material-symbols-outlined">table</span>Tabla de empleados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>cargo</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>Direccion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->id }}</td>
                    <td>{{ $empleado->name }} {{ $empleado->second_name }}</td>
                    <td>{{ $empleado->cargo }}</td>
                    <td>{{ $empleado->telefono }}</td>
                    <td>{{ $empleado->email }}</td>  
                    <td>{{ $empleado->direccion }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div class="seccion2">
    <div class="formulario-contenedor">
        <div class="encabezado">
            <span class="material-symbols-outlined">business_center</span>
            <h2 class="tutulo-seccion">Datos de la empresa</span></h2>
        </div>
        <form action="{{ route('empleados.empresaUpdate') }}" method="POST">
            @csrf
            <div id="campos-formulario-contenedor">
                <div class="detalles-contenedor">
                    <div class="detalles-producto-contenedor">
                        <div class="input-contenedor">
                            <label>Nombre</label>
                            <input type="text" name="nombre"
                           value="{{ old('nombre', $empresa->nombre) }}">                      
                        </div>
                        <div class="input-contenedor">
                            <label>Correo electronico</label>
                            <input type="text" name="correo"
                           value="{{ old('correo', $empresa->correo) }}">                         
                        </div>
                        <div class="input-contenedor">
                            <label>Numero RUC</label>
                            <input type="text" name="ruc"
                           value="{{ old('ruc', $empresa->ruc) }}">                         
                        </div>
                        <div class="input-contenedor">
                            <label>Telefono</label>
                            <input type="text" name="telefono" 
                           value="{{ old('telefono', $empresa->telefono) }}">                      
                        </div>
                        <div class="input-contenedor">
                            <label>Direccion</label>
                            <input type="text" name="direccion" 
                           value="{{ old('direccion', $empresa->direccion) }}">                         
                        </div>
                    </div>
                    <button type="submit" class="boton boton-enviar">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>

    
</div>



<Script>

    function seleccionCargo(anything) {
          document.getElementById("opcion").value = anything;
     }

</Script>

@endsection