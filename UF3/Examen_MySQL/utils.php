<?php
const FILE_USERS = 'users.json';
const FILE_CONNX = 'connections.json';

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

/**
 * Mostra les connexions d'un usuari amb status success
 *
 * @param string $email
 * @return string
 */
function print_conns(string $email): string{
    $output = "";
    $data = llegeix(FILE_CONNX);

    foreach ($data as $vals){
        if($vals["user"] == $email && str_contains($vals["status"], "success"))
            $output .= "Connexió des de " . $vals["ip"] . " amb data " . $vals["time"]. "<br>\n";
    }

    return $output;
}
