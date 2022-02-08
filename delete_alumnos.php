<?php 
    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

    
    include("db.php");
    $idCancel = $_POST["idCancel"];


    $query = "UPDATE cuotas_alumnos SET estado = '0' WHERE id = $idCancel";
    $consulta = $conexion->query($query);

    //Redirige y actualiza pagina de cuotas
    header('Location: https://simbiosisdigitalsae.online/wp-admin/contabilidad/cuotas.php');
?>