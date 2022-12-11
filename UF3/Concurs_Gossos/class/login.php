<?php
require "database.php";
session_start();
//Comprovem si s'ha omplert tot
if (isset($_POST["user"]) && isset($_POST["password"])){
    if (!empty($_POST["user"]) && !empty($_POST["password"])){
        checkuser(); 
    }else{
        header("Location: ../login.php");
    }
}else{
    header("Location: ../login.php");
}


//Comprovem si l'usuari i la contrasenya són correctes
function checkuser(){
    if ($_POST["password"] == searchuser($_POST["user"])){
        header("Location: ../admin.php");
        $_SESSION['login'] = "correcte";
    }else{
        header("Location: ../login.php");
        $_SESSION['login'] = "error";
    }
}
?>