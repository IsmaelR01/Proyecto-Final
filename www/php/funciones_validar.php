<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

function validarCadena($campo) {
    
    $campo = trim($campo ?? '');
      
    $campo = strip_tags($campo);
    $campo = htmlspecialchars($campo, ENT_QUOTES, 'UTF-8');
    
   return $campo;
}

function validarRadio($parametro, $parametrosValidos) {
    return in_array($parametro, $parametrosValidos) ? $parametro : false;
}

function validarCheck($valor) {
    return (is_array($valor) && count($valor) > 0) ? $valor : false;
}
     
function validarEdad($edad) {
    $edadArreglada = filter_var($edad, FILTER_SANITIZE_NUMBER_INT);
    return filter_var($edadArreglada, FILTER_VALIDATE_INT, ["options" => ["min_range" => 18, "max_range" => 100]]);
}

function validarNombre($nombre) {
    $nombreArreglado = validarCadena($nombre); 
   
    $patron = '/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*$/';
    return (preg_match($patron, $nombreArreglado)) ? $nombreArreglado : false;
}

function validarEmail($email) {
    $emailArreglado = filter_var($email, FILTER_SANITIZE_EMAIL);
    $patron = '/^[^_]+@[^_]+(\.es|\.com)$/';

    return (filter_var($emailArreglado, FILTER_VALIDATE_EMAIL) && preg_match($patron, $emailArreglado)) ? $emailArreglado : false;
}

function validarURL($url) {
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $patron = '/^https?:\/\/(www\.([a-zA-Z0-9\/]*)?)\.(es|com)(\/.*)?$/';

    return (filter_var($url, FILTER_VALIDATE_URL) && preg_match($patron, $url)) ? $url : false;
}

function validarDni($dni) {
    $dni = trim($dni);

    $dni = strip_tags($dni);

    $patron = '/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/';

    if (preg_match($patron, $dni)) {
        // Validamos la letra según el número del DNI
        $letraEsperada = substr("TRWAGMYFPDXBNJZSQVHLCKE", intval(substr($dni, 0, 8)) % 23, 1);
        
        // Comprobamos si la letra al final del DNI coincide
        if (strtoupper(substr($dni, -1)) === $letraEsperada) {
            $dniValido = $dni;  
        } else {
            $dniValido = false;  
        }
    } else {
        $dniValido = false;  
    }

    return $dniValido;  
}


function validarIdentificador($identificador) {
    $identificador = trim($identificador);
    $identificador = strip_tags($identificador);
    
    $patron = '/[A-Z]{3}[0-9]{5}$/';
    
    return preg_match($patron, $identificador) ? $identificador : false;
}

function validarPrecio($precio) {
    $precio = filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    return filter_var($precio, FILTER_VALIDATE_FLOAT, [
        "options" => [
            "min_range" => 9.99,
            "max_range" => 999.99
        ]
    ]);
}

function validarMatricula($matricula) {
    $matricula = trim($matricula);
    
    $matricula = strip_tags($matricula);
    
    
    $matricula = htmlspecialchars($matricula, ENT_QUOTES,'UTF-8'); 
    
    $patron = "/^[0-9]{4}[BCDFGHJKLMNPRSTVWXYZ]{3}$/";
    
    return preg_match($patron, $matricula) ? $matricula : false;
}

function validarUsuario($usuario) {
    $usuario = validarCadena($usuario);
    $patron = "/^[A-Za-z][a-z0-9]{0,11}$/";
    return preg_match($patron,$usuario) ? $usuario : false;
}

function validarContrasena($contrasena) {
    $contrasena = validarCadena($contrasena);
    $patron = "/^[A-Z][a-z0-9]*[.]?[a-z0-9]*$/";
    return preg_match($patron,$contrasena) ? $contrasena : false;
}

function validarCodigoEspectaculo($codigoEspec) {
    $codigoEspec = validarCadena($codigoEspec);
    $patron = "/^[A-Z]{3}$/";
    return preg_match($patron, $codigoEspec) ? $codigoEspec : false;
}

function validarCodigoProducto($cod_producto) {
    $cod_producto = validarCadena($cod_producto);
    $patron = "/^[CJ][0-9]{4}$/";
    return preg_match($patron, $cod_producto) ? $cod_producto : false;
}

function validarModelo($modelo) {
    $modeloArreglado = filter_var($modelo, FILTER_SANITIZE_NUMBER_INT);
    return filter_var($modeloArreglado, FILTER_VALIDATE_INT, ["options" => ["min_range" => 2010, "max_range" => 2035]]);
}

function validarCif($cif) {
    $cif = validarCadena($cif);
    $patron = "/^[A-Z][0-9]{7}[A-Z0-9]?$/i";
    return preg_match($patron, $cif) ? $cif : false;
}

function validarTelefono($telefono) {
    $telefono = validarCadena($telefono);
    $patron = "/^[6789][0-9]{8}$/";
    return preg_match($patron, $telefono) ? $telefono : false;
}

function validarDireccion($direccion) {
    $direccion = validarCadena($direccion);
    $patron = '/^(Calle|Avenida|Plaza)\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+,\s*\d{1,4},\s*\d{5}\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+(\s*\([a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+\))?$/';
    return preg_match($patron, $direccion) ? $direccion : false;
}

function validarCantidad($cantidad) {
    $cantidad = filter_var($cantidad, FILTER_SANITIZE_NUMBER_INT);
    return filter_var($cantidad, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 5]]);
}