<?php

function sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
    $HTTPHeader = 'HTTP/1.1 '.$status.' '.'UNKNOWN';

    // Scrivono nella parte 'riservata' della risposta http
    header($HTTPHeader);
    header('Content-type: '.$content_type);

    // Scrivo nel body della risposta
    echo $body;

}


class serviceAPI
{
    // Tutti i metodi/funzioni dell'API

    private $db_connection;


    function __construct()
    {
        
        $dsn = 'mysql:host=multe.ddns.net;port=8081;dbname=Multe';
        $user = 'francy';
        $password = 'francy1!';
        
        // DB connection
        try
        {
            $this->db_connection = new PDO($dsn,$user,$password);
        }
        catch(PDOException $e)
        {
            sendResponse(500,$e->getMessage(),"application/json");
        }
    }

    function __destruct()
    {
        // DB release

        $db_connection = null;
    }

    // API

    function authentication()
    {
       
         
        try
        {
            $code = $_POST['mcode'];
            $psw = $_POST['password']; // va poi fatto l'hash

            $miaQuery = "SELECT admin FROM vigile WHERE user = '$code' AND pssw = '$psw';";
            $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);

            /*
            $risultati = []; // Array PHP per contenere i risultati

            foreach($statement as $row)
            {
                $risultati[] = $row;  // $risultati.push($row)
            }
            */

            $risultati = $statement->fetchAll();
            $risultati = json_encode($risultati);
            $risultati = substr($risultati, 1, strlen($risultati)-2);

            if(strlen($risultati)==0)
                header("Location: login.php");
            else{
                $risultati = substr($risultati, 10, 1);
                if($risultati == 1){
                    header("Location: admplc.php");
                }else{
                    header("Location: token.php");
                }
            }

            
        }
        catch(Exception $e)
        {
            sendResponse(500,$e->getMessage());
        }
        
        
        

       
        
    }


}


// Istanzia l'oggetto API 

$api = new serviceAPI();


$function = $_POST['function'];

switch($function)
{
    case "authentication":
        $api->authentication();
        break;
    default:
        sendResponse(500,"Richiesta errata! :(");
        break;
}