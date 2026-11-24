<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ public_path('css/ventas/factura.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ventas/factura.css') }}">
    <title>Proyecto</title>
</head>

@php
    if ($pedido->tipo_descuento == 'Porcentual') {
        $signo = '%';
    } elseif ($pedido->tipo_descuento == 'Monto fijo') {
        $signo = 'C$';
    } else {
        $signo = '';
    }

    $descontado = $pedido->total_pedido - $pedido->subtotal_pedido;
@endphp
<body>
    <div class="factura-contenedor">

        {{-- SECCIÓN 1: Encabezado de Factura --}}
        <table class="tabla-invisible encabezado-tabla">
            <tr>
                <td>
                    <h1 class="marca-titulo">FACTURA</h1>
                </td>
                <td class="text-right">
                    <span class="badge-factura">FACT-#0{{ $pedido->id }}</span>
                </td>
            </tr>
        </table>


        {{-- SECCIÓN 2 y 3: Bloques de Información (Empresa, Pedido, Cliente, Facturación) --}}
        <table class="tabla-invisible" style="margin-bottom: 20px;">
            <tr>
                <td style="width: 48%; vertical-align: top;">
                    <div class="bloque-info">
                        <h4>Información de la empresa</h4>
                        <div class="info-contenedor">
                            <span class="encabezado-info">Nombre</span>
                            <p>{{ $empresa->nombre }}</p>
                        </div>
                        <div class="info-contenedor">
                            <span class="encabezado-info">Número RUC</span>
                            <p>{{ $empresa->ruc }}</p>
                        </div>
                    </div>

                    <div class="bloque-info" style="margin-top: 10px;">
                        <h4>Información del pedido</h4>
                        <div class="info-contenedor">
                            <span class="encabezado-info">Número de pedido</span>
                            <p>#{{ $pedido->id }}</p>
                        </div> 
                        <div class="info-contenedor">
                            <span class="encabezado-info">Fecha de emisión</span>
                            <p>{{ $fecha }}</p>
                        </div> 
                    </div>
                </td>

                <td style="width: 4%;"></td>

                <td style="width: 48%; vertical-align: top;">
                    <div class="bloque-info">
                        <h4>Información del cliente</h4>
                        <div class="info-contenedor">
                            <span class="encabezado-info">Nombre</span>
                            <p>{{ $pedido->cliente }}</p>
                        </div>
                    </div>

                    <div class="bloque-info" style="margin-top: 10px;">
                        <h4>Información de facturación</h4>
                        <div class="info-contenedor">
                            <span class="encabezado-info">Vendedor</span>
                            <p>{{ $pedido->user->name }} {{ $pedido->user->second_name }}</p>
                        </div>
                        <div class="info-contenedor">
                            <span class="encabezado-info">Cajero</span>
                            <p>{{ $pedido->cajero }}</p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>


        {{-- SECCIÓN 4: Tabla de Detalles de Productos --}}
        <div class="tabla-titulo-barra">
            <h4>Detalles de los productos</h4>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th class="th-producto" style="width: 45%;">Producto</th>
                    <th style="width: 15%; text-align: center;">Unidades</th>
                    <th style="width: 20%; text-align: right;">Precio Unitario</th> 
                    <th style="width: 20%; text-align: right;">Total de línea</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $detalle)
                    <tr>
                        <td class="th-producto">
                            <strong style="font-size: 1.05em; color: #1e293b;">{{ ucfirst($detalle->nombre) }}</strong>
                            <div class="descripcion">{{ ucfirst($detalle->descripcion) }}</div>
                        </td>
                        <td style="text-align: center;">{{ $detalle->cantidad_unitaria }}</td>
                        <td class="text-right">C$ {{ number_format($detalle->precio_compra, 2) }}</td>
                        <td class="text-right" style="font-weight: bold; color: #1e293b;">C$ {{ number_format($detalle->total_linea, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        {{-- SECCIÓN 5: Bloques de Totales e Información de Contacto Inferior --}}
        <table class="tabla-invisible no-romper" style="margin-top: 25px;">
            <tr>
                <td style="width: 48%; vertical-align: top;">
                    <div class="detalles-seccion">
                        <table class="tabla-invisible">
                            <tr>
                                <td><span class="encabezado-info" style="font-size: 1em; font-weight: bold; color: #1e293b;">Descuento aplicado</span></td>
                                <td class="text-right">
                                    @if ($signo == '')
                                        <span class="descuento-toggle no">No</span>
                                    @else
                                        <span class="descuento-toggle si">Sí</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        
                        <table class="tabla-invisible" style="margin-top: 8px; border-top: dashed 1px #e0e0e0; padding-top: 5px;">
                            <tr>
                                <td><span class="descripcion">Descuento por promoción</span></td>
                                <td class="text-right"><span style="font-weight: bold; color: #dd5353;">{{ $pedido->valor_descuento }} {{ $signo }}</span></td>
                            </tr>
                        </table>
                    </div>

                    <div style="margin-top: 15px; padding-left: 5px;">
                        <div class="info-contenedor">
                            <span class="encabezado-info">Teléfono:</span> <span style="font-size: 0.9em; color:#1e293b;">{{ $empresa->telefono }}</span>
                        </div>
                        <div class="info-contenedor" style="margin-top: 3px;">
                            <span class="encabezado-info">Correo:</span> <span style="font-size: 0.9em; color:#1e293b;">{{ $empresa->correo }}</span>
                        </div>
                    </div>
                </td>

                <td style="width: 4%;"></td>

                <td style="width: 48%; vertical-align: top;">
                    <div class="detalles-seccion izquierda">
                        <table class="tabla-invisible">
                            <tr>
                                <td style="padding-bottom: 6px;"><span class="descripcion" style="font-size: 0.95em;">Subtotal</span></td>
                                <td class="text-right" style="padding-bottom: 6px;"><span style="color: #1e293b; font-weight: 500;">C$ {{ number_format($pedido->subtotal_pedido) }}</span></td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 8px;"><span class="descripcion" style="font-size: 0.95em;">Descuento</span></td>
                                <td class="text-right" style="padding-bottom: 8px;"><span style="color: #dd5353;">- C$ {{ $descontado }}</span></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 10px; border-top: dashed 1px #bebebe;">
                                    <span style="font-weight: bold; color: #000; font-size: 1.05em;">Total final</span>
                                </td>
                                <td class="text-right" style="padding-top: 10px; border-top: dashed 1px #bebebe;">
                                    <span class="monto-total">C$ {{ number_format($pedido->total_pedido) }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style="margin-top: 15px; padding-left: 5px;">
                        <span class="encabezado-info">Dirección física:</span>
                        <p style="font-size: 0.85em; color: #475569; margin-top: 2px; line-height: 1.3;">{{ $empresa->direccion }}</p>
                    </div>
                </td>
            </tr>
        </table>


        {{-- PIE DE PAGINA --}}
        <footer class="footer">
            <span class="descripcion" style="font-style: Inter;">Productos con IVA incluido. Gracias por su compra.</span>
        </footer>

    </div>
</body>
</html>