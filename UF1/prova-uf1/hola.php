<?php
session_start();
//mira el temps de la session
if (!isset($_SESSION["temps"])) {
    header("Location: index.php");
}else {
    if (time()-$_SESSION["temps"]>60) {
        header("Location: index.php");
    }
}


//mostra cada log correcte de l'usuari
function mostrarLog(){
$array = llegeix("connexions.json");
for ($i=0; $i< count($array); $i++){
    if (($array[$i][1] == $_SESSION['gmail']) && (($array[$i][3] == "correcte") || ($array[$i][3] == "nou-usuari"))){
        $ip = $array[$i][0];
        $mail = $array[$i][1];
        $data = $array[$i][2];
        $status = $array[$i][3];
        echo ('<br>IP:'.$ip.' Mail:'.$mail.' Data:'.$data.' Status:'.$status);
    }
}
}








function llegeix(string $file) : array
{
    $var = [];
    if ( file_exists($file) ) {
        $var = json_decode(file_get_contents($file), true);
    }
    return $var;
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <title>Benvingut</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>
<body>
<div class="container noheight" id="container">
    <div class="welcome-container">
        <h1>Benvingut!</h1>
        <div>Hola <?php echo $_SESSION['nom']?>, les teves darreres connexions són: <?php mostrarLog(); ?></div>
        <form action="process.php" method="post">
        <input type="hidden" name="method" value="logout"/>
            <button>Tanca la sessió</button>
        </form>
    </div>
</div>
</body>
</html>