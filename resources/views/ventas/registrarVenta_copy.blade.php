@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/ventas/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/ventas/modal.css') }}">

@endpush

@php
    use Carbon\Carbon;
@endphp

@section('content')

<div class="contendor">
    <header>
        <h1>Registrar un nuevo pedido</h1>
    </header>
    
    <h2 class="tutulo-seccion">Rellena para registrar un nuevo pedido</h2> 
    <div class="formulario-contenedor">
        <form action="{{ route('ventas.crearPedido') }}" method="POST" id="pedidoForm">
            @csrf
            <div id="campos-formulario-contenedor">
                <div class="detalles-contenedor productClass">
                    <div class="detalles-producto-contenedor">
                        <div class="input-contenedor nombre">
                            <label>Nombre</label>
                            <input type="text" class="product" id="producto" name="detalles[0][producto]" required placeholder="Nombre del Producto">
                            <input type="number" class="productID" id="idProducto_0" name="detalles[0][id]" hidden>
                        </div>
                        <div class="input-contenedor">
                            <label>Unidades</label>
                            <input type="number" class="cantidad-input" id="cantidadU_0" name="detalles[0][cantidad]" required oninput="calcularTotal(0)" placeholder="Cantidad">
                        </div>
                        <div class="input-contenedor">
                            <label>Precio unitario</label>
                            <input type="number" class="precio-input" id="precioU_0" name="detalles[0][precioU]" step="1" required oninput="calcularTotal(0)" placeholder="Precio Unitario">
                        </div>
                        <div class="input-contenedor">
                            <label>Total del producto</label>
                            <input type="number" class="precioT" id="precioT_0" name="detalles[0][precioT]" step="1" required readonly placeholder="Precio Total"> 
                        </div>
                    </div>
                    <button type="button" class="boton boton-quitar" onclick="borrarProducto(this)">Borrar</button>

                    <hr>
                </div>
            </div>


            <div class="detalles-formulario-contenedor">
                    <div class="input-contenedor contenedor-detalle">
                    <label>Subtotal</label>
                    <input type="text" class="form-control" id="subtotal" name="subtotal" readonly placeholder="Valor subtotal">
                </div>
                <div class="input-contenedor contenedor-detalle">
                    <label>Total</label>
                    <input type="number" class="form-control" id="total" name="total" readonly placeholder="Valor total">
                </div>
                <div class="input-contenedor contenedor-detalle">
                    <label>Tipo de descuento:</label>
                    <select name="opcion" id="opcion">
                        <option value="Ninguno">N/A</option>
                        <option value="Porcentual">Porcentual</option>
                        <option value="Monto fijo">Monto fijo</option>
                    </select>
                </div>
                <div class="input-contenedor contenedor-detalle">
                    <label>Valor de descuento</label>
                    <input type="number" class="form-control" id="descuento" name="descuento" step="1"  required>
                </div>
            </div>
            <hr>
            
            <div class="detalles-formulario-contenedor">
                <div class="input-contenedor contenedor-detalle campo-formulario-vendedor">
                    <label>Vendedor</label>
                    <input type="text" id="vendedor" name="vendedor" required autocomplete="off" class="vendedor" placeholder="Ninguno">
                    <input type="number" class="idvendedor" name="idvendedor" id="idvendedor" value="{{ Auth::user()->id }}" hidden>
                </div>
                <div class="input-contenedor contenedor-detalle">
                    <label>Cliente</label>
                    <input type="text" id="cliente" name="cliente" required autocomplete="off"  placeholder="Nombre del cliente">
                </div>
                <div class="input-contenedor">
                    <input type="text" name="cajero" id="cajero" value="{{ Auth::user()->name }} {{ Auth::user()->second_name }}" hidden> 
                </div>
            </div>

            <div class="botones-formulario-contenedor">
                <button type="submit" class="boton boton-enviar">Registrar</button>
                <button type="button" class="boton boton-agregar">
                    <span class="material-symbols-outlined">add_2</span>
                </button>
            </div>
        </form>
    </div>

    <div class="seccion-tabla-contenedor">
        <div class="encabezado-tabla-contenedor">
            <h2 class="tutulo-seccion">Productos registrados</h2>
        </div>

        <div class="tabla-contenedor">
            <table class ="table">
                <thead>
                    <tr>
                        <th>Pedido ID</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($ventas as $venta)
                        @php
                            $ventaFormat = $venta->created_at;
                            $ventaFormat = Carbon::parse($ventaFormat)->format('d/m/y');
                            $horaFormat = Carbon::parse($venta->created_at)->format('H:i:s');
                        @endphp
                        <tr>
                            <td>{{ $venta->id }}</td>
                            <td>{{ $venta->name }}</td>
                            <td>C$ {{ $venta->total_pedido }}</td>
                            <td>
                                {{ $ventaFormat }} - <span class="horaVenta">{{ $horaFormat }}</span>
                            </td>
                            <td>
                                <a class="detallesBoton" href="{{ route('ventas.detalles', $venta->id) }}">Ver detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    

    {{-- Modal --}}
    <div id="modalPreview" class="modal">
        <div class="modal-content">
            <h2>Previsualizacion de factura</h2>

            <div id="previewContenido"></div>
            <div id="previewDetalles"></div>
            
            <div class="modal-buttons">
                <button id="confirmarBtn" class="boton boton-enviar">Confirmar</button>
                <button id="cancelarBtn" class="boton boton-cerrar">Cerrar</button>  
            </div>
        </div>
    </div>
</div>



<!-- JQuery y jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">  
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


<script>

/*Aplicar autocompletado para cada fila*/

function aplicarAutocompletadoProducto(row) {
        row.find('.product').autocomplete({
            source: function(request, response){
        $.ajax({
            url: "{{ route('ventas.autocompletar') }}",
            dataType: "json",
            data: {
                term: request.term
            },
            success: function(data){
                response(data);
            }
        })},
            select: function(event, ui) {
                $(this).val(ui.item.value);
                row.find('.precio-input').val(ui.item.precio);
                row.find('.cantidad-input').val('');
                row.find('.precioT').val('');
                row.find('#idProducto_0').val(ui.item.id);
                return false;
            }
        });
    }

function aplicarAutocompletadoVendedor(row2) {
        row2.find('.vendedor').autocomplete({
            source: function(request, response){
        $.ajax({
            url: "{{ route('ventas.vendedorAutocompletar') }}",
            dataType: "json",
            data: {
                term: request.term
            },
            success: function(data){
                response(data);
            }
        })},
            select: function(event, ui) {
                $(this).val(ui.item.value);
                row2.find('.vendedor').val(ui.item.value);
                row2.find('.idvendedor').val(ui.item.id);
                return false;
            }
        });
    }



    $(document).ready(function() {
    let row2 = $('.campo-formulario-vendedor');
    aplicarAutocompletadoVendedor(row2);

    aplicarAutocompletadoProducto($('.productClass').first());
    document.getElementById('descuento').value = 0;
    document.getElementById('descuento').readOnly = true;

    // Evento para agregar una nueva fila
    let index = 1;
    $('.botones-formulario-contenedor').on('click', '.boton-agregar', function() {
        
        const nuevaFila = $(`
            <div class="detalles-contenedor productClass">
                    <div class="detalles-producto-contenedor ">
                        <div class="input-contenedor nombre">
                            <label>Nombre</label>
                            <input type="text" class="product" id="producto" name="detalles[${index}][producto]" required placeholder="Nombre del Producto">
                            <input type="number" class="" id="idProducto_0" name="detalles[${index}][id]" hidden>
                        </div>
                        <div class="input-contenedor">
                            <label>Unidades</label>
                            <input type="number" class="cantidad-input" id="cantidadU_${index}" name="detalles[${index}][cantidad]" required oninput="calcularTotal(${index})" placeholder="Cantidad">
                        </div>
                        <div class="input-contenedor">
                            <label>Precio unitario</label>
                            <input type="number" class="precio-input" id="precioU_${index}" name="detalles[${index}][precioU]" step="1" required oninput="calcularTotal(${index})" placeholder="Precio Unitario">
                        </div>
                        <div class="input-contenedor">
                            <label>Total del producto</label>
                            <input type="number" class="precioT" id="precioT_${index}" name="detalles[${index}][precioT]" step="1" required readonly placeholder="Precio Total"> 
                        </div>
                        </div>
                        <button type="button" class="boton boton-quitar" onclick="quitarProducto(this)">Eliminar</button>

                    <hr>
            </div>
        `);
        
        index++;
        $('#campos-formulario-contenedor').append(nuevaFila);
        aplicarAutocompletadoProducto(nuevaFila);
        
    });
});

    
/* Remover un producto del formulario */
function quitarProducto(boton) {
    const item = boton.closest('.productClass');
    item.remove();
    calcularSubtotal();
    calcularTotalFinal()
}

function borrarProducto(boton) {
    
    document.getElementById('producto').value = '';
    document.getElementById('idProducto_0').value = '';
    document.getElementById('cantidadU_0').value = '';
    document.getElementById('precioU_0').value = '';
    document.getElementById('precioT_0').value = '';

    calcularSubtotal();
    calcularTotalFinal()
}


// Función para calcular el total-linea de un producto
function calcularTotal(index) {
        const cantidad = document.getElementById(`cantidadU_${index}`);
        const precioUnitario = document.getElementById(`precioU_${index}`);
        const precioTotal = document.getElementById(`precioT_${index}`);
        const cantidadVal = parseFloat(cantidad.value) || 0;
        const precioVal = parseFloat(precioUnitario.value) || 0;
        precioTotal.value = (cantidadVal * precioVal);
    }




// Función para calcular el subtotal de todos los productos
function calcularSubtotal() {
    let total = 0;

    $('.productClass').each(function () {
        const row = $(this);
        const precio = parseFloat(row.find('.precio-input').val()) || 0;
        const cantidad = parseInt(row.find('.cantidad-input').val()) || 0;
        const subtotal = precio * cantidad;

        row.find('.subtotal-input').val(subtotal.toFixed(2));
        total += subtotal;
    });

    $('#subtotal').val(total);
    $('#total').val(total);
}

// Actualizar el total al hacer un cambio en la cantidad o precio
$('#campos-formulario-contenedor').on('input', '.cantidad-input',  function () {
    calcularSubtotal();
    calcularTotalFinal()
});
$('#campos-formulario-contenedor').on('input', '.precio-input',  function () {
    calcularSubtotal();
    calcularTotalFinal()
});



// Evento para habilitar o deshabilitar el campo de descuento
$('#opcion').on('change', function() {
    if ($(this).val() == "Ninguno") {
        $('#descuento').attr('placeholder', '');
        $('#descuento').val('0').prop('readonly', true); // Limpia el input

        let subtotal = parseFloat($('#subtotal').val()) || 0;
        $('#total').val(subtotal)
    }
    else if ($(this).val() == "Porcentual") {
        $('#descuento').attr('placeholder', 'Porcentaje de descuento');
        $('#descuento').val('').prop('readonly', false); // Limpia el input

        let subtotal = parseFloat($('#subtotal').val()) || 0;
        $('#total').val(subtotal)

    } else if ($(this).val() == "Monto fijo") {
        $('#descuento').attr('placeholder', 'Monto de descuento');
        $('#descuento').val('').prop('readonly', false); // Limpia el input

        let subtotal = parseFloat($('#subtotal').val()) || 0;
        $('#total').val(subtotal)
    }
});


// Función para calcular el total final
function calcularTotalFinal() {
    let subtotal = parseFloat($('#subtotal').val()) || 0;
    let descuento = parseFloat($('#descuento').val()) || 0;
    let tipo = $('#opcion').val();
    let total = subtotal;

    if (tipo == "Porcentual") {
        total = subtotal - (subtotal * (descuento / 100));
        total = Math.round(total);
    } else if (tipo == "Monto fijo") {
        total = subtotal - descuento;
    }

    // Evitar que el total sea negativo
    if (total < 0) total = 0;
    $('#total').val(total);
}

$('#descuento, #subtotal, #tipoDescuento').on('input change', function() {
    calcularTotalFinal();
});

</script>


{{-- Modal JS --}}
<script>

const formulario = document.getElementById('pedidoForm');

const modal = document.getElementById('modalPreview');
const previewContenido = document.getElementById('previewContenido');
const previewDetalles = document.getElementById('previewDetalles');




formulario.addEventListener('submit', function(e) {
    e.preventDefault();


    const subtotal = document.getElementById('subtotal').value;
const total = document.getElementById('total').value;
const tipoDescuento = document.getElementById('opcion').value;
const valorDescuento = document.getElementById('descuento').value;
const vendedor = document.getElementById('vendedor').value;
const cliente = document.getElementById('cliente').value;

    const productos = document.querySelectorAll('.detalles-producto-contenedor');

    let html = `
    <p><strong>Vendedor:</strong> ${vendedor}</p>
    <p><strong>Cliente:</strong> ${cliente}</p>
    
    <div class="tabla-contenedor">
     <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>`;

    productos.forEach(function(producto) {

        const nombre = producto.querySelector('.product').value;
        const cantidad = producto.querySelector('.cantidad-input').value;
        const precioU = producto.querySelector('.precio-input').value;
        const precioT = producto.querySelector('.precioT').value;

        html += `
            <tr>
                <td>${nombre}</td>
                <td>${cantidad}</td>
                <td>C$ ${precioU}</td>
                <td>C$ ${precioT}</td>
            </tr>`;

        });
        
        
        html+= `
        </tbody>
        </table>
        `;
        
         previewContenido.innerHTML = html;

         html2 = `
         <p><strong>Subtotal:</strong> C$ ${subtotal}</p>
        <p><strong>Descuento:</strong> ${tipoDescuento} - ${valorDescuento}</p>
        <p><strong>Total a pagar:</strong> C$ ${total}</p>
            `;
    
        previewDetalles.innerHTML = html2;

        modal.style.display = 'flex';
});



document.getElementById('confirmarBtn')
.addEventListener('click', function(){
    formulario.submit();
});

document.getElementById('cancelarBtn')
.addEventListener('click', function(){
    modal.style.display = 'none';
});

</script>


@endsection