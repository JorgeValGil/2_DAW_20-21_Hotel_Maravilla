<?php

namespace loginYregistro;

session_start();

include '../../autoload.php';

/**
 * Clase loginAndRegister
 * 
 * Contiene funciones para la creación de una cuenta de usuario y el inicio de sesión de la misma.
 * 
 * @author Adrian Fernandez Perez y Jorge Val Gil
 * @version 1.0
 */
class loginAndRegister {

    //úsanse na function login
    private $blank;
    private $err;
    //úsanse na function create
    private $newaccountblank;
    private $email_error;
    private $email_format;
    private $phone_error;
    private $password_error;

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
     * Función login
     * 
     * Si el formulario tiene datos, crea un objeto conexión con el cual comprobará los datos introducidos, si es correcto
     * crea una $_SESSION con ciertos datos de la cuenta del usuario, y crea un objeto admin y registra un nuevo acceso.
     * Luego nos mostrará un index diferente dependiendo del rol del usuario.
     * Si algo de lo anterior fue incorrecto, mostrará un error dependiendo del caso.
     * 
     */
    function login() {

        if ($_POST['email'] == '' || $_POST['clave'] == '') {
            $this->blank = true;
        } else {
            $conexion = new \functionsUsers\Conexion;
            $usu = $conexion->comprobar_usuario($_POST['email'], $_POST['clave']);
            if ($usu === false) {
                $this->err = true;
            } else {
                $_SESSION['usuario'] = $usu[0];
                $_SESSION['rol'] = $usu[1];
                $_SESSION['username'] = $usu[2];
                $_SESSION['id_user'] = $usu[3];
                
                $admin = new \functionsUsers\Admin();
                $id_access= array($usu[3]);
                $admin->last_access($id_access);
                
                
                if ($usu[1] == 1) {
                    header("Location: ../adminMenu/AdminFunctions.php");
                } else if ($usu[1] == 2) {
                    header("Location: ../../index.php");
                } else if ($usu[1] == 3) {
                    header("Location: ../../index.php");
                }
            }
        }
    }

    /**
     * Función create
     * 
     * Primero comprueba que el formulario tenga datos, si no es así, mostrará un error, si no, creará diferentes objetos de diferentes clases para
     * comprobar cada dato introducido, si todo está correcto, guardará todos los datos en un array, creará un usuario admin y llamará a la función
     * añadir_usuario pasandole por parametro el array de datos.
     * Si todo fue correctamente, iniciamos sesión con los datos del usuario, los guardamos en $_SESSION y nos muestra el index.
     * Luego mandará un correo al email del usuario conforme creó la cuenta.
     * 
     */
    function create() { {
            if ($_POST['personalname'] == '' || $_POST['newemail'] == '' || $_POST['pass1'] == '' || $_POST['pass2'] == '' || $_POST['phone'] == '' || $_POST['address'] == '') {
                $this->newaccountblank = true;
            } else {
                // Objeto de la clase Conexion para llamar a sus funciones
                $objetoConexion = new \functionsUsers\Conexion();
                $email = $objetoConexion->comprobar_email(array($_POST['newemail']));
                if ($email === false) {
                    $this->email_error = true;
                }

                // Objeto de la clase registrarValidar para llamar a sus funciones
                $objetoValidarRegistrar = new \validarRegistro\RegistrarValidar();
                $email_valid = $objetoValidarRegistrar->valid_email($_POST['newemail']);
                if ($email_valid === false) {
                    $this->email_format = true;
                }
                $phone = $objetoValidarRegistrar->valid_phone($_POST['phone']);
                if ($phone === false) {
                    $this->phone_error = true;
                }
                $password = $objetoValidarRegistrar->compare_password($_POST['pass1'], $_POST['pass2']);
                if ($password === false) {
                    $this->password_error = true;
                }
                if (!isset($this->password_error) && !isset($this->email_error) && !isset($this->phone_error) && !isset($this->email_format)) {
                    $datos_usuario = array($_POST['personalname'], $_POST['newemail'], $_POST['phone'], $_POST['address'], $password, 2);

                    $admin = new \functionsUsers\Admin();
                    $anadido = $admin->anadir_usuario($datos_usuario);

                    if ($anadido) {
                        //facemos uso do método comprobar_usuario, unha vez foi creado, para coller o seu id
                        $conexion1 = new \functionsUsers\Conexion;
                        $user = $conexion1->comprobar_usuario($_POST['newemail'], $_POST['pass1']);

                        $_SESSION['usuario'] = $_POST['newemail'];
                        $_SESSION['rol'] = 2;
                        $_SESSION['username'] = $_POST['personalname'];
                        $_SESSION['create'] = true;
                        $_SESSION['id_user'] = $user[3];



                        $datos_correo = array($_POST['newemail'], $_POST['personalname']);

                        $correo = new \correo\Correo();
                        $asunto = 'Account created in Hotel Maravilla';
                        $correo->enviar_correos_account($datos_correo[0], $datos_correo[1], $asunto);


                        header("Location: ../../index.php");
                    }
                }
            }
        }
    }

}

if (isset($_POST['Login'])) {
    $login = new \loginYregistro\loginAndRegister();
    $login->login();
    $blank = $login->blank;
    $err = $login->err;
}

if (isset($_POST['Create'])) {
    $create = new \loginYregistro\loginAndRegister();
    $create->create();
    $newaccountblank = $create->newaccountblank;
    $email_error = $create->email_error;
    $email_format = $create->email_format;
    $phone_error = $create->phone_error;
    $password_error = $create->password_error;
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
        <script>
            $(function () {
                $(document).tooltip();
            });
        </script>
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
                                           aria-expanded="false"><img class="icon_navbar"
                                                                   src="../../images/icons/chat-left-dots.svg" alt="icono contacto">
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
                                            echo'loginAndRegister.php';
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
            <!--Registro y Cuenta-->
            <div class="row">
                <div class="col">

                    <?php
                    if (!isset($_SESSION['usuario'])) {
                        ?>
                        <h3 class="display-5 bg-secondary text-white mb-1 mt-2 text-center">Login & Create Account</h3>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 form_1">
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Login</h5>
                                <?php
                                if (isset($blank) and $blank == true) {
                                    echo "<p style='color: red; font-weight: bold'> Rellena ambos campos</p>";
                                }
                                if (isset($err) and $err == true) {
                                    echo "<p style='color: red; font-weight: bold'> Revise usuario y contraseña</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="email" class="form-control" id="exampleInputEmail1"
                                               aria-describedby="emailHelp" name="email" placeholder="Enter email" title="You must enter the email with which you are registered. example@domain.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1" name = "clave"
                                               placeholder="Password" title="You must enter the password with which you are registered.">
                                    </div>
                                    <button type="submit" name="Login" class="btn btn-primary">Login</button>
                                </form>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <h5 class="display-5 text-dark mb-1 mt-2 text-center">Create Account</h5>
                                <?php
                                if (isset($newaccountblank) and $newaccountblank == true) {
                                    echo "<p style='color: red; font-weight: bold'>Rellena todos os campos</p>";
                                }
                                if (isset($password_error) and $password_error == true) {
                                    echo "<p style='color: red; font-weight: bold'>As contrasinais non coinciden</p>";
                                }
                                if (isset($email_error) and $email_error == true) {
                                    echo "<p style='color: red; font-weight: bold'>Xa hai un usuario rexistrado con este correo</p>";
                                }
                                if (isset($phone_error) and $phone_error == true) {
                                    echo "<p style='color: red; font-weight: bold'>Erro no formato do teléfono</p>";
                                }
                                if (isset($email_format) and $email_format == true) {
                                    echo "<p style='color: red; font-weight: bold'>Erro no formato do email</p>";
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                                    <div class="form-group">
                                        <label for="exampleInputText">Name</label>
                                        <input type="text" class="form-control" id="exampleInputText"
                                               aria-describedby="textHelp" name="personalname" placeholder="Enter your name" title="You must enter your full name. example: Jose Perez Rodriguez">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1"
                                               aria-describedby="emailHelp" name="newemail" placeholder="Enter email. Example: example@example.com" title="You must enter your email, example: example@example.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1"
                                               placeholder="Password" name="pass1" title="Enter your password.">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword2">Password Confirmation</label>
                                        <input type="password" class="form-control" id="exampleInputPassword2"
                                               placeholder="Password" name="pass2" title="Re-enter the same password.">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Enter your phone number:</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                               placeholder="Phone Number" title="Enter your phone number. example: 666111222.">
                                        <small>Format: 600123456</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Enter your address:</label>
                                        <input type="text" class="form-control" id="name" name="address"
                                               placeholder="Address" title="Enter your address. Example: Avenida de Galicia, 18. Vigo">
                                    </div>
                                    <div>
                                        <button type="submit" name="Create" class="btn btn-primary">Create Account</button>
                                    </div> 
                                </form>
                            </div>
                        </div>               
                        <?php
                    } else {
                        echo "<h2>You already have a session started. <a href='../logout.php'>Log out.</a></h2>";
                    }
                    ?>


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
                <!--arquivos js-->
                <script src="../../js/bootstrap/jquery.js"></script>
                <script src="../../js/bootstrap/popper.min.js"></script>
                <script src="../../js/bootstrap/bootstrap.min.js"></script>
                </body>

                </html>