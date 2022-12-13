<?php

namespace profile;

session_start();
include '../../autoload.php';

/**
 * Clase modifyProfile
 * 
 * Contiene funciones que permiten a un usuario modificar su perfil.
 * 
 * @author Adrian Fernandez Perez y Jorge Val Gil
 * @version 1.0
 */
class modifyProfile {
    //Funciones
    private $blank_modify;
    private $modify_profile;
    private $blank_password;
    private $modify_password;
    private $password_fail;
    private $equal_password;

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
     * @return mixed Devuelve el valor que pedimos
     */
    public function __get($value) {
        return $this->$value;
    }

    /**
     * Función updateProfile
     * 
     * Comprobamos que tengan datos los campos, si no es así, muestra un mensaje de error, si no, creamos un objeto de la clase
     * registrarValidar y validamos primero el teléfono, si es correcto, guardamos en un array los valores de los campos y le cambiamos
     * la $_SESSION por el nombre modificado, crea un objeto admin y llama a la función updateProfile y le pasa el array de datos.
     * Por ultimo modifica el log de la ultima vez actualizado.
     * 
     */
    function updateProfile() {
        if ($_POST['name_modify'] == '' || $_POST['phone_modify'] == '' || $_POST['address_modify'] == '') {
            $this->blank_modify = true;
        } else {

            $validar = new \validarRegistro\RegistrarValidar();
            $phone = $validar->valid_phone($_POST['phone_modify']);

            if ($phone) {
                $datos_perfil = array($_POST['name_modify'], $_POST['phone_modify'], $_POST['address_modify'], $_SESSION['id_user']);
                $_SESSION['username'] = $_POST['name_modify'];
                $admin = new \functionsUsers\Admin();
                $this->modify_profile = $admin->updateprofile($datos_perfil);

                if ($this->modify_profile == true) {
                    $id = array($_SESSION['id_user']);
                    $this->update_last_modified($id);
                }
            }
        }
    }

    /**
     * Function changePassword
     * 
     * Función que comprueba que los campos esten con datos primero, si no da un error,
     * luego modifica la contraseña actual del usuario
     * 
     */
    function changePassword() {
        if ($_POST['pass'] == '' || $_POST['new_pass'] == '' || $_POST['new_pass1'] == '') {
            $this->blank_password = true;
        } else {
            $id = array();
            $id[0] = $_SESSION['id_user'];
            $conexion = new \functionsUsers\Conexion();
            $actual_password = $conexion->getPassword($id);

            if ($actual_password) {

                $password_verify = password_verify($_POST['pass'], $actual_password);

                if ($password_verify) {
                    $validar = new \validarRegistro\RegistrarValidar();
                    $new_pass = $validar->compare_password($_POST['new_pass'], $_POST['new_pass1']);
                    if ($new_pass == false) {
                        $this->equal_password = $new_pass;
                    }
                } else {
                    $this->password_fail = true;
                }
            }

            if (isset($new_pass) && $new_pass != false) {
                $admin = new \functionsUsers\Admin();
                $userdata = array($new_pass, $_SESSION['id_user']);
                $this->modify_password = $admin->updatepassword($userdata);

                if ($this->modify_password == true) {

                    $id = array($_SESSION['id_user']);
                    $this->update_last_modified($id);

                    $correo_user = $conexion->getEmail($_SESSION['id_user']);

                    if ($correo_user != false) {
                        $correo = new \correo\Correo();
                        $asunto = 'Password changed in Hotel Maravilla';
                        $correo->enviar_correos_contrasena($correo_user, $asunto);
                    }
                    
                    header('Location: ../password_changed.php');
                }
            }
        }
    }

    /**
     * Function update_last_modified
     * 
     * Crea un objeto admin y actualiza el log de la ultima actualización del usuario
     * 
     * @param mixed $id id del usuario
     * 
     */
    function update_last_modified($id) {
        $admin = new \functionsUsers\Admin();
        $admin->last_modify($id);
    }

}

if (isset($_POST['Modify_profile'])) {
    $modify = new \profile\modifyProfile();
    $modify->updateProfile();
    $blank_modify = $modify->blank_modify;
    $modify_profile = $modify->modify_profile;
}

if (isset($_POST['Change_password'])) {
    $password = new \profile\modifyProfile();
    $password->changePassword();
    $blank_password = $password->blank_password;
    $modify_password = $password->modify_password;
    $password_fail = $password->password_fail;
    $equal_password = $password->equal_password;
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
                    <a href="../index.php"><img src="../../images/hotel/hotel.png" alt="logo da páxina"></a>
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
                                            <a class="nav-link text-wrap" href="../adminMenu/AdminFunctions.php" role="button" aria-haspopup="true"
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
                                            echo 'modifyProfile.php';
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

            <div class="row">
                <div class="col">
                    <div class="col">
                        <!--cards de habitacions-->
                        <h3 class="display-5 bg-secondary text-white mb-1 mt-2 text-center">Modify Profile</h3>
                        <p id="aviso" style="color: red; font-weight: bold"></p>
                        <div class="row justify-content-center">
                            <?php
                            if (isset($_SESSION['username'])) {

                                $id = array();
                                $id[0] = $_SESSION['id_user'];
                                $conexion = new \functionsUsers\Conexion();
                                $datos = $conexion->cargar_datos_modify($id);
                                ?>
                                <div class="col-xl-6 col-lg-6 col-md-6 form_1">
                                    <h5 class="display-5 text-dark mb-1 mt-2 text-center">Modify Personal Data</h5>
                                    <?php
                                    if (isset($blank_modify) and $blank_modify == true) {
                                        echo "<p style='color: red; font-weight: bold'> You cannot leave any fields empty</p>";
                                    }
                                    if (isset($modify_profile) and $modify_profile == true) {
                                        echo "<p style='color: blue; font-weight: bold'> Profile modified successfully</p>";
                                    }
                                    ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                        <div class="form-group">
                                            <label for="exampleInputText">Name:</label>
                                            <input type="text" class="form-control" id="exampleInputText"
                                                   aria-describedby="textHelp" name="name_modify" placeholder="Enter your name" title="You must enter your full name. example: Jose Perez Rodriguez" 
                                                   <?php
                                                   if (isset($datos[0])) {
                                                       echo 'value="' . $datos[0] . '"';
                                                   }
                                                   ?>>
                                        </div>

                                        <hr>
                                        <div class="form-group">
                                            <label for="phone">Enter your phone number:</label>
                                            <input type="tel" class="form-control" id="phone" name="phone_modify"
                                                   placeholder="Phone Number" title="Enter your phone number. example: 666111222." <?php
                                                   if (isset($datos[1])) {
                                                       echo 'value="' . $datos[1] . '"';
                                                   }
                                                   ?>>
                                            <small>Format: 600123456</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Enter your address:</label>
                                            <input type="text" class="form-control" id="name" name="address_modify"
                                                   placeholder="Address" title="Enter your address. Example: Avenida de Galicia, 18. Vigo" <?php
                                                   if (isset($datos[2])) {
                                                       echo 'value="' . $datos[2] . '"';
                                                   }
                                                   ?>>
                                        </div>
                                        <hr>


                                        <div>
                                            <button type="submit" name="Modify_profile" class="btn btn-primary">Modify Profile</button>
                                        </div> 
                                    </form>
                                </div>


                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <h5 class="display-5 text-dark mb-1 mt-2 text-center">Change Password</h5>
                                    <?php
                                    if (isset($blank_password) and $blank_password == true) {
                                        echo "<p style='color: red; font-weight: bold'> You cannot leave any fields empty.</p>";
                                    }
                                    if (isset($password_fail) and $password_fail == true) {
                                        echo "<p style='color: red; font-weight: bold'> Enter current password correctly.</p>";
                                    }
                                    if (isset($equal_password) and $equal_password == false) {
                                        echo "<p style='color: red; font-weight: bold'> the new passwords do not match.</p>";
                                    }
                                    if (isset($modify_password) and $modify_password == true) {
                                        echo "<p style='color: blue; font-weight: bold'> Password modified successfully</p>";
                                    }
                                    ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Actual Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1"
                                                   placeholder="Password" name="pass" title="Enter your password.">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword2">New Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword2"
                                                   placeholder="Password" name="new_pass" title="Enter the new password.">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword3">New Password Confirmation</label>
                                            <input type="password" class="form-control" id="exampleInputPassword3"
                                                   placeholder="Password" name="new_pass1" title="Re-enter the new password.">
                                        </div>
                                        <div>
                                            <button type="submit" name="Change_password" class="btn btn-primary">Change Password</button>
                                        </div> 
                                    </form>
                                </div>

                                <?php
                            } else {
                                echo "<p style='font-size:14pt; color:red; font-weight:bold;'>You must be logged in to view the content of this page.</p>";
                            }
                            ?>


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
            <!--arquivos js-->
            <script src="../../js/bootstrap/jquery.js"></script>
            <script src="../../js/bootstrap/popper.min.js"></script>
            <script src="../../js/bootstrap/bootstrap.min.js"></script>
            <!--script para función popover-->
            <script>
                $(function () {
                    $('[data-toggle="popover"]').popover()
                })
            </script>
            <!--script para función tooltip-->
            <script>
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })
            </script>
    </body>

</html>