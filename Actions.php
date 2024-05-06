<?php 
// Inicia una nueva sesión o reanuda una existente.
session_start(); 

// Incluye el archivo para la conexión a la base de datos.
require_once('DBConnection.php'); 

// Define la clase 'Actions' que extiende la conexión a la base de datos.
class Actions extends DBConnection {
    // Constructor de la clase. Llama al constructor de la clase padre (DBConnection).
    function __construct() {
        parent::__construct(); 
    }

    // Destructor de la clase. Llama al destructor de la clase padre para liberar recursos.
    function __destruct() {
        parent::__destruct(); 
    }

    // Función para manejar el inicio de sesión.
    function login() {
        // Extrae las variables enviadas por POST.
        extract($_POST); 

        // Consulta SQL para verificar si el usuario y contraseña coinciden con la base de datos.
        $sql = "SELECT * FROM user_list WHERE username = '{$username}' AND `password` = '".md5($password)."' ";
        
        // Ejecuta la consulta y obtiene el resultado.
        @$qry = $this->db->query($sql)->fetch_array(); 
        
        // Verifica si la consulta no encontró ningún resultado.
        if(!$qry) {
            // Si no se encontró un usuario, se devuelve un estado de error.
            $resp['status'] = "Fallido"; 
            $resp['msg'] = "Nombre de usuario o contraseña invalidos."; 
        } else {
            // Si se encontró el usuario, inicia sesión exitosamente.
            $resp['status'] = "success"; 
            $resp['msg'] = "Login successfully."; 
            
            // Guarda los datos del usuario en la sesión.
            foreach($qry as $k => $v) {
                if(!is_numeric($k)) 
                    $_SESSION[$k] = $v; 
            }
        }

        // Devuelve la respuesta en formato JSON.
        return json_encode($resp); 
    }

    // Función para cerrar sesión.
    function logout() {
        // Destruye la sesión actual.
        session_destroy(); 
        
        // Redirige a la página principal o de inicio de sesión.
        header("location:./"); 
    }

    // Función para guardar o actualizar datos de usuario.
    function save_user() {
        // Extrae las variables enviadas por POST.
        extract($_POST); 
        
        // Prepara una cadena para almacenar los datos.
        $data = ""; 
        
        // Recorre las claves y valores enviados por POST.
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id'))) { // Excluye el campo 'id'.
                if(!empty($id)) { // Si 'id' no está vacío, significa que se está actualizando un usuario existente.
                    if(!empty($data)) 
                        $data .= ","; 
                    
                    // Agrega los datos de la actualización.
                    $data .= " `{$k}` = '{$v}' "; 
                } else { // Si 'id' está vacío, significa que se está creando un nuevo usuario.
                    $cols[] = $k; 
                    $values[] = "'{$v}'"; 
                }
            }
        }

        // Si 'id' está vacío, se crea un nuevo usuario con un password por defecto (encriptado con MD5).
        if(empty($id)) { 
            $cols[] = 'password'; 
            $values[] = "'".md5($username)."'"; 
        }

        // Si existen columnas y valores definidos, crea la cadena para la inserción o actualización.
        if(isset($cols) && isset($values)) { 
            $data = "(".implode(',', $cols).") VALUES (".implode(',', $values).")"; 
        }

        // Verifica si el nombre de usuario ya existe, excluyendo el usuario actual si se está actualizando.
        @$check = $this->db->query("SELECT count(user_id) as `count` FROM user_list WHERE `username` = '{$username}' ".($id > 0 ? " AND user_id != '{$id}' " : ""))->fetch_array()['count']; 
        
        // Si ya existe, se devuelve un mensaje de error.
        if(@$check > 0) { 
            $resp['status'] = 'failed'; 
            $resp['msg'] = "El nombre de usuario ya existe"; 
        } else { 
            // Si no existe, se procede a insertar o actualizar.
            if(empty($id)) { 
                $sql = "INSERT INTO `user_list` {$data}"; 
            } else { 
                $sql = "UPDATE `user_list` set {$data} WHERE user_id = '{$id}'"; 
            }
            
            // Intenta ejecutar la consulta para guardar o actualizar el usuario.
            @$save = $this->db->query($sql); 
            
            // Verifica si la consulta fue exitosa.
            if($save) { 
                $resp['status'] = 'success'; 
                if(empty($id)) 
                    $resp['msg'] = 'Nuevo usuario guardado satisfactoriamente.'; 
                else 
                    $resp['msg'] = 'Atrubutos de usuario guardados satisfactoriamente.'; 
            } else { 
                // Si hubo un error al guardar, se devuelve un mensaje de error.
                $resp['status'] = 'failed'; 
                $resp['msg'] = 'Atrubutos de usuario no fueron guardados satisfactoriamente. Error: '.$this->db->error; 
                $resp['sql'] = $sql; 
            }
        }

        // Devuelve la respuesta en formato JSON.
        return json_encode($resp); 
    }

    function delete_user() {
        // Extrae las variables del array POST.
        extract($_POST);
    
        // Ejecuta la consulta SQL para eliminar al usuario con el ID proporcionado.
        @$delete = $this->db->query("DELETE FROM `user_list` WHERE user_id = '{$id}'");
    
        // Verifica si la eliminación fue exitosa.
        if($delete) {
            // Configura el estado de respuesta y un mensaje de éxito.
            $resp['status'] = 'success';
            $_SESSION['flashdata']['type'] = 'success'; // Define un tipo de mensaje para flash data.
            $_SESSION['flashdata']['msg'] = 'Usuario eliminado satisfactiriamente.'; // Mensaje para notificar que el usuario se eliminó con éxito.
        } else {
            // Si la eliminación falló, devuelve un estado de error.
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error; // Guarda el error de la base de datos.
        }
    
        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }
    
    function update_credentials() {
        // Extrae las variables del array POST.
        extract($_POST);
    
        // Prepara la cadena de datos para la actualización.
        $data = "";
        
        // Recorre las variables POST para generar los datos a actualizar.
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id', 'old_password')) && !empty($v)) {
                if(!empty($data)) 
                    $data .= ","; 
                
                if($k == 'password') 
                    $v = md5($v); // Si es la contraseña, encripta con MD5.
                
                $data .= " `{$k}` = '{$v}' "; // Agrega los datos a actualizar.
            }
        }
    
        // Verifica si la contraseña antigua proporcionada es incorrecta.
        if(!empty($password) && md5($old_password) != $_SESSION['password']) {
            $resp['status'] = 'failed'; // Estado de error.
            $resp['msg'] = "Old password is incorrect."; // Mensaje de error.
        } else {
            // Consulta SQL para actualizar las credenciales del usuario en la base de datos.
            $sql = "UPDATE `user_list` set {$data} WHERE user_id = '{$_SESSION['user_id']}'";
            
            // Intenta guardar los cambios.
            @$save = $this->db->query($sql);
            
            // Verifica si la actualización fue exitosa.
            if($save) {
                $resp['status'] = 'success'; // Estado de éxito.
                $_SESSION['flashdata']['type'] = 'success'; // Mensaje flash de tipo éxito.
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.'; // Mensaje de éxito.
                
                // Actualiza la sesión con los nuevos valores.
                foreach($_POST as $k => $v) {
                    if(!in_array($k, array('id', 'old_password')) && !empty($v)) {
                        if($k == 'password') 
                            $v = md5($v); // Encripta la contraseña antes de guardar en la sesión.
                        
                        $_SESSION[$k] = $v; // Guarda en la sesión.
                    }
                }
            } else {
                // Si la actualización falla, devuelve un estado de error.
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: ' . $this->db->error; // Mensaje de error con el detalle del error de la base de datos.
                $resp['sql'] = $sql; // Almacena la consulta SQL.
            }
        }
    
        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }
    
    function save_category() {
        // Extrae las variables del array POST.
        extract($_POST);

        // Prepara la cadena de datos para la inserción o actualización.
        $data = "";

        // Recorre las claves y valores del array POST.
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id'))) { // Excluye el campo 'id'.
                $v = addslashes(trim($v)); // Escapa y recorta el valor.
                if(empty($id)) {
                    // Para una nueva categoría, crea listas de columnas y valores.
                    $cols[] = "`{$k}`";
                    $vals[] = "'{$v}'";
                } else {
                    // Para actualizar, construye el fragmento de actualización.
                    if(!empty($data)) 
                        $data .= ", ";
                    
                    $data .= " `{$k}` = '{$v}' ";
                }
            }
        }

        // Verifica si se definieron las columnas y valores.
        if(isset($cols) && isset($vals)) {
            $cols_join = implode(",", $cols); // Une las columnas para la inserción.
            $vals_join = implode(",", $vals); // Une los valores para la inserción.
        }

        // Prepara la consulta SQL para insertar o actualizar la categoría.
        if(empty($id)) {
            $sql = "INSERT INTO `category_list` ({$cols_join}) VALUES ({$vals_join})"; // Inserción para nuevas categorías.
        } else {
            $sql = "UPDATE `category_list` set {$data} WHERE category_id = '{$id}'"; // Actualización para categorías existentes.
        }

        // Verifica si el nombre de la categoría ya existe.
        @$check = $this->db->query("SELECT COUNT(category_id) as count FROM `category_list` WHERE `name` = '{$name}' ".($id > 0 ? " AND category_id != '{$id}'" : ""))->fetch_array()['count'];
        
        if(@$check > 0) {
            $resp['status'] = 'failed'; // Estado de error.
            $resp['msg'] = 'Category already exists.'; // Mensaje de error si la categoría ya existe.
        } else {
            // Intenta guardar o actualizar la categoría.
            @$save = $this->db->query($sql); 
            
            if($save) {
                $resp['status'] = "success"; // Estado de éxito.
                if(empty($id))
                    $resp['msg'] = "Category successfully saved."; // Mensaje para nueva categoría.
                else
                    $resp['msg'] = "Category successfully updated."; // Mensaje para categoría actualizada.
            } else {
                $resp['status'] = "failed"; // Estado de error.
                if(empty($id))
                    $resp['msg'] = "Saving New Category Failed."; // Mensaje para error en creación de nueva categoría.
                else
                    $resp['msg'] = "Updating Category Failed."; // Mensaje para error en actualización de categoría.
                
                $resp['error'] = $this->db->error; // Mensaje con detalle del error de la base de datos.
            }
        }

        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }

    function delete_category() {
        // Extrae las variables del array POST.
        extract($_POST);

        // Actualiza la base de datos para marcar la categoría como eliminada usando un 'delete_flag'.
        @$update = $this->db->query("UPDATE `category_list` set `delete_flag` = 1 WHERE category_id = '{$id}'");

        // Verifica si la actualización fue exitosa.
        if($update) {
            $resp['status'] = 'success'; // Estado de éxito.
            $_SESSION['flashdata']['type'] = 'success'; // Tipo de mensaje para flash data.
            $_SESSION['flashdata']['msg'] = 'Category successfully deleted.'; // Mensaje de éxito.
        } else {
            $resp['status'] = 'failed'; // Estado de error.
            $resp['error'] = $this->db->error; // Mensaje con detalle del error de la base de datos.
        }

        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }

    function save_product() {
        // Extrae las variables del array POST.
        extract($_POST);
    
        // Prepara la cadena de datos para inserción o actualización.
        $data = "";
    
        // Recorre las claves y valores del array POST.
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id'))) { // Excluye el campo 'id'.
                $v = addslashes(trim($v)); // Escapa y recorta el valor.
                if(empty($id)) {
                    // Para un nuevo producto, crea listas de columnas y valores.
                    $cols[] = "`{$k}`";
                    $vals[] = "'{$v}'";
                } else {
                    // Para actualizar, construye el fragmento de actualización.
                    if(!empty($data)) 
                        $data .= ", ";
                    
                    $data .= " `{$k}` = '{$v}' ";
                }
            }
        }
    
        // Si existen columnas y valores definidos, únelos para inserción o actualización.
        if(isset($cols) && isset($vals)) {
            $cols_join = implode(",", $cols); // Une las columnas.
            $vals_join = implode(",", $vals); // Une los valores.
        }
    
        // Dependiendo de si 'id' está vacío, decide si se trata de un inserto o actualización.
        if(empty($id)) {
            $sql = "INSERT INTO `product_list` ({$cols_join}) VALUES ({$vals_join})"; // Inserción para nuevos productos.
        } else {
            $sql = "UPDATE `product_list` set {$data} WHERE product_id = '{$id}'"; // Actualización para productos existentes.
        }
    
        // Verifica si el código o nombre del producto ya existen, evitando duplicados.
        @$check = $this->db->query("SELECT COUNT(product_id) as count FROM `product_list` WHERE `product_code` = '{$product_code}' AND delete_flag = 0 ".($id > 0 ? "AND product_id != '{$id}'" : ""))->fetch_array()['count'];
        @$check2 = $this->db->query("SELECT COUNT(product_id) as count FROM `product_list` WHERE `name` = '{$name}' AND delete_flag = 0 ".($id > 0 ? "AND product_id != '{$id}'" : ""))->fetch_array()['count'];
    
        // Si el código o nombre del producto ya existe, devuelve un estado de error.
        if(@$check > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Product Code already exists.';
        } elseif (@$check2 > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Product Name already exists.';
        } else {
            // Intenta guardar o actualizar el producto.
            @$save = $this->db->query($sql);
    
            // Si la operación es exitosa, devuelve un estado de éxito.
            if($save) {
                $resp['status'] = "success";
                if(empty($id))
                    $resp['msg'] = "Product successfully saved."; // Mensaje para nuevo producto.
                else
                    $resp['msg'] = "Product successfully updated."; // Mensaje para producto actualizado.
            } else {
                // Si la operación falla, devuelve un estado de error.
                $resp['status'] = "failed";
                if(empty($id))
                    $resp['msg'] = "Saving New Product Failed."; // Mensaje para error en nuevo producto.
                else
                    $resp['msg'] = "Updating Product Failed."; // Mensaje para error en actualización.
                
                $resp['error'] = $this->db->error; // Guarda el error de la base de datos.
            }
        }
    
        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }
    
    function delete_product() {
        // Extrae las variables del array POST.
        extract($_POST);
    
        // Actualiza la base de datos para marcar el producto como eliminado usando un 'delete_flag'.
        @$update = $this->db->query("UPDATE `product_list` set delete_flag = 1 WHERE product_id = '{$id}'");
    
        // Verifica si la actualización fue exitosa.
        if($update) {
            $resp['status'] = 'success';
            $_SESSION['flashdata']['type'] = 'success'; // Define el tipo de mensaje para flash data.
            $_SESSION['flashdata']['msg'] = 'Product successfully deleted.'; // Mensaje de éxito para notificar la eliminación del producto.
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error; // Guarda el error de la base de datos.
        }
    
        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }
    
    function save_stock() {
        // Extrae las variables del array POST.
        extract($_POST);
    
        // Prepara la cadena de datos para inserción o actualización.
        $data = "";
    
        // Recorre las claves y valores del array POST.
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id'))) { // Excluye el campo 'id'.
                $v = addslashes(trim($v)); // Escapa y recorta el valor.
                if(empty($id)) {
                    // Para nuevo stock, crea listas de columnas y valores.
                    $cols[] = "`{$k}`";
                    $vals[] = "'{$v}'";
                } else {
                    // Para actualizar stock, construye el fragmento de actualización.
                    if(!empty($data)) 
                        $data .= ", ";
                    
                    $data .= " `{$k}` = '{$v}' ";
                }
            }
        }
    
        // Si existen columnas y valores definidos, únelos para inserción o actualización.
        if(isset($cols) && isset($vals)) {
            $cols_join = implode(",", $cols); // Une las columnas.
            $vals_join = implode(",", $vals); // Une los valores.
        }
    
        // Dependiendo de si 'id' está vacío, se decide si es un inserto o una actualización.
        if(empty($id)) {
            $sql = "INSERT INTO `stock_list` ({$cols_join}) VALUES ($vals_join)"; // Inserción para nuevo stock.
        } else {
            $sql = "UPDATE `stock_list` set {$data} WHERE stock_id = '{$id}'"; // Actualización para stock existente.
        }
    
        // Intenta guardar o actualizar el stock.
        @$save = $this->db->query($sql);
    
        // Verifica si la operación fue exitosa.
        if($save) {
            $resp['status'] = "success"; // Estado de éxito.
            if(empty($id))
                $resp['msg'] = "Stock successfully saved."; // Mensaje para nuevo stock.
            else
                $resp['msg'] = "Stock successfully updated."; // Mensaje para stock actualizado.
        } else {
            // Si la operación falla, devuelve un estado de error.
            $resp['status'] = "failed";
            if(empty($id))
                $resp['msg'] = "Saving New Stock Failed."; // Mensaje para error en nuevo stock.
            else
                $resp['msg'] = "Updating Stock Failed."; // Mensaje para error en stock actualizado.
            
            $resp['error'] = $this->db->error; // Guarda el error de la base de datos.
        }
    
        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }
    
    function delete_stock() {
        // Extrae las variables del array POST.
        extract($_POST);
    
        // Ejecuta la consulta SQL para eliminar el stock con el ID proporcionado.
        @$delete = $this->db->query("DELETE FROM `stock_list` WHERE stock_id = '{$id}'");
    
        // Verifica si la operación fue exitosa.
        if($delete) {
            $resp['status'] = 'success'; // Estado de éxito.
            $_SESSION['flashdata']['type'] = 'success'; // Tipo de mensaje para flash data.
            $_SESSION['flashdata']['msg'] = 'Stock successfully deleted.'; // Mensaje para indicar que el stock fue eliminado con éxito.
        } else {
            $resp['status'] = 'failed'; // Estado de error.
            $resp['error'] = $this->db->error; // Detalle del error de la base de datos.
        }
    
        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }
    
    function save_transaction() {
        // Extrae las variables del array POST.
        extract($_POST);
    
        // Variable para almacenar la cadena de datos para inserción o actualización.
        $data = "";
    
        // Genera un número de recibo único basado en el tiempo actual.
        $receipt_no = time(); 
        $i = 0;
        // Asegura que el número de recibo sea único incrementando hasta que no haya duplicados.
        while(true) {
            $i++;
            $chk = $this->db->query("SELECT count(transaction_id) `count` FROM `transaction_list` WHERE receipt_no = '{$receipt_no}' ")->fetch_array()['count'];
            if($chk > 0) { // Si el número de recibo ya existe, añade el contador al final.
                $receipt_no = time() . $i; 
            } else {
                break; // Si el número de recibo es único, sale del bucle.
            }
        }
    
        // Añade el número de recibo y el ID del usuario a los datos de POST.
        $_POST['receipt_no'] = $receipt_no; 
        $_POST['user_id'] = $_SESSION['user_id']; 
    
        // Recorre las claves y valores del array POST para construir los datos.
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id')) && !is_array($_POST[$k])) { // Excluye el campo 'id'.
                $v = addslashes(trim($v)); // Escapa y recorta el valor.
                if(empty($id)) { // Para una nueva transacción.
                    $cols[] = "`{$k}`"; // Lista de columnas para inserción.
                    $vals[] = "'{$v}'"; // Lista de valores para inserción.
                } else { // Para actualizar una transacción existente.
                    if(!empty($data)) 
                        $data .= ", ";
                    
                    $data .= " `{$k}` = '{$v}' "; // Fragmento de actualización.
                }
            }
        }
    
        // Une las columnas y valores para inserción o actualización.
        if(isset($cols) && isset($vals)) {
            $cols_join = implode(",", $cols); 
            $vals_join = implode(",", $vals);
        }
    
        // Prepara la consulta SQL para insertar o actualizar la transacción.
        if(empty($id)) { 
            $sql = "INSERT INTO `transaction_list` ({$cols_join}) VALUES ($vals_join)"; 
        } else {
            $sql = "UPDATE `transaction_list` set {$data} WHERE stock_id = '{$id}'"; 
        }
    
        // Ejecuta la consulta para guardar la transacción.
        @$save = $this->db->query($sql);
    
        // Verifica si la operación fue exitosa.
        if($save) {
            $resp['status'] = "success"; // Estado de éxito.
            $_SESSION['flashdata']['type'] = "success"; // Tipo de mensaje para flash data.
            if(empty($id)) {
                $_SESSION['flashdata']['msg'] = "Transaction successfully saved."; // Mensaje para nueva transacción.
                $last_id = $this->db->insert_id; // Obtiene el ID de la última inserción.
                $tid = empty($id) ? $last_id : $id; // Determina el ID de la transacción.
            } else {
                $_SESSION['flashdata']['msg'] = "Transaction successfully updated."; // Mensaje para transacción actualizada.
            }
    
            // Si es una nueva transacción, construye los datos para `transaction_items`.
            if(empty($id)) {
                $data = "";
                foreach($product_id as $k => $v) {
                    if(!empty($data)) 
                        $data .= ","; 
                    
                    // Datos para `transaction_items`.
                    $data .= "('{$tid}', '{$v}', '{$quantity[$k]}', '{$price[$k]}')"; 
                }
    
                // Si hay datos para insertar, elimina los existentes y luego inserta los nuevos.
                if(!empty($data)) {
                    $this->db->query("DELETE FROM transaction_items WHERE transaction_id = '{$tid}'");
                    $sql = "INSERT INTO transaction_items (`transaction_id`, `product_id`, `quantity`, `price`) VALUES {$data}"; 
                    $save = $this->db->query($sql); // Inserta los datos.
                }
    
                // Incluye el ID de la transacción en la respuesta.
                $resp['transaction_id'] = $tid;
            }
        } else {
            // Si la operación falla, devuelve un estado de error.
            $resp['status'] = "failed"; 
            if(empty($id)) {
                $resp['msg'] = "Saving New Transaction Failed."; // Mensaje para error en nueva transacción.
            } else {
                $resp['msg'] = "Updating Transaction Failed."; // Mensaje para error en actualización.
            }
            $resp['error'] = $this->db->error; // Detalle del error de la base de datos.
        }
    
        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }
    
    function delete_transaction() {
        // Extrae las variables del array POST.
        extract($_POST);
    
        // Ejecuta la consulta para eliminar la transacción con el ID proporcionado.
        @$delete = $this->db->query("DELETE FROM `transaction_list` WHERE transaction_id = '{$id}'");
    
        // Verifica si la operación fue exitosa.
        if($delete) {
            $resp['status'] = 'success'; // Estado de éxito.
            $_SESSION['flashdata']['type'] = 'success'; // Tipo de mensaje para flash data.
            $_SESSION['flashdata']['msg'] = 'Transaction successfully deleted.'; // Mensaje de éxito para notificar que la transacción se eliminó.
        } else {
            $resp['status'] = 'failed'; // Estado de error.
            $resp['error'] = $this->db->error; // Detalle del error de la base de datos.
        }
    
        // Devuelve la respuesta en formato JSON.
        return json_encode($resp);
    }
    
}
$a = isset($_GET['a']) ? $_GET['a'] : ''; // Obtiene el valor del parámetro 'a' de la URL.
$action = new Actions(); // Crea una instancia de la clase 'Actions'.

switch($a) { // Ejecuta una acción según el valor de 'a'.
    case 'login':
        echo $action->login(); // Acción de inicio de sesión.
        break;
    case 'customer_login':
        echo $action->customer_login(); // Acción de inicio de sesión para clientes.
        break;
    case 'logout':
        echo $action->logout(); // Acción de cierre de sesión.
        break;
    case 'customer_logout':
        echo $action->customer_logout(); // Acción de cierre de sesión para clientes.
        break;
    case 'save_user':
        echo $action->save_user(); // Acción para guardar un usuario.
        break;
    case 'delete_user':
        echo $action->delete_user(); // Acción para eliminar un usuario.
        break;
    case 'update_credentials':
        echo $action->update_credentials(); // Acción para actualizar credenciales.
        break;
    case 'save_category':
        echo $action->save_category(); // Acción para guardar una categoría.
        break;
    case 'delete_category':
        echo $action->delete_category(); // Acción para eliminar una categoría.
        break;
    case 'save_product':
        echo $action->save_product(); // Acción para guardar un producto.
        break;
    case 'delete_product':
        echo $action->delete_product(); // Acción para eliminar un producto.
        break;
    case 'save_stock':
        echo $action->save_stock(); // Acción para guardar stock.
        break;
    case 'delete_stock':
        echo $action->delete_stock(); // Acción para eliminar stock.
        break;
    case 'save_transaction':
        echo $action->save_transaction(); // Acción para guardar una transacción.
        break;
    case 'delete_transaction':
        echo $action->delete_transaction(); // Acción para eliminar una transacción.
        break;
    default:
        // Acción por defecto si no se encuentra un caso coincidente.
        break;
}
