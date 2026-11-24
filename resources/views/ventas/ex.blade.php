<div id="modalPreview" class="modal">
    <div class="modal-sub-contenedor modal-content">
        <div class="tituloModal-contenedor">  
            <div class="tutulo-seccion detalles-del-pedido">
                <div class="icono-pedidos">
                    <span class="material-symbols-outlined">receipt_long</span>
                </div>
                <div>
                    <h4>Previsualizacion de factura</h4>
                    <br>
                    <p>Revisa que todos los datos de la factura esten correctos antes de confirmar</p>
                </div>
            </div>
        </div>

            <div class="detallesVentaModal-contenedor">
                <div class="nombreModal-contenedor">
                    <div class="nombreModal">
                        <div class="icono-pedidos icono-nombres">
                            <span class="material-symbols-outlined">Person</span>
                        </div>
                        <div>
                            <span>Vendedor</span>
                            <br>
                            <span> </span>
                        </div>
                    </div>
                    <div class="nombreModal">
                        <div class="icono-pedidos icono-nombres">
                            <span class="material-symbols-outlined">Person</span>
                        </div>
                        <div>
                            <span>Vendedor</span>
                            <br>
                            <span> </span>
                        </div>
                    </div>
                </div>

                <div id="previewContenido"></div>

                <div id="previewDetalles">
                        <div class="DetallesModal-contenedor borde">
                            <div class="DetalleModal">
                                <span>Subtotal</span>
                                <span>C$ </span>
                            </div>
                            <div class="DetalleModal">
                                <span>Descuento ${}</span>
                                <span>- C$ </span>
                            </div>
                        </div>
                        <div class="DetallesModal-contenedor">
                            <div class="DetalleModal">
                                <span>Total a pagar</span>
                                <span>C$ </span>
                            </div>
                        </div>
                </div>

            </div>

        </div>

        <div class="botonesModal-contenedor">
            <button id="confirmarBtn" class="btn-cancelarModal">Cancelar</button>
            <button id="cancelarBtn" class="btn-ConfirmarModal">Confirmar y registrar</button>
        </div>
    </div>
</div>