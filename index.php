<?php
session_start();
include 'autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <!--icono da páxina-->
        <link rel="icon" type="image/png" href="images/hotel/hotel_icon.png">
        <!--arquivos css-->
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css"
        <!--título da páxina-->
        <title>HOTEL MARAVILLA</title>
    </head>
    <!--body-->
    <body>
        <!--contedor do menu superior e do navbar-->
        <div class="container fondo-claro">
            <!-- Aviso de cookies, con link a outra páxina-->
            <div class="row">
                <div class="col">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Accept">
                            <span>Accept</span>

                        </button> This page uses cookies <a class="alert-link" href="html/cookies.php">Read more</a>
                    </div>
                </div>
            </div>
            <!--menu superior-->
            <div class="row">
                <div class="col">
                    <div class="rowtop d-flex justify-content-end">
                        <!--telefono-->
                        <div><a href="#"><img class="icon_navbar" src="images/icons/telephone-fill.svg" alt="icono telefono"></a>
                            <span>+34986000123</span><a href="#">
                        </div>
                        <!--rrss-->
                        <div>
                            <a href="https://web.whatsapp.com/send?phone=0034666000666"><img class="icon_navbar"
                                                                                             src="images/icons/whatsapp.svg" alt="icono whatsapp"></a>
                            <a href="https://twitter.com/hotelmaravilla"><img class="icon_navbar"
                                                                              src="images/icons/facebook.svg" alt="icono facebook"></a>
                            <a href="https://www.facebook.com/HotelMaravilla/"><img class="icon_navbar"
                                                                                    src="images/icons/twitter.svg" alt="icono twitter"></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--navbar-->
            <div class="row">
                <!--logo da páxina-->
                <div class="logo col-12 col-sm-3 col-md-2">
                    <a href="index.php"><img src="images/hotel/hotel.png" alt="logo da páxina"></a>
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
                                        <a class="nav-link text-wrap" href="index.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar"
                                                                   src="images/icons/house-fill.svg" alt="icono casa">
                                            HOME
                                        </a>
                                    </li>
                                    <?php
                                    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) {
                                        ?>

                                        <li class="nav-item">
                                            <a class="nav-link text-wrap" href="html/adminMenu/AdminFunctions.php" role="button" aria-haspopup="true"
                                               aria-expanded="false"><img class="icon_navbar"
                                                                       src="images/icons/gear-fill.svg" alt="icono engranaje">
                                                ADMIN
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="html/rooms.php" role="button"
                                           aria-haspopup="true" aria-expanded="false"><img class="icon_navbar"
                                                                                        src="images/icons/door-open.svg" alt="icono porta">
                                            ROOMS
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="html/gallery.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar" src="images/icons/images.svg" alt="icono galería de imáxenes">
                                            GALLERY
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="html/contacto.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar"
                                                                   src="images/icons/chat-left-dots.svg" alt="icono contacto">
                                            CONTACT
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="html/location.php" role="button" aria-haspopup="true"
                                           aria-expanded="false"><img class="icon_navbar" src="images/icons/globe.svg" alt="icono globo terráqueo, icono ubicación">
                                            LOCATION
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-wrap" href="<?php
                                        if (isset($_SESSION['username'])) {
                                            echo 'html/profile/modifyProfile.php';
                                        } else {
                                            echo'html/loginYregistro/loginAndRegister.php';
                                        }
                                        ?>" role="button" aria-haspopup="true"
                                           aria-expanded="false" ><img class="icon_navbar"
                                                                    }
                                                                    src="images/icons/person-square.svg" alt="icono usuario">
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
            $cabecera->cabeceraIndex();
            if (isset($_SESSION['create'])) {
                echo "<p style='text-align:center; color: darkblue; margin-bottom: 0; padding-bottom: 3px;'>User <b>" . $_SESSION['usuario'] . "</b> created successfully</p>";
                unset($_SESSION['create']);
            }
            ?>
        </div>
        <!--zona central-->
        <div class="container mb-3 fondo-claro">
            <div class="row fondo-claro">
                <div class="col-12 pb-4">
                    <!--carrusel-->
                    <div id="carrusel-proba" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carrusel-proba" data-slide-to="0" class="active"></li>
                            <li data-target="#carrusel-proba" data-slide-to="1"></li>
                            <li data-target="#carrusel-proba" data-slide-to="2"></li>
                            <li data-target="#carrusel-proba" data-slide-to="3"></li>
                            <li data-target="#carrusel-proba" data-slide-to="4"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="images/carrusel/BARANDILLA.jpg" alt="Vistas dende balcón hotel">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/carrusel/CRISTALERA.jpg" alt="Vistas dende jacuzzi hotel">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/carrusel/LAGO.jpg" alt="Vista aérea 1">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/carrusel/LAGO2.jpg" alt="Vista aérea 2">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/carrusel/PAISAJE.jpg" alt="Vista aérea hotel">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carrusel-proba" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="carousel-control-next" href="#carrusel-proba" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Seguinte</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--titulo nuestras habitaciones-->
            <div class="row">
                <div class="col">
                    <h3 class="display-5 bg-secondary text-white mb-1 mt-2 text-center">Most Popular Rooms</h3>
                </div>
            </div>
            <!--cards de habitacions-->
            <div class="col" align="center">
                <div class="row mt-2">

                    <p id="aviso" style="color: red; font-weight: bold"></p>
                    <div class='col-md-6 col-sm-6 mb-4'>

                        <label for="start">Start date:</label>

                        <input type="date" id="start" name="start"> 

                    </div>

                    <div class='col-md-6 col-sm-6 mb-4'>
                        
                     <label for="end">End date:</label>

                    <input type="date" id="end" name="end">   
                        
                    </div>

                    

                </div>




                <div class="row justify-content-center">
                    <?php
                    $tipo_habitacion = new \functionsUsers\Conexion();
                    $nombre_habitacion = array('double');
                    $nombre_habitacion2 = array('suite');
                    $nombre_habitacion3 = array('family');
                    $html = $tipo_habitacion->cargar_habitacion($nombre_habitacion);
                    echo $html;
                    $html = $tipo_habitacion->cargar_habitacion($nombre_habitacion2);
                    echo $html;
                    $html = $tipo_habitacion->cargar_habitacion($nombre_habitacion3);
                    echo $html;
                    ?>
                </div>
            </div>

            <!--zona sobre nosotros-->
            <div class="row">
                <div class="col">
                    <h3 class="display-5 bg-secondary text-white mb-1 mt-2 text-center">About us</h3>
                    <p class="sobre_nosotros">The <span>Maravilla Hotel</span> occupies an old building built to be used as a prison, on 
                        Monteagudo Island: the internal structure of this exclusive hotel is extremely welcoming and functional, 
                        to guarantee guests a stay characterized by comfort and relaxation.<br>
                        <br>The decoration in a modern style and the warmth of the attention contribute to a unique 
                        and unequaled experience in the world.<br>
                        <br><span>Hotel Maravilla</span> will be happy to point you to the most interesting places to visit, even outside the
                        traditional tourist circuits, so that you can discover even the most hidden treasures of the city. Two examples 
                        are Samil beach and the characteristic Monte de O Castro. As for the pleasures of the palate, Vigo offers the possibility 
                        of tasting many specialties. And to savor Galician cuisine, nothing better than receiving the advice of the Galicians themselves: 
                        the <span>Hotel Maravilla</span> will suggest food and wine itineraries in the most characteristic and best quality inns and restaurants.
                    </p>
                </div>
            </div>
            <!--footer-->
            <div class="footer">
                <!--datos hotel-->
                <div class="datos">
                    <!--logo-->
                    <div class="logo_footer"><img src="images/hotel/hotel.png" alt="logo da páxina"></div>
                    <!--direccion-->
                    <div>
                        <a href="#"><img class="icon_navbar" src="images/icons/geo-alt-fill.svg" alt="icono marca ubicación"></a>
                        <span>Avda. de Galicia, 101, 36216 Vigo, Pontevedra</span>
                    </div>
                    <!--telefono-->
                    <div>
                        <a href="#"><img class="icon_navbar" src="images/icons/telephone-fill.svg" alt="icono telefono"></a>
                        <span>+34986000123</span>
                    </div>
                    <!--email-->
                    <div>
                        <a href="#"><img class="icon_navbar" src="images/icons/envelope-fill.svg" alt="icono sobre de mensaxe"></a>
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
        <script src="js/bootstrap/jquery.js"></script>
        <script src="js/bootstrap/popper.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/filtro_habitaciones.js"></script>
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