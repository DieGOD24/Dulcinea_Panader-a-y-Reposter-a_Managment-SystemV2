<?php
// Define una clase para manejar conexiones a bases de datos MySQL
Class DBConnection {
    // Propiedad protegida para almacenar la conexión a la base de datos
    protected $db;

    // Constructor de la clase, se ejecuta al crear una nueva instancia de la clase DBConnection
    function __construct() {
        // Intenta establecer una conexión a la base de datos
        // Argumentos: servidor, usuario, contraseña, nombre de la base de datos
        $this->db = new mysqli('localhost', 'root', '', 'bsms_db'); // Conexión a una base de datos llamada 'bsms_db'
        
        // Comprueba si la conexión falló
        if (!$this->db) { // Si la conexión falla
            // Termina la ejecución y muestra un mensaje de error
            die('Conección a base de datos fallida. Error: ' . $this->db->error);
        }
    }

    // Método para obtener la conexión a la base de datos
    function db_connect() {
        return $this->db; // Devuelve la conexión a la base de datos
    }

    // Destructor de la clase, se ejecuta cuando la instancia es destruida
    function __destruct() {
        $this->db->close(); // Cierra la conexión a la base de datos para liberar recursos
    }
}

// Define una función para formatear números con decimales opcionales
function format_num($number = '', $decimal = '') {
    // Verifica si la entrada es un número
    if (is_numeric($number)) {
        // Divide el número por el punto decimal para verificar si tiene decimales
        $ex = explode(".", $number); // Divide el número en dos partes usando el punto decimal
        
        // Obtiene la longitud de la parte decimal
        $dec_len = isset($ex[1]) ? strlen($ex[1]) : 0; // Si hay decimales, determina su longitud
        
        // Verifica si se especificó un número de decimales o si el argumento es numérico
        if (!empty($decimal) || is_numeric($decimal)) {
            return number_format($number, $decimal); // Formatea el número con el número de decimales especificado
        } else {
            return number_format($number, $dec_len); // Formatea el número usando la longitud de la parte decimal original
        }
    } else {
        // Si la entrada no es un número, devuelve un mensaje de error
        return 'Entrada invalida.'; // Mensaje que indica que la entrada no es válida
    }
}

// Crea una nueva instancia de la clase DBConnection para establecer una conexión a la base de datos
$db = new DBConnection();

// Utiliza el método db_connect() para obtener la conexión a la base de datos
$conn = $db->db_connect(); // Obtiene la conexión a la base de datos