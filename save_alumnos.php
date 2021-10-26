<?php
include("db.php");

$meses = array(
    "enero",
    "febrero",
    "marzo",
    "abril",
    "mayo",
    "junio",
    "julio",
    "agosto",
    "septiembre",
    "octubre",
    "noviembre",
    "diciembre"

);

if (isset($_POST['save_alumno'])) {
    $dni = $_POST['alumnos'];
    //$mes = $_POST['cuota'];
    $descuento = $_POST['descuento'];
    $valor = $_POST['valor'];

    $resultados=mysqli_query($prueba, "SELECT * from wp_wpsp_student");
    while ($datos = mysqli_fetch_array($resultados)) {
        if ($datos['s_rollno'] == $dni) {
            $apellido = $datos['s_lname'];
            $nombre= $datos['s_fname'];//unificar
            $id_alumno = $datos['sid'];
            //$mail = $datos['s_email'];
        }
    }
    if ($descuento == "familiar") {
        $porcentaje_descuento = 30/100;
    }elseif ($descuento == "beca") {
        $porcentaje_descuento = 80/100;
    }else {
        $porcentaje_descuento = 0;
    }
    $valor = $valor - $valor * $porcentaje_descuento;
    $valor_cuotas = $valor / 12;



    for ($i=0; $i < count($meses); $i++) { 

        $query = "INSERT INTO cuotas_alumnos(id_alumno,mes,valor,estado,descuento) VALUES ('$id_alumno','$meses[$i]',$valor_cuotas,0,'$descuento')";
        $result = mysqli_query($prueba,$query);
    }

    if (!$result) {
        die("Query Failed");
    }else {

    
           header("Location: https://simbiosisdigitalsae.online/wp-admin/contabilidad/registros.php");
    }




}

?>