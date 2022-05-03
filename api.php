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


    function __construct(){
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
    function __destruct(){
        // DB release

        $db_connection = null;
    }
    // API
    function checkToken($token){
        $query = "SELECT hash FROM token WHERE token.hash='$token';";
        $statement = $this->db_connection->query($query,PDO::FETCH_ASSOC);
        $risultati = $statement->fetchAll();

        $check = 0;
        if($risultati[0]['hash'] == "")
            $check = 0;
        else{
            $check = 1;
        }
        return $check;
    }
    function getAdminLevel($token){
        if(!$this->checkToken($token)){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $query = "SELECT admin FROM token join vigile on token.vigile = vigile.user where token.hash = '$token'";
        $statement = $this->db_connection->query($query,PDO::FETCH_ASSOC);
        $risultati = $statement->fetchAll();

        return $risultati[0]['admin'];
    }
    function getExpiration($token){
        if(!$this->checkToken($token)){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $query = "SELECT scadenza FROM token WHERE token.hash='$token';";
        $statement = $this->db_connection->query($query,PDO::FETCH_ASSOC);
        $risultati = $statement->fetchAll();

        $dat =  $risultati[0]['scadenza'];

        return $dat;
    }
    function checkExpiration($token){
        $query = "SELECT * FROM token WHERE hash = '$token' AND scadenza >= curdate();";
        $statement = $this->db_connection->query($query,PDO::FETCH_ASSOC);
        $risultati = $statement->fetchAll();

        return $risultati[0]['hash'] == "" ? 0 : 1;

    }
    function enter($err = "") {
        echo '
        <form name="eheh" method="post" action="../login.php">
            <input type="hidden" name="redirect" value="true">
            <input type="hidden" name="err" value='.$err.'>
        </form>
        <script type="text/javascript">
            document.eheh.submit();
        </script>
        ';
    }
    function enter_token($token_current, $token_displayed, $err = "") {
        if(!$this->checkToken($token_current)){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $dat =  $this->getExpiration($token_current);
        $a = $this->getAdminLevel($token_current);
        echo '
        <form name="eheh" method="post" action="../token.php">
            <input type="hidden" name="codecurrent" value='.$token_current.'>
            <input type="hidden" name="codedisplayed" value='.$token_displayed.'>
            <input type="hidden" name="err" value='.$err.'>
            <input type="hidden" name="a" value='.$a.'>
            <input type="hidden" name="data" value='.$dat.'>
        </form>
        <script type="text/javascript">
            document.eheh.submit();
        </script>
        ';
    }
    function enter_admin($token) {
        if(!$this->checkToken($token) || $this->getAdminLevel($token) == 0){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        echo '
        <form name="eheh" method="post" action="../admplc.php">
            <input type="hidden" name="token" value='.$token.'>
        </form>
        <script type="text/javascript">
            document.eheh.submit();
        </script>
        ';
    }
    function enter_create($admintoken, $err = ""){
        if(!$this->checkToken($admintoken) || $this->getAdminLevel($admintoken) == 0){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $adminlevel = $this->getAdminLevel($admintoken);
        echo '
        <form name="eheh" method="post" action="../nuovovigile.php">
            <input type="hidden" name="admincode" value='.$admintoken.'>
            <input type="hidden" name="alevel" value='.$adminlevel.'>
            <input type="hidden" name="err" value='.$err.'>
        </form>
        <script type="text/javascript">
            document.eheh.submit();
        </script>
        ';
    }
    function enter_delete($admintoken){
        if(!$this->checkToken($admintoken) || $this->getAdminLevel($admintoken) == 0){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $bigpplevel = $this->getAdminLevel($admintoken);

        if($bigpplevel == 1){
            $miaQuery = "SELECT user, nome, cognome, admin from vigile where admin = '0';";
        }else if($bigpplevel == 2){
            $miaQuery = "SELECT user, nome, cognome, admin from vigile where admin <> '2';";
        }
        $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);
        $risultati = $statement->fetchAll();
        $risultati = json_encode($risultati);
        echo '
        <form name="eheh" method="post" action="../eliminavigile.php">
            <input type="hidden" name="admincode" value='.$admintoken.'>
            <input type="hidden" name="vigili" value='.$risultati.'>
        </form>
        <script type="text/javascript">
            document.eheh.submit();
        </script>
        ';
    }
    function enter_query($admintoken){
        if(!$this->checkToken($admintoken) || $this->getAdminLevel($admintoken) == 0){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $query = "SELECT vigile.nome, vigile.cognome, multa.*, GROUP_CONCAT(effrazione.nome) AS effrazioni FROM vigile JOIN multa ON vigile.user = multa.vigile JOIN multa_effrazioni ON multa.id = multa_effrazioni.multa JOIN effrazione ON multa_effrazioni.effrazione = effrazione.id GROUP BY multa.id;";
        $statement = $this->db_connection->query($query,PDO::FETCH_ASSOC);
        $risultati = $statement->fetchAll();
        $risultati = json_encode($risultati);
        
        $risultati = str_replace(' ', '_', $risultati);
        echo '
        <form name="eheh" method="post" action="../querymulte.php">
            <input type="hidden" name="token" value='.$admintoken.'>
            <input type="hidden" name="multe" value='.$risultati.'>
        </form>
        <script type="text/javascript">
            document.eheh.submit();
        </script>
        ';
    }
    function new_user($atoken){
        try{
            if(!$this->checkToken($atoken) || $this->getAdminLevel($atoken) == 0){
                sendResponse(500,"Non è stato possibile autenticarti!");
                return;
            }
            $fn = $_POST['fname'];
            $ln = $_POST['lname'];
            $adminv = isset($_POST['a']) ? 1 : 0;
            $mc = '';

            $psw = $_POST['password'];
            if($psw == "") $this->enter_create($atoken);
            else $psw = hash("sha256", $psw);
            if($fn == "" && $ln == ""){
                $this->enter_create($atoken);
            }
            $mc = hash("md5", ($fn.$ln));
            $miaQuery = "INSERT INTO vigile VALUES ('$mc', '$fn', '$ln', '$psw', '$adminv');";
            $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);
            
            $tok = $this->htok($mc);
            $dat = date("Y-m-d");
            $miaQuery = "INSERT INTO token VALUES ('$tok', '$dat', '$mc');";
            $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);

            $this->enter_admin($atoken);
        }catch(Exception $e){
            enter_create($atoken, "Could_not_create_new_user._Try_to_change_values");
        }
    }
    function remove_user($atoken){
        if(!$this->checkToken($atoken) || $this->getAdminLevel($atoken) == 0){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $mc = $_POST['vigile'];
        $miaQuery = "DELETE FROM vigile WHERE user = '$mc';";
        $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);

        $this->enter_admin($atoken);
    }
    function htok($code){
        $tohash = $code." ".date('Y-m-d H:i:s');
        $hashed = hash("sha256", $tohash);
        return $hashed;
    }
    function generateToken($token){
        if(!$this->checkToken($token)){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $miaQuery = "SELECT user FROM token JOIN vigile ON token.vigile = vigile.user where token.hash = '$token';";
        $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);
        $risultati = $statement->fetchAll();

        $u = $risultati[0]['user'];
        $newtoken = $this->htok($u);
        $this->enter_token($token, $newtoken);
    }
    function saveToken($token){
        if(!$this->checkToken($token)){
            sendResponse(500,"Non è stato possibile autenticarti!");
            return;
        }
        $ntoken = $_POST['ntoken'];
        $stoken = $_POST['scadenza'];

        $miaQuery = "UPDATE token SET hash='$ntoken', scadenza='$stoken' WHERE hash='$token'";
        $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);
        
        $this->enter();
    }
    function authentication() {
        try
        {
            $code = $_POST['mcode'];
            $psw = hash("sha256", $_POST['password']);

            $miaQuery = "SELECT hash, nome, cognome, admin FROM token JOIN vigile ON token.vigile = vigile.user where user = '$code' AND pssw ='$psw';";
            $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);

            $risultati = $statement->fetchAll();
            $token = $risultati[0]['hash'];

            if(!$this->checkToken($token))
                $this->enter("Invalid_credentials._Please_try_again");

            $admin = $risultati[0]['admin'];
            if($admin > 0){
                $this->enter_admin($token);
            }else{
                $this->enter_token($token, $token);
            }
        }
        catch(Exception $e)
        {
            sendResponse(500,$e->getMessage());
        } 
    }
    function app_authentication() {
        try
        {
            $code = $_POST['mcode'];
            $psw = hash("sha256", $_POST['password']);

            $miaQuery = "SELECT hash, nome, cognome, admin FROM token JOIN vigile ON token.vigile = vigile.user where user = '$code' AND pssw ='$psw';";
            $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);

            $risultati = $statement->fetchAll();
            $tok = $risultati[0]['hash'];

            $codice = 1;
            if(! ($this->checkExpiration($tok) == 1)){
                $codice = 0;
            }
            $risposta = [
                'nome' => $risultati[0]['nome'],
                'cognome' => $risultati[0]['cognome'],
                'mcode' => $code,
                'adminLevel' => $risultati[0]['admin'],
                'token' => $risultati[0]['hash'],
                'esito' => $codice
            ];
            sendResponse(200, json_encode($risposta), "application/json");
        }
        catch(Exception $e)
        {
            sendResponse(500,$e->getMessage());
        } 
    }
    function app_checkToken($token){
        $controllo = $this->checkExpiration($token);
        sendResponse(200, "{valido:$controllo}", "application/json");
    }
    function app_getEffrazioni($token){
        $controllo = $this->checkExpiration($token);
        if($controllo == 1){
            $miaQuery = "SELECT * FROM effrazione;";
            $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);

            $risultati = $statement->fetchAll();

            sendResponse(200, '{"effrazioni":'.json_encode($risultati).'}', "application/json");
        }else{
            sendResponse(500, "{esito:$controllo}", "application/json");
        }
    }
    function app_nuovaMulta($token){
        try{
            $controllo = $this->checkExpiration($token);
            if($controllo == 1){
                $miaQuery = "SELECT id FROM multa;";
                $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);
                $risultati = $statement->fetchAll();

                if(sizeof($risultati) == 0){
                    $nuovoId = 1;
                }else{
                    $miaQuery = "
                        SELECT MAX(id) as n FROM multa;
                    ";
                    $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);
                    $risultati = $statement->fetchAll();

                    $nuovoId = $risultati[0]['n'] + 1;
                }
                $vigile = $_POST['vigile'];
                $targa = $_POST['targa'];
                $luogo = $_POST['luogo'];
                $importo = $_POST['importo'];
                $dataora = $_POST['data']." ".$_POST['ora'];
                $latitudine = $_POST['latitudine'];
                $longitudine = $_POST['longitudine'];


                $miaQuery = "INSERT INTO multa VALUES($nuovoId, '$vigile', '$targa', '$luogo', $importo, '$dataora', $latitudine , $longitudine, NULL);";
                $statement = $this->db_connection->query($miaQuery);
                $effr = explode(",",$_POST['effrazioni']);

                for($i = 0; i < sizeof($effr); $i++){
                    $miaQuery = "INSERT INTO multa_effrazioni VALUES($nuovoId, $effr[$i]);";
                    $statement = $this->db_connection->query($miaQuery);
                }
                sendResponse(200, '{"esito": "ok"}', "application/json");
            }else{
                sendResponse(500, "Non è stato possibile autenticarti", "application/json");
            }
        }catch(Exception $e){
            sendResponse(500, "Non è stato possibile autenticarti", "application/json");
        }
    }
    function app_visualizzaMulte($token){
        $controllo = $this->checkExpiration($token);
        if($controllo == 1){
            $miaQuery = "SELECT multa.*, GROUP_CONCAT(effrazione.nome) as effrazioni FROM multa JOIN vigile ON multa.vigile = vigile.user JOIN token ON token.vigile = vigile.user JOIN multa_effrazioni ON multa.id = multa_effrazioni.multa JOIN effrazione on multa_effrazioni.effrazione = effrazione.id WHERE hash = '$token' group by multa.id;";
            $statement = $this->db_connection->query($miaQuery,PDO::FETCH_ASSOC);
            $risultati = $statement->fetchAll();

            sendResponse(200, json_encode($risultati), "application/json");
        }
    }
    
}


// Istanzia l'oggetto API 

$api = new serviceAPI();
$token = $_POST['token'];

$function = $_POST['function'];
switch($function)
{
    case "authentication":
        $api->authentication();
        break;
    case "enter":
        $api->enter();
        break;
    case "gen-token":
        $api->generateToken($token);
        break;
    case "save-token":
        $api->saveToken($token);
        break;
    case "new-user":
        $api->enter_create($token);
        break;
    case "del-user":
        $api->enter_delete($token);
        break;  
    case "insert-user":
        $api->new_user($token);
        break;
    case "remore-user":
        $api->remove_user($token);
        break;
    case "query":
        $api->enter_query($token);
        break;
    case "tok":
        $api->enter_token($token, $token);
        break;
    case "app-authentication":
        $api->app_authentication();
        break;
    case "app-checktoken":
        $api->app_checkToken($token);
        break;
    case "app-geteffrazioni":
        $api->app_getEffrazioni($token);
        break;
    case "app-nuovamulta":
        $api->app_nuovaMulta($token);
        break;
    case "app-visualizzamulte":
        $api->app_visualizzaMulte($token);
        break;
    default:
        sendResponse(500,"Richiesta errata! :(");
        break;
}
?>