<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Conexion
 *
 * @author Ismael
 */
class Conexion {
    private static $host = 'db';
    private static $usuario = 'ismael';
    private static $password = '123456';
    private static $baseDatos = 'tienda';
    
    private static $conexion;
    
    public static function conexionBD() {
        if (self::$conexion == null) {
            try {
                self::$conexion = new mysqli(self::$host,self::$usuario,self::$password,self::$baseDatos);
                self::$conexion->set_charset("utf8mb4");
                self::$conexion->query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_spanish_ci'");
            } catch (Exception $e) {
                $mensaje = "Error: " . $e->getMessage();
            }
        }
        return self::$conexion;
    }
    
    // Cerrar conexión estática
    public static function cerrarConexionBD() {
        if (self::$conexion != null) {
            self::$conexion->close();
            self::$conexion = null;
        }
    }
}