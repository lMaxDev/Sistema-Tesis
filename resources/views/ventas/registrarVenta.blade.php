@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<link rel="stylesheet" href="{{ asset('css/ventas/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/desings.css') }}">
<link rel="stylesheet" href="{{ asset('css/ventas/registrar.css') }}">
<link rel="stylesheet" href="{{ asset('css/ventas/modal.css') }}">
@endpush

@php
    use Carbon\Carbon;
@endphp

@section('content')

<div class="contendor">
    <header>
        <div>
            <h1>Registrar un nuevo pedido</h1> 
            <br>
            <p>Agrega los productos y completa los detalles del pedido</p>
        </div>
    </header>
    
<div class="contenedor-registro">
    <div class="formulario-contenedor">
        <form action="{{ route('ventas.crearPedido') }}" method="POST" id="pedidoForm">
            @csrf
        
        <div class="separador">
            <div class="detalles-contenedor ">
                <div class="tutulo-seccion">
                    <div class="icono-pedidos">
                        <span class="material-symbols-outlined">stack</span>
                    </div>
                    <h4>Rellena para registrar un pedido</h4>
                </div>
                <div class="tabla-contenedor">
                <table class ="table">    
                    <thead>
                        <tr class="tabla-agregar-productos-tr">
                            <th>Producto</th>
                            <th class="th-unidades">Unidades</th>
                            <th class="th-precio">Precio</th> 
                            <th class="th-totalLinea">Tota de linea</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>                    

                    <tbody id="campos-formulario-contenedor">
                        <tr class="productClass">
                            <td class="input-contenedor nombre">
                                <input type="text" class="product" id="producto" name="detalles[0][producto]" required placeholder="Nombre del Producto">
                                <input type="number" class="productID" id="idProducto_0" name="detalles[0][id]" hidden>
                                {{-- <input type="text" class="inputDescripcion" id="descripcion"> --}}
                                <p class="descripcion" id="descripcion"></p>
                            </td>
                            <td class="input-contenedor">
                            
                                <input type="number" class="cantidad-input" id="cantidadU_0" name="detalles[0][cantidad]" required oninput="calcularTotal(0)" placeholder="Cantidad">
                            </td>
                            <td class="input-contenedor">
                            
                                <span class="input-cordoba">C$</span>
                                <input type="number" class="precio-input" id="precioU_0" name="detalles[0][precioU]" step="1" required oninput="calcularTotal(0)" placeholder="Precio Unitario">
                            </td>
                            <td class="input-contenedor">
                                
                                <span class="input-cordoba">C$</span>
                                <input type="number" class="precioT" id="precioT_0" name="detalles[0][precioT]" step="1" required readonly placeholder="Precio Total"> 
                            </td>
                            <td class="input-contenedor">
                                <button type="button" class="botonTabla boton-quitar" onclick="borrarProducto(this)"><span class="material-symbols-outlined">delete</span></button>
                            </td>
                        </tr>

                    </tbody> 
                </table>
                </div>
                    <div class="botones-formulario-contenedor">
                        <button type="button" class="boton boton-agregar">
                            <span class="material-symbols-outlined">add_2</span>
                            <span>Agregar prodcuto</span>
                        </button>
                    </div>
            </div>

            



            <div class="seccion-nombres">
                <div class="tutulo-seccion detalles-del-pedido">
                    <div class="icono-pedidos">
                        <span class="material-symbols-outlined">grain</span>
                    </div>
                    <h4>Detalles del pedido</h4>
                </div>
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
                    <div class="input-contenedor" hidden>
                        <input type="text" name="cajero" id="cajero" value="{{ Auth::user()->name }} {{ Auth::user()->second_name }}" hidden> 
                    </div>
                </div>
            </div>

        </div>

    <div class="contenedor-detalles">
        <div class="tutulo-seccion detalles-del-pedido">
            <div class="icono-pedidos">
                <span class="material-symbols-outlined">receipt_long</span>
            </div>
            <h4>Resumen del pedido</h4>
        </div>

        <div class="input-contenedor contenedor-detalle contenedor-flex">
            <label>Subtotal</label>
            <span class="input-cordoba2">C$</span>
            <input type="text" class="form-control" id="subtotal" name="subtotal" readonly placeholder="0"> 
        </div>
        <hr>
        <div class="input-contenedor contenedor-detalle contenedor-flex descuentos">
            <label>Descuento</label>
            <span class="input-cordoba2 input-cordoba2-descuento">C$</span>
            <input type="number" class="form-control" id="descontado" name="descontado">
        </div>
        <div class="input-contenedor contenedor-detalle contenedor-flex descuentos">
            <label>Tipo de descuento</label>
            <select name="opcion" id="opcion">
                <option value="Ninguno">N/A</option>
                <option value="Porcentual">Porcentual</option>
                <option value="Monto fijo">Monto fijo</option>
            </select>
        </div>
        <div class="input-contenedor contenedor-detalle contenedor-flex descuentos">
            <label>Valor de descuento</label>
            <input type="number" class="form-control" id="descuento" name="descuento" step="1"  required>
        </div>
        <hr>
        <div class="input-contenedor contenedor-detalle contenedor-flex">
            <label>Total</label>
            <span class="input-cordoba2 input-cordoba2-total">C$</span>
            <input type="number" class="form-control" id="total" name="total" readonly placeholder="0">
        </div>
        
        <div class="botones-formulario-contenedor">
            <button type="submit" class="boton boton-enviar" form="pedidoForm">Registrar pedido</button>
        </div>
    </div> 

        </form>
    </div>
</div>


    <div class="seccion-tabla-contenedor">
        <div class="encabezado-tabla-contenedor">
            <h2 class="tutulo-seccion">Ventas recientes</h2>
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
                            <td>{{ $venta->name }} {{ $venta->second_name }}</td>
                            <td class="dinero">C$ {{ number_format($venta->total_pedido) }}</td>
                            <td>
                                {{ $ventaFormat }} - <span class="horaVenta">{{ $horaFormat }}</span>
                            </td>
                            <td>
                                <a class="detallesBoton" href="{{ route('ventas.detalles', $venta->id) }}">Ver detalles <span class="material-symbols-outlined">keyboard_arrow_right</span></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    

    {{-- Modal --}}
    <div id="modalPreview" class="modal">
        <div class="modal-sub-contenedor modal-content">
            <div class="tituloModal-contenedor">  
                <div class="detalles-del-pedido">
                    <div class="icono-pedidos iconoModal">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </div>
                    <div>
                        <h3>Previsualizacion de factura</h4>
                        <br>
                        <p class="textoModal">Revisa que los datos de la factura esten correctos antes de confirmar</p>
                    </div>
                </div>
            </div>

            <div class="detallesVentaModal-contenedor">
                <div id="nombreModal-contenedor"></div>

                <div id="previewContenido"></div>

                <div id="previewDetalles"></div>
            </div>

            <div class="botonesModal-contenedor">
                <button id="cancelarBtn" class="btn-cancelarModal">Cancelar</button>
                <button id="confirmarBtn" class="btn-ConfirmarModal">Confirmar y registrar</button>
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
                row.find('.cantidad-input').val(1);
                row.find('.precioT').val(ui.item.precio);
                row.find('#idProducto_0').val(ui.item.id);
                row.find('.descripcion').text(ui.item.descripcion);
                calcularSubtotal();
                calcularTotalFinal();
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
            <tr class="productClass">
                <td class="input-contenedor nombre">
                    <input type="text" class="product" id="producto" name="detalles[${index}][producto]" required placeholder="Nombre del Producto">
                    <input type="number" class="" id="idProducto_0" name="detalles[${index}][id]" hidden>
                    <p class="descripcion" id="descripcion"></p>
                </td>
                <td class="input-contenedor">
                    
                    <input type="number" class="cantidad-input" id="cantidadU_${index}" name="detalles[${index}][cantidad]" required oninput="calcularTotal(${index})" placeholder="Cantidad">
                </td>
                <td class="input-contenedor">

                    <span class="input-cordoba">C$</span>
                    <input type="number" class="precio-input" id="precioU_${index}" name="detalles[${index}][precioU]" step="1" required oninput="calcularTotal(${index})" placeholder="Precio Unitario">
                </td>
                <td class="input-contenedor">
                    
                    <span class="input-cordoba">C$</span>
                    <input type="number" class="precioT" id="precioT_${index}" name="detalles[${index}][precioT]" step="1" required readonly placeholder="Precio Total"> 
                </td>
                <td class="input-contenedor">
                    <button type="button" class="botonTabla boton-quitar" onclick="quitarProducto(this)"><span class="material-symbols-outlined">delete</span></button>
                </td>
            </tr>
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
        $('#descontado').val(0);

        let subtotal = parseFloat($('#subtotal').val()) || 0;
        $('#total').val(subtotal)
    }
    else if ($(this).val() == "Porcentual") {
        $('#descuento').attr('placeholder', '0');
        $('#descuento').val('').prop('readonly', false); // Limpia el input
        $('#descontado').val(0);

        let subtotal = parseFloat($('#subtotal').val()) || 0;
        $('#total').val(subtotal)

    } else if ($(this).val() == "Monto fijo") {
        $('#descuento').attr('placeholder', '0');
        $('#descuento').val('').prop('readonly', false); // Limpia el input
        $('#descontado').val(0);

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
    let descontado = 0;

    if (tipo == "Porcentual") {
        total = subtotal - (subtotal * (descuento / 100));
        total = Math.round(total);
        descontado = total - subtotal;
    } else if (tipo == "Monto fijo") {
        total = subtotal - descuento;
        descontado = total - subtotal;
    }

    // Evitar que el total sea negativo
    if (total < 0) total = 0;
    $('#total').val(total);
    $('#descontado').val(descontado);

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
const previewNombres = document.getElementById('nombreModal-contenedor');




formulario.addEventListener('submit', function(e) {
    e.preventDefault();


const subtotal = document.getElementById('subtotal').value;
const total = document.getElementById('total').value;
let tipoDescuento = document.getElementById('opcion').value;
const valorDescuento = document.getElementById('descuento').value;
const valorDescontado = document.getElementById('descontado').value;
const vendedor = document.getElementById('vendedor').value;
const cliente = document.getElementById('cliente').value;

if (tipoDescuento == 'Ninguno') {
    tipoDescuento = '';
}
if (tipoDescuento == 'Monto fijo') {
    tipoDescuento = ' C$';
}
if (tipoDescuento == 'Porcentual') {
    tipoDescuento = ' %';
}

    const productos = document.querySelectorAll('.productClass');

    let html3 = `
                    <div class="nombreModal">
                        <div class="icono-pedidos icono-nombres">
                            <span class="material-symbols-outlined">Person</span>
                        </div>
                        <div>
                            <span>Vendedor</span>
                            <br>
                            <span>${vendedor}</span>
                        </div>
                    </div>
                    <div class="nombreModal">
                        <div class="icono-pedidos icono-nombres">
                            <span class="material-symbols-outlined">Group</span>
                        </div>
                        <div>
                            <span>Cliente</span>
                            <br>
                            <span>${cliente}</span>
                        </div>
                    </div>
    `;

    let html = `
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

                <div class="DetallesModal-contenedor borde">
                    <div class="DetalleModal">
                        <span class="DetalleModal-subtotal">Subtotal</span>
                        <span>C$ ${subtotal}</span>
                    </div>
                    <div class="DetalleModal">
                        <span>Descuento (${valorDescuento}${tipoDescuento})</span>
                        <span class="DetalleModal-descontado">C$ ${valorDescontado}</span>
                    </div>
                </div>
                <div class="DetallesModal-contenedor">
                    <div class="DetalleModal Detalle-total-contenedor">
                        <span>Total a pagar</span>
                        <span class="DetalleModal-total">C$ ${total}</span>
                    </div>
                </div>


            `;
    
        previewDetalles.innerHTML = html2;
        previewNombres.innerHTML = html3;

        modal.style.display = 'flex';
});



document.getElementById('confirmarBtn')
.addEventListener('click', function(){
    formulario.submit();
});

document.getElementById('cancelarBtn')
.addEventListener('click', function(e){
    e.preventDefault;
    modal.style.display = 'none';
});

</script> 


@endsection