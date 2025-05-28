<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';
// Si no existe la sesión del usuario y su rol no es admin lo redirijo a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
// Comrpuebo si existe la variable del campo oculto que es origen
if (filter_has_var(INPUT_POST, 'origen')) {
    /* valido todos los campos incluido el campo oculto con funciones de validacion para evitar inyecciones XSS o html,
    el campo oculto origen lo utilizo para saber si el formulario viene del archivo jerseys.php o chaquetas.php */
    $origen = validarCadena(filter_input(INPUT_POST, 'origen'));

    $cod_producto = validarCodigoProducto(filter_input(INPUT_POST, 'cod_producto'));
    $nombre = validarNombre(filter_input(INPUT_POST, 'nombre'));
    $modelo = validarModelo(filter_input(INPUT_POST, 'modelo'));
    $descripcion = validarCadena(filter_input(INPUT_POST, 'descripcion'));
    $precioProducto = validarPrecio(filter_input(INPUT_POST, 'precio'));
    // Creo una variable para hacer conexión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();
    // Creo una variable con los orígenes permitidos
    $origenesPermitidos = ['jerseys', 'chaquetas']; 
    // Si el origen no es válido o no está en el array de permitidos lo redirijo a la página principal
    if (!in_array($origen, $origenesPermitidos) || !$origen) {
        $_SESSION['error'] = 'Origen desconocido';
        header("Location: ../index.php");
        exit();
    }
    $nuevaImagen = '';

    //Aqui compruebo si se ha enviado el archivo imagen y no ha habido ningún error en la subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Se obtiene el nombre del archivo sin los directorios
        $nombreImagen = basename($_FILES['imagen']['name']);
        // Aquí especifico la ruta donde se va a guardar el archivo
        $rutaDestino = 'imagenes/productos/' . $nombreImagen;
        // Aquí especifico la ruta donde actualmente se encuentra el sistema de archivos
        $rutaServidor = '../' . $rutaDestino;
        // Aqui me creo un array con las extensiones que voy a permitir
        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        // Aquí convierto la extensión del archivo a minúsculas si el usuario ha introducido la extensión con mayúsculas
        $extensionArchivo = strtolower(pathinfo($nombreImagen, PATHINFO_EXTENSION));
        /* Si la extensión del archivo no coincide con las extensiones permitidas del array redirijo al usuario al origen de donde proviene
        el envío del formulario y le muestro un mensaje de error */
        if (!in_array($extensionArchivo, $extensionesPermitidas)) {
            $_SESSION['error'] = "Solo se permiten imágenes JPG, JPEG o PNG.";
            header('Location: ../' . $origen . '.php');
            exit();
        }
        /* Aquí intenta mover el archivo desde la ruta donde el usuario ha seleccionado el archivo hasta la ruta donde se guardarán las
        imágenes en el sistema de archivos, si esto falla lo redirijo a donde proviene el envío del formulario y le muestro un mensaje de error */
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaServidor)) {
            $_SESSION['error'] = "Error al subir la nueva imagen.";
            header('Location: ../' . $origen . '.php');
            exit();
        }

        $nuevaImagen = $rutaDestino;
    } 
    // Si alguno de los campos no es correcto le muestro un mensaje de error
    if (!$cod_producto || !$nombre || !$modelo || !$descripcion || !$precioProducto) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: ../' . $origen . '.php');
        exit();
    } else {
        // Si no hay ningún campo inválido procedo con la inserción en la base de datos
        if ($nuevaImagen !== '') {
            /* Si el campo de imagen no está vacío quiere decir que el usuario ha seleccionado una imagen, por lo que ejecuto la actualización
            en la base de datos incluyendo la imagen */
            $editarProducto = $conexionBaseDatos->prepare("UPDATE Productos SET nombre = ?, modelo = ?, descripcion = ?, precio = ?, imagen = ? WHERE cod_producto = ?");
            $editarProducto->bind_param("sisdss", $nombre, $modelo, $descripcion, $precioProducto, $nuevaImagen, $cod_producto);
        } else {
            /* Si el campo imagen está vacío quiere decir que el usuario no ha seleccionado una imagen por lo que se ejecuta la actualización
            en la base de datos in incluir la imagen */
            $editarProducto = $conexionBaseDatos->prepare("UPDATE Productos SET nombre = ?, modelo = ?, descripcion = ?, precio = ? WHERE cod_producto = ?");
            $editarProducto->bind_param("sisds", $nombre, $modelo, $descripcion, $precioProducto, $cod_producto);
        }

        if ($editarProducto->execute()) {
            if ($editarProducto->affected_rows > 0) {
                /* Si la actualización se ha ejecutado correctamente y el número de filas afectadas es mayor que 0 quiere decir que la 
                actualización en la base de datos se hizo correctamente por lo que mostraré un mensaje de exito */
                $_SESSION['mensaje'] = "Producto editado correctamente.";
            } else {
                /* Si la actualización se ha ejecutado correctamente pero el número de filas afectadas es 0 quiere decir que la 
                actualización en la base de datos no se ha producido por lo que mostraré un mensaje de error */
                $_SESSION['error'] = "No se realizaron los cambios porque los datos de los campos son iguales.";
            }
        } else {
            // Si la ejecución de la actualización falla, mostraré un mensaje de error
            $_SESSION['error'] = "Error al editar el producto.";
        }
        // Cierro la consulta de actualización
        $editarProducto->close();
    }
    // Cierro la conexión con la base de datos
    Conexion::cerrarConexionBD();
    /* Aquí redirijo al origen de donde proviene el envío del formulario y muestro los mensajes de error o éxito dependiendo de lo que haya
    ocurrido */
    header('Location: ../' . $origen . '.php');
    exit();
}
?>
