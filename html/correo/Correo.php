<?php

namespace correo;

use \PHPMailer\PHPMailer\PHPMailer as phpmailer;

require __DIR__ . "/../../vendor/autoload.php";

/**
 * Clase Correo
 * Clase que controla o envío de correos
 * @version 1.0
 * @author Jorge Val Gil e Adrián Fernández Pérez
 */
class Correo {

    /**
     * Carga os datos do email
     * Obtén os datos do email que se encarga do envío de correos, obtén os datos un ficheiro xml e valida con xsd
     * @param string $nombre ficheiro XML cos datos dirección de correo electrónico e password
     * @param string $esquema ficheiro XSD encargado de validar o XML
     * 
     * @return array devolve un array de dúas posicións, composto por clave e usuario
     */
    function leer_configCorreo($nombre, $esquema) {
        $config = new \DOMDocument();
        $config->load($nombre);
        $res = $config->schemaValidate($esquema);
        if ($res === FALSE) {
            throw new \InvalidArgumentException("Revise fichero de configuración");
        }
        $datos = simplexml_load_file($nombre);
        $usu = $datos->xpath("//usuario");
        $clave = $datos->xpath("//clave");
        $resul = [];
        $resul[] = $usu[0];
        $resul[] = $clave[0];
        return $resul;
    }

    /**
     * Envía correo de creación de conta
     * Recibe os datos para a creación do correo para creación de novas contas, obtén o corpo do correo e envía os datos á función de envío de correos
     * @param string $correo correo de destino
     * @param string $nombre nome de usuario
     * @param string $asunto asunto do correo
     * 
     * @return [type] chama á function enviar_correo e envialle os datos
     * @see crear_correo_account()
     * @see enviar_correo()
     */
    function enviar_correos_account($correo, $nombre, $asunto) {

        $cuerpo = $this->crear_correo_account($nombre);
        return $this->enviar_correo($correo, $cuerpo, $asunto);
    }

    /**
     * Envía correo de creación de reserva aceptada
     * Recibe os datos para a creación do correo para reserva aceptada, obtén o corpo co correo e envía os datos á función de envío de correos
     * @param string $correo correo de destino
     * @param string $asunto asunto do correo
     * 
     * @return [type] chama á function enviar_correo e envialle os datos
     * @see reserva_aceptada()
     * @see enviar_correo()
     */
    function enviar_correos_reserva($correo, $asunto) {

        $cuerpo = $this->reserva_aceptada();
        return $this->enviar_correo($correo, $cuerpo, $asunto);
    }

    /**
     * Envía correo de cambio de contrasinal
     * Recibe os datos para a creación do correo para cambio de contrasinal, obtén o corpo co correo e envía os datos á función de envío de correos
     * @param string $correo correo de destino
     * @param string $asunto asunto do correo
     * 
     * @return [type] chama á function enviar_correo e envialle os datos
     * @see password_changed()
     * @see enviar_correo()
     */
    function enviar_correos_contrasena($correo, $asunto) {

        $cuerpo = $this->password_changed();
        return $this->enviar_correo($correo, $cuerpo, $asunto);
    }

    /**
     * Función que envía un correo
     * Obtén os datos do correo que envía. Recibe a dirección de correo, asunto e corpo. Envía o correo electónico.
     * @param string $correo correo de destino
     * @param string $cuerpo corpo do correo
     * @param string $asunto asunto do correo
     * 
     * @return mixed se todo foi ben devolve TRUE. Se sucedeu algún problema devolve un mensaxe de error.
     * @see leer_configCorreo()
     */
    function enviar_correo($correo, $cuerpo, $asunto) {

        $res = $this->leer_configCorreo(__DIR__ . "/../../config/correo.xml", __DIR__ . "/../../config/correo.xsd");
        $mail = new phpmailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;  // cambiar a 1 o 2 para ver errores
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = $res[0];  //usuario de gmail
        $mail->Password = $res[1]; //contraseña de gmail          
        $mail->SetFrom('webhotelmaravilla@gmail.com', 'Hotel Maravilla');
        $mail->Subject = utf8_decode($asunto);
        $mail->MsgHTML($cuerpo);
        $mail->addAddress($correo);
        if (!$mail->Send()) {
            return $mail->ErrorInfo;
        } else {
            return TRUE;
        }
    }

    /**
     * Corpo email creación de conta
     * Recibe o nome do usuario, introduceo no corpo e devolve o corpo
     * @param mixed $nombre
     * 
     * @return string corpo do email para a creación da conta
     */
    function crear_correo_account($nombre) {
        $texto = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional //EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd' />
<html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
  <head> </head>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <meta name='x-apple-disable-message-reformatting' />
    <!--[if !mso]><!-->
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <!--<![endif]-->
    <style type='text/css'>
      * {
        text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        -moz-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }

      html {
        height: 100%;
        width: 100%;
      }

      body {
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        mso-line-height-rule: exactly;
      }

      div[style*='margin: 16px 0'] {
        margin: 0 !important;
      }

      table,
      td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }

      img {
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
      }

      .ReadMsgBody,
      .ExternalClass {
        width: 100%;
      }

      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass td,
      .ExternalClass div {
        line-height: 100%;
      }
    </style>
    <!--[if gte mso 9]>
      <style type='text/css'>
      li { text-indent: -1em; }
      table td { border-collapse: collapse; }
      </style>
      <![endif]-->
    <title>Welcome to Hotel Maravilla!</title>
    <style>
      @media only screen and (max-width:600px) {
        .column,
        .column-filler {
          box-sizing: border-box;
          float: left;
        }
        .col-sm-12 {
          display: block;
          width: 100%!important;
        }
      }
    </style>
    <!-- content -->
    <!--[if gte mso 9]><xml>
       <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
       </o:OfficeDocumentSettings>
      </xml><![endif]-->
  </head>
  <body class='body' style='background-color: #FFF5EA; margin: 0; width: 100%;'>
    <table class='bodyTable' role='presentation' width='100%' align='left' border='0' cellpadding='0' cellspacing='0' style='width: 100%; background-color: #FFF5EA; margin: 0;' bgcolor='#FFF5EA'>
      <tr>
        <td class='body__content' align='left' width='100%' valign='top' style='color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px;'>
          <div class='container' style='margin: 0 auto; max-width: 600px; width: 100%;'> <!--[if mso | IE]>
            <table class='container__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto;width: 600px' width='600' align='center'>
              <tr>
                <td> <![endif]-->
                  <table class='container__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%'>
                    <tr class='container__row'>
                      <td class='container__cell' width='100%' align='left' valign='top'>
                        <h1 class='header h1' style='margin: 20px 0; line-height: 40px; color: #000000; font-family: Helvetica,Arial,sans-serif;'>Welcome " . $nombre . "! &#x1F44B;&#x1F44B;&#x1F44B;</h1>
                        <div class='hr' style='margin: 0 auto; width: 100%;'> <!--[if mso | IE]>
                          <table class='hr__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto; width: 100%;' width='100%' align='center'>
                            <tr>
                              <td> <![endif]-->
                                <table class='hr__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
                                  <tr class='hr__row'>
                                    <td class='hr__cell' width='100%' align='left' valign='top' style='border-top: 1px solid #9A9A9A;'>&nbsp;</td>
                                  </tr>
                                </table> <!--[if mso | IE]> </td>
                            </tr>
                          </table> <![endif]--> </div>
                        <div class='row'>
                          <table class='row__table' width='100%' align='center' role='presentation' border='0' cellpadding='0' cellspacing='0' style='table-layout: fixed;'>
                            <tr class='row__row'>
                              <td class='column col-sm-12' width='198' style='width: 33%' align='left' valign='top'>
                                <div class='columna' style='margin-right: 2em;'>
                                  <h3 class='header h3' style='margin: 20px 0; line-height: 24px; color: #000000; font-family: Helvetica,Arial,sans-serif;'>Hotel Maravilla</h3>
                                  <p class='text p' style='display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px;'>Visit our website, explore the different sections and live the Hotel Maravilla experience.</p>
                                  <div class='button'>
                                    <table role='presentation' width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>
                                      <tr>
                                        <td>
                                          <table role='presentation' width='auto' align='center' border='0' cellspacing='0' cellpadding='0' class='button__table' style='margin: 0 auto; margin-bottom: 2em;'>
                                            <tr>
                                              <td align='center' class='button__cell' style='background-color: #2097E4; border-radius: 3px; padding: 6px 12px;' bgcolor='#2097E4'><a href='https://hotelmaravilla.teis25.dewordpress.org/index.php' class='button__link' style='background-color: #2097E4; color: #FFFFFF; text-decoration: none; display: inline-block;'><span class='button__text' style='color: #FFFFFF; text-decoration: none;'>Visit our Hotel &#x1F3E8;</span></a></td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </td>
                              <td class='column col-sm-12' width='198' style='width: 33%' align='left' valign='top'>
                                <div class='columna' style='margin-right: 2em;'>
                                  <h3 class='header h3' style='margin: 20px 0; line-height: 24px; color: #000000; font-family: Helvetica,Arial,sans-serif;'>Rooms</h3>
                                  <p class='text p' style='display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px;'>Visit the rooms section and reserve the one that best suits your needs.</p>
                                  <div class='button'>
                                    <table role='presentation' width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>
                                      <tr>
                                        <td>
                                          <table role='presentation' width='auto' align='center' border='0' cellspacing='0' cellpadding='0' class='button__table' style='margin: 0 auto; margin-bottom: 2em;'>
                                            <tr>
                                              <td align='center' class='button__cell' style='background-color: #2097E4; border-radius: 3px; padding: 6px 12px;' bgcolor='#2097E4'><a href='https://hotelmaravilla.teis25.dewordpress.org/html/rooms.php' class='button__link' style='background-color: #2097E4; color: #FFFFFF; text-decoration: none; display: inline-block;'><span class='button__text' style='color: #FFFFFF; text-decoration: none;'>Explore our Rooms &#x1F6AA; </span></a></td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </td>
                              <td class='column col-sm-12' width='198' style='width: 33%' align='left' valign='top'>
                                <div class='columna' style='margin-right: 2em;'>
                                  <h3 class='header h3' style='margin: 20px 0; line-height: 24px; color: #000000; font-family: Helvetica,Arial,sans-serif;'>Gallery</h3>
                                  <p class='text p' style='display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px;'>Explore the image gallery of our hotel, in which you can see the rooms..</p>
                                  <div class='button'>
                                    <table role='presentation' width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>
                                      <tr>
                                        <td>
                                          <table role='presentation' width='auto' align='center' border='0' cellspacing='0' cellpadding='0' class='button__table' style='margin: 0 auto; margin-bottom: 2em;'>
                                            <tr>
                                              <td align='center' class='button__cell' style='background-color: #2097E4; border-radius: 3px; padding: 6px 12px;' bgcolor='#2097E4'><a href='https://hotelmaravilla.teis25.dewordpress.org/html/gallery.php' class='button__link' style='background-color: #2097E4; color: #FFFFFF; text-decoration: none; display: inline-block;'><span class='button__text' style='color: #FFFFFF; text-decoration: none;'>Explore our gallery &#x1F4F7; </span></a></td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>
                        <div class='hr' style='margin: 0 auto; width: 100%;'> <!--[if mso | IE]>
                          <table class='hr__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto; width: 100%;' width='100%' align='center'>
                            <tr>
                              <td> <![endif]-->
                                <table class='hr__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
                                  <tr class='hr__row'>
                                    <td class='hr__cell' width='100%' align='left' valign='top' style='border-top: 1px solid #9A9A9A;'>&nbsp;</td>
                                  </tr>
                                </table> <!--[if mso | IE]> </td>
                            </tr>
                          </table> <![endif]--> </div>
                        <p class='text p' style='display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; line-height: 20px; font-size: 20pt; font-weight: 700; text-align: center;'>Hotel Maravilla &#xa9; 2021</p>
                      </td>
                    </tr>
                  </table> <!--[if mso | IE]> </td>
              </tr>
            </table> <![endif]--> </div>
        </td>
      </tr>
    </table>
    <div style='display:none; white-space:nowrap; font-size:15px; line-height:0;'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
  </body>
</html>";
        return $texto;
    }

    /**
     * Corpo email reserva aceptada
     * Crease o corpo do coreo e devólvese dito corpo
     * 
     * @return string corpo do email para reserva aceptada
     */
    function reserva_aceptada() {
        $texto = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional //EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd' />
<html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
  <head> </head>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <meta name='x-apple-disable-message-reformatting' />
    <!--[if !mso]><!-->
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <!--<![endif]-->
    <style type='text/css'>
      * {
        text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        -moz-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }

      html {
        height: 100%;
        width: 100%;
      }

      body {
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        mso-line-height-rule: exactly;
      }

      div[style*='margin: 16px 0'] {
        margin: 0 !important;
      }

      table,
      td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }

      img {
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
      }

      .ReadMsgBody,
      .ExternalClass {
        width: 100%;
      }

      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass td,
      .ExternalClass div {
        line-height: 100%;
      }
    </style>
    <!--[if gte mso 9]>
      <style type='text/css'>
      li { text-indent: -1em; }
      table td { border-collapse: collapse; }
      </style>
      <![endif]-->
    <title>Welcome to Hotel Maravilla!</title>
    <!-- content -->
    <!--[if gte mso 9]><xml>
       <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
       </o:OfficeDocumentSettings>
      </xml><![endif]-->
  </head>
  <body class='body' style='background-color: #FFF5EA; margin: 0; width: 100%;'>
    <table class='bodyTable' role='presentation' width='100%' align='left' border='0' cellpadding='0' cellspacing='0' style='width: 100%; background-color: #FFF5EA; margin: 0;' bgcolor='#FFF5EA'>
      <tr>
        <td class='body__content' align='left' width='100%' valign='top' style='color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px;'>
          <div class='container' style='margin: 0 auto; max-width: 600px; width: 100%;'> <!--[if mso | IE]>
            <table class='container__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto;width: 600px' width='600' align='center'>
              <tr>
                <td> <![endif]-->
                  <table class='container__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%'>
                    <tr class='container__row'>
                      <td class='container__cell' width='100%' align='left' valign='top'>
                        <h1 class='header h1' style='margin: 20px 0; line-height: 40px; color: #000000; font-family: Helvetica,Arial,sans-serif;'>Accepted reservation! &#x1F973;&#x1F973;&#x1F973;</h1>
                        <div class='hr' style='margin: 0 auto; width: 100%;'> <!--[if mso | IE]>
                          <table class='hr__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto; width: 100%;' width='100%' align='center'>
                            <tr>
                              <td> <![endif]-->
                                <table class='hr__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
                                  <tr class='hr__row'>
                                    <td class='hr__cell' width='100%' align='left' valign='top' style='border-top: 1px solid #9A9A9A;'>&nbsp;</td>
                                  </tr>
                                </table> <!--[if mso | IE]> </td>
                            </tr>
                          </table> <![endif]--> </div>
                        <div class='row'>
                          <table class='row__table' width='100%' align='center' role='presentation' border='0' cellpadding='0' cellspacing='0' style='table-layout: fixed;'>
                            <tr class='row__row'>
                              <td class='column col-sm-12' width='600' style='width: 100%' align='left' valign='top'>
                                <h2 class='header h2' style='margin: 20px 0; line-height: 30px; color: #000000; font-family: Helvetica,Arial,sans-serif;'>Congratulations, your reservation has been accepted.</h2>
                                <div class='columna' style='margin-right: 2em; text-align: center;'>
                                  <p class='text p' style='display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; line-height: 20px; font-size: 16pt; text-align: center;'>You will be able to enjoy the Hotel Maravilla experience.<br/> We hope to see you soon.<br/> For more information visit our website.</p>
                                  <div class='button'>
                                    <table role='presentation' width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>
                                      <tr>
                                        <td>
                                          <table role='presentation' width='auto' align='center' border='0' cellspacing='0' cellpadding='0' class='button__table' style='margin: 0 auto; margin-bottom: 2em;'>
                                            <tr>
                                              <td align='center' class='button__cell' style='background-color: #2097E4; border-radius: 3px; padding: 6px 12px;' bgcolor='#2097E4'><a href='https://hotelmaravilla.teis25.dewordpress.org/index.php' class='button__link' style='background-color: #2097E4; color: #FFFFFF; text-decoration: none; display: inline-block;'><span class='button__text' style='color: #FFFFFF; text-decoration: none;'>Hotel Maravilla Website &#x1F3E8;</span></a></td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>
                        <div class='hr' style='margin: 0 auto; width: 100%; margin-top: 1em;'> <!--[if mso | IE]>
                          <table class='hr__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto; width: 100%;' width='100%' align='center'>
                            <tr>
                              <td> <![endif]-->
                                <table class='hr__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
                                  <tr class='hr__row'>
                                    <td class='hr__cell' width='100%' align='left' valign='top' style='border-top: 1px solid #9A9A9A;'>&nbsp;</td>
                                  </tr>
                                </table> <!--[if mso | IE]> </td>
                            </tr>
                          </table> <![endif]--> </div>
                        <p class='text p' style='display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; line-height: 20px; font-size: 20pt; font-weight: 700; text-align: center;'>Hotel Maravilla &#xa9; 2021</p>
                      </td>
                    </tr>
                  </table> <!--[if mso | IE]> </td>
              </tr>
            </table> <![endif]--> </div>
        </td>
      </tr>
    </table>
    <div style='display:none; white-space:nowrap; font-size:15px; line-height:0;'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
  </body>
</html>";
        return $texto;
    }

    /**
     * Corpo email contrasinal cambiado
     * Crease o corpo do coreo e devólvese dito corpo
     * 
     * @return string corpo do email para cambio de contrasinal
     */
    function password_changed() {
        $texto = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional //EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd' />
<html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
  <head> </head>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <meta name='x-apple-disable-message-reformatting' />
    <!--[if !mso]><!-->
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <!--<![endif]-->
    <style type='text/css'>
      * {
        text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        -moz-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }

      html {
        height: 100%;
        width: 100%;
      }

      body {
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        mso-line-height-rule: exactly;
      }

      div[style*='margin: 16px 0'] {
        margin: 0 !important;
      }

      table,
      td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }

      img {
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
      }

      .ReadMsgBody,
      .ExternalClass {
        width: 100%;
      }

      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass td,
      .ExternalClass div {
        line-height: 100%;
      }
    </style>
    <!--[if gte mso 9]>
      <style type='text/css'>
      li { text-indent: -1em; }
      table td { border-collapse: collapse; }
      </style>
      <![endif]-->
    <title>Welcome to Hotel Maravilla!</title>
    <!-- content -->
    <!--[if gte mso 9]><xml>
       <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
       </o:OfficeDocumentSettings>
      </xml><![endif]-->
  </head>
  <body class='body' style='background-color: #FFF5EA; margin: 0; width: 100%;'>
    <table class='bodyTable' role='presentation' width='100%' align='left' border='0' cellpadding='0' cellspacing='0' style='width: 100%; background-color: #FFF5EA; margin: 0;' bgcolor='#FFF5EA'>
      <tr>
        <td class='body__content' align='left' width='100%' valign='top' style='color: #000000; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 20px;'>
          <div class='container' style='margin: 0 auto; max-width: 600px; width: 100%;'> <!--[if mso | IE]>
            <table class='container__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto;width: 600px' width='600' align='center'>
              <tr>
                <td> <![endif]-->
                  <table class='container__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%'>
                    <tr class='container__row'>
                      <td class='container__cell' width='100%' align='left' valign='top'>
                        <h1 class='header h1' style='margin: 20px 0; line-height: 40px; color: #000000; font-family: Helvetica,Arial,sans-serif;'>&#x1F511;&#x1F511; Password Changed! &#x1F511;&#x1F511;</h1>
                        <div class='hr' style='margin: 0 auto; width: 100%;'> <!--[if mso | IE]>
                          <table class='hr__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto; width: 100%;' width='100%' align='center'>
                            <tr>
                              <td> <![endif]-->
                                <table class='hr__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
                                  <tr class='hr__row'>
                                    <td class='hr__cell' width='100%' align='left' valign='top' style='border-top: 1px solid #9A9A9A;'>&nbsp;</td>
                                  </tr>
                                </table> <!--[if mso | IE]> </td>
                            </tr>
                          </table> <![endif]--> </div>
                        <div class='row'>
                          <table class='row__table' width='100%' align='center' role='presentation' border='0' cellpadding='0' cellspacing='0' style='table-layout: fixed;'>
                            <tr class='row__row'>
                              <td class='column col-sm-12' width='600' style='width: 100%' align='left' valign='top'>
                                <h2 class='header h2' style='margin: 20px 0; line-height: 30px; color: #000000; font-family: Helvetica,Arial,sans-serif;'>Your password has been changed correctly.</h2>
                                <div class='columna' style='margin-right: 2em; text-align: center;'>
                                  <p class='text p' style='display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; line-height: 20px; font-size: 16pt; text-align: center;'>From now on you will be able to log into your account with the new password.<br/> Enjoy the Hotel Maravilla experience.<br/> We hope to see you soon..</p>
                                  <div class='button'>
                                    <table role='presentation' width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>
                                      <tr>
                                        <td>
                                          <table role='presentation' width='auto' align='center' border='0' cellspacing='0' cellpadding='0' class='button__table' style='margin: 0 auto; margin-bottom: 2em;'>
                                            <tr>
                                              <td align='center' class='button__cell' style='background-color: #2097E4; border-radius: 3px; padding: 6px 12px;' bgcolor='#2097E4'><a href='https://hotelmaravilla.teis25.dewordpress.org/index.php' class='button__link' style='background-color: #2097E4; color: #FFFFFF; text-decoration: none; display: inline-block;'><span class='button__text' style='color: #FFFFFF; text-decoration: none;'>Hotel Maravilla Website &#x1F3E8;</span></a></td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>
                        <div class='hr' style='margin: 0 auto; width: 100%; margin-top: 1em;'> <!--[if mso | IE]>
                          <table class='hr__table__ie' role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin-right: auto; margin-left: auto; width: 100%;' width='100%' align='center'>
                            <tr>
                              <td> <![endif]-->
                                <table class='hr__table' role='presentation' border='0' align='center' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
                                  <tr class='hr__row'>
                                    <td class='hr__cell' width='100%' align='left' valign='top' style='border-top: 1px solid #9A9A9A;'>&nbsp;</td>
                                  </tr>
                                </table> <!--[if mso | IE]> </td>
                            </tr>
                          </table> <![endif]--> </div>
                        <p class='text p' style='display: block; margin: 14px 0; color: #000000; font-family: Helvetica,Arial,sans-serif; line-height: 20px; font-size: 20pt; font-weight: 700; text-align: center;'>Hotel Maravilla &#xa9; 2021</p>
                      </td>
                    </tr>
                  </table> <!--[if mso | IE]> </td>
              </tr>
            </table> <![endif]--> </div>
        </td>
      </tr>
    </table>
    <div style='display:none; white-space:nowrap; font-size:15px; line-height:0;'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
  </body>
</html>";
        return $texto;
    }

}
