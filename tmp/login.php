
<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <?php
            include "layout.php";
            if($_POST['redirect'] == "true"){
                echo '
                    <button class="sopraSVG pulsanti getapp">
                        Sei un vigile? Scarica l'."'".'APP
                    </button>
                    <svg id="cc" height="100%" width="100%" class="sopraSVG banner">
                        <rect x="5%" y="7.5%" fill="#2b2b2b" width="27%" height="60%" rx="20"/>
                    </svg>

                    <form method="POST" action="API-PHP/api.php" class="sopraSVG" id="form">
                        <input class="input" type="text" placeholder="Enter Matriculation Code" name="mcode">
                        <br>
                        <br>
                        <br>
                        <input class="input" type="password" placeholder="Enter Password" name="password">
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <input type="hidden" name="function" value="authentication">
                        <input class="input" name="login" type="submit" value="Log In">
                        <br>
                        <br>
                    </form>
                    <p id="sottologin" class="sopraSVG err">'.str_replace('_', ' ', $_POST['err']).'</p>
                ';
            }else{
                $HTTPHeader = 'HTTP/1.1 500 UNKNOWN';
                header($HTTPHeader);
                header('Content-type: text/html');
                echo 
                
                "Richiesta errata! :(";
            }
        ?>

    </body>

</html>
