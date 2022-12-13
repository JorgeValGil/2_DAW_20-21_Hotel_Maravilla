<?php

namespace cabeceras;

/**
 * Clase Cabeceras
 * 
 * Contiene funciones que muestran boton de cerrar sesión
 * 
 * @author Adrian Fernandez Perez y Jorge Val Gil
 * @version 1.0
 */
class Cabeceras {

    /**
     * Función cabecera
     * 
     * Devuelve un botón para cerrar sesión desde una ruta especifica
     * 
     */
    function cabecera() {
        if (isset($_SESSION['usuario'])) {
            echo "<div style='text-align: right; margin-right: 6%;'>
        <div><a href='logout.php'>Logout</a></div>
    </div>";
        }
    }

    /**
     * Función cabecera
     * 
     * Devuelve un botón para cerrar sesión
     * 
     */
    function cabecera1() {
        if (isset($_SESSION['usuario'])) {
            echo "<div style='text-align: right; margin-right: 6%;'>
        <div><a href='../logout.php'>Logout</a></div>
    </div>";
        }
    }

    /**
     * Función cabecera
     * 
     * Devuelve un botón para cerrar sesión
     * 
     */
    function cabeceraIndex() {
        if (isset($_SESSION['usuario'])) {
            echo "<div style='text-align: right; margin-right: 6%;'>
        <div><a href='html/logout.php'>Logout</a></div>
    </div>";
        }
    }

}

?>