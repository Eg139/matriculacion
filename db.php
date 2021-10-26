<?php


$usuario = "c2350785_wordpre";
$contraseña = "ekvrlgqetc7Vloj";
$host = "localhost";
$bd = "c2350785_wordpre";

try {
    $prueba = mysqli_connect($host,$usuario,$contraseña,$bd);
    $conexion = new PDO('mysql:host='.$host.';dbname='.$bd,$usuario,$contraseña);
} catch (PDOException $e) {
    print "¡Error!: ".$e->getMessage()."<br/>";
}

session_start();

?>
