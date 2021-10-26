<?php
include("db.php");

if (isset($_GET['id'])) {

    $pago = date('Y-m-d');
    $id = $_GET['id'];
    $updateData =  ("UPDATE cuotas_alumnos SET 
        estado='1',
		fecha_pago='$pago'
        WHERE id='" . $id . "'
    ");

    $result = mysqli_query($prueba,$updateData);


    if (!$result) {
        die("Query Failed");
    }

    $_SESSION['message'] = 'Pago Exitoso';
    $_SESSION['message_type'] = 'Success';

    header("Location: https://simbiosisdigitalsae.online/wp-admin/contabilidad/index.php");
}






?>