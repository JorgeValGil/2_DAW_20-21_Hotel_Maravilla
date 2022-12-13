<?php

namespace functionsUsers;

/**
 * Clase coas funcións que necesitan rol de admin
 * @version 1.0
 * @author Jorge Val Gil e Adrián Fernández Pérez
 */
Class Admin {

    /**
     * Contructor de clase
     */
    public function __construct() {
        
    }

    /**
     * Crea un usuario
     * Recibe os datos para a creación dun usuario e insertao na BBDD
     * @param array datos do usuario
     * 
     * @return mixed devolve true se todo foi ben. Pode producir unha excepción
     */
    function anadir_usuario($datos_usuario) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'insert into usuarios(nombre,email,telf,direccion,password,rol_usuario) values (?,?,?,?,?,?)';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $datos_usuario[0],
                                $datos_usuario[1],
                                $datos_usuario[2],
                                $datos_usuario[3],
                                $datos_usuario[4],
                                $datos_usuario[5]
                            )
                    )
            ) {
                return true;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido añadir el usuario";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Crea unha habitación
     * Recibe os datos para a creación dunha habitación e insertao na BBDD
     * @param array datos da habitación
     * 
     * @return mixed devolve true se todo foi ben. Pode producir unha excepción
     */
    function anadir_habitacion($datos_habitacion) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'insert into habitaciones(m2,ventana,tipo_de_habitacion,servicio_limpieza,internet,precio) values (?,?,?,?,?,?)';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $datos_habitacion[0],
                                $datos_habitacion[1],
                                $datos_habitacion[2],
                                $datos_habitacion[3],
                                $datos_habitacion[4],
                                $datos_habitacion[5]
                            )
                    )
            ) {
                return true;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido insertar una habitacion";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Borra unha habitación
     * Recibe o id dunha habitación e borrao da BBDD, usa una transaction e borrao de tres taboas
     * @param string id da habitación
     * 
     * @return mixed devolve true se todo foi ben. devolve false non se puido borrar.
     */
    function borrar_habitacion($id) {
        try {
            $flag = false;
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $pdo->beginTransaction();
            $sql = 'delete from habitaciones_reservas where id_habitacion=?';
            $stmt = $pdo->prepare($sql);
            if (!($stmt->execute(array($id[0])))) {
                $flag = true;
            } else {
                $sql = 'delete from habitacion_servicio where id_habitacion=?';
                $stmt = $pdo->prepare($sql);
                if (!($stmt->execute(array($id[0])))) {
                    $flag = true;
                } else {
                    $sql = 'delete from habitaciones where id=?';
                    $stmt = $pdo->prepare($sql);
                    if (!($stmt->execute(array($id[0])))) {
                        $flag = true;
                    }
                }
            }
            if (!$flag) {
                $pdo->commit();
                return true;
            } else {
                $pdo->rollback();
                return false;
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error al borrar una habitacion";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

     /**
     * Crea un servicio
     * Recibe os datos para a creación dun servicio e insertao na BBDD
     * @param array datos do servicio
     * 
     * @return mixed devolve true se todo foi ben. Pode producir unha excepción
     */
    function añadir_servicio($datos_servicio) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'insert into servicios(nombre_servicio,precio_servicio,descripcion,disponibilidad) values (?,?,?,?)';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $datos_servicio[0],
                                $datos_servicio[1],
                                $datos_servicio[2],
                                $datos_servicio[3]
                            )
                    )
            ) {
                return true;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido insertar una habitacion";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Carga os datos dunha habitación
     * Recibe o id dunha habitación, fai unha consulta na BBDD e devolve os datos da habitación
     * @param string $id id dunha habitación
     * 
     * @return mixed devolve un array de datos, cos datos da habitación. devolve falso se o id non existe.
     */
    function cargar_habitacion_id($id) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'select m2, ventana, tipo_de_habitacion, servicio_limpieza, internet, precio from habitaciones where id=?';
            $stmt = $pdo->prepare($sql);
            $array_datos = array();
            if ($stmt->execute(array(
                        $id[0]
                    ))) {
                $filas = $stmt->fetchAll();
                foreach ($filas as $fila) {
                    array_push($array_datos, $fila['m2']);
                    array_push($array_datos, $fila['ventana']);
                    array_push($array_datos, $fila['tipo_de_habitacion']);
                    array_push($array_datos, $fila['servicio_limpieza']);
                    array_push($array_datos, $fila['internet']);
                    array_push($array_datos, $fila['precio']);
                    array_push($array_datos, $id[0]);
                }
                return $array_datos;
            } else {
                return false;
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
     * Actualiza unha habitación
     * Recibe os novos datos dunha habitación e actualiza os datos
     * @param array $roomdata novos datos dunha habitación
     * 
     * @return mixed devolve true se todo foi ben. Pode producir unha excepción
     */
    function updateroom($roomdata) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'update habitaciones set m2=?,'
                    . 'ventana=?,'
                    . 'tipo_de_habitacion=?,'
                    . 'servicio_limpieza=?,'
                    . 'internet=?,'
                    . 'precio=? '
                    . 'where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $roomdata[0],
                                $roomdata[1],
                                $roomdata[2],
                                $roomdata[3],
                                $roomdata[4],
                                $roomdata[5],
                                $roomdata[6]
                            )
                    )
            ) {
                return true;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha actualizar la información de la habitacion";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Crea unha reserva
     * Recibe os datos para a creación dunha reserva e insertao na BBDD,  usa una transaction
     * @param array datos da reserva
     * 
     * @return boolean devolve true se todo foi ben. devove false non conseguiu.
     */
    function anadir_reserva($reserva) {
        try {
            $flag = false;
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $pdo->beginTransaction();
            $sql = 'insert into reservas(id_usuario,fecha_entrada,fecha_salida) values (?,?,?)';
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute(
                            array(
                                $reserva[0],
                                $reserva[1],
                                $reserva[2]
                            )
                    )
            ) {
                $flag = true;
            } else {
                $sql = 'SELECT num_reserva FROM reservas ORDER BY num_reserva DESC LIMIT 1';
                $stmt = $pdo->query($sql);
                $fila = $stmt->execute();
                $fila = $stmt->fetchAll();
                $num_reserva = '';
                if ($fila) {
                    $num_reserva = $fila[0];
                }

                $sql = 'insert into habitaciones_reservas(num_reserva,id_habitacion)values(?,?)';
                $stmt = $pdo->prepare($sql);
                if (!($stmt->execute(array(
                            $num_reserva[0],
                            $reserva[3]
                        )))) {
                    $flag = true;
                }
            }
            if (!$flag) {
                $pdo->commit();
                return true;
            } else {
                $pdo->rollback();
                return false;
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha conseguido insertar una habitacion";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Acepta unha reserva
     * Recibe o id dunha reserva e cambia o estado a aceptada
     * @param string $reserva numero da reserva
     * 
     * @return boolean devolve true se consegue facer o update. devolve false se non o consegue
     */
    function accept_reserva($reserva) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'update reservas set estado=1 where num_reserva=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $reserva[0]
                            )
                    )
            ) {
                return true;
            } else {
                return false;
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha aceptado la reserva";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Borra unha reserva
     * Recibe o numero dunha reserva e borra a reserva. Usa unha trasación e borra de dúas táboas.
     * @param string $reserva numero de reserva
     * 
     * @return devolve true se consegue facer o update. devolve false se non o consegue
     */
    function delete_reserva($reserva) {
        try {
            $flag = false;
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $pdo->beginTransaction();
            $sql = 'delete from habitaciones_reservas where num_reserva=?';
            $stmt = $pdo->prepare($sql);
            if (!($stmt->execute(array($reserva[0])))) {
                $flag = true;
            } else {
                $sql = 'delete from reservas where num_reserva=?';
                $stmt = $pdo->prepare($sql);
                if (!($stmt->execute(array($reserva[0])))) {
                    $flag = true;
                }
            }
            if (!$flag) {
                $pdo->commit();
                return true;
            } else {
                $pdo->rollback();
                return false;
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error al borrar una reserva";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Actualiza o último acceso dun usuario
     * Recibe un id dun usuario e cambia o seu último acceso a ese momento exacto
     * @param string $id id usuario
     * 
     * @return boolean devolve true se consegue facer o update. devolve false se non o consegue
     */
    function last_access($id) {
        try {
            $bd = new \bd\BBDD('admin');
            $date = new \DateTime();
            $date_string = $date->format('Y-m-d H:i:s');
            $pdo = $bd->PDO;
            $sql = 'update usuarios set ultimo_acceso="' . $date_string . '" where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $id[0]
                            )
                    )
            ) {
                return true;
            } else {
                return false;
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha actualizado el último acceso";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }
    
        /**
         * Actualiza a última modificación dun usuario
         * Recibe un id dun usuario e cambia a súa última modificación a ese momento exacto
         * @param string $id id usuario
         * 
         * @return boolean devolve true se consegue facer o update. devolve false se non o consegue
         */
        function last_modify($id) {
        try {
            $bd = new \bd\BBDD('admin');
            $date = new \DateTime();
            $date_string = $date->format('Y-m-d H:i:s');
            $pdo = $bd->PDO;
            $sql = 'update usuarios set ultimo_modificacion="' . $date_string . '" where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $id[0]
                            )
                    )
            ) {
                return true;
            } else {
                return false;
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha actualizado el último modificación";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Marca unha habitación como non reservable
     * Recibe o id dunha habitación cambia o seu estado a non reservable
     * @param string $id id de habitacion
     * 
     * @return boolean devolve true se consegue facer o update. devolve false se non o consegue
     */
    function set_no_reservable($id) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'update habitaciones set reservable=0 where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $id[0]
                            )
                    )
            ) {
                return true;
            } else {
                return false;
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha podido cambiar la disponibilidad a no reservable";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Marca unha habitación como reservable
     * Recibe o id dunha habitación cambia o seu estado a reservable
     * @param string $id id de habitacion
     * 
     * @return boolean devolve true se consegue facer o update. devolve false se non o consegue
     */
    function set_reservable($id) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'update habitaciones set reservable=1 where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $id[0]
                            )
                    )
            ) {
                return true;
            } else {
                return false;
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha podido cambiar la disponibilidad a reservable";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    /**
     * Modifica datos dun usuario
     * Recibe o id dun usuario, e modifica nombre, telefono e direccion.
     * @param array $userdata datos dun usuario
     * 
     * @return boolean devolve true se conseguiu modificar os datos
     */
    function updateprofile($userdata) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'update usuarios set nombre=?,'
                    . 'telf=?,'
                    . 'direccion=?'
                    . 'where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $userdata[0],
                                $userdata[1],
                                $userdata[2],
                                $userdata[3]
                            )
                    )
            ) {
                return true;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha actualizar la información del usuario";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }
    
    
    /**
     * Cambio de contrasinal dun usuario
     * Cambia a contrasinal dun usuario. Recibe a nova password e o id do usuario.
     * @param array $userdata password e id dun usuario
     * 
     * @return boolean devolve true se consegue modificar a contrasinal
     */
    function updatepassword($userdata) {
        try {
            $bd = new \bd\BBDD('admin');
            $pdo = $bd->PDO;
            $sql = 'update usuarios set password=?'
                    . 'where id=?';
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(
                            array(
                                $userdata[0],
                                $userdata[1]
                            )
                    )
            ) {
                return true;
            } else {
                throw new \PDOException();
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            echo "Se ha producido un error, no se ha podido actualizar la password del usuario";
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

}
