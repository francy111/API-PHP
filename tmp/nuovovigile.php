<html>
    <head>
        <title>Nuovo vigile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./../styles.css">
    </head>

    <body>
        <?php
            if(isset($_POST['admincode'])){
                include "layout.php";
                echo'
                <svg id="cc" height="100%" width="100%" class="sopraSVG banner">
                    <rect x="5%" y="7.5%" fill="#2b2b2b" width="27%" height="62%" rx="20"/>
                </svg>
                ';
                echo '
                <form method="post" class="sopraSVG" id="form" action="API-PHP/api.php" style="text-align: center;">
                <input class="input" type="text" name = "fname" placeholder = "Enter first name">
                <br>
                <br>
                <br>
                <input class="input" type="text" name = "lname" placeholder = "Enter last name">
                <br>';

                echo '<br>';
                if($_POST['alevel'] == 2){
                    echo '
                    <p style="color:#DEDEDE;font-family:arial;">Is this user an Admin?
                    <input type="checkbox" name = "a" value = "0"></p>
                    ';
                }
                
                echo '<br>
                <input class="input"type="password" name = "password" placeholder = "Create a password">
                <br>
                <br>
                <br>

                <input type="hidden" name="token" value='.$_POST['admincode'].'>
                <input type="hidden" name="function" value="insert-user">
                <input class="input" name="nuovo" type="submit" value="Create user">
                </form>
            ';
            echo '
                <form method="post" class="sopraSVG" id="del" action="API-PHP/api.php" style="text-align: center;">
                    <input type="hidden" name="token" value='.$_POST['admincode'].'>
                    <input type="hidden" name="function" value="getback">
                    <input class="pulsanti" type="submit" value="Torna indietro">
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
