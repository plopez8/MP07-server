<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
//si es logout elimina la session y va index
    if($_POST['method'] == 'logout'){
        unset($_SESSION);
        session_destroy();
        session_write_close();
        header("Location: index.php");
        die;
    }else{
//si el login s'ha guardat fa menys de 60 seg deixa entra sense omplir
        if(time()-$_SESSION["temps"]<60) {
            saveLog("correcte");
            header("Location: hola.php");
        } else{
//si es singup comprova si esta tot omplert y utilitza funcio crearUsuari
            if($_POST['method'] == 'signup'){
                if(compCompUp() == true){
                    crearUsuari();
                }else{
                    saveLog("creacio-fallida");
                    header("Location: index.php?error=Falta omplir");
                }
            }
//si es signin comproba si tot esta omplert y utilitza funcio comprobarusuari
            if($_POST['method'] == 'signin'){
                if(compCompIn() == true){
                    comprobarUsuari();
                }else{
                    header("Location: index.php?error=Falta omplir");
                }
            }
        }
    }
}
//comproba si signin te tot omplert
function compCompIn(){
    if(($_POST['email'] != "")  && ($_POST['pwd'] != "")){
        return true;
    }else{
        return false;
    }
}

//comproba si signup te tot omplert
function compCompUp(){
    if(($_POST['email'] != "")  && ($_POST['pwd'] != "")  && ($_POST['nom'] != "")){
        return true;
    }else{
        return false;
    }
}

//crea l'usuari si no hi ha cap usuari igual;
function crearUsuari(){
    $nom = $_POST['nom'];
    $gmail = $_POST['email'];
    $contrasenya = $_POST['pwd'];
    $json_data = llegeix("users.json");
    $igual = 0;
    for ($i=0; $i< count($json_data); $i++){
        if ($json_data[$i][1] == $gmail){
            $igual++;
        }
    }
    if ($igual == 0){
        $myArr = array($nom, $gmail, $contrasenya);
        $json_data[] = $myArr;
        $myJSON = json_encode($json_data);
        file_put_contents('users.json', $myJSON);
        $_SESSION['gmail'] = $gmail;
        $_SESSION['nom'] = $_POST['nom'];
        $_SESSION["temps"] = time();
        saveLog("nou-usuari");
        header("Location: hola.php");
    }else{
        saveLog("creacio-fallida");
        header("Location: index.php?error=Email ja registrat");
    }
}
//comproba si mail i password es igual amb algun del fitxer json
function comprobarUsuari(){
    $gmail = $_POST['email'];
    $contrasenya = $_POST['pwd'];
    $json = file_get_contents('users.json');
    $json_data = json_decode($json,true);
    $igual = 0;
    $mailok = 0;
    for ($i=0; $i< count($json_data); $i++){
        if (($json_data[$i][1] == $gmail) && ($json_data[$i][2] == $contrasenya)){
            $igual++;
            $nom = $json_data[$i][0];
            break;
        }
        if($json_data[$i][1] == $gmail){
            saveLog("contrasenya-incorrecte");
            $mailok++;
            header("Location: index.php?error=Contrasenya Incorrecte");
        }
    }
    if ($igual == 1){
        $_SESSION['gmail'] = $gmail;
        $_SESSION['nom'] = $nom;
        $_SESSION["temps"] = time();
        saveLog("correcte");
        header("Location: hola.php");   
    }
    if($mailok == 0) {
        saveLog("usuari-incorrecte");
        header("Location: index.php?error=Email incorrecte");
    }
}
//Guarda l'array que conte els log al fitxer json
function saveLog($status){
    $myArr = arrayLog($status);
    $json_data = llegeix("connexions.json");
    $json_data[] = $myArr;
    escriu($json_data, "connexions.json");
}

//crea array amb les dades del log
function arrayLog($status){
    $fecha = date("Y-m-d")." ".date("H:i:sa");
    $myArr = array(getUserIpAddr(), $_POST['email'], $fecha, $status);
    return $myArr;
}

//obte la ip de l'usuari, es tret de internet per no fallar si s'utilitza proxy 
function getUserIpAddr(){
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //ip from share internet
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //ip pass from proxy
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}


/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */
function llegeix(string $file) : array
{
    $var = [];
    if ( file_exists($file) ) {
        $var = json_decode(file_get_contents($file), true);
    }
    return $var;
}

/**
 * Guarda les dades a un fitxer
 *
 * @param array $dades
 * @param string $file
 */
function escriu(array $dades, string $file): void
{
    file_put_contents($file,json_encode($dades, JSON_PRETTY_PRINT));
}
?>