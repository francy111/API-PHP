<html>
    <head>
        <title>Elenco multe</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./../styles.css">
    </head>

    <body>
        <?php
            if(isset($_POST['token'])){
                include("layout.php");
                echo'
                <svg id="cc" height="100%" width="100%" class="sopraSVG banner">
                    <rect x="5%" y="7.5%" fill="#2b2b2b" width="84%" height="62%" rx="20"/>
                </svg>
                ';

                echo '<table style="border: 2px solid black;" class="sopraSVG table2">
                <tr>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">Vigile</th>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">ID multa</th>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">Targa veicolo</th>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">Luogo effrazioni</th>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">Importo multa</th>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">Data e ora</th>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">Latitudine</th>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">Longitudine</th>
                    <th class="input4" style="font-family:arial; border: 1px solid black;">Lista effrazioni</th>
                </tr> ';
                $multe = $_POST['multe'];
                $token = $_POST['token'];
                $multe = str_replace('_', ' ', $multe);
                $multe = json_decode($multe, true);

                $min = $_POST['min'];
                $max = $_POST['max'];
                $nv = sizeof($multe) >= $max ? $max : sizeof($multe);

                for($i = $min; $i < $nv; $i++){
                    $effrazioni = $multe[$i]['effrazioni'];
                    $effrazioni = explode(',', $effrazioni);

                    echo '<tr>';
                    echo '<td class="pad2">'.$multe[$i]['nome'].' '.$multe[$i]['cognome'].'</td>';
                    echo '<td class="pad2">'.$multe[$i]['id'].'</td>';
                    echo '<td class="pad2">'.$multe[$i]['targa'].'</td>';
                    echo '<td class="pad2">'.$multe[$i]['luogo'].'</td>';
                    echo '<td class="pad2">'.$multe[$i]['importo'].' €</td>';
                    echo '<td class="pad2">'.$multe[$i]['dataora'].'</td>';
                    echo '<td class="pad2">'.$multe[$i]['latitudine'].'</td>';
                    echo '<td class="pad2">'.$multe[$i]['longitudine'].'</td>';
                    echo '<td class="pad2">';
                    for($j = 0; $j < sizeof($effrazioni); $j ++){
                        echo $effrazioni[$j];
                        echo '<br>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '
                    <form method="POST" id="sottomulte" class="sopraSVG" action="API-PHP/api.php">
                        <input class="input3" readonly type="text" value="Vigili totali: '.sizeof($multe).'">
                        <input class="input3" readonly type="text" value="Intervallo attuale: '.($min+1).'-'.$nv.'">
                    </form>
                ';
                if($min > 0){
                    echo '
                        <form method="POST" id="frecciasinistra" class="sopraSVG" action="API-PHP/api.php">
                            <input type="hidden" name="min" value="'.$min.'">
                            <input type="hidden" name="max" value="'.$max.'">
                            <input type="hidden" name="function" value="prima">
                            <input type="hidden" name="token" value="'.$token.'">
                            <input class="input3" name="canbiO" type="submit" value="<">
                        </form>
                    ';
                }
                if($nv < sizeof($multe)){
                    echo '
                        <form method="POST" id="frecciadestra" class="sopraSVG" action="API-PHP/api.php">
                            <input type="hidden" name="min" value="'.$min.'">
                            <input type="hidden" name="max" value="'.$max.'">
                            <input type="hidden" name="function" value="dopo">
                            <input type="hidden" name="token" value="'.$token.'">
                            <input class="input3" name="canbiO" type="submit" value=">">
                        </form>
                    ';
                }

                echo '
                    <form method="post" class="sopraSVG" id="del" action="API-PHP/api.php" style="text-align: center;">
                        <input type="hidden" name="token" value='.$_POST['token'].'>
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