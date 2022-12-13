<?php
session_start();
include '../autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <!--icono da páxina-->
        <link rel="icon" type="image/png" href="../images/hotel/hotel_icon.png">
        <!--arquivos css-->
        <link rel="stylesheet" href="../css/location.css">
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">
        <script src="../js/jquery-ui/jquery-ui.js"></script>
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
                        <div><a href="#"><img class="icon_navbar" src="../images/icons/telephone-fill.svg"
                                              alt="icono telefono"></a>
                            <span>+34986000123</span><a href="#">
                        </div>
                        <!--rrss-->
                        <div>
                            <a href="https://web.whatsapp.com/send?phone=0034666000666"><img class="icon_navbar"
                                                                                             src="../images/icons/whatsapp.svg" alt="icono whatsapp"></a>
                            <a href="https://twitter.com/hotelmaravilla"><img class="icon_navbar"
                                                                              src="../images/icons/facebook.svg" alt="icono facebook"></a>
                            <a href="https://www.facebook.com/HotelMaravilla/"><img class="icon_navbar"
                                                                                    src="../images/icons/twitter.svg" alt="icono twitter"></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--navbar-->
            <div class="row">
                <!--logo da páxina-->
                <div class="logo col-12 col-sm-3 col-md-2">
                    <a href="../index.php"><img src="../images/hotel/hotel.png" alt="logo da páxina"></a>
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
                                        <a class="nav-link text-wrap" href="../index.php" role="button"
                                           aria-haspopup="true" aria-expanded="false"><img class="icon_navbar"
                                                                                        src="../images/icons/house-fill.svg" alt="icono casa">
                                            HOME
                                        </a>
                                    </li>
                                    <?php
                                    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) {
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link text-wrap" href="adminMenu/AdminFunctions.php" role="button" aria-haspopup="true"
                                               aria-expanded="false"><img class="icon_navbar"
                                                                       src="../images/icons/gear-fill.svg" alt="icono engranaje">
                                                ADMIN
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="rooms.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar"
                                                                   src="../images/icons/door-open.svg" alt="icono porta">
                                            ROOMS
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="gallery.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar" src="../images/icons/images.svg"
                                                                   alt="icono galería de imáxenes">
                                            GALLERY
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="contacto.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar" src="../images/icons/chat-left-dots.svg" 
                                                                   alt="icono contacto">
                                            CONTACT
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="location.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar" src="../images/icons/globe.svg"
                                                                   alt="icono globo terráqueo, icono ubicación">
                                            LOCATION
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href=" <?php
                                        if (isset($_SESSION['username'])) {
                                            echo 'profile/modifyProfile.php';
                                        } else {
                                            echo'loginYregistro/loginAndRegister.php';
                                        }
                                        ?>" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar"
                                                                   src="../images/icons/person-square.svg" alt="icono usuario">
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
            $cabecera->cabecera();
            ?>
        </div>
        <!--zona central-->
        <div class="container mb-3 fondo-claro">

            <div class="row">
                <div class="col">
                    <div class="col">
                        <h3 class="display-5 bg-secondary text-white mb-1 mt-2 text-center">Contact us</h3>
                        <div class="row justify-content-center">
                            <div class="col-md-9 mb-md-0 mb-5">
                                <form id="contact-form" name="contact-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="md-form mb-0">
                                                <label for="name" class="">Your name</label>
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" title="You must enter your full name. example: Jose Perez Rodriguez">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="md-form mb-0">
                                                <label for="email" class="">Your email</label>
                                                <input type="text" id="email" name="email" class="form-control" placeholder="Enter email. Example: example@example.com" title="You must enter your email, example: example@example.com">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="md-form mb-0">
                                                <label for="subject" class="">Subject</label>
                                                <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter message subject." title="You must enter the message subject.">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="md-form">
                                                <label for="message">Your message</label>
                                                <textarea type="text" id="message" name="message" rows="2" class="form-control md-textarea" placeholder="Enter your message." title="You must enter the message subject."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <button type="submit" name="Send" class="btn btn-primary">Send</button>
                                    </div>
                                </form>
                            </div>     
                        </div>
                    </div>
                    <!--footer-->
                    <div class="footer">
                        <!--datos hotel-->
                        <div class="datos">
                            <!--logo-->
                            <div class="logo_footer"><img src="../images/hotel/hotel.png" alt="logo da páxina"></div>
                            <!--direccion-->
                            <div>
                                <a href="#"><img class="icon_navbar" src="../images/icons/geo-alt-fill.svg"
                                                 alt="icono marca ubicación"></a>
                                <span>Avda. de Galicia, 101, 36216 Vigo, Pontevedra</span>
                            </div>
                            <!--telefono-->
                            <div>
                                <a href="#"><img class="icon_navbar" src="../images/icons/telephone-fill.svg"
                                                 alt="icono telefono"></a>
                                <span>+34986000123</span>
                            </div>
                            <!--email-->
                            <div>
                                <a href="#"><img class="icon_navbar" src="../images/icons/envelope-fill.svg"
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
                <script src="../js/bootstrap/jquery.js"></script>
                <script src="../js/bootstrap/popper.min.js"></script>
                <script src="../js/bootstrap/bootstrap.min.js"></script>
                <script src="../js/lightbox/js/lightbox.js"></script>
                </body>

                </html>