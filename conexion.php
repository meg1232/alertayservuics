<?php
class Conexion {
    private $host = "bjuwfa0aljnprvf2c47l-mysql.services.clever-cloud.com";
    private $usuario = "usabwq64z9xbvt3j";
    private $clave = "P2MxeGpPtj1eeY8G3Fv9";
    private $bd = "bjuwfa0aljnprvf2c47l";
    private $puerto = 3306;
    private $conexion;

    public function __construct() {
        try {
            $this->conexion = new PDO("mysql:host=$this->host;port=$this->puerto;dbname=$this->bd;charset=utf8", $this->usuario, $this->clave);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function obtenerConexion() {
        return $this->conexion;
    }
}
?>