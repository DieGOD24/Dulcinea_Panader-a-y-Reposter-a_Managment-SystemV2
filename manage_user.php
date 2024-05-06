<?php
// Conexión a la base de datos
require_once("DBConnection.php");

// Verifica si se recibió un ID por la URL
if (isset($_GET['id'])) {
    // Consulta para obtener información del usuario con el ID dado
    $qry = $conn->query("SELECT * FROM `user_list` WHERE user_id = '{$_GET['id']}'");

    // Almacena cada campo y valor del resultado como variables
    foreach ($qry->fetch_assoc() as $k => $v) {
        $$k = $v; // Crea una variable con el nombre del campo y su valor correspondiente
    }
}
?>

<!-- Sección HTML para el formulario -->
<div class="container-fluid">
    <!-- Formulario para agregar o editar un usuario -->
    <form action="" id="user-form">
        <!-- Campo oculto para el ID del usuario (si está editando) -->
        <input type="hidden" name="id" value="<?php echo isset($user_id) ? $user_id : ''; ?>">

        <!-- Campo para el nombre completo -->
        <div class="form-group">
            <label for="fullname" class="control-label">Nombre completo</label>
            <input 
                type="text" 
                name="fullname" 
                id="fullname" 
                required 
                class="form-control form-control-sm rounded-0" 
                value="<?php echo isset($fullname) ? $fullname : ''; ?>"
            >
        </div>

        <!-- Campo para el nombre de usuario -->
        <div class="form-group">
            <label for="username" class="control-label">Nombre de usuario</label>
            <input 
                type="text" 
                name="username" 
                id="username" 
                required 
                class="form-control form-control-sm rounded-0" 
                value="<?php echo isset($username) ? $username : ''; ?>"
            >
        </div>

        <!-- Campo para seleccionar el tipo de usuario -->
        <div class="form-group">
            <label for="type" class="control-label">Tipo de usuario</label>
            <select 
                name="type" 
                id="type" 
                class="form-select form-select-sm rounded-0" 
                required
            >
                <!-- Opción para el administrador -->
                <option 
                    value="1" 
                    <?php echo isset($type) && $type == 1 ? 'selected' : ''; ?>
                >
                    Administrador
                </option>
                <!-- Opción para el cajero -->
                <option 
                    value="0" 
                    <?php echo isset($type) && $type == 2 ? 'selected' : ''; ?>
                >
                    Cajero
                </option>
            </select>
        </div>
    </form>
</div>

<!-- Script para manejar el envío del formulario y la interacción AJAX -->
<script>
    $(function() {
        // Evento para manejar el envío del formulario
        $('#user-form').submit(function(e) {
            e.preventDefault(); // Evita que el formulario se envíe de forma predeterminada
            $('.pop_msg').remove(); // Elimina mensajes previos
            var _this = $(this); // Referencia al formulario actual
            var _el = $('<div>'); // Crea un elemento para mostrar mensajes
            _el.addClass('pop_msg'); // Añade clase para estilo de mensaje
            $('#uni_modal button').attr('disabled', true); // Deshabilita botones mientras se procesa
            $('#uni_modal button[type="submit"]').text('submitting form...'); // Cambia el texto mientras se envía

            // Envío AJAX para guardar el usuario
            $.ajax({
                url: './Actions.php?a=save_user', // URL del endpoint para guardar el usuario
                method: 'POST', // Método HTTP POST
                data: $(this).serialize(), // Datos del formulario
                dataType: 'JSON', // Se espera una respuesta en formato JSON
                error: err => { // Manejo de errores
                    console.log(err); // Registra el error en la consola
                    _el.addClass('alert alert-danger'); // Estilo para mensajes de error
                    _el.text("Ocurrió un error."); // Texto del mensaje de error
                    _this.prepend(_el); // Añade el mensaje al formulario
                    _el.show('slow'); // Muestra el mensaje lentamente
                    $('#uni_modal button').attr('disabled', false); // Habilita los botones
                    $('#uni_modal button[type="submit"]').text('Save'); // Restaura el texto del botón
                },
                success: function(resp) { // Manejo de respuestas exitosas
                    if (resp.status == 'success') { // Si la respuesta indica éxito
                        _el.addClass('alert alert-success'); // Estilo para mensajes de éxito
                        _el.text(resp.msg); // Muestra el mensaje de éxito
                        $('#uni_modal').on('hide.bs.modal', function() { // Evento al cerrar el modal
                            location.reload(); // Recarga la página
                        });
                        if ("<?php echo isset($user_id); ?>" != 1) {
                            _this.get(0).reset(); // Limpia el formulario si no es un administrador
                        }
                    } else { // Si hay un error en la respuesta
                        _el.addClass('alert alert-danger'); // Estilo para mensajes de error
                        _el.text(resp.msg); // Texto del mensaje de error
                    }

                    // Añade el mensaje al formulario
                    _this.prepend(_el);
                    _el.show('slow'); // Muestra el mensaje lentamente
                    $('#uni_modal button').attr('disabled', false); // Habilita los botones
                    $('#uni_modal button[type="submit"]').text('Save'); // Restaura el texto del botón
                }
            });
        });
    });
</script>