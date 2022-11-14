<?php
// const FILE_USERS = 'users.json';
// const FILE_CONNX = 'connections.json';
const FILE_USERS = "select nom, mail, password FROM users";
const FILE_CONNX = "select ip, mail, data, status FROM log";
  function llegeix(string $basededades) : array
  {
    try {
      $hostname = "localhost";
      $dbname = "dwes-paulopez-autpdo";
      $username = "dwes-user";
      $pw = "dwes-pass";
      $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
      echo "Failed to get DB handle: " . $e->getMessage() . "\n";
      exit;
    }
    $query = $pdo->prepare($basededades);
    $query->execute();
    $var = [];
if($basededades == "select nom, mail, password FROM users"){
foreach($query as $fila){
  $array[$fila["mail"]] = [ "email" => $fila["mail"], "password" => $fila["password"], "name" => $fila["nom"]];

  $var[] = $array;
}
}else{
  foreach($query as $fila){
  $array[$fila["mail"]] = [ "ip" => $fila["ip"], "user" => $fila["mail"], "time" => $fila["data"], "status" => $fila["status"]];
    
    $var[] = $array;
  }
}
      unset($pdo); 
      unset($query);
      return $var;
  }




/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */
// function llegeix(string $file) : array
// {
//     $var = [];
//     if ( file_exists($file) ) {
//         $var = json_decode(file_get_contents($file), true);
//     }
//     return $var;
// }

/**
 * Guarda les dades a un fitxer
 *
 * @param array $dades
 * @param string $file
 */
function escriu(array $dades, string $file): void
{
    
  // file_put_contents($file,json_encode($dades, JSON_PRETTY_PRINT));
    try {
      $hostname = "localhost";
      $dbname = "dwes-paulopez-autpdo";
      $username = "dwes-user";
      $pw = "dwes-pass";
      $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
      echo "Failed to get DB handle: " . $e->getMessage() . "\n";
      exit;
    }

    if($file == "select nom, mail, password FROM users"){ 
      if(isset($dades[0]["name"]) && isset($dades[0]["email"]) && isset($dades[0]["password"])){
        $query = $pdo->prepare("INSERT INTO users (nom, mail, password)");
    $sql = "INSERT INTO users (nom, mail, password) VALUES (".$dades[0]["name"].",".$dades[0]["email"].",".$dades[0]["password"].")";
    $query->execute($sql); 


    $query = $pdo->prepare("INSERT INTO users (nom, mail, password) VALUES (?, ?, ?)");
    $sql = "INSERT INTO log (nom, mail, password) VALUES (".$dades[0]["name"].", ".$dades[0]["email"].", ".$dades[0]["password"].", ".$dades[0]["status"].")";
    $query->execute(array($dades[0]["name"], $dades[0]["email"], $dades[0]["password"]));
      }
  }else{
    if(isset($dades[0]["ip"]) && isset($dades[0]["user"]) && isset($dades[0]["time"]) && isset($dades[0]["status"])){
    $query = $pdo->prepare("INSERT INTO log (ip, mail, data, status) VALUES (?, ?, ?, ?)");
    $sql = "INSERT INTO log (ip, mail, data, status) VALUES (".$dades[0]["ip"].", ".$dades[0]["user"].", ".$dades[0]["time"].", ".$dades[0]["status"].")";
    $query->execute(array($dades[0]["ip"], $dades[0]["user"], $dades[0]["time"], $dades[0]["status"]));
    }
  }

}

/**
 * Mostra les connexions d'un usuari amb status success
 *
 * @param string $email
 * @return string
 */
function print_conns(string $email): string{
    $output = "";
    $data = llegeix(FILE_CONNX);
    print_r($data);
    foreach ($data as $vals){
        if($vals["user"] == $email && str_contains($vals["status"], "success"))
            $output .= "Connexió des de " . $vals["ip"] . " amb data " . $vals["time"]. "<br>\n";
    }

    return $output;
}
