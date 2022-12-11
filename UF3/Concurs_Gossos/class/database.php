<?php
//Fitxer per fer les operacions a la base de dades
function connect(){
    $servername = "localhost";
    $username = "perroadmin";
    $password = "sabuesodelava";
    $db = "concurs_gossos";
    try {
        return( new PDO ("mysql:host=$servername;dbname=$db","$username","$password"));
    
    }catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
    }
}



function mostrartaules(){
    $sql = "SHOW TABLES";
    $conn = connect();
    $statement = $conn->prepare($sql);
    $statement->execute();
    $tables = $statement->fetchAll(PDO::FETCH_NUM);
    foreach($tables as $table){
        echo $table[0], '<br>';
    }
}

// function getValues($columnes, $taula){
//     $sql = "SELECT ".$columnes." FROM ".$taula;
//     echo($sql);
//     $con = mysqli_connect("localhost","perroadmin","sabuesodelava","concurs_gossos");
//     $result = mysqli_query($con, $sql);
// }



function setValues($taula, $columnes, $valors){
    $sql = "INSERT INTO ".$taula."(".$columnes.") VALUES(".$valors.")";
    $con = mysqli_connect("localhost","perroadmin","sabuesodelava","concurs_gossos");
    $result = mysqli_query($con, $sql);
}


function searchuser($valor){
    $sql = "SELECT nom, password FROM usuaris WHERE nom = '".$valor."'";
    $con = mysqli_connect("localhost","perroadmin","sabuesodelava","concurs_gossos");
    $result = mysqli_query($con, $sql);
    while($row = $result->fetch_assoc()) {
       $password = $row["password"];
      }
    return $password;  
}


?>