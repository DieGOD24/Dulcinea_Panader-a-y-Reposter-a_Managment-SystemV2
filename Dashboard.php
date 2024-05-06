<div class="content py-3">
    <!-- Esta es un contenedor para el contenido de la página. La clase 'content' proporciona un estilo específico y 'py-3' agrega relleno vertical de 3 unidades. -->

    <div class="card rounded-0 shadow">
        <!-- Esta es una tarjeta de estilo Bootstrap. 'rounded-0' elimina bordes redondeados y 'shadow' agrega sombra. -->

        <div class="card-body">
            <!-- El contenido de la tarjeta se encuentra aquí. -->

            <h1>¡BIENVENIDO! | SGBD Dulcinea</h1>
            <!-- Título de la página de bienvenida del sistema de gestión de panadería. -->

            <hr>
            <!-- Una línea horizontal para separar contenido. -->

            <div class="col-12">
                <!-- Contenedor de una sola columna para centrar el contenido. -->

                <div class="row gx-3 row-cols-4">
                    <!-- Una fila de Bootstrap con espaciado entre columnas ('gx-3') y 4 columnas por fila. -->

                    <!-- Primera tarjeta: Categorías -->
                    <div class="col">
                        <!-- Contenedor para la primera columna. -->

                        <div class="card text-dark">
                            <!-- Tarjeta con texto oscuro. -->

                            <div class="card-body">
                                <!-- Contenido de la tarjeta. -->

                                <div class="w-100 d-flex align-items-center">
                                    <!-- Contenedor que ocupa todo el ancho y usa flexbox para alinear elementos verticalmente. -->

                                    <div class="col-auto pe-1">
                                        <!-- Columna para ícono, con relleno a la derecha ('pe-1'). -->

                                        <span class="fa fa-th-list fs-1 text-primary"></span>
                                        <!-- Ícono de lista con tamaño de fuente 1 y color azul primario. -->
                                    </div>

                                    <div class="col-auto flex-grow-1">
                                        <!-- Columna que crece automáticamente para ocupar el espacio restante. -->

                                        <div class="fs-4 text-end"><b>Categorías</b></div>
                                        <!-- Texto para el título de la tarjeta. -->

                                        <div class="fs-4 text-end fw-bold">
                                            <!-- Contenedor para mostrar el conteo de categorías, alineado a la derecha y con negrita. -->
                                            <br><br>
                                            <?php 
                                            $category = $conn->query("SELECT count(category_id) as `count` FROM `category_list` where delete_flag = 0 ")->fetch_array()['count'];
                                            // Consulta a la base de datos para contar las categorías que no están marcadas como eliminadas.

                                            echo $category > 0 ? format_num($category) : 0;
                                            // Muestra el número de categorías formateado si es mayor que cero, de lo contrario muestra cero.
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Segunda tarjeta: Productos -->
                    <div class="col">
                        <!-- Contenedor para la segunda columna. -->

                        <div class="card text-dark">
                            <!-- Tarjeta con texto oscuro. -->

                            <div class="card-body">
                                <!-- Contenido de la tarjeta. -->

                                <div class="w-100 d-flex align-items-center">
                                    <!-- Contenedor para alinear el contenido de la tarjeta. -->

                                    <div class="col-auto pe-1">
                                        <!-- Columna para ícono con espacio a la derecha. -->

                                        <span class="fas fa-shopping-bag fs-1 text-secondary"></span>
                                        <!-- Ícono de bolsa de compras con color secundario. -->
                                    </div>

                                    <div class="col-auto flex-grow-1">
                                        <!-- Columna que crece para ocupar el espacio disponible. -->

                                        <div class="fs-4 text-end"><b>Productos</b></div>
                                        <!-- Texto del encabezado de la tarjeta. -->

                                        <div class="fs-4 text-end fw-bold">
                                            <!-- Texto para mostrar el conteo de productos, alineado a la derecha y en negrita. -->

                                            <br><br>
                                            <?php 
                                            $product = $conn->query("SELECT count(product_id) as `count` FROM `product_list` where delete_flag = 0 ")->fetch_array()['count'];
                                            // Consulta a la base de datos para contar la cantidad de productos activos.

                                            echo $product > 0 ? format_num($product) : 0;
                                            // Muestra el número de productos formateado o cero si no hay ninguno.
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tercera tarjeta: Stocks -->
                    <div class="col">
                        <!-- Contenedor para la tercera columna. -->

                        <div class="card text-dark">
                            <!-- Tarjeta con texto oscuro. -->

                            <div class="card-body">
                                <!-- Contenido de la tarjeta. -->

                                <div class="w-100 d-flex align-items-center">
                                    <!-- Contenedor para alinear el contenido de la tarjeta. -->

                                    <div class="col-auto pe-1">
                                        <!-- Columna para ícono con espacio a la derecha. -->

                                        <span class="fa fa-file-alt fs-1 text-info"></span>
                                        <!-- Ícono de archivo o documento con color informativo (azul claro). -->
                                    </div>

                                    <div class="col-auto flex-grow-1">
                                        <!-- Columna que crece para ocupar el espacio disponible. -->

                                        <div class="fs-4 text-end"><b>Total de<br>existencias</b></div>
                                        <!-- Encabezado para la tarjeta que muestra el stock total. -->

                                        <div class="fs-4 text-end fw-bold">
                                            <!-- Texto para mostrar la cantidad total de stock. -->
                                            <br>
                                            <?php 
                                            $stock = 0;
                                            // Inicializa la variable para almacenar el stock total.

                                            $stock_query = $conn->query("SELECT * FROM `stock_list` where product_id in (SELECT product_id FROM `product_list` where delete_flag = 0) and unix_timestamp(CONCAT(`expiry_date`)) >= unix_timestamp(CURRENT_TIMESTAMP) ");
                                            // Consulta para obtener el stock de productos no eliminados que no han expirado.

                                            while($row = $stock_query->fetch_assoc()):
                                                // Bucle para iterar sobre cada fila de resultados.

                                                $stock_in = $conn->query("SELECT sum(quantity) as `total` FROM `stock_list` where unix_timestamp(CONCAT(`expiry_date`, ' 23:59:59')) >= unix_timestamp(CURRENT_TIMESTAMP) and product_id = '{$row['product_id']}' ")->fetch_array()['total'];
                                                // Consulta para obtener el total de stock entrante para un producto específico.

                                                $stock_out = $conn->query("SELECT sum(quantity) as `total` FROM `transaction_items` where product_id = '{$row['product_id']}' ")->fetch_array()['total'];
                                                // Consulta para obtener el total de stock saliente para un producto específico.

                                                $stock_in = $stock_in > 0 ? $stock_in : 0;
                                                // Si el stock entrante es mayor que cero, se usa ese valor; de lo contrario, se usa cero.

                                                $stock_out = $stock_out > 0 ? $stock_out : 0;
                                                // Lo mismo para el stock saliente.

                                                $qty = $stock_in - $stock_out;
                                                // Calcula la cantidad neta de stock.

                                                $qty = $qty > 0 ? $qty : 0;
                                                // Si la cantidad es menor que cero, se convierte a cero.

                                                $stock += $qty;
                                                // Suma la cantidad neta al stock total.
                                            endwhile;

                                            echo $stock > 0 ? format_num($stock) : 0;
                                            // Muestra el total de stock formateado o cero si no hay stock.
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cuarta tarjeta: Ventas del día -->
                    <div class="col">
                        <!-- Contenedor para la cuarta columna. -->

                        <div class="card text-dark">
                            <!-- Tarjeta con texto oscuro. -->

                            <div class="card-body">
                                <!-- Contenido de la tarjeta. -->

                                <div class="w-100 d-flex align-items-center">
                                    <!-- Contenedor para alinear el contenido de la tarjeta. -->

                                    <div class="col-auto pe-1">
                                        <!-- Columna para ícono con espacio a la derecha. -->

                                        <span class="fa fa-coins fs-1 text-warning"></span>
                                        <!-- Ícono de monedas con color amarillo (aviso). -->
                                    </div>

                                    <div class="col-auto flex-grow-1">
                                        <!-- Columna que crece para ocupar el espacio disponible. -->

                                        <div class="fs-4 text-end"><b>Ventas<br>del día</b></div>
                                        <!-- Encabezado para la tarjeta que muestra las ventas del día. -->

                                        <div class="fs-4 text-end fw-bold">
                                            <!-- Texto para mostrar las ventas del día. -->

                                            <br>
                                            <?php 
                                            $sales = $conn->query("SELECT sum(total) as `total` FROM `transaction_list` where date(date_added) = date(CURRENT_TIMESTAMP) ".(($_SESSION['type'] != 1)? " and user_id = '{$_SESSION['user_id']}' " : ""))->fetch_array()[0];
                                            // Consulta para obtener las ventas totales del día. Si el usuario no es de tipo administrador, filtra por su ID.

                                            echo $sales > 0 ? format_num($sales) : 0;
                                            // Muestra el total de ventas formateado o cero si no hay ventas.
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Línea divisoria para separar secciones. -->

                <div class="row">
                    <div class="col-12">
                        <h3>Stock Disponible</h3>
                        <!-- Título para la sección de inventario. -->

                        <hr>
                        <!-- Línea horizontal para separar el título de la tabla. -->

                        <table class="table table-striped table-hover table-bordered" id="inventory">
                            <!-- Tabla con estilo rayado, con efecto hover y bordes. Se asigna un ID para facilitar la interacción con JavaScript. -->

                            <colgroup>
                                <!-- Define los anchos relativos de las columnas. -->
                                <col width="25%">
                                <col width="25%">
                                <col width="25%">
                                <col width="25%">
                            </colgroup>

                            <thead>
                                <!-- Encabezado de la tabla. -->

                                <tr>
                                    <!-- Fila para el encabezado. -->

                                    <th class="py-0 px-1">Categoría</th>
                                    <!-- Columna para mostrar la categoría del producto. -->

                                    <th class="py-0 px-1">Código del producto</th>
                                    <!-- Columna para mostrar el código del producto. -->

                                    <th class="py-0 px-1">Nombre del producto</th>
                                    <!-- Columna para mostrar el nombre del producto. -->

                                    <th class="py-0 px-1">Cantidad disponible</th>
                                    <!-- Columna para mostrar la cantidad disponible de stock. -->
                                </tr>
                            </thead>

                            <tbody>
                                <!-- Cuerpo de la tabla para mostrar los datos. -->

                                <?php
                                    $sql = "SELECT p.*, c.name as cname FROM `product_list` p INNER JOIN `category_list` c ON p.category_id = c.category_id WHERE p.status = 1 AND p.delete_flag = 0 ORDER BY `name` ASC";
                                    // Consulta SQL para obtener productos activos, no eliminados, junto con sus categorías asociadas, ordenados alfabéticamente por nombre.

                                    $qry = $conn->query($sql);
                                    // Ejecuta la consulta y obtiene el resultado.

                                    while($row = $qry->fetch_assoc()):
                                        // Bucle para iterar sobre cada producto encontrado.

                                        $stock_in = $conn->query("SELECT sum(quantity) as `total` FROM `stock_list` where unix_timestamp(CONCAT(`expiry_date`, ' 23:59:59')) >= unix_timestamp(CURRENT_TIMESTAMP) and product_id = '{$row['product_id']}' ")->fetch_array()['total'];
                                        // Consulta para obtener el stock entrante para el producto actual.

                                        $stock_out = $conn->query("SELECT sum(quantity) as `total` FROM `transaction_items` where product_id = '{$row['product_id']}' ")->fetch_array()['total'];
                                        // Consulta para obtener el stock saliente para el producto actual.

                                        $stock_in = $stock_in > 0 ? $stock_in : 0;
                                        // Si el stock entrante es mayor que cero, se usa ese valor; de lo contrario, se usa cero.

                                        $stock_out = $stock_out > 0 ? $stock_out : 0;
                                        // Si el stock saliente es mayor que cero, se usa ese valor; de lo contrario, se usa cero.

                                        $qty = $stock_in - $stock_out;
                                        // Calcula la cantidad neta disponible.

                                        $qty = $qty > 0 ? $qty : 0;
                                        // Si la cantidad neta es menor que cero, se convierte a cero.

                                        $row_class = $qty < 50 ? "bg-danger bg-opacity-25" : '';
                                        // Define una clase CSS para resaltar las filas con bajo stock.

                                ?>
                                    <tr class="<?php echo $row_class; ?>">
                                        <!-- Fila de la tabla. La clase se usa para resaltar el bajo stock. -->

                                        <td class="py-0 px-1"><?php echo $row['cname']; ?></td>
                                        <!-- Muestra la categoría del producto. -->

                                        <td class="py-0 px-1"><?php echo $row['product_code']; ?></td>
                                        <!-- Muestra el código del producto. -->

                                        <td class="py-0 px-1"><?php echo $row['name']; ?></td>
                                        <!-- Muestra el nombre del producto. -->

                                        <td class="py-0 px-1 text-end">
                                            <!-- Columna para la cantidad disponible, alineada a la derecha. -->

                                            <?php  if($_SESSION['type'] == 1): ?>
                                            <?php echo $qty < $row['alert_restock'] ? "<a href='javascript:void(0)' class='restock me-1' data-pid = '".$row['product_id']."' data-name = '".$row['product_code'].' - '.$row['name']."'> Reabastecer</a>" : ''; ?>
                                            <!-- Si el stock es bajo, muestra un enlace para reabastecer. Solo para usuarios de tipo 1 (administrador). -->

                                            <?php endif; ?>
                                            <?php echo $qty; ?>
                                            <!-- Muestra la cantidad disponible. -->
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Esta función se ejecutará cuando el documento HTML esté completamente cargado y listo para interactuar.
    $(function() {
        // Se seleccionan todos los elementos con la clase 'restock' y se añade un evento para manejar el clic.
        $('.restock').click(function() {
            
            /*Se llama a la función 'uni_modal' para abrir un modal (ventana emergente).
            
            El primer parámetro es el título del modal, que incluye texto estático y un nombre de producto obtenido dinámicamente.
            
            El segundo parámetro es la URL para cargar en el modal, con un parámetro de producto ('pid') obtenido del atributo 'data-pid' del elemento clicado.
            */
            uni_modal(
                'Añade nuevo stock a <span class="text-primary">' + $(this).attr('data-name') + '</span>',
                'manage_stock.php?pid=' + $(this).attr('data-pid')
            );
        });

        /* Selecciona el elemento de la tabla con ID 'inventory' e inicializa el plugin DataTables.
        
        DataTables agrega características interactivas como paginación, búsqueda y ordenación a la tabla de inventario.
        */
        $('table#inventory').dataTable();
    });
</script>
