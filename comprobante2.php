<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include "fpdf/fpdf.php";
include("db.php");

$idCuota = htmlspecialchars($_GET["id"]);
$idStudent = htmlspecialchars($_GET["sid"]);

//Datos de la cuota
$query = "SELECT s_fname,s_mname,s_lname,s_address,s_phone,s_email FROM wp_wpsp_student WHERE sid = $idStudent";
$consulta = $prueba->query($query);
while($row = mysqli_fetch_array($consulta)) {
    $name_student = $row["s_fname"]." ".$row["s_mname"]." ".$row["s_lname"];
    $address_student = $row["s_address"];
    $phone_student = $row["s_phone"];
    $email_student = $row["s_email"];
}

//Datos del alumno
$query = "SELECT mes,valor,arancel_uno,motivo_uno,arancel_dos,motivo_dos,arancel_tres,motivo_tres,fecha_pago,fecha_vencimiento FROM cuotas_alumnos WHERE id = $idCuota";
$consulta = $prueba->query($query);
while($row = mysqli_fetch_array($consulta)) {
    $cuota_descripcion = "Cuota ".$row["mes"]." (Vencimiento: ".$row["fecha_vencimiento"].")";
    $valor_cuota = $row["valor"];
    $motivo_uno = $row["motivo_uno"];
    $arancel_uno = $row["arancel_uno"];
    $motivo_dos = $row["motivo_dos"];
    $arancel_dos = $row["arancel_dos"];
    $motivo_tres = $row["motivo_tres"];
    $arancel_tres = $row["arancel_tres"];
    $fecha_pago = "Fecha de Emision: ".$row["fecha_pago"];
}


//Datos del Colegio
$name_school = "S.A.E. Simbiosis";
$address_school = "Lisboa 2746, Berisso, Buenos Aires";
$phone_school = "+54 9 2604 55-3707";
$email_school = "info@simbiosisdigital.com.ar";


$pdf = new FPDF($orientation='P',$unit='mm');
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);    
$textypos = 5;
$pdf->setY(80);
$pdf->setX(10);
// Agregamos los datos de la empresa
$pdf->Image('imagenes/logo.png',10,8,33);
$pdf->setY(12);
$pdf->setX(70);
$pdf->Cell(5,$textypos,"S.A.E. SIMBIOSIS");
$pdf->SetFont('Arial','B',10);    
$pdf->setY(30);$pdf->setX(10);
$pdf->Cell(5,$textypos,"DATOS DEL COLEGIO:");
$pdf->SetFont('Arial','',10);    
$pdf->setY(35);$pdf->setX(10);
$pdf->Cell(5,$textypos,$name_school);
$pdf->setY(40);$pdf->setX(10);
$pdf->Cell(5,$textypos,$address_school);
$pdf->setY(45);$pdf->setX(10);
$pdf->Cell(5,$textypos,$phone_school);
$pdf->setY(50);$pdf->setX(10);
$pdf->Cell(5,$textypos,$email_school);

// Agregamos los datos del cliente
$pdf->SetFont('Arial','B',10);    
$pdf->setY(30);$pdf->setX(75);
$pdf->Cell(5,$textypos,"DATOS DEL CLIENTE:");
$pdf->SetFont('Arial','',10);    
$pdf->setY(35);$pdf->setX(75);
$pdf->Cell(5,$textypos,$name_student);
$pdf->setY(40);$pdf->setX(75);
$pdf->Cell(5,$textypos,$address_student);
$pdf->setY(45);$pdf->setX(75);
$pdf->Cell(5,$textypos,$phone_student);
$pdf->setY(50);$pdf->setX(75);
$pdf->Cell(5,$textypos,$email_student);

// Agregamos los datos del cliente
$pdf->SetFont('Arial','B',10);    
$pdf->setY(30);$pdf->setX(135);
$pdf->Cell(5,$textypos,"COMPROBANTE X");
$pdf->SetFont('Arial','',10);    
$pdf->setY(35);$pdf->setX(135);
$pdf->Cell(5,$textypos,$fecha_pago);
$pdf->setY(40);$pdf->setX(135);
$pdf->Cell(5,$textypos,"");
$pdf->setY(45);$pdf->setX(135);
$pdf->Cell(5,$textypos,"");
$pdf->setY(50);$pdf->setX(135);
$pdf->Cell(5,$textypos,"");

/// Apartir de aqui empezamos con la tabla de productos
$pdf->setY(60);$pdf->setX(135);
    $pdf->Ln();
/////////////////////////////
//// Array de Cabecera
$header = array("Descripcion","Cant.","Importe","Total");
//// Arrar de Productos
$products = array(
	array($cuota_descripcion,1,$valor_cuota,$valor_cuota),
	array($motivo_uno,1,$arancel_uno,$arancel_uno),
	array($motivo_dos,1,$arancel_dos,$arancel_dos),
	array($motivo_tres,1,$arancel_tres,$arancel_tres),
	//array(" ","","",""),
	//array(" ","","",""),
);
    // Column widths
    $w = array(100, 25, 30, 30);
    // Header
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
    $pdf->Ln();
    // Data
    $total = 0;
    foreach($products as $row)
    {
        $pdf->Cell($w[0],6,$row[0],1);
        $pdf->Cell($w[1],6,$row[1],1);
        $pdf->Cell($w[2],6,number_format($row[2]),'1',0,'R');
        $pdf->Cell($w[3],6,"$ ".number_format($row[3],2,".",","),'1',0,'R');
        //$pdf->Cell($w[4],6,"$ ".number_format($row[3]*$row[2],2,".",","),'1',0,'R');

        $pdf->Ln();
        $total+=$row[3];//*$row[2];

    }
/////////////////////////////
//// Apartir de aqui esta la tabla con los subtotales y totales
$yposdinamic = 50 + (count($products)*10);

$pdf->setY($yposdinamic);
$pdf->setX(235);
    $pdf->Ln();
/////////////////////////////
$header = array("", "");
$data2 = array(
	//array("Subtotal",$total),
	//array("Descuento", 0),
	//array("Impuesto", 0),
	array("Total", $total),
);
    // Column widths
    $w2 = array(40, 30);
    // Header

    $pdf->Ln();
    // Data
    foreach($data2 as $row)
    {
        $pdf->setX(125);
        $pdf->Cell($w2[0],6,$row[0],1);
        $pdf->Cell($w2[1],6,"$ ".number_format($row[1], 2, ".",","),'1',0,'R');

        $pdf->Ln();
    }
/////////////////////////////

$yposdinamic += (count($data2)*10);
$pdf->SetFont('Arial','B',10);    

$pdf->setY($yposdinamic+10);
$pdf->setX(10);
$pdf->Cell(5,$textypos,"TERMINOS Y CONDICIONES");
$pdf->SetFont('Arial','',10);    

$pdf->setY($yposdinamic+20);
$pdf->setX(10);
$pdf->Cell(5,$textypos,"Comprobante autorizado.");
$pdf->setY($yposdinamic+30);
$pdf->setX(10);
$pdf->Cell(5,$textypos,"Powered by https://simbiosisdigital.com.ar/");


$pdf->output();



?>