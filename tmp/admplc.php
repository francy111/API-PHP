<html>
    <head>
        <title>Admin zone</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./../styles.css">
    </head>
    <body>
        <?php
            if(isset($_POST['token'])){
                include "layout.php";
                echo'
                <svg id="cc" height="100%" width="100%" class="sopraSVG banner">
                    <rect x="5%" y="7.5%" fill="#2b2b2b" width="33%" height="60%" rx="20"/>
                </svg>
                <p class="sopraSVG" id="adminarea">Admin area</p>
                ';

                echo '
                    <form method="post" class="sopraSVG" id="new" action="API-PHP/api.php" style="text-align: center;">
                        <input type="hidden" name="token" value='.$_POST['token'].'>
                        <input type="hidden" name="function" value="new-user">
                        <input class="pulsanti" type="submit" value="Inserisci un vigile">
                    </form>
                ';
                echo '
                    <form method="post" class="sopraSVG" id="del" action="API-PHP/api.php" style="text-align: center;">
                        <input type="hidden" name="token" value='.$_POST['token'].'>
                        <input type="hidden" name="function" value="del-user">
                        <input class="pulsanti" type="submit" value="Rimuovi un vigile">
                    </form>
                ';

                echo '
                    <form method="post" class="sopraSVG" id="tok" action="API-PHP/api.php" style="text-align: center;">
                        <input type="hidden" name="token" value='.$_POST['token'].'>
                        <input type="hidden" name="function" value="query">
                        <input class="input" type="submit" value="Controlla multe" name="tkk">
                    </form>
                ';
                echo '
                    <form method="post" class="sopraSVG" id="query" action="API-PHP/api.php" style="text-align: center;">
                        <input type="hidden" name="token" value='.$_POST['token'].'>
                        <input type="hidden" name="function" value="tok">
                        <input class="input" type="submit" value="Controlla token" name="queru">
                    </form>
                ';
            }else{
                $HTTPHeader = 'HTTP/1.1 500 UNKNOWN';
                header($HTTPHeader);
                header('Content-type: text/html');
                echo "Richiesta errata! :(";
            }
        ?>
    </body>
</html>
