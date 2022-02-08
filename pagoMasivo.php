<?php 
    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    //Cambia el "estado de pago" en la base de datos a "Pagado" y luego actualiza la página
    include("db.php");

    $arrayChecks = $_POST['arrayChecks'];


    $controlEstado = "algo";
    foreach ($arrayChecks as &$item){
        $query2 = "SELECT estado FROM cuotas_alumnos WHERE id = $item";
        $consulta2 = $prueba->query($query2);
        while($row = mysqli_fetch_array($consulta2)) {
            $estado = $row["estado"];
            if($estado==1){
                $controlEstado="encontrado";
            }
        }
    }



    if($controlEstado != "encontrado"){
        $response = "exito";
        $now=date('Y-m-d');

        foreach($arrayChecks as &$item){
            //Actualizo estado (=1), pasa a "Abonado"
            $query = "UPDATE cuotas_alumnos SET estado = '1',fecha_pago = '$now' WHERE id = $item";
            $consulta = $conexion->query($query);
            
            //Traigo el id del alumno desde la cuota
            $query2 = "SELECT id_alumno,mes FROM cuotas_alumnos WHERE id = $item";
            $consulta2 = $prueba->query($query2);
            while($row = mysqli_fetch_array($consulta2)) {
                $idAlumno = $row["id_alumno"];
                $mes = $row["mes"];
            }

            //Traigo los datos del alumno
            $query3 = "SELECT s_email,s_fname,s_mname,s_lname,s_rollno FROM wp_wpsp_student WHERE sid = $idAlumno";
            $consulta3 = $prueba->query($query3);
            while($row = mysqli_fetch_array($consulta3)) {
                $s_email = $row["s_email"];
                $s_fname = $row["s_fname"];
                $s_mname = $row["s_mname"];
                $s_lname = $row["s_lname"];
                $s_dni = $row["s_rollno"];
            }

            //Envio mail
            $msg = 'Se registró el pago de la cuota del mes de '.$mes.' en el Colegio S.A.E. Simbiosis Digital <br><br>
            Para el alumno: '.$s_fname.' '.$s_mname.' '.$s_lname.', DNI: '.$s_dni;

            wpsp_send_mail($s_email, 'Cuota Abonada', $msg);

        }

    }else{
        //Indicar "fracaso" porque alguna de las cuotas está abonada
        $response = "fracaso";
    }

    echo json_encode($response);

?>