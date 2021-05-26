<?php
session_start();
session_unset();
?>


<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Wafa's Ticketing System</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css" />
    </head>
    <body>
        <h1>Wafa's Ticketing System</h1>
        <div id="loginform">
            <h2>You have successfully logged out!</h2>
            <button class="logoutBtn"><a href="index.php">Return to login</a></button>
        </div>
    </body>
</html>