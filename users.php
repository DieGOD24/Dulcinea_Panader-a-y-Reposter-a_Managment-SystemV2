<div class="card rounded-0 shadow">
    <!-- Encabezado de la tarjeta -->
    <div class="card-header d-flex justify-content-between">
        <!-- Título de la tarjeta -->
        <h3 class="card-title">Lista de usuarios</h3>
        <!-- Herramientas de la tarjeta -->
        <div class="card-tools align-middle">
            <!-- Botón para agregar un nuevo usuario -->
            <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Añadir uno nuevo</button>
        </div>
    </div>

    <!-- Cuerpo de la tarjeta -->
    <div class="card-body">
        <!-- Tabla para mostrar la lista de usuarios -->
        <table class="table table-hover table-striped table-bordered">
            <!-- Definición de las proporciones de las columnas -->
            <colgroup>
                <col width="5%">   <!-- Índice -->
                <col width="30%">  <!-- Nombre completo -->
                <col width="25%">  <!-- Nombre de usuario -->
                <col width="25%">  <!-- Tipo de usuario -->
                <col width="15%">  <!-- Acciones -->
            </colgroup>

            <!-- Cabecera de la tabla -->
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>       <!-- Índice de fila -->
                    <th class="text-center p-0">Nombre completo</th>   <!-- Nombre completo -->
                    <th class="text-center p-0">Nombre de usuario</th>  <!-- Nombre de usuario -->
                    <th class="text-center p-0">Tipo de usuario</th>   <!-- Tipo de usuario -->
                    <th class="text-center p-0">Acciones disponibles</th>  <!-- Acciones disponibles -->
                </tr>
            </thead>

            <!-- Cuerpo de la tabla -->
            <tbody>
                <!-- PHP para obtener datos de la base de datos -->
                <?php 
                // Consulta SQL para obtener la lista de usuarios, excluyendo al administrador principal
                $sql = "SELECT * FROM `user_list` WHERE user_id != 1 ORDER BY `fullname` ASC";
                // Ejecución de la consulta
                $qry = $conn->query($sql);
                $i = 1; // Contador para numerar filas

                // Bucle para recorrer cada fila de resultados
                while ($row = $qry->fetch_assoc()):
                ?>
                <tr>
                    <!-- Número de fila -->
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <!-- Nombre completo del usuario -->
                    <td class="py-0 px-1"><?php echo $row['fullname']; ?></td>
                    <!-- Nombre de usuario -->
                    <td class="py-0 px-1"><?php echo $row['username']; ?></td>
                    <!-- Tipo de usuario (administrador o cajero) -->
                    <td class="py-0 px-1"><?php echo ($row['type'] == 1) ? "Administrador" : "Cajero"; ?></td>
                    <!-- Botones de acción -->
                    <th class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <!-- Botón de menú desplegable para acciones -->
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                                Acciones
                            </button>
                            <!-- Lista de acciones en el menú desplegable -->
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <!-- Enlace para editar un usuario -->
                                <li><a class="dropdown-item edit_data" data-id='<?php echo $row['user_id']; ?>' href="javascript:void(0)">Editar</a></li>
                                <!-- Enlace para eliminar un usuario -->
                                <li><a class="dropdown-item delete_data" data-id='<?php echo $row['user_id']; ?>' data-name='<?php echo $row['fullname']; ?>' href="javascript:void(0)">Eliminar</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(function() {
    // Acciones al hacer clic en el botón "Add New"
    $('#create_new').click(function() {
        // Abre un modal para agregar un nuevo usuario
        uni_modal('Añade un nuevo usuario', "manage_user.php");
    });

    // Acciones al hacer clic en "Edit"
    $('.edit_data').click(function() {
        // Abre un modal para editar un usuario existente
        uni_modal('Editar detalles del usuario', "manage_user.php?id=" + $(this).attr('data-id'));
    });

    // Acciones al hacer clic en "Delete"
    $('.delete_data').click(function() {
        // Muestra una confirmación antes de eliminar
        _conf("Estas seguro que deseas elimiar a <b>" + $(this).attr('data-name') + "</b> de la lista?", 'delete_data', [$(this).attr('data-id')]);
    });

    // Alinea el contenido de las celdas al centro
    $('table td, table th').addClass('align-middle');

    // Inicializa la tabla con jQuery DataTables
    $('table').dataTable({
        columnDefs: [
            // Desactiva la opción de ordenar en la columna de acción
            { orderable: false, targets: 4 }
        ]
    });
});

function delete_data($id) {
    // Deshabilita los botones mientras se ejecuta la acción
    $('#confirm_modal button').attr('disabled', true);

    // Envía una solicitud AJAX para eliminar el usuario
    $.ajax({
        url: './Actions.php?a=delete_user', // URL del endpoint para eliminar usuarios
        method: 'POST', // Método HTTP
        data: { id: $id }, // Datos enviados con la solicitud
        dataType: 'JSON', // Tipo de respuesta esperada
        error: err => { // Manejo de errores
            console.log(err); // Muestra el error en la consola
            alert("Ocurrió un error."); // Alerta de error
            $('#confirm_modal button').attr('disabled', false); // Reactiva los botones
        },
        success: function(resp) { // Manejo de éxito
            if(resp.status == 'success') { // Si la operación fue exitosa
                location.reload(); // Recarga la página para reflejar los cambios
            } else { // Si hubo un error
                alert("Ocurrió un error."); // Muestra una alerta
                $('#confirm_modal button').attr('disabled', false); // Reactiva los botones
            }
        }
    });
}
</script>