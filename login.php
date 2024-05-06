<?php
// Inicia una sesión para mantener la información del usuario.
session_start();

// Si el usuario ya está autenticado, redirige a la página principal.
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
    header("Location:./"); // Redirige a la raíz del sitio.
    exit; // Detiene la ejecución del código para evitar seguir procesando.
}

// Conexión a la base de datos.
require_once('DBConnection.php');

// Determina la página actual a mostrar, por defecto es 'Dashboard'.
$page = isset($_GET['page']) ? $_GET['page'] : 'Dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Configuraciones del encabezado HTML -->
    <meta charset="UTF-8">  <!-- Configura el conjunto de caracteres -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- Garantiza compatibilidad con IE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Diseño responsivo -->
    
    <!-- Título de la página -->
    <title>LOGIN | Dulcinea</title>
    
    <!-- Enlaces a hojas de estilo y scripts -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">  <!-- Bootstrap CSS -->
    <script src="./js/jquery-3.6.0.min.js"></script>  <!-- jQuery -->
    <script src="./js/popper.min.js"></script>  <!-- Popper.js -->
    <script src="./js/bootstrap.min.js"></script>  <!-- Bootstrap JS -->
    <script src="./js/script.js"></script>  <!-- Archivo de script personalizado -->

    <!-- Estilos CSS para la página -->
    <style>
        html, body {
            height: 100%;  // Establece la altura del contenido
        }

        body {
            background-image: url('./images/Backgroud_Dulcinea.jpg');  // Imagen de fondo
            background-size: 50% 50%;  // Ajusta el tamaño de la imagen
            background-repeat: no-repeat;  // Evita repetición de la imagen
            background-position: center center;  // Centra la imagen
            backdrop-filter: brightness(0.7);  // Filtro para brillo
        }

        h1#sys_title {
            font-size: 6em;  // Tamaño grande para el título
            text-shadow: 3px 3px 10px #000000;  // Sombras para el texto
        }

        @media (max-width: 700px) {
            h1#sys_title {
                font-size: inherit !important;  // Ajuste de tamaño en pantallas pequeñas
            }
        }
    </style>
</head>

<body class="">
   <!-- Contenedor que ocupa toda la altura y centra el contenido -->
   <div class="h-100 d-flex justify-content-center align-items-center">
       <div class='w-100'>  <!-- Contenedor que abarca todo el ancho -->
        <!-- Título del sistema -->
        <h1 class="py-5 text-center text-light px-4 text-black" id="sys_title">Dulcinea | Panadería y Repostería Management System</h1>



        <!-- Tarjeta para el formulario de inicio de sesión -->
        <div class="card my-3 col-md-4 offset-md-4">
            <div class="card-body">  <!-- Cuerpo de la tarjeta -->
                <!-- Formulario para iniciar sesión -->
                <form action="" id="login-form">
                    <!-- Mensaje de bienvenida -->
                    <center><small>Por favor ingresa con tus credenciales.</small></center>
                    
                     <!-- Agregar imagen -->
                    <center>
                        <img src="./images/Logo_Dulcinea_Oficial.png" alt="Logo Dulcinea" width="250" height="250">
                    </center>
                    <!-- Campo para el nombre de usuario -->
                    <div class="form-group">
                        <label for="username" class="control-label">Usuario</label>
                        <input 
                            type="text" 
                            id="username" 
                            autofocus 
                            name="username" 
                            class="form-control form-control-sm rounded-0" 
                            required 
                        >
                    </div>

                    <!-- Campo para la contraseña -->
                    <div class="form-group">
                        <label for="password" class="control-label">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control form-control-sm rounded-0" 
                            required 
                        >
                    </div>

                    <!-- Botón para enviar el formulario -->
                    <div class="form-group d-flex w-100 justify-content-end">
                        <button 
                            class="btn btn-sm btn-primary rounded-0 my-1"
                        >
                            Ingresar
                        </button>
                    </div>
                </form>
            </div>
        </div>
       </div>
   </div>
</body>

<!-- Script para manejar el envío del formulario y el proceso AJAX -->
<script>
    $(function() {
        // Evento para manejar el envío del formulario de inicio de sesión
        $('#login-form').submit(function(e) {
            e.preventDefault();  // Evita el envío predeterminado del formulario
            $('.pop_msg').remove();  // Elimina mensajes previos
            var _this = $(this);  // Referencia al formulario
            var _el = $('<div>');  // Crea un elemento para mensajes
            _el.addClass('pop_msg');  // Añade clase para estilo de mensaje
            _this.find('button').attr('disabled', true);  // Deshabilita el botón mientras se procesa
            _this.find('button[type="submit"]').text('Loging in...');  // Cambia el texto mientras se envía

            // Solicitud AJAX para intentar el inicio de sesión
            $.ajax({
                url: './Actions.php?a=login',  // URL del endpoint para inicio de sesión
                method: 'POST',  // Método HTTP POST
                data: $(this).serialize(),  // Datos del formulario
                dataType: 'JSON',  // Se espera una respuesta JSON
                error: err => {  // Manejo de errores
                    console.log(err);  // Registra el error en la consola
                    _el.addClass('alert alert-danger');  // Estilo para mensaje de error
                    _el.text("An error occurred.");  // Texto para el mensaje de error
                    _this.prepend(_el);  // Añade el mensaje al formulario
                    _el.show('slow');  // Muestra el mensaje lentamente
                    _this.find('button').attr('disabled', false);  // Habilita el botón nuevamente
                    _this.find('button[type="submit"]').text('Ingresar');  // Restaura el texto del botón
                },
                success: function(resp) {  // Manejo de respuestas exitosas
                    if (resp.status == 'success') {  // Si el inicio de sesión fue exitoso
                        _el.addClass('alert alert-success');  // Estilo para mensaje de éxito
                        _el.text(resp.msg);  // Texto para el mensaje de éxito
                        setTimeout(() => {
                            location.replace('./');  // Redirige a la página principal
                        }, 2000);  // Espera 2 segundos antes de redirigir
                    } else {  // Si el inicio de sesión falla
                        _el.addClass('alert alert-danger');  // Estilo para mensaje de error
                        _el.text(resp.msg);  // Texto para el mensaje de error
                    }

                    _el.hide();  // Oculta el mensaje
                    _this.prepend(_el);  // Añade el mensaje al formulario
                    _el.show('slow');  // Muestra el mensaje lentamente
                    _this.find('button').attr('disabled', false);  // Habilita el botón nuevamente
                    _this.find('button[type="submit"]').text('Ingresar');  // Restaura el texto del botón
                }
            });
        });
    });
</script>
</html>