<?php
require_once("db.php");
require('fpdf/fpdf.php');

$where = "";
$fechaRecibida = $_POST['fechaVencida'];
$vencimiento = date("Y-m-d", strtotime($fechaRecibida));

$comprobarEstado = "";

if (isset($_POST['filtro'])) {
    
    if (isset($_POST['fechaVencida'])) {

        if (!empty($_POST['fechaVencida'])) {
            $where = "where Cuot.estado = 1 AND  fecha_pago = '" . $vencimiento . "'";
            
            $valor_cuota_total = 0;

            $query = "SELECT s_rollno, s_fname, s_lname,
            Cuot.id,Cuot.mes,Cuot.valor,Cuot.estado,Cuot.descuento,Cuot.id_alumno,Cuot.fecha_vencimiento, Cuot.fecha_pago,
            Cuot.arancel_uno,Cuot.motivo_uno,Cuot.arancel_dos,Cuot.motivo_dos,Cuot.arancel_tres,Cuot.motivo_tres
            FROM  cuotas_alumnos Cuot
            INNER JOIN wp_wpsp_student ON Cuot.id_alumno = sid $where";
            $consulta = $conexion->query($query);
            $indice = 1;
            $acumulador = 0;
            $pdf = new FPDF();
            $pdf-> AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',10);

            $pdf->Image('includes/logo.png',10,8,20);
            // Arial bold 15
            $pdf->SetFont('Arial','B',12);
            // Movernos a la derecha
            $pdf->Cell(70);
            // Título
            $pdf->Cell(45,10,'Balance del dia '.$fechaRecibida,0,0,'C');
            // Salto de línea
            $pdf->Ln(30);

            $pdf->SetFont('Arial','B',10);
                //Cabecera de la Tabla
                $pdf->Cell(20,8,'#',1,0,'C',0);
                $pdf->Cell(40,8,'Nombre',1,0,'C',0);
                $pdf->Cell(30,8,'Dni',1,0,'C',0);
                $pdf->Cell(35,8,'Fecha de Pago',1,0,'C',0);
                $pdf->Cell(30,8,'Descuento',1,0,'C',0);
                $pdf->Cell(40,8,utf8_decode('Monto'),1,1,'C',0);


            while($row = $consulta->fetch(PDO::FETCH_ASSOC)){
                $valor_cuota_total = $row['valor'] + $row['arancel_uno'] +$row['arancel_dos'] +$row['arancel_tres'];

                $pdf->Cell(20,8,utf8_decode($indice),1,0,'C',0);
                $pdf->Cell(40,8,utf8_decode($row['s_fname']." ".$row['s_lname']),1,0,'C',0);
                $pdf->Cell(30,8,$row['s_rollno'],1,0,'C',0);
                $pdf->Cell(35,8,$row['fecha_pago'],1,0,'C',0);
                $pdf->Cell(30,8,$row['estado'],1,0,'C',0);
                $pdf->Cell(40,8,utf8_decode($valor_cuota_total),1,1,'C',0);                

                $acumulador = $acumulador + $valor_cuota_total;
                $valor_cuota_total =0;
                $indice++;
            }
            $indice = 1;
            $pdf->Cell(195,10,utf8_decode("El Ingreso diario fue de : ".$acumulador."$"),1,0,'C',0);
        } 

        $pdf->Output();
    }






}


?>