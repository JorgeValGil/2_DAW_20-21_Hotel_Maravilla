<?php

namespace functionsUsers;

/**
 * Clase coas funcións que necesitan rol de conexion
 * @version 1.0
 * @author Jorge Val Gil e Adrián Fernández Pérez
 */
Class Conexion {

    /**
     * Contructor de clase
     */
    public function __construct() {
        
    }

    /**
     * Comprobación de email
     * Comproba se existe un email na base de datos
     * @param string $email email a comprobar
     * 
     * @return boolean devolve true se o correo xa está rexistrado. false se non está rexistrado.
     */
    function comprobar_email($email) {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select email from usuarios where email=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(
                        $email[0]
                    ))) {
                if ($stmt->rowCount() != 0) {
                    return false;
                } else {
                    return true;
                }
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido comprobar el email.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Devolve datos dun usuario
     * Úsase para o inicio de sesión. Recibe un email e unha clave, se obten resultados da consulta, comproba a clave e devolve os datos ou false
     * @param mixed $nombre email dun usuario
     * @param mixed $clave clave do usuario
     * 
     * @return mixed devolve false se a clave non é correcta ou se non existe o email. Devolve un array de datos se todo foi correcto
     */
    function comprobar_usuario($nombre, $clave) {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = "select nombre, rol_usuario, password, id from usuarios where email=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array($nombre))) {
                $fila = $stmt->fetch();
                if ($fila) {
                    $nombrepersonal = $fila['nombre'];
                    $hash = $fila['password'];
                    $rol = $fila['rol_usuario'];
                    $id = $fila['id'];
                    if (password_verify($clave, $hash)) {
                        return array($nombre, $rol, $nombrepersonal, $id);
                    } else {
                        return false;
                    }
                }
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga as habitaciones
     * Carga todas as habitaciones do hotel
     * @return string devolve un código html no que envía o nome da habitación e a súa descripción
     */
    function cargar_habitaciones() {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select tipo_habitacion, descripcion from habitacion_tipo';
            $stmt = $pdo->query($sql);
            $filas = $stmt->fetchAll();
            $texto = '';
            foreach ($filas as $fila) {
                $nombre_habitacion = $fila['tipo_habitacion'];
                $descripcion = $fila['descripcion'];
                $room = $nombre_habitacion . " Room";
                $texto .= "<div class='col-md-4 col-sm-6 mb-4'>
                    <div class='card h-100  mt-1 border-dark '>
                        <img class='card-img-top' src='../images/rooms/" . $nombre_habitacion . "/" . $nombre_habitacion . ".jpg' alt='.$room.'>
                        <div class='card-body'>
                            <a href='room.php?tipo=" . $nombre_habitacion . "' class='tipo_habitacion' name=" . $nombre_habitacion . ">
                                <h5 class='card-title text-center'>" . $room . "</h5>
                            </a>
                        </div>
                        <hr>
                        <div class='card-body'>
                                    <p class='card-text'>" . $descripcion . "</p>
                                </div>
                    </div>
                </div>";
            }
            return $texto;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar el tipo de habitación.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga un tipo de habitación
     * Carga un tipo de habitación que recibe como parámetro
     * @param mixed $tipo_habitacion nome dun tipo de habitación
     * 
     * @return string devolve un código html no que envía o tipo da habitación e a súa descripción
     */
    function cargar_habitacion($tipo_habitacion) {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select tipo_habitacion, descripcion from habitacion_tipo where tipo_habitacion=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(
                        $tipo_habitacion[0]
                    ))) {

                $fila = $stmt->fetch();
                if ($fila) {
                    $nombre_habitacion = $fila['tipo_habitacion'];
                    $descripcion = $fila['descripcion'];
                    $room = $nombre_habitacion . " Room";
                    return "<div class='col-md-4 col-sm-6 mb-4'>
                    <div class='card mb-1 mt-1 border-dark h-100'>
                        <img class='card-img-top' src='images/rooms/" . $nombre_habitacion . "/" . $nombre_habitacion . ".jpg' alt='.$room.'>
                        <div class='card-body'>
                            <a href='html/room.php?tipo=" . $nombre_habitacion . "' class='tipo_habitacion' name=" . $nombre_habitacion . ">
                                <h5 class='card-title text-center'>" . $room . "</h5>
                            </a>
                        </div>
                        <hr>
                        <div class='card-body'>
                                    <p class='card-text'>" . $descripcion . "</p>
                                </div>
                    </div>
                </div>";
                }
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar el tipo de habitación.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga habitacions dentro dun rango de fechas e dun tipo en específico
     * Carga unha habitación, dun tipo en específico, e dentro dun rango de fechas. Recibe por parámetro dúas fechas, inicio e entrada, e un tipo de habitación
     * @param array $tipo fecha de entrada, fecha de salisa e tipo de habitación
     * 
     * @return string devolve un código html no que envía as habitacións
     */
    function cargar_habitacion_tipo($tipo) {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = "select h.id, h.m2, h.ventana, h.servicio_limpieza, h.internet, h.precio from habitaciones as h where h.id not in(select 
hr.id_habitacion from habitaciones_reservas as hr 
inner join reservas as r on hr.num_reserva=r.num_reserva 
where ? BETWEEN r.fecha_entrada AND r.fecha_salida
and ?
 BETWEEN r.fecha_entrada AND r.fecha_salida) and h.tipo_de_habitacion=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(
                        $tipo[0],
                        $tipo[1],
                        $tipo[2]
                    ))) {
                $filas = $stmt->fetchAll();
                $texto = '';

                foreach ($filas as $fila) {
                    $id = $fila[0];
                    $metros = $fila[1];
                    $ventana = $fila[2];
                    $ventana_texto = '';
                    if ($ventana == '0') {
                        $ventana_texto .= 'No';
                    } else {
                        $ventana_texto .= 'Yes';
                    }
                    $servicio_limpieza = $fila[3];
                    $servicio_limpieza_texto = '';
                    if ($servicio_limpieza == '0') {
                        $servicio_limpieza_texto .= 'No';
                    } else {
                        $servicio_limpieza_texto .= 'Yes';
                    }
                    $internet = $fila[4];
                    $servicio_internet = '';
                    if ($internet == '0') {
                        $servicio_internet .= 'No';
                    } else {
                        $servicio_internet .= 'Yes';
                    }
                    $precio = $fila[5];
                    $room = $tipo[2] . " Room";
                    $texto .= "<div class='card' style='width: 18rem;'>
                                <img class='card-img-top' src='../images/rooms/" . $tipo[2] . "/" . $id . ".jpg' alt='$room'>
                                <ul class='list-group list-group-flush'>
                                    <li class='list-group-item'>Area: " . $metros . "m²</li>
                                    <li class='list-group-item'>Window: " . $ventana_texto . "</li>
                                    <li class='list-group-item'>Cleaning Service: " . $servicio_limpieza_texto . "</li>
                                        <li class='list-group-item'>Internet connection: " . $servicio_internet . "</li>
                                    <li class='list-group-item'>Price: " . $precio . "€</li>
                                </ul>
                                <div class='card-body' style='text-align:center;'>
                                   <button type='submit' class='boton_reserva btn btn-primary' value='" . $id . "' class='btn btn-primary'>Reservar</button>
                                </div>
                            </div>";
                }
                return $texto;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar el tipo de habitación.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga habitaciones, en formato "<option>"
     * Carga todas as habitaciones do hotel
     * @return string devolve un código html, formado por "<option>"no que envía o tipo da habitación
     */
    function cargar_tipo_habitaciones() {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select tipo_habitacion from habitacion_tipo';
            $stmt = $pdo->query($sql);
            $filas = $stmt->fetchAll();
            $texto = '';

            foreach ($filas as $fila) {
                $texto .= "<option value='" . $fila['tipo_habitacion'] . "'>" . $fila['tipo_habitacion'] . "</option>";
            }
            return $texto;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar el tipo de habitación.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga os tipos de habitacions
     * Obtén os tipos de habitación e devolveas nun array
     * @return array devolve os tipos de habitacions, en formato array
     */
    function cargar_tipo_habitaciones_array() {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select tipo_habitacion from habitacion_tipo';
            $stmt = $pdo->query($sql);
            $filas = $stmt->fetchAll();
            $array = array();

            foreach ($filas as $fila) {
                array_push($array, $fila);
            }
            return $array;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar el tipo de habitación.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga as reservas sen aceptar
     * Carga as reservas sen aceptar e devolveas en formato "<option>"
     * @return string devolve código html cas reservas sen aceptar en formato "<option>"
     */
    function cargar_reservas_admin() {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select r.num_reserva, r.fecha_entrada, r.fecha_salida, hr.id_habitacion '
                    . 'from reservas as r inner join habitaciones_reservas as hr '
                    . 'on r.num_reserva=hr.num_reserva where r.estado=0';
            $stmt = $pdo->query($sql);
            $filas = $stmt->fetchAll();
            $texto = '';

            foreach ($filas as $fila) {
                $texto .= "<option value='" . $fila['num_reserva'] . "'>" . "Room ID: " . $fila['id_habitacion'] . "&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;" . $fila['fecha_entrada'] . "&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;" . $fila['fecha_salida'] . "</option>";
            }
            return $texto;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar el tipo de habitación.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Obtén nome e email dun usuario
     * Obtén nome e email dun usuario, a través dun número de reserva
     * @param mixed $reserva numero de reserva
     * 
     * @return array array composto do email e o nome dun usuario
     */
    function get_email_name($reserva) {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select u.nombre, u.email from reservas as r inner join usuarios as u on r.id_usuario=u.id where num_reserva=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(
                        $reserva[0]
                    ))) {
                $datos = array();
                $fila = $stmt->fetch();
                if ($fila) {
                    array_push($datos, $fila['email']);
                    array_push($datos, $fila['nombre']);
                }
                return $datos;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar el tipo de habitación.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Obtén datos de accesos dun usuario
     * Obtén id, nome, email, ultimo_aceso e ultima_modificacion dun usuario
     * @param mixed $id id dun usuario
     * 
     * @return string devolve os datos dun usuario en formato html, nunha lista
     */
    function ver_accesos($id) {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = "select id, nombre, email, ultimo_acceso, ultimo_modificacion from usuarios where id=?";
            $stmt = $pdo->prepare($sql);
            $texto = '';
            if ($stmt->execute(array($id[0]))) {
                $fila = $stmt->fetch();
                if ($fila) {
                    $id = $fila['id'];
                    $nombre = $fila['nombre'];
                    $email = $fila['email'];
                    $ultimo_acceso = $fila['ultimo_acceso'];
                    $ultimo_modificacion = $fila['ultimo_modificacion'];


                    $texto .= "<ul><li>Id: " . $id . "</li><li>Name: " . $nombre . "</li><li>Email: " . $email . "</li><li>Last access: " . $ultimo_acceso . "</li><li>Last Modify: " . $ultimo_modificacion . "</li></ul>";
                }
                return $texto;
            } else {
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga as habitacións reservables
     * Busca as habitacións reservables e devolveas en formato html
     * 
     * @return string devolve as habitacións reservables, en formato "<html>"
     */
    function cargar_reservables() {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select id from habitaciones where reservable=1';
            $stmt = $pdo->query($sql);
            $filas = $stmt->fetchAll();
            $texto = '';

            foreach ($filas as $fila) {
                $texto .= "<option value='" . $fila['id'] . "'>" . "Room ID: " . $fila['id'] . "</option>";
            }
            return $texto;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar las habitaciones reservables.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga as habitacións non reservables
     * Busca as habitacións non reservables e devolveas en formato html
     * 
     * @return string devolve as habitacións non reservables, en formato "<html>"
     */
    function cargar_no_reservables() {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select id from habitaciones where reservable=0';
            $stmt = $pdo->query($sql);
            $filas = $stmt->fetchAll();
            $texto = '';

            foreach ($filas as $fila) {
                $texto .= "<option value='" . $fila['id'] . "'>" . "Room ID: " . $fila['id'] . "</option>";
            }
            return $texto;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar las habitaciones no reservables.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Obtén os datos das reservas
     * Mediante o número de reserva. Obtén número de reserva, id de usuario, id de habitación, fecha de reserva
     * 
     * @return string devolve os datos nunha lista html
     */
    function reservas_logs() {
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select r.num_reserva, r.id_usuario,hr.id_habitacion, r.fecha_reserva  from reservas as r inner join habitaciones_reservas as hr on r.num_reserva=hr.num_reserva';
            $stmt = $pdo->query($sql);
            $filas = $stmt->fetchAll();
            $texto = '';
            $texto .= '<ul>';
            foreach ($filas as $fila) {
                $texto .= "<li> Reservation number: " . $fila['num_reserva'] . "&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;ID User: " . $fila['id_usuario'] . "&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;ID Room: ".$fila['id_habitacion'] ."&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Reservation date: " . $fila['fecha_reserva'] . "</option>";
            }
            $texto .= '</ul>';
            return $texto;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido la lista de las reservas.";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }
    
    /**
     * Carga os datos dun usuario
     * Devolve o nome, telefono e direccion dun usuario
     * @param mixed $id id dun usuario
     * 
     * @return array array de datos dun usuario. nome, telefono e direccion
     */
    function cargar_datos_modify($id){
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select nombre, telf, direccion from usuarios where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(
                        $id[0]
                    ))) {
                $datos = array();
                $fila = $stmt->fetch();
                if ($fila) {
                    array_push($datos, $fila['nombre']);
                    array_push($datos, $fila['telf']);
                    array_push($datos, $fila['direccion']);
                }
                return $datos;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar los datos de usuario";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    
    }
    
    /**
     * Obtén a password dun usuario
     * Recibe o id dun usuario e obtén a sua contrasinal
     * 
     * @param mixed $id id dun usuario
     * 
     * @return mixed devolve a password se todo foi ben ou false
     */
    function getPassword($id){
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select password from usuarios where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(
                        $id[0]
                    ))) {
                $fila = $stmt->fetch();
                if ($fila) {
                    return $fila[0];
                }else{
                    return false;
                }
            } else {
                return false;
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar los datos de usuario";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    
    
    }
    
    /**
     * Obtén o email dun usuario
     * Recibe o id dun usuario e obtén a seu email
     * 
     * @param mixed $id id dun usuario
     * 
     * @return mixed devolve o email se todo foi ben ou false
     */
    function getEmail($id){
        try {
            $bd = new \bd\BBDD('conexion');
            $pdo = $bd->PDO;
            $sql = 'select email from usuarios where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(
                        $id[0]
                    ))) {
                $fila = $stmt->fetch();
                if ($fila) {
                    return $fila[0];
                }else{
                    return false;
                }
            } else {
                return false;
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido cargar los datos de usuario";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    
    
    }

}
