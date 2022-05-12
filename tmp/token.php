<html>
    <head>
        <title>Personal token</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <?php 
            include("layout.php");
            if($_POST['a'] == 0){
                echo '
                    <button class="sopraSVG pulsanti getapp">
                        Sei un vigile? Scarica l'."'".'APP
                    </button>
                ';
            }
            echo'
                <svg id="cc" height="100%" width="100%" class="sopraSVG banner">
                    <rect x="5%" y="7.5%" fill="#2b2b2b" width="33%" height="60%" rx="20"/>
                </svg>
            ';
            if(isset($_POST['codecurrent'])){
                echo '<form method="post" id="salvatok" class="sopraSVG" action="API-PHP/api.php">';
                echo '<input class="input" id="bpp" type="text" name="ntoken" value ='.$_POST["codedisplayed"];
                if($_POST['a'] != 2) echo ' readonly ';
                echo '>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<input class="input" type="date" name="scadenza" id="data" value = ';
                        
                    if($_POST["codedisplayed"] == $_POST["codecurrent"]){
                        echo $_POST["data"];
                    }else{
                        echo date("Y-m-d");
                    }
                        
                echo '>';

                echo '<br>';
                echo '<input type="hidden" name="token" value ='.$_POST["codecurrent"].'>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<input type="hidden" name="function" value="save-token">';
                echo '<input class="input" type="submit" value="Save" name="save">';
                echo '</form>';
                echo '<form method="post" id="gentok" class="sopraSVG" action="API-PHP/api.php">';
                echo '<input type="hidden" name="token" value ='.$_POST["codecurrent"].'>';
                echo '<input type="hidden" name="function" value="gen-token">';
                echo '<input class="input" type="submit" value="Generate" name="gen">';
                echo '</form>';
                echo '<p class="err">'.str_replace('_', ' ', $_POST['err']).'</p>';

                if($_POST['a'] != 0){
                    echo '
                        <form method="post" class="sopraSVG" id="del" action="API-PHP/api.php" style="text-align: center;">
                            <input type="hidden" name="token" value='.$_POST['codecurrent'].'>
                            <input type="hidden" name="function" value="getback">
                            <input class="pulsanti" type="submit" value="Torna indietro">
                        </form>
                    ';
                }
            }
            else{
                $HTTPHeader = 'HTTP/1.1 500 UNKNOWN';
                header($HTTPHeader);
                header('Content-type: text/html');
                echo "Richiesta errata! :(";
            }
        ?>
    </body>

</html>