@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/perfil/perfil.css') }}">

@endpush

@section('content')

<div class="contendor">
    <header>
        <h1>Mi perfil</h1>
    </header>

    <div class="perfil-contenedor">
        <div class="informacion-contenedor">
            <div class="foto-perfil-contenedor">
                <img class="perfil-imagen" src="/imagenes/perfil.png" alt="img-perfil">
            </div>
            <div class="datos-perfil-contenedor">
                <span>{{ Auth::User()->name }} {{ Auth::User()->second_name }}</span>
                <span>{{ Auth::User()->cargo }}</span>
                <span>Fecha de inicio {{ $fechaFormateada }}</span>
            </div>
        </div>



        <div class="editar-contenedor">
            <div class="datos-editables-contenedor">
                <div class="dato-editable-contenedor">
                    <label>Correo</label>
                    <input type="email" value="{{ Auth::User()->email }}">
                </div>
                <div class="dato-editable-contenedor">
                    <label>Telefono</label>
                    <input type="text" value="{{ Auth::User()->telefono }}">
                </div>
                <div class="dato-editable-contenedor">
                    <label>
                        Direccion
                    </label>
                    <input type="text" value="{{ Auth::User()->direccion }}">
                </div>
            </div>

            <div class="cerrar-sesion-contenedor">
                <form action="{{ route('perfil.logout') }}" method="get">
                    <button class="boton-cerrar-sesion">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </div>
        

@endsection
