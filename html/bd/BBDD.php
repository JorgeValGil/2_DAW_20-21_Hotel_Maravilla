<?php

namespace bd;


/**
 * Clase BBDD
 * 
 * Contiene las funciones necesarias para la conexión con la base de datos.
 * 
 * @author Adrian Fernandez Perez y Jorge Val Gil
 * @version 1.0
 */
class BBDD {

    //Variables
    private $nombre;
    private $password;
    private $servidor;
    private $bd;
    public $PDO;

    
    /**
     * Constructor de la clase
     * 
     * TryCatch en el cual obtenemos los valores del archivo xml y validación del xsd y creamos un objeto PDO con los datos
     * 
     * @see leer_config()
     * 
     * @param mixed $rol Rol del usuario
     */
    public function __construct($rol) {
        try {
            $res = $this->leer_config(__DIR__ . "/../../config/configuracion.xml", __DIR__ . "/../../config/configuracion.xsd", $rol);
            $this->servidor = $res[0];
            $this->bd = $res[1];
            $this->nombre = $res[2];
            $this->password = $res[3];
            $this->PDO = new \PDO("mysql:dbname=" . $this->bd . ";host=" . $this->servidor, $this->nombre, $this->password);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }


    
    
    /**
     * Función leer_config
     * 
     * Función que lee los archivos de configuración, los valida etc.
     * 
     * @param mixed $fichero_config_BBDD archivo xml
     * @param mixed $esquema archivo xsd
     * @param mixed $rol Rol del usuario
     * 
     * @return array Devuelve un array con los datos de conexión
     */
    function leer_config($fichero_config_BBDD, $esquema, $rol) {
        $config = new \DOMDocument();
        $config->load($fichero_config_BBDD);
        $res = $config->schemaValidate($esquema);
        if ($res === FALSE) {
            throw new InvalidArgumentException("Revise el fichero de configuración");
        }
        $datos = simplexml_load_file($fichero_config_BBDD);
        $array = [
            "" . $datos->xpath('//servidor[../rol="' . $rol . '"]')[0],
            "" . $datos->xpath('//bd[../rol="' . $rol . '"]')[0],
            "" . $datos->xpath('//nombre[../rol="' . $rol . '"]')[0],
            "" . $datos->xpath('//password[../rol="' . $rol . '"]')[0]
        ];

        return $array;
    }

}
