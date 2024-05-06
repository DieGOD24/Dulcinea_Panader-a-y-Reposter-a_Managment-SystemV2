<?php 
// Obtener la fecha de inicio del filtro (por defecto, una semana antes de la fecha actual)
$dfrom = isset($_GET['date_from']) ? $_GET['date_from'] : date("Y-m-d", strtotime(date("Y-m-d") . " -1 week"));

// Obtener la fecha de fin del filtro (por defecto, la fecha actual)
$dto = isset($_GET['date_to']) ? $_GET['date_to'] : date("Y-m-d");
?>

<!-- Crear una tarjeta con un encabezado y cuerpo para el informe de ventas -->
<div class="card rounded-0 shadow">
    <div class="card-header d-flex justify-content-between">
        <!-- Título de la tarjeta -->
        <h3 class="card-title">Reporte de ventas</h3>
    </div>

    <div class="card-body">
        <!-- Sección para los filtros -->
        <h5>Filtros</h5>
        <div class="row align-items-end">
            <!-- Campo para la fecha de inicio del filtro -->
            <div class="form-group col-md-2">
                <label for="date_from" class="control-label">Desde la fecha</label>
                <input type="date" name="date_from" id="date_from" value="<?php echo $dfrom ?>" class="form-control rounded-0">
            </div>
            
            <!-- Campo para la fecha de fin del filtro -->
            <div class="form-group col-md-2">
                <label for="date_to" class="control-label">Hasta la fecha</label>
                <input type="date" name="date_to" id="date_to" value="<?php echo $dto ?>" class="form-control rounded-0">
            </div>

            <!-- Botones para aplicar el filtro y para imprimir -->
            <div class="form-group col-md-4 d-flex">
                <div class="col-auto">
                    <!-- Botón para aplicar el filtro -->
                    <button class="btn btn-primary rounded-0" id="filter" type="button">
                        <i class="fa fa-filter"></i> Filtar
                    </button>
                    <!-- Botón para imprimir el informe -->
                    <button class="btn btn-success rounded-0" id="print" type="button">
                        <i class="fa fa-print"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>

        <hr>
        <div class="clear-fix mb-2"></div>

        <!-- Área para mostrar el contenido imprimible -->
        <div id="outprint">
            <!-- Tabla para mostrar los datos del informe de ventas -->
            <table class="table table-hover table-striped table-bordered">
                <colgroup>
                    <col width="5%">
                    <col width="20%">
                    <col width="25%">
                    <col width="10%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <!-- Encabezado de la tabla -->
                    <tr class="bg-dark bg-opacity-75 text-light">
                        <th class="text-center p-0">#</th>
                        <th class="text-center p-0">Fecha</th>
                        <th class="text-center p-0">Recibo N°</th>
                        <th class="text-center p-0">Productos</th>
                        <th class="text-center p-0">Cantidad total</th>
                        <th class="text-center p-0">Procesado por</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    // Condición adicional para limitar las transacciones por usuario si no es administrador
                    $user_where = "";
                    if($_SESSION['type'] != 1){
                        $user_where = " and user_id = '{$_SESSION['user_id']}' ";
                    }

                    // Obtener lista de usuarios involucrados en transacciones en el rango de fechas
                    $user_qry = $conn->query("SELECT user_id, fullname FROM user_list where user_id in (SELECT user_id FROM  `transaction_list` where date(date_added) between '{$dfrom}' and '{$dto}' {$user_where})");
                    $user_arr = array_column($user_qry->fetch_all(MYSQLI_ASSOC), 'fullname', 'user_id');

                    // Consulta para obtener las transacciones dentro del rango de fechas
                    $sql = "SELECT * FROM  `transaction_list` where date(date_added) between '{$dfrom}' and '{$dto}' {$user_where}  order by unix_timestamp(date_added) asc";
                    $qry = $conn->query($sql);

                    // Contador para numerar las filas
                    $i = 1;

                    // Recorrer cada fila de resultados y mostrarla en la tabla
                    while($row = $qry->fetch_assoc()):
                        // Contar el número de artículos para cada transacción
                        $items = $conn->query("SELECT count(transaction_id) as `count` FROM `transaction_items` where transaction_id = '{$row['transaction_id']}' ")->fetch_assoc()['count'];
                    ?>
                    <!-- Fila de la tabla -->
                    <tr>
                        <td class="text-center p-0"><?php echo $i++; ?></td>
                        <td class="py-0 px-1"><?php echo date("Y-m-d", strtotime($row['date_added'])) ?></td>
                        <td class="py-0 px-1">
                            <!-- Enlace para ver detalles de la transacción -->
                            <a href="javascript:void(0)" class="view_data" data-id="<?php echo $row['transaction_id'] ?>"><?php echo $row['receipt_no'] ?></a>
                        </td>
                        <td class="py-0 px-1 text-end"><?php echo format_num($items) ?></td>
                        <td class="py-0 px-1 text-end"><?php echo format_num($row['total']) ?></td>
                        <td class="py-0 px-1"><?php echo isset($user_arr[$row['user_id']]) ? $user_arr[$row['user_id']] : 'N/A' ?></td>
                    </tr>
                    <?php endwhile; ?>

                    <!-- Mostrar un mensaje si no hay transacciones en el rango de fechas -->
                    <?php if($qry->num_rows <= 0): ?>
                        <tr>
                            <th colspan="6">
                                <center>No se ha listado ninguna transacción en la fecha seleccionada.</center>
                            </th>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript para funciones adicionales como filtros y vista previa de impresión -->
<script>
    $(function(){
        // Evento para abrir la vista previa de un recibo cuando se hace clic
        $('.view_data').click(function(){
            uni_modal('Receipt', "view_receipt.php?view_only=true&id=" + $(this).attr('data-id'), '')
        });

        // Evento para filtrar las transacciones por fecha
        $('#filter').click(function(){
            location.href = "./?page=sales_report&date_from=" + $('#date_from').val() + "&date_to=" + $('#date_to').val();
        });
        
        // Añadir clase para centrar el texto en las celdas de la tabla
        $('table td, table th').addClass('align-middle');

        // Evento para imprimir el informe
        $('#print').click(function(){
            var h = $('head').clone();
            var p = $('#outprint').clone();

            var el = $('<div>');
            el.append(h);

            // Mostrar el rango de fechas en el encabezado de impresión
            if ('<?php echo $dfrom ?>' == '<?php echo $dto ?>') {
                date_range = "<?php echo date('M d, Y', strtotime($dfrom)) ?>";
            } else {
                date_range = "<?php echo date('M d, Y', strtotime($dfrom)) .' - '. date('M d, Y', strtotime($dto)) ?>";
            }
            
            el.append("<div class='text-center lh-1 fw-bold'>Informe de Ventas | DULCINEA<br/>Fechas entre<br/>" + date_range + "</div><hr/>");

            // Prepara el contenido para imprimir
            p.find('a').addClass('text-decoration-none');
            el.append(p);

            var nw = window.open("", "", "width=500, height=900");
            nw.document.write(el.html());
            nw.document.close();
            
            // Imprimir y cerrar después de un tiempo
            setTimeout(() => {
                nw.print();
                setTimeout(() => {
                    nw.close();
                }, 150);
            }, 200);
        });
    });
</script>