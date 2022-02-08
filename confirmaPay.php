<?php 
    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    //Cambia el "estado de pago" en la base de datos a "Pagado" y luego actualiza la página
    include("db.php");
    $idPagar = $_POST["idPagar"];
    $idAlumno = $_POST["idAlumno"];
    $now=date('Y-m-d');

    $query = "UPDATE cuotas_alumnos SET estado = '1',fecha_pago = '$now' WHERE id = $idPagar";
    $consulta = $conexion->query($query);

    //Traer datos de la cuota
    $query2 = "SELECT mes FROM cuotas_alumnos WHERE id = $idPagar";
    $consulta2 = $prueba->query($query2);
        while($row = mysqli_fetch_array($consulta2)) {
            $mes = $row["mes"];
        }

    //traer mail con el id del alumno de la base de datos y del administrador
    $query2 = "SELECT s_email,s_fname,s_mname,s_lname,s_rollno FROM wp_wpsp_student WHERE sid = $idAlumno";
    $consulta2 = $prueba->query($query2);
        while($row = mysqli_fetch_array($consulta2)) {
            $s_email = $row["s_email"];
            $s_fname = $row["s_fname"];
            $s_mname = $row["s_mname"];
            $s_lname = $row["s_lname"];
            $s_dni = $row["s_rollno"];
        }


    //Send Email Parent
    $msg = 'Se registró el pago de la cuota del mes de '.$mes.' en el Colegio S.A.E. Simbiosis Digital <br><br>
            Para el alumno: '.$s_fname.' '.$s_mname.' '.$s_lname.', DNI: '.$s_dni;

    wpsp_send_mail($s_email, 'Cuota Abonada', $msg);

    //Muestra alerta de exito y actualiza pagina de cuotas
    echo "
    <script>
    alert('El registro del pago se realizó con éxito');
    history.back();
    reload();
    window.open('http://simbiosisdigitalsae.online/wp-admin/contabilidad/comprobante2.php?id=' + ".$idPagar." + '&sid=' + ".$idAlumno.", 'Comprobante - S.A.E.', 'width=900, height=600');
    </script>";
?>