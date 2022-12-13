<?php

function carga($nombre_clase) {

    $file = __DIR__ . DIRECTORY_SEPARATOR .'html'.DIRECTORY_SEPARATOR. str_replace('\\', DIRECTORY_SEPARATOR, $nombre_clase) . ".php";


    if ($file != "") {
        if (file_exists($file)) {
            include $file;
        }
    }
}

spl_autoload_register("carga");
