<?php
// Inicia la sesión para gestionar información de usuarios
session_start();

// Comprueba si el usuario está autenticado
if (!isset($_SESSION['user_id'])) { 
    // Si el usuario no está autenticado, redirige a la página de inicio de sesión
    header("Location:./login.php"); 
    exit; // Termina la ejecución para evitar continuar con el script
}

// Incluye la conexión a la base de datos
require_once('DBConnection.php');

// Determina la página a mostrar. Si no está especificada, se muestra 'Dashboard'
$page = isset($_GET['page']) ? $_GET['page'] : 'Dashboard';

// Verifica si el usuario no tiene permiso para acceder a ciertas páginas
if ($_SESSION['type'] != 1 && in_array($page, array('maintenance', 'products', 'stocks'))) {
    // Si el usuario no es de tipo administrador y la página está en la lista restringida, redirige a la página principal
    header("Location:./"); 
    exit; // Termina la ejecución para evitar acceso no autorizado
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Define el título de la página usando el nombre de la página actual -->
    <title><?php echo ucwords(str_replace('_', '', $page)) ?> | Bakery Shop Management System</title>
    <!-- Enlaces a recursos CSS y scripts JavaScript -->
    <link rel="stylesheet" href="./Font-Awesome-master/css/all.min.css"> <!-- Íconos de Font Awesome -->
    <link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- Estilos de Bootstrap -->
    <link rel="stylesheet" href="./select2/css/select2.min.css"> <!-- Estilos para Select2 -->
    <script src="./js/jquery-3.6.0.min.js"></script> <!-- Carga jQuery -->
    <script src="./js/popper.min.js"></script> <!-- Carga Popper.js, necesario para Bootstrap -->
    <script src="./js/bootstrap.min.js"></script> <!-- Carga scripts de Bootstrap -->
    <link rel="stylesheet" href="./DataTables/datatables.min.css"> <!-- Estilos para DataTables -->
    <script src="./DataTables/datatables.min.js"></script> <!-- Scripts para DataTables -->
    <script src="./select2/js/select2.full.min.js"></script> <!-- Scripts para Select2 -->
    <script src="./Font-Awesome-master/js/all.min.js"></script> <!-- Scripts para Font Awesome -->
    <script src="./js/script.js"></script> <!-- Script adicional de la aplicación -->
    <!-- Estilos personalizados -->
    <style>
        :root{
            --bs-success-rgb:71, 222, 152 !important; // Define un color para el éxito
        }
        html, body{
            height: 100%; // Establece el alto completo
            width: 100%;  // Establece el ancho completo
        }
        @media screen {
            body {
                background-image: url('./images/Backgroud_Dulcinea.jpg'); // Imagen de fondo
                background-size: 50% 50%; // Ajusta la imagen para que se contenga completamente en el área
                background-repeat: no-repeat; // No repetir la imagen
                background-position: center center; // Centrar la imagen
                backdrop-filter: brightness(0.7); // Filtro de brillo
            }
        }

        main{
            height: 100%; // Establece el alto completo
            display: flex; // Usa flexbox
            flex-flow: column; // Disposición en columna
        }
        #page-container{
            flex: 1 1 auto; // Permite que este elemento crezca y se ajuste automáticamente
            overflow: auto; // Permite el desbordamiento con desplazamiento
        }
        #topNavBar{
            flex: 0 1 auto; // El navbar no crece
        }
        .thumbnail-img{
            width: 50px; // Define el ancho
            height: 50px; // Define el alto
            margin: 2px; // Agrega un margen
        }
        .truncate-1 {
            overflow: hidden; // Oculta el desbordamiento
            text-overflow: ellipsis; // Muestra puntos suspensivos si el texto es largo
            display: -webkit-box; // Usa flexbox
            -webkit-line-clamp: 1; // Limita a una línea
            -webkit-box-orient: vertical; // Establece la orientación
        }
        .truncate-3 {
            overflow: hidden; 
            text-overflow: ellipsis; 
            display: -webkit-box; 
            -webkit-line-clamp: 3; 
            -webkit-box-orient: vertical; 
        }
        .modal-dialog.large {
            width: 80% !important; // Amplía el tamaño de la ventana modal
            max-width: unset; // Elimina la limitación de ancho
        }
        .modal-dialog.mid-large {
            width: 50% !important; 
            max-width: unset; 
        }
        @media (max-width: 720px){
            .modal-dialog.large {
                width: 100% !important; 
                max-width: unset; 
            }
            .modal-dialog.mid-large {
                width: 100% !important; 
                max-width: unset; 
            }  
        }
        .display-select-image{
            width: 60px; // Define el tamaño de imagen para select
            height: 60px; // Define el tamaño de altura
            margin: 2px; 
        }
        img.display-image {
            width: 100%; /* La imagen ocupa todo el ancho*/
            height: 45vh; /* Define la altura*/
            object-fit: cover; /* Ajusta para cubrir*/
            background: black; /* Fondo negro para las imágenes*/
        }
        /* Personalización del scroll */
        ::-webkit-scrollbar {
            width: 20px; // Ancho del scroll
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1; // Fondo del track del scroll
        }

        ::-webkit-scrollbar-thumb {
            background: #888; // Color del thumb del scroll
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555; // Color del thumb al pasar el ratón
        }
        .img-del-btn{
            right: 2px; 
            top: -3px; 
        }
        .img-del-btn>.btn{
            font-size: 10px; // Tamaño del texto del botón
            padding: 0px 2px !important; // Ajusta el padding del botón
        }
    </style>
</head>

<body>
    <main>
        <!-- Barra de navegación superior -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
            <div class="container">
                <a class="navbar-brand" href="./">
                <img src="./images/Logo_Dulcinea_Oficial.png" alt="Logo de Dulcinea" width="120" height="120">
                Dulcinea | Panadería y Repostería Management System
                </a><!-- Marca de la barra de navegación -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span> <!-- Icono para abrir/cerrar el navbar en dispositivos móviles -->
                </button>
                <div class="collapse navbar-collapse" id="navbarNav"> <!-- Contenido colapsable -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'Dashboard') ? 'active' : '' ?>" aria-current="page" href="./">Dashboard</a> <!-- Enlace a la página principal -->
                        </li>
                        <?php if ($_SESSION['type'] == 1): ?> <!-- Opciones para administradores -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'products') ? 'active' : '' ?>" href="./?page=products">Productos</a> <!-- Enlace a la página de productos -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'stocks') ? 'active' : '' ?>" href="./?page=stocks">Stocks</a> <!-- Enlace a la página de stocks -->
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'sales') ? 'active' : '' ?>" href="./?page=sales">POS</a> <!-- Enlace a la página de punto de venta -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'sales_report') ? 'active' : '' ?>" href="./?page=sales_report">Ventas</a> <!-- Enlace a la página de reportes de ventas -->
                        </li>
                        <?php if ($_SESSION['type'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'users') ? 'active' : '' ?>" href="./?page=users">Usuarios</a> <!-- Enlace a la página de usuarios -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./?page=maintenance">Categorias</a> <!-- Enlace a la página de mantenimiento -->
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- Sección de dropdown para la cuenta del usuario -->
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle bg-transparent text-light border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Hello <?php echo $_SESSION['fullname'] ?> <!-- Saludo con el nombre del usuario -->
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="./?page=manage_account">Administrar cuenta</a></li> <!-- Opción para administrar la cuenta -->
                        <li><a class="dropdown-item" href="./Actions.php?a=logout">Cerrar sesión</a></li> <!-- Opción para cerrar sesión -->
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Contenedor principal para el contenido de la página -->
        <div class="container py-3" id="page-container">
            <?php 
            // Si hay mensajes flash en la sesión, se muestran aquí
            if (isset($_SESSION['flashdata'])):
            ?>
            <!-- Muestra un mensaje de alerta dinámico -->
            <div class="dynamic_alert alert alert-<?php echo $_SESSION['flashdata']['type'] ?> rounded-0 shadow">
                <!-- Botón para cerrar el mensaje -->
                <div class="float-end">
                    <a href="javascript:void(0)" class="text-dark text-decoration-none" onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a> 
                </div>
                <?php echo $_SESSION['flashdata']['msg'] ?> <!-- Mensaje de alerta -->
            </div>
            <?php 
            unset($_SESSION['flashdata']); // Elimina el mensaje flash después de mostrarlo
            endif; 
            ?>
            <?php
            // Incluye la página correspondiente basada en el valor de 'page'
            include $page . '.php'; 
            ?>
        </div>
    </main>
    
    <!-- Ventana modal principal -->
    <div class="modal fade" id="uni_modal" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true"> <!-- Configuración para modal -->
        <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document"> <!-- Define el tamaño del modal -->
            <div class="modal-content rounded-0">
                <div class="modal-header py-2"> <!-- Encabezado del modal -->
                    <h5 class="modal-title"></h5> <!-- Título del modal -->
                </div>
                <div class="modal-body"> <!-- Cuerpo del modal -->
                </div>
                <div class="modal-footer py-1"> <!-- Pie del modal -->
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Guardar</button> <!-- Botón para guardar -->
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Cerrar</button> <!-- Botón para cerrar -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ventana modal secundaria -->
    <div class="modal fade" id="uni_modal_secondary" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document"> <!-- Configuración similar a la del modal principal -->
            <div class="modal-content rounded-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal_secondary form').submit()">Guardar</button> 
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ventana modal para confirmaciones -->
    <div class="modal fade" id="confirm_modal" role='dialog'> <!-- Ventana modal para confirmaciones -->
        <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
            <div class="modal-content rounded-0 rounded-0">
                <div class="modal-header py-2"> <!-- Encabezado para confirmaciones -->
                    <h5 class="modal-title">confirmaciones</h5> <!-- Título del modal -->
                </div>
                <div class="modal-body"> <!-- Cuerpo para confirmaciones -->
                    <div id="delete_content"></div> <!-- Contenido para confirmaciones -->
                </div>
                <div class="modal-footer py-1"> <!-- Pie del modal -->
                    <button type="button" class="btn btn-primary btn-sm rounded-0" id='confirm' onclick="">Continuar</button> <!-- Botón para continuar -->
                    <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Cerrar</button> <!-- Botón para cerrar -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>