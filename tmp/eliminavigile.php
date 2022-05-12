<html>
    <head>
        <title>Rimuovi vigile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./../styles.css">
    </head>

    <body>
        <?php
            if(isset($_POST['admincode'])){
                include("layout.php");
                echo'
                <svg id="cc" height="100%" width="100%" class="sopraSVG banner">
                    <rect x="5%" y="7.5%" fill="#2b2b2b" width="90%" height="62%" rx="20"/>
                </svg>
                ';

                $min = $_POST['min'];
                $max = $_POST['max'];
                $token = $_POST['admincode'];
                $vigili = $_POST['vigili'];
                $vigili = json_decode($vigili, true);

                $nv = sizeof($vigili) >= $max ? $max : sizeof($vigili);
                echo '<br>';

                echo '<table style="border: 2px solid black;" class="sopraSVG table" id="form">';
                    echo '<tr>';
                        echo '<th class="input pad" style="font-family:arial; border: 1px solid black;">Vigili</th>';
                    echo '</tr>';

                    for($i = $min; $i < $nv; $i ++){
                        echo '<tr>';
                            echo '<td>';
                            echo '
                                <form name="eheh" method="post" action="API-PHP/api.php">
                                    <input readonly class="input2 pad" type="text" name="vigile" value='.$vigili[$i]['user'].'>
                                    <input class="input2 pad" type="text" name="nome" value=';
                                    echo $vigili[$i]['nome'].' readonly>
                                    <input class="input2 pad" type="text" name="cognome" value=';
                                    echo $vigili[$i]['cognome'].' readonly>
                                    <input class="input2 pad" type="text" value=';
                                    if ($vigili[$i]['admin'] == 0) echo "Vigile";
                                    else if($vigili[$i]['admin'] == 1) echo "Admin";
                                    echo ' readonly>
                                    <input type="hidden" name="token" value='.$token.'>
                                    <input type="hidden" name="function" value="remore-user">
                                    <input class="input2 pad" name="elimina" type="submit" value="X">
                                </form>
                            ';
                            echo '</td>';
                        echo '</tr>';
                    }
                echo '</table>';
                echo '
                    <form method="post" class="sopraSVG" id="del" action="API-PHP/api.php" style="text-align: center;">
                        <input type="hidden" name="token" value='.$_POST['admincode'].'>
                        <input type="hidden" name="function" value="getback">
                        <input class="pulsanti" type="submit" value="Torna indietro">
                    </form>
                ';
                echo '
                    <form method="POST" id="sottovigili" class="sopraSVG" action="API-PHP/api.php">
                        <input class="input3" readonly type="text" value="Vigili totali: '.sizeof($vigili).'">
                        <input class="input3" readonly type="text" value="Intervallo attuale: '.($min+1).'-'.$nv.'">
                    </form>
                ';
                if($min > 0){
                    echo '
                        <form method="POST" id="frecciasinistra" class="sopraSVG" action="API-PHP/api.php">
                            <input type="hidden" name="min" value="'.$min.'">
                            <input type="hidden" name="max" value="'.$max.'">
                            <input type="hidden" name="function" value="diminuisci-intervallo">
                            <input type="hidden" name="token" value="'.$token.'">
                            <input class="input3" name="canbiO" type="submit" value="<">
                        </form>
                    ';
                }
                if($nv < sizeof($vigili)){
                    echo '
                        <form method="POST" id="frecciadestra" class="sopraSVG" action="API-PHP/api.php">
                            <input type="hidden" name="min" value="'.$min.'">
                            <input type="hidden" name="max" value="'.$max.'">
                            <input type="hidden" name="function" value="aumenta-intervallo">
                            <input type="hidden" name="token" value="'.$token.'">
                            <input class="input3" name="canbiO" type="submit" value=">">
                        </form>
                    ';
                }
            }else{
                $HTTPHeader = 'HTTP/1.1 500 UNKNOWN';
                header($HTTPHeader);
                header('Content-type: text/html');
                echo "Richiesta errata! :(";
            }
        ?>
    </body>

</html>
