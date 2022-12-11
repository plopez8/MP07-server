<?php
require "database.php";
session_start();
//Creem l'usuari
if (isset($_POST["nomcrear"]) && isset($_POST["passwordcrear"])){
    if (!empty($_POST["nomcrear"]) && !empty($_POST["passwordcrear"])){
        crearusuari();
        header("Location: ../admin.php");
    }else{
        header("Location: ../admin.php");
    }
}else{
    header("Location: ../admin.php");
}


function crearusuari(){
    setValues("usuaris", "nom, password", "'".$_POST["nomcrear"]."', '".$_POST["passwordcrear"]."'");
}
?>