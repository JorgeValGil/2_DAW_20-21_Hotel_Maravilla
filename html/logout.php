<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <title>Sesión cerrada</title>
        <link rel="icon" type="image/png" href="../images/hotel/hotel_icon.png">
        <link rel="stylesheet" href="../css/logout.css">
    </head>
    <body>
        <div><p>The session was closed successfully, see you soon</p>
            <p class="info">Redirecting to the login page ...</p></div>
    </body>
</html>
<?php
header("Refresh:3; url=loginYregistro/loginAndRegister.php");
?>