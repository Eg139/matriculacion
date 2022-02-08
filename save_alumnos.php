<?php
include("db.php");

$meses = array(
    "Matricula",
    "Materiales",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre"
);

if (isset($_POST['save_alumno'])) {
    $dni = $_POST['alumnos'];
    $anio = $_POST['anioVencimiento'];
    //$mes = $_POST['cuota'];
    $descuento = $_POST['descuento'];
    $valor = $_POST['valor'];
    $diaVencimiento = $_POST['diaVencimiento'];
    
    //Comprueba si el valor es mayor a 0 (el valor de la cuota no puede ser 0 o negativo)
    if(isset($_POST['valor']) && $valor>0){

        $resultados=mysqli_query($prueba, "SELECT * from wp_wpsp_student");
        while ($datos = mysqli_fetch_array($resultados)) {
            if ($datos['s_rollno'] == $dni) {
                $apellido = $datos['s_lname'];
                $nombre= $datos['s_fname'];//unificar
                $id_alumno = $datos['sid'];
                //$mail = $datos['s_email'];
            }
        }
        $valor = $valor - $valor * $descuento/100;

        //$valor_cuotas = $valor / 12;
        $valor_cuotas = $valor;


        for ($i=0; $i < count($meses); $i++) { 
            $mes = $i+1;
            $fechaVencimiento = $anio."-".$mes."-".$diaVencimiento;
            $query = "INSERT INTO cuotas_alumnos(id_alumno,mes,valor,estado,descuento,fecha_vencimiento) VALUES ('$id_alumno','$meses[$i]',$valor_cuotas,0,'$descuento','$fechaVencimiento')";
            $result = mysqli_query($prueba,$query);

            $query = "UPDATE wp_wpsp_student SET reg_cuota = '1' WHERE sid = $id_alumno";
            $consulta = $conexion->query($query);
        }

        if (!$result) {
            die("Query Failed");
        }else {
            echo "<script>alert('El alumno se registró con éxito');location.href='registros.php';</script>";
        }

    }else{
        echo "<script>alert('El alumno NO FUE REGISTRADO. El valor de la cuota debe ser mayor a 0');location.href='registros.php';</script>";
    }
}

?>