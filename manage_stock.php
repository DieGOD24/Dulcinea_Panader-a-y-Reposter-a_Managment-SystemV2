<?php
require_once("DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `stock_list` where stock_id = '{$_GET['id']}'");
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="stock-form">
        <input type="hidden" name="id" value="<?php echo isset($stock_id) ? $stock_id : '' ?>">
        <div class="col-12">
            <div class="row">
                <div class="col-md-12">
                    <?php if(!isset($_GET['pid'])): ?>
                    <div class="form-group">
                        <label for="product_id" class="control-label">Product</label>
                        <select name="product_id" id="product_id" class="form-select form-select-sm rounded-0 select2" required >
                            <option <?php echo (!isset($product_id)) ? 'selected' : '' ?> disabled>Please Select Here</option>
                            <?php
                            $prod_qry = $conn->query("SELECT * FROM product_list where `status` = 1 and delete_flag = 0  order by `name` asc");
                            while($row= $prod_qry->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['product_id'] ?>" <?php echo ((isset($product_id) && $product_id == $row['product_id']) || (isset($_GET['pid']) && $_GET['pid'] == $row['product_id']) ) ? 'selected' : '' ?>><?php echo $row['name'].'-'.$row['product_code'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="product_id" value="<?php echo $_GET['pid'] ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="quantity" class="control-label">Quantity</label>
                        <input type="number" step="any" name="quantity"  id="quantity" required class="form-control form-control-sm rounded-0 text-end" value="<?php echo isset($quantity) ? $quantity : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="expiry_date" class="control-label">Expiry Date</label>
                        <input type="date" name="expiry_date"  id="expiry_date" required class="form-control form-control-sm rounded-0" value="<?php echo isset($expiry_date) ? date("Y-m-d", strtotime($expiry_date)) : '' ?>">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(function() {
        $('#stock-form').submit(function(e) {
            e.preventDefault(); // Evita el envío tradicional del formulario
            $('.pop_msg').remove(); // Elimina cualquier mensaje emergente anterior

            var _this = $(this); // Referencia al formulario
            var _el = $('<div>').addClass('pop_msg'); // Crea un elemento para mostrar mensajes emergentes

            $('#uni_modal button').attr('disabled', true); // Desactiva botones en el modal
            $('#uni_modal button[type="submit"]').text('Submitting form...'); // Cambia el texto del botón para indicar que el formulario está en proceso de envío

            $.ajax({
                url: './Actions.php?a=save_stock', // La URL a la que se envía el formulario para guardar el stock
                data: new FormData($(this)[0]), // Crea un objeto FormData con los datos del formulario
                cache: false, // Desactiva la caché para esta solicitud
                contentType: false, // Permite que FormData establezca el tipo de contenido
                processData: false, // No procesa los datos para permitir el envío de archivos si es necesario
                method: 'POST', // Método POST para la solicitud AJAX
                type: 'POST', // Tipo de método (redundante pero a menudo utilizado para compatibilidad)
                dataType: 'json', // Se espera una respuesta en formato JSON
                error: err => { // Manejador de errores para la solicitud AJAX
                    console.log(err); // Registra el error en la consola
                    _el.addClass('alert alert-danger'); // Agrega clase de alerta para indicar un error
                    _el.text("An error occurred."); // Mensaje de error
                    _this.prepend(_el); // Agrega el mensaje emergente al principio del formulario
                    _el.show('slow'); // Muestra el mensaje lentamente
                    $('#uni_modal button').attr('disabled', false); // Reactiva los botones del modal
                    $('#uni_modal button[type="submit"]').text('Save'); // Restablece el texto del botón
                },
                success: function(resp) { // Manejador de éxito para la solicitud AJAX
                    if (resp.status == 'success') { // Si la respuesta indica éxito
                        _el.addClass('alert alert-success'); // Agrega clase de alerta para éxito
                        $('#uni_modal').on('hide.bs.modal', function() { // Evento para el cierre del modal
                            location.reload(); // Recarga la página cuando el modal se cierra
                        });
                        if ("<?php echo isset($product_id) ?>" != 1) { // Si el producto no está definido
                            _this.get(0).reset(); // Restablece el formulario
                            $('.select2').val('').trigger('change'); // Restablece el valor del select
                        }
                    } else { // Si la respuesta indica error
                        _el.addClass('alert alert-danger'); // Alerta de error
                    }
                    _el.text(resp.msg); // Muestra el mensaje de la respuesta

                    _el.hide(); // Oculta el mensaje
                    _this.prepend(_el); // Precede el mensaje al formulario
                    _el.show('slow'); // Muestra el mensaje lentamente

                    $('#uni_modal button').attr('disabled', false); // Reactiva botones del modal
                    $('#uni_modal button[type="submit"]').text('Save'); // Restablece el texto del botón
                }
            });
        });
    });
</script>