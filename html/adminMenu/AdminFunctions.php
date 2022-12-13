<?php

namespace adminMenu;

session_start();
include '../../autoload.php';

/**
 * Clase adminFunctions 
 * 
 * Contiene funciones solo disponibles para los usuarios administradores
 * 
 * @author Adrian Fernandez Perez y Jorge Val Gil
 * @version 1.0
 */
class AdminFunctions {

    //úsanse na function delete
    private $blank_delete;
    private $delete;
    //úsanse na function create
    private $blank_create;
    private $create;
    //úsanse na function createAdminUser
    private $newaccountblankAdmin;
    private $añadido;
    private $email_errorAdmin;
    private $email_formatAdmin;
    private $phone_errorAdmin;
    private $password_errorAdmin;
    //úsanse na function createService
    private $blank_service;
    private $addService;
    private $blank_id;
    private $actual_id;
    private $blank_modify;
    private $modify;
    //
    private $reserva;
    private $reserva_accept;
    private $reserva_delete;
    private $blank_logs;
    private $logs;
    private $reservable_blank;
    private $set_no_reservable;
    private $no_reservable_blank;
    private $set_reservable;

    /**
     * Constructor de la clase
     */
    public function __construct() {
        
    }

    /**
     * Función __get mágico
     * 
     * Devuelve valores a los que no podemos acceder
     * 
     * @param mixed $value Pasamos el valor que queremos obtener por parámetro
     * 
     * @return mixed Devuelve ese valor
     */
    public function __get($value) {
        return $this->$value;
    }

    /**
     * Funcion uploadImg
     * 
     * Función para subir una imagen cuando creamos una habitación
     * 
     * @param mixed Tipo de habitación
     * 
     */
    function uploadimg($tipo_habitacion) {
        $target_dir = dirname(__DIR__, 2) . "/images/rooms/" . $tipo_habitacion . "/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
        if (isset($_POST["Create_room"])) {
            $check = getimagesize($_FILES["imagen"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

// Check file size
        if ($_FILES["imagen"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

// Allow certain file formats
        if ($imageFileType != "jpg") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    /**
     * Función delete
     * 
     * Función delete para eliminar una habitación segun un id.
     * Si está vacio el campo muestra un mensaje, si no crea un objeto admin y llama a la función que borra habitaciones.
     * 
     */
    function delete() {

        if ($_POST['id'] == '') {
            $this->blank_delete = true;
        } else {
            $admin = new \functionsUsers\Admin();
            $id = array($_POST['id']);
            $this->delete = $admin->borrar_habitacion($id);
        }
    }

    /**
     * Función create
     * 
     * Función que sirve para crear una habitación, comprueba que los campos del formulario tengan datos.
     * Nos muestra un error dependiendo del caso o crea un objeto admin y llama a la función añadir_habitación de un admin para crearla.
     * 
     * @see uploadimg()
     * 
     */
    function create() {
        if ($_POST['area'] == '' || $_POST['window'] == '' || $_POST['tipo_habitacion'] == '' || $_POST['cleaning_service'] == '' || $_POST['internet'] == '' || $_POST['price'] == '') {
            $this->blank_create = true;
        } else {
            $window = '';
            if ($_POST['window'] == 'w_yes') {
                $window = true;
            } else {
                $window = false;
            }
            $cleaning_service = '';
            if ($_POST['cleaning_service'] == 'cs_yes') {
                $cleaning_service = true;
            } else {
                $cleaning_service = false;
            }
            $internet = '';
            if ($_POST['internet'] == 'i_yes') {
                $internet = true;
            } else {
                $internet = false;
            }

            $datos_habitacion = array($_POST['area'], $window, $_POST['tipo_habitacion'], $cleaning_service, $internet, $_POST['price']);

            $admin = new \functionsUsers\Admin();
            $this->create = $admin->anadir_habitacion($datos_habitacion);

            if ($this->create == true) {
                $upload = new \adminMenu\AdminFunctions();
                $upload->uploadimg($_POST['tipo_habitacion']);
            }
        }
    }

    /**
     * Función createAdminUser
     * 
     * Función que comprueba que los campos del formulario de crear cuenta administradora tengan datos.
     * Nos mostrará mensajes de error dependiendo del caso o creará un objeto admin y llamará a la función añadir_usuario
     * pasando como parametro un array con los datos del usuario introducidos y crea un usuario administrador.
     * 
     */
    function createAdminUser() {
        if ($_POST['personalname'] == '' || $_POST['newemail'] == '' || $_POST['pass1'] == '' || $_POST['pass2'] == '' || $_POST['phone'] == '' || $_POST['address'] == '') {
            $this->newaccountblankAdmin = true;
        } else {
            // Objeto de la clase Conexion para llamar a sus funciones
            $objetoConexion = new \functionsUsers\Conexion();
            $email = $objetoConexion->comprobar_email(array($_POST['newemail']));
            if ($email === false) {
                $this->email_errorAdmin = true;
            }

            // Objeto de la clase registrarValidar para llamar a sus funciones
            $objetoValidarRegistrar = new \validarRegistro\RegistrarValidar();
            $email_valid = $objetoValidarRegistrar->valid_email($_POST['newemail']);
            if ($email_valid === false) {
                $this->email_formatAdmin = true;
            }
            $phone = $objetoValidarRegistrar->valid_phone($_POST['phone']);
            if ($phone === false) {
                $this->phone_errorAdmin = true;
            }
            $password = $objetoValidarRegistrar->compare_password($_POST['pass1'], $_POST['pass2']);
            if ($password === false) {
                $this->password_errorAdmin = true;
            }
            if (!isset($this->password_errorAdmin) && !isset($this->email_errorAdmin) && !isset($this->phone_errorAdmin) && !isset($this->email_formatAdmin)) {
                $datos_usuario = array($_POST['personalname'], $_POST['newemail'], $_POST['phone'], $_POST['address'], $password, 1);

                $admin = new \functionsUsers\Admin();
                $this->añadido = $admin->anadir_usuario($datos_usuario);
            }
        }
    }

    /**
     * Función createService
     * 
     * Función que comprueba que los campos del formulario de crear un servicio tengan datos.
     * Nos mostrará mensajes de error dependiendo del caso o creará un objeto admin y llamará a la función añadir_servicio
     * pasando como parametro un array con los datos del servicio introducidos y crea el servicio.
     * 
     */
    function createService() {
        if ($_POST['servicename'] == '' || $_POST['serviceprice'] == '' || $_POST['servicedescription'] == '' || $_POST['availability_service'] == '') {
            $this->blank_service = true;
        } else {
            $availability_service = '';
            if ($_POST['availability_service'] == 'as_yes') {
                $availability_service = true;
            } else {
                $availability_service = false;
            }
            $datos_servicio = array($_POST['servicename'], $_POST['serviceprice'], $_POST['servicedescription'], $availability_service);
            $admin = new \functionsUsers\Admin();
            $this->addService = $admin->añadir_servicio($datos_servicio);
        }
    }

    /**
     * Funcion acceptReservation
     * 
     * Comprueba si seleccionamos una reserva, si es así, crea un objeto admin y llama a la función accept_reserva
     * pasandole por parametro la reserva, si no muestra un error.
     * Si todo está correcto, crea un objeto correo y llama a la función enviar_correos_reserva, la cual nos enviará un mensaje al email
     * confirmando que fué acpetada. 
     * 
     */
    function acceptReservation() {
        if ($_POST['reserva'] == '') {
            $this->reserva = true;
        } else {
            $conexion = new \functionsUsers\Conexion();
            $datos_reserva = array($_POST['reserva']);
            $datos_correo = $conexion->get_email_name($datos_reserva);

            $admin = new \functionsUsers\Admin();
            $this->reserva_accept = $admin->accept_reserva($datos_reserva);

            if ($this->reserva_accept == true && is_array($datos_correo)) {
                $correo = new \correo\Correo();
                $asunto = 'Accepted reservation in Hotel Maravilla';
                $correo->enviar_correos_reserva($datos_correo[0], $asunto);
            }
        }
    }

    /**
     * Función deleteReservation
     * 
     * Comprueba que seleccionamos una reserva y crea un array con los datos del formulario,
     * crea un objeto admin y llama a la funcion delete_reserva pasandole por parametro el array de datos y elimina la reserva, o nos muestra un error.
     * 
     */
    function deleteReservation() {
        if ($_POST['reserva'] == '') {
            $this->reserva = true;
        } else {
            $datos_reserva = array($_POST['reserva']);
            $admin = new \functionsUsers\Admin();
            $this->reserva_delete = $admin->delete_reserva($datos_reserva);
        }
    }

    /**
     * Funcion loadRoomData
     * 
     * Comprueba que el campo del formulario tenga datos y nos muestra un error, o crea un array con los datos del formulario,
     * crea un objeto admin y llama a la funcion cargar_habitacion_id pasandole por parametro el array de datos y muestra los datos de la habitación
     * a cargar.
     * 
     * @return array datos de una habitación
     */
    function loadRoomData() {
        if ($_POST['id_modify'] == '') {
            $this->blank_id = true;
        } else {
            $id_find = array($_POST['id_modify']);
            $admin = new \functionsUsers\Admin();
            $data = $admin->cargar_habitacion_id($id_find);
            if (is_array($data)) {
                $this->actual_id = true;
                return $data;
            }
        }
    }

    /**
     * Funcion updateRoom
     * 
     * Comprueba que el campo del formulario tenga datos y nos muestra un error dependiendo del caso, o crea un array con los datos del formulario,
     * crea un objeto admin y llama a la funcion updateroom pasandole por parametro el array de datos y actualizará los datos de la habitación.
     * 
     */
    function updateRoom() {
        if ($_POST['area_modify'] == '' || $_POST['window_modify'] == '' || $_POST['tipo_habitacion_modify'] == '' || $_POST['cleaning_service_modify'] == '' || $_POST['internet_modify'] == '' || $_POST['price_modify'] == '' || $_POST['id_to_modify'] == '') {
            $this->blank_modify = true;
        } else {
            $window = '';
            if ($_POST['window_modify'] == 'w_yes') {
                $window = true;
            } else {
                $window = false;
            }
            $cleaning_service = '';
            if ($_POST['cleaning_service_modify'] == 'cs_yes') {
                $cleaning_service = true;
            } else {
                $cleaning_service = false;
            }
            $internet = '';
            if ($_POST['internet_modify'] == 'i_yes') {
                $internet = true;
            } else {
                $internet = false;
            }
            $datos_habitacion = array($_POST['area_modify'], $window, $_POST['tipo_habitacion_modify'], $cleaning_service, $internet, $_POST['price_modify'], $_POST['id_to_modify']);

            $admin = new \functionsUsers\Admin();
            $this->modify = $admin->updateroom($datos_habitacion);
        }
    }

    /**
     * Function logs
     * 
     * Comprueba que el campo del formulario tenga datos y nos muestra un error, si es así o crea un array con los datos del formulario,
     * crea un objeto conexion y llama a la funcion ver_accesos pasandole por parametro el array de datos que mostrará los logs.
     * 
     */
    function logs() {
        if ($_POST['id_logs'] == '') {
            $this->blank_logs = true;
        } else {
            $id_logs = array($_POST['id_logs']);
            $conexion = new \functionsUsers\Conexion();
            $data = $conexion->ver_accesos($id_logs);
            $this->logs = $data;
        }
    }

    /**
     * Function set_no_reservable
     * 
     * Comprueba que seleccionamos una reserva, si es así, crea un array con los datos del formulario,
     * crea un objeto admin y llama a la funcion set_no_reservable pasandole por parametro el array de datos y pone la reserva seleccionada como
     * no reservable, si no muestra un error.
     * 
     */
    function set_no_reservable() {
        if ($_POST['reservables'] == '') {
            $this->reservable_blank = true;
        } else {
            $datos = array($_POST['reservables']);

            $admin = new \functionsUsers\Admin();
            $this->set_no_reservable = $admin->set_no_reservable($datos);
        }
    }

    /**
     * Function set_reservable
     * 
     * Comprueba que seleccionamos una reserva, si es así, crea un array con los datos del formulario,
     * crea un objeto admin y llama a la funcion set_reservable pasandole por parametro el array de datos y pone la reserva seleccionada como
     * reservable, si no muestra un error.
     * 
     */
    function set_reservable() {
        if ($_POST['no_reservables'] == '') {
            $this->no_reservable_blank = true;
        } else {
            $datos = array($_POST['no_reservables']);

            $admin = new \functionsUsers\Admin();
            $this->set_reservable = $admin->set_reservable($datos);
        }
    }

}

if (isset($_POST['Delete_room'])) {
    $room = new \adminMenu\AdminFunctions();
    $room->delete();
    $blank_delete = $room->blank_delete;
    $delete = $room->delete;
}

if (isset($_POST['Create_room'])) {
    $room = new \adminMenu\AdminFunctions();
    $room->create();
    $blank_create = $room->blank_create;
    $create = $room->create;
}

if (isset($_POST['Id_room_modify'])) {
    $room = new \adminMenu\AdminFunctions();
    $data = $room->loadRoomData();
    if (is_array($data)) {
        $load_type = true;
        $area_modify = $data[0];
        $window_modify = $data[1];
        $type_modify = $data[2];
        $cleaning_modify = $data[3];
        $internet_modify = $data[4];
        $price_modify = $data[5];
        $id = $data[6];
    }
    $blank_id = $room->blank_id;
    $actual_id = $room->actual_id;
}

if (isset($_POST['Modify_room'])) {
    $room = new \adminMenu\AdminFunctions();
    $data = $room->updateRoom();
    $blank_modify = $room->blank_modify;
    $modify = $room->modify;
}

if (isset($_POST['CreateAccount'])) {
    $room = new \adminMenu\AdminFunctions();
    $room->createAdminUser();
    $newaccountblankAdmin = $room->newaccountblankAdmin;
    $email_errorAdmin = $room->email_errorAdmin;
    $email_formatAdmin = $room->email_formatAdmin;
    $phone_errorAdmin = $room->phone_errorAdmin;
    $password_errorAdmin = $room->password_errorAdmin;


    $createUserAdmin = $room->createAdminUser();
    $añadido = $room->añadido;
}

if (isset($_POST['Create_service'])) {
    $room = new \adminMenu\AdminFunctions();
    $room->createService();
    $blank_service = $room->blank_service;
    $addService = $room->addService;
}

if (isset($_POST['Accept_reservation'])) {
    $reservation = new \adminMenu\AdminFunctions();
    $reservation->acceptReservation();
    $reserva = $reservation->reserva;
    $reserva_accept = $reservation->reserva_accept;
}
if (isset($_POST['Delete_reservation'])) {
    $reservation = new \adminMenu\AdminFunctions();
    $reservation->deleteReservation();
    $reserva = $reservation->reserva;
    $reserva_delete = $reservation->reserva_delete;
}
if (isset($_POST['id_user_logs'])) {
    $user_logs = new \adminMenu\AdminFunctions();
    $user_logs->logs();
    $blank_logs = $user_logs->blank_logs;
    $logs = $user_logs->logs;
}
if (isset($_POST['Set_no_reservable'])) {
    $set_no = new \adminMenu\AdminFunctions();
    $set_no->set_no_reservable();
    $reservable_blank = $set_no->reservable_blank;
    $set_no_reservable = $set_no->set_no_reservable;
}
if (isset($_POST['Set_reservable'])) {
    $set = new \adminMenu\AdminFunctions();
    $set->set_reservable();
    $no_reservable_blank = $set->no_reservable_blank;
    $set_reservable = $set->set_reservable;
}
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <!--icono da páxina-->
        <link rel="icon" type="image/png" href="../../images/hotel/hotel_icon.png">
        <!--arquivos css-->
        <link rel="stylesheet" href="../../css/login.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">
        <script src="../../js/jquery-ui/jquery-ui.js"></script>
        <!--título da páxina-->
        <title>HOTEL MARAVILLA</title>
    </head>
    <!--body-->

    <body>
        <!--contedor do menu superior e do navbar-->
        <div class="container fondo-claro">
            <!--menu superior-->
            <div class="row">
                <div class="col">
                    <div class="rowtop d-flex justify-content-end">
                        <!--telefono-->
                        <div><a href="#"><img class="icon_navbar" src="../../images/icons/telephone-fill.svg"
                                              alt="icono telefono"></a>
                            <span>+34986000123</span><a href="#">
                        </div>
                        <!--rrss-->
                        <div>
                            <a href="https://web.whatsapp.com/send?phone=0034666000666"><img class="icon_navbar"
                                                                                             src="../../images/icons/whatsapp.svg" alt="icono whatsapp"></a>
                            <a href="https://twitter.com/hotelmaravilla"><img class="icon_navbar"
                                                                              src="../../images/icons/facebook.svg" alt="icono facebook"></a>
                            <a href="https://www.facebook.com/HotelMaravilla/"><img class="icon_navbar"
                                                                                    src="../../images/icons/twitter.svg" alt="icono twitter"></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--navbar-->
            <div class="row">
                <!--logo da páxina-->
                <div class="logo col-12 col-sm-3 col-md-2">
                    <a href="../../index.php"><img src="../../images/hotel/hotel.png" alt="logo da páxina"></a>
                </div>
                <!--nav-->
                <div class="col-12 col-sm-4 col-md-9 nav justify-content-end">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto nav-justified">
                                <ul class="navbar-nav mr-auto nav-justified">
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="../../index.php" role="button"
                                           aria-haspopup="true" aria-expanded="false"><img class="icon_navbar"
                                                                                        src="../../images/icons/house-fill.svg" alt="icono casa">
                                            HOME
                                        </a>
                                    </li>
                                    <?php
                                    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) {
                                        ?>

                                        <li class="nav-item">
                                            <a class="nav-link text-wrap" href="AdminFunctions.php" role="button" aria-haspopup="true"
                                               aria-expanded="false"><img class="icon_navbar"
                                                                       src="../../images/icons/gear-fill.svg" alt="icono engranaje">
                                                ADMIN
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="../rooms.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar"
                                                                   src="../../images/icons/door-open.svg" alt="icono porta">
                                            ROOMS
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="../gallery.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar" src="../../images/icons/images.svg"
                                                                   alt="icono galería de imáxenes">
                                            GALLERY
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="#" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar" src="../../images/icons/chat-left-dots.svg" 
                                                                   alt="icono contacto">
                                            CONTACT
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="../location.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar" src="../../images/icons/globe.svg"
                                                                   alt="icono globo terráqueo, icono ubicación">
                                            LOCATION
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href=" <?php
                                        if (isset($_SESSION['username'])) {
                                            echo '../profile/modifyProfile.php';
                                        } else {
                                            echo'../loginYregistro/loginAndRegister.php';
                                        }
                                        ?>" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar"
                                                                   src="../../images/icons/person-square.svg" alt="icono usuario">
                                                                   <?php
                                                                   if (isset($_SESSION['username'])) {
                                                                       echo $_SESSION['username'];
                                                                   } else {
                                                                       echo 'LOGIN & SIGN UP';
                                                                   }
                                                                   ?>
                                        </a>
                                    </li>
                                </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <?php
            $cabecera = new \cabeceras\Cabeceras();
            $cabecera->cabecera1();
            ?>
        </div>
        <!--zona central-->
        <div class="container mb-3 fondo-claro">
            <?php
            if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) {
                ?>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 form_1">
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Delete Room</h5>
                                <?php
                                if (isset($blank_delete) and $blank_delete == true) {
                                    echo "<p style='color: red; font-weight: bold'>Fill in all the fields of the form.</p>";
                                }
                                if (isset($delete) and $delete == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Process completed without error.</p>";
                                }
                                if (isset($delete) and $delete == false) {
                                    echo "<p style='color: red; font-weight: bold'>Error in the deletion process.</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <div class="form-group">
                                        <label for="exampleInputText">Id room</label>
                                        <input type="number" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="id" step="1" placeholder="Enter room ID" title="Enter the id of the room to delete">
                                    </div>
                                    <hr>
                                    <div>
                                        <button type="submit" name="Delete_room" class="btn btn-primary">Delete Room</button>
                                    </div> 
                                </form>
                                <hr>
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Reservation Logs</h5>
                                <div style="margin-top:1em;">
                                    <a  href="../historial_reservas.php"><button type="submit" name="reservation_logs" class="btn btn-primary">Check Reservation Logs</button>                   
                                    </a>

                                </div> 

                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 form_1">
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Add Room</h5>
                                <?php
                                if (isset($blank_create) and $blank_create == true) {
                                    echo "<p style='color: red; font-weight: bold'>Fill in all the fields of the form.</p>";
                                }
                                if (isset($create) and $create == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Process completed without error.</p>";
                                }
                                if (isset($create) and $create == false) {
                                    echo "<p style='color: red; font-weight: bold'>Error in the creation process.</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="exampleInputText">Area</label>
                                        <input type="number" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="area" step="0.01" placeholder="Enter room area" title="Enter the area of the room to create. Example: 15.65">
                                        <small>Format: 25.99</small>
                                    </div>
                                    <hr>
                                    <p>Window:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="window" id="flexRadioDefault1" value="w_yes" checked title="Select this option if the room has a window.">
                                        <label class="form-check-label" for="flexRadioDefault1" >
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="window" id="flexRadioDefault2" value="w_no" title="Select this option if the room does not have a window.">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                    <hr>
                                    <p>Select room type:</p>
                                    <select name="tipo_habitacion" title="Select the type of room to create">                <?php
                                        $tipos_habitaciones = new \functionsUsers\Conexion();
                                        $html = $tipos_habitaciones->cargar_tipo_habitaciones();
                                        echo $html;
                                        ?></select>
                                    <hr>

                                    <p>Cleaning Service:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cleaning_service" id="flexRadioDefault1" value="cs_yes" checked title="Select this option if the room has cleaning service.">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cleaning_service" id="flexRadioDefault2" value="cs_no" title="Select this option if the room does not have cleaning service.">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                    <hr>
                                    <p>Internet connection:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="internet" id="flexRadioDefault1" value="i_yes" checked title="Select this option if the room has internet connection.">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="internet" id="flexRadioDefault2" value="i_no" title="Select this option if the room does not have internet connection.">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="exampleInputText">Price</label>
                                        <input type="number" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="price" step="0.01" placeholder="Enter room price" title="Enter the price of the room to create. Example: 40.75">
                                        <small>Format: 50.12</small>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="imagen">Upload image:</label>
                                        <input type="file" class="form-control-file" id="imagen" name="imagen"  title="Select the image of the room to create.">
                                    </div>
                                    <hr>
                                    <div>
                                        <button type="submit" name="Create_room" class="btn btn-primary">Add Room</button>
                                    </div> 
                                </form>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Select Room to Modify</h5>
                                <?php
                                if (isset($blank_id) and $blank_id == true) {
                                    echo "<p style='color: red; font-weight: bold'>You must fill in the ID field.</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <div class="form-group">
                                        <label for="id_modify">Id room</label>
                                        <input type="number" class="form-control" id="id_modify"
                                               aria-describedby="textHelp" name="id_modify" step="1" placeholder="Enter room ID to modify" title="Enter the ID of the room to modify"> 
                                    </div>
                                    <div>
                                        <button type="submit" name="Id_room_modify" class="btn btn-primary">Enter ID</button>
                                    </div> 
                                </form><hr>
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Modify Room</h5>
                                <?php
                                if (isset($blank_modify) and $blank_modify == true) {
                                    echo "<p style='color: red; font-weight: bold'>You must fill all the fields.</p>";
                                }
                                if (isset($actual_id) and $actual_id == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Selected id: " . $_POST['id_modify'] . "</p>";
                                }
                                if (isset($modify) and $modify == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Room data updated correctly.</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <div class="form-group">
                                        <label for="exampleInputText">Area</label>
                                        <input type="number" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="area_modify" step="0.01" placeholder="Enter room area" title="Room area to be modified. example: 12.28" <?php
                                               if (isset($area_modify)) {
                                                   echo 'value="' . $area_modify . '"';
                                               }
                                               ?>>
                                        <small>Format: 25.99</small>
                                    </div>

                                    <input style="display: none" type="number" class="form-control" id="exampleInputText"
                                           aria-describedby="textHelp" name="id_to_modify" step="1" <?php
                                           if (isset($id)) {
                                               echo 'value="' . $id . '"';
                                           }
                                           ?>>
                                    <hr>
                                    <p>Window:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="window_modify" id="flexRadioDefault1" value="w_yes" title="Select this option if the room to modify has window." <?php
                                        if (isset($window_modify) && $window_modify == '1') {
                                            echo 'checked';
                                        }
                                        ?>>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="window_modify" id="flexRadioDefault2" value="w_no" title="Select this option if the room to modify does not have window." <?php
                                        if (isset($window_modify) && $window_modify == '0') {
                                            echo 'checked';
                                        }
                                        ?>>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                    <hr>
                                    <p>Select room type:</p>
                                    <select name="tipo_habitacion_modify" title="select the type of room to modify.">
                                        <?php
                                        if (isset($load_type) && $load_type == true) {
                                            $tipos_habitaciones = new \functionsUsers\Conexion();
                                            $result = $tipos_habitaciones->cargar_tipo_habitaciones_array();
                                            if (is_array($result)) {
                                                for ($i = 0; $i < sizeof($result); $i++) {
                                                    if (isset($type_modify) && $type_modify == $result[$i]['tipo_habitacion']) {
                                                        echo "<option value='" . $result[$i]['tipo_habitacion'] . "'selected>" . $result[$i]['tipo_habitacion'] . "</option>";
                                                    } else {
                                                        echo "<option value='" . $result[$i]['tipo_habitacion'] . "'>" . $result[$i]['tipo_habitacion'] . "</option>";
                                                    }
                                                }
                                            }
                                        }
                                        ?></select>
                                    <hr>

                                    <p>Cleaning Service:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cleaning_service_modify" id="flexRadioDefault1" value="cs_yes" title="Select this option if the room to modify has cleaning service." <?php
                                        if (isset($cleaning_modify) && $cleaning_modify == '1') {
                                            echo 'checked';
                                        }
                                        ?>>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cleaning_service_modify" id="flexRadioDefault2" value="cs_no" title="Select this option if the room to modify does not have cleaning sevice." <?php
                                        if (isset($cleaning_modify) && $cleaning_modify == '0') {
                                            echo 'checked';
                                        }
                                        ?>>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                    <hr>
                                    <p>Internet connection:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="internet_modify" id="flexRadioDefault1" value="i_yes" title="Select this option if the room to modify has internet connection."<?php
                                        if (isset($internet_modify) && $internet_modify == '1') {
                                            echo 'checked';
                                        }
                                        ?>>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="internet_modify" id="flexRadioDefault2" value="i_no" title="Select this option if the room to modify does not have internet connection." <?php
                                        if (isset($internet_modify) && $internet_modify == '0') {
                                            echo 'checked';
                                        }
                                        ?>>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="exampleInputText">Price</label>
                                        <input type="number" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="price_modify" step="0.01" placeholder="Enter room price" title="enter the price of the room to be modified. example: 80.10" <?php
                                               if (isset($price_modify)) {
                                                   echo 'value="' . $price_modify . '"';
                                               }
                                               ?>>
                                        <small>Format: 50.12</small>
                                    </div>
                                    <hr>
                                    <div>
                                        <button type="submit" name="Modify_room" class="btn btn-primary">Modify Room</button>
                                    </div> 
                                </form>
                            </div>
                        </div>
                        <hr> <!-- AddAdminUser and AddService -->
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 form_1">
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Add Admin User</h5>
                                <?php
                                if (isset($newaccountblankAdmin) and $newaccountblankAdmin == true) {
                                    echo "<p style='color: red; font-weight: bold'>Fill in all the fields of the form.</p>";
                                }
                                if (isset($añadido) and $añadido == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Process completed without error.</p>";
                                }
                                if (isset($añadido) and $añadido == false) {
                                    echo "<p style='color: red; font-weight: bold'>Error in the creation user process.</p>";
                                }
                                if (isset($password_errorAdmin) and $password_errorAdmin == true) {
                                    echo "<p style='color: red; font-weight: bold'>As contrasinais non coinciden</p>";
                                }
                                if (isset($email_errorAdmin) and $email_errorAdmin == true) {
                                    echo "<p style='color: red; font-weight: bold'>Xa hai un usuario rexistrado con este correo</p>";
                                }
                                if (isset($phone_errorAdmin) and $phone_errorAdmin == true) {
                                    echo "<p style='color: red; font-weight: bold'>Erro no formato do teléfono</p>";
                                }
                                if (isset($email_formatAdmin) and $email_formatAdmin == true) {
                                    echo "<p style='color: red; font-weight: bold'>Erro no formato do email</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <div class="form-group">
                                        <label for="exampleInputText">Name</label>
                                        <input type="text" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="personalname" placeholder="Enter your name" title="Enter the name that the new user will have">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1"
                                               aria-describedby="emailHelp" name="newemail" placeholder="Enter email. Example: example@example.com" title="You must enter an email for the user, example: example@example.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1"
                                               placeholder="Password" name="pass1" title="Enter a password for the user that must match the following password">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword2">Password Confirmation</label>
                                        <input type="password" class="form-control" id="exampleInputPassword2"
                                               placeholder="Password" name="pass2" title="Enter a password for the user that must match the previous password">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Enter your phone number:</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                               placeholder="Phone Number" title="Enter a phone number for the user, example: 986452378">
                                        <small>Format: 600123456</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Enter your address:</label>
                                        <input type="text" class="form-control" id="name" name="address"
                                               placeholder="Address" title="Enter the user's address, example: Vigo, rua vilagarcia de arousa, Nº2D">
                                    </div>
                                    <div>
                                        <button type="submit" name="CreateAccount" class="btn btn-primary">Create Account</button>
                                    </div> 
                                </form>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Add Service</h5>
                                <?php
                                if (isset($blank_service) and $blank_service == true) {
                                    echo "<p style='color: red; font-weight: bold'>Fill in all the fields of the form.</p>";
                                }
                                if (isset($addService) and $addService == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Process completed without error.</p>";
                                }
                                if (isset($addService) and $addService == false) {
                                    echo "<p style='color: red; font-weight: bold'>Error in the creation process.</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <div class="form-group">
                                        <label for="exampleInputText">Name</label>
                                        <input type="text" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="servicename" placeholder="Enter the service name" title="Enter the name for the new service">
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="exampleInputText">Price</label>
                                        <input type="number" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="serviceprice" step="0.01" placeholder="Enter service price" title="Enter the price for the new service">
                                        <small>Format: 50.12</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputText">Description</label>
                                        <input type="text" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="servicedescription" placeholder="Enter the service description" title="Enter the service description for the new service"">
                                    </div>
                                    <p>Service availability:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="availability_service" id="flexRadioDefault1" value="sa_yes" checked title="Select 'yes' if the service will be available">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="availability_service" id="flexRadioDefault2" value="sa_no" title="Select 'no' if the service won't be available">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                    <hr>
                                    <div>
                                        <button type="submit" name="Create_service" class="btn btn-primary">Add Service</button>
                                    </div> 
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 form_1">
                                <hr>
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Accept and Delete Reservations</h5>
                                <?php
                                if (isset($reserva) and $reserva == true) {
                                    echo "<p style='color: red; font-weight: bold'>Select a reservation.</p>";
                                }
                                if (isset($reserva_accept) and $reserva_accept == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Successful confirmation process.</p>";
                                }
                                if (isset($reserva_accept) and $reserva_accept == false) {
                                    echo "<p style='color: red; font-weight: bold'>Error when accepting the reservation.</p>";
                                }
                                if (isset($reserva_delete) and $reserva_delete == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Successful erasure process.</p>";
                                }
                                if (isset($reserva_delete) and $reserva_delete == false) {
                                    echo "<p style='color: red; font-weight: bold'>Error deleting a reservation..</p>";
                                }
                                ?>
                                <p>Select reservation:</p>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <select class="anchoselect" name="reserva" title="Select the reservation: ">    
                                        <option value=''>Select Reservation</option>
                                        <?php
                                        $reservas = new \functionsUsers\Conexion();
                                        $html = $reservas->cargar_reservas_admin();
                                        echo $html;
                                        ?></select>
                                    <hr>
                                    <div>
                                        <button type="submit" name="Accept_reservation" class="btn btn-success" style="margin-right: 5%">Accept Reservation</button>
                                        <button type="submit" name="Delete_reservation" class="btn btn-danger">Delete Reservation</button>
                                    </div> 
                                </form>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 form_1">
                                <hr>
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Logs</h5>
                                <?php
                                if (isset($blank_logs) and $blank_logs == true) {
                                    echo "<p style='color: red; font-weight: bold'>You must fill in the ID field.</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <div class="form-group">
                                        <label for="id_logs">ID user</label>
                                        <input type="number" class="form-control" id="id_logs"
                                               aria-describedby="textHelp" name="id_logs" step="1" placeholder="Enter user ID to see the logs" title="Enter the ID of the a user to see the logs"> 
                                    </div>
                                    <div>
                                        <button type="submit" name="id_user_logs" class="btn btn-primary">Enter ID</button>
                                    </div> 
                                </form>

                                <?php
                                if (isset($logs) and $logs != false and $logs != '') {
                                    echo "<hr><h5 class='display-5 text-dark mb-1 mt-2 text-center'>User Data</h5>" . $logs . "<hr>";
                                }
                                if (isset($logs) and $logs == '') {
                                    echo "<hr><p style='font-size:14pt;font-weight:bold;color:red;'>The ID entered is not of any registered user.</p><hr>";
                                }
                                ?>    
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <hr>
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Set reservable or not reservable</h5>
                                <?php
                                if (isset($reservable_blank) and $reservable_blank == true) {
                                    echo "<p style='color: red; font-weight: bold'>Select a reservable room.</p>";
                                }
                                if (isset($set_no_reservable) and $set_no_reservable == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Room status changed to Not Reservable</p>";
                                }
                                ?>
                                <p>Select reservable room:</p>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <select name="reservables" title="Select the reservation: ">    
                                        <option value=''>Select Reservable Room</option>
                                        <?php
                                        $reservas = new \functionsUsers\Conexion();
                                        $html = $reservas->cargar_reservables();
                                        echo $html;
                                        ?></select>
                                    <hr>
                                    <div>
                                        <button type="submit" name="Set_no_reservable" class="btn btn-danger" style="margin-right: 5%">Set no reservable</button>

                                    </div> 
                                </form>
                                <br>
                                <hr>
                                <?php
                                if (isset($no_reservable_blank) and $no_reservable_blank == true) {
                                    echo "<p style='color: red; font-weight: bold'>Select a not reservable room.</p>";
                                }
                                if (isset($set_reservable) and $set_reservable == true) {
                                    echo "<p style='color: blue; font-weight: bold'>Room status changed to Reservable</p>";
                                }
                                ?>
                                <p>Select no reservable room:</p>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <select name="no_reservables" title="Select the reservation: ">    
                                        <option value=''>Select No Reservable Room</option>
                                        <?php
                                        $reservas = new \functionsUsers\Conexion();
                                        $html = $reservas->cargar_no_reservables();
                                        echo $html;
                                        ?></select>
                                    <hr>
                                    <div>
                                        <button type="submit" name="Set_reservable" class="btn btn-success" style="margin-right: 5%">Set reservable</button>

                                    </div> 
                                </form>
                            </div>
                        </div>

                        <!--footer-->
                        <div class="footer">
                            <!--datos hotel-->
                            <div class="datos">
                                <!--logo-->
                                <div class="logo_footer"><img src="../../images/hotel/hotel.png" alt="logo da páxina"></div>
                                <!--direccion-->
                                <div>
                                    <a href="#"><img class="icon_navbar" src="../../images/icons/geo-alt-fill.svg"
                                                     alt="icono marca ubicación"></a>
                                    <span>Avda. de Galicia, 101, 36216 Vigo, Pontevedra</span>
                                </div>
                                <!--telefono-->
                                <div>
                                    <a href="#"><img class="icon_navbar" src="../../images/icons/telephone-fill.svg"
                                                     alt="icono telefono"></a>
                                    <span>+34986000123</span>
                                </div>
                                <!--email-->
                                <div>
                                    <a href="#"><img class="icon_navbar" src="../../images/icons/envelope-fill.svg"
                                                     alt="icono sobre de mensaxe"></a>
                                    <span>reservas@hotelmaravilla.com</span>
                                </div>
                            </div>
                            <!--nota legal-->
                            <div class="nota_legal">
                                <p>Hotel Maravilla © 2021<br>Legal Note</p>
                            </div>
                        </div>
                    </div>
                </div>   
                <?php
            } else {
                echo "<h2>You must be an administrator user to access this section.<a href='../../index.php'> Back to index</a></h2>";
            }
            ?>
        </div>
        <!--footer-->

    </div>
</div>  
<!--arquivos js-->
<script src="../../js/bootstrap/jquery.js"></script>
<script src="../../js/bootstrap/popper.min.js"></script>
<script src="../../js/bootstrap/bootstrap.min.js"></script>
</body>

</html>