<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

////////////////////////////////// COMPROBAR LOGIN
$user = wp_get_current_user();
$current_user_role = $user->roles[0];

if ($user->ID != 0 or $user->ID != null) {
    if ($current_user_role == 'administrator') {


include("db.php");

/* -----------------------------------------Cambio el metodo Get a Post-----------------------------------------------------*/
if (isset($_POST['idEdit'])) {
/* ------------------------------------------------------------------------------------------------------------*/
    $id = $_POST['idEdit'];

    $query = "SELECT s_rollno, s_fname, s_lname,
    Cuot.id,Cuot.mes,Cuot.valor,Cuot.estado,Cuot.descuento,Cuot.id_alumno,Cuot.fecha_vencimiento,
    Cuot.arancel_uno,Cuot.motivo_uno,Cuot.arancel_dos,Cuot.motivo_dos,Cuot.arancel_tres,Cuot.motivo_tres
    FROM  cuotas_alumnos Cuot
    INNER JOIN wp_wpsp_student ON Cuot.id_alumno = sid WHERE id = $id";

    $result = mysqli_query($prueba,$query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $al_dni = $row['s_rollno'];
        $al_name = $row['s_fname'];
        $al_lname = $row['s_lname'];

/* ------------------------------------------------------------------------------------------------------------*/
        $id_cuot = $row['id'];
/* ------------------------------------------------------------------------------------------------------------*/

        $mes = $row['mes'];
        $valor = $row['valor'];
        $arancelUno = $row['arancel_uno'];
        $motivo1 = $row['motivo_uno'];
        $arancelDos = $row['arancel_dos'];
        $motivo2 = $row['motivo_dos'];
        $arancelTres = $row['arancel_tres'];
        $motivo3 = $row['motivo_tres'];
    }
}

if (isset($_POST['update'])) {

/* ------------------------------------------------------------------------------------------------------------*/
    $id = $_POST['idEdit'];
/* ------------------------------------------------------------------------------------------------------------*/
    $arancelUno = $_POST['arancel1'];
    $motivo1 = $_POST['motivo1'];
    $arancelDos = $_POST['arancel2'];
    $motivo2 = $_POST['motivo2'];
    $arancelTres = $_POST['arancel3'];
    $motivo3 = $_POST['motivo3'];

        $updateData =  ("UPDATE cuotas_alumnos SET 
        arancel_uno='$arancelUno',
		motivo_uno='$motivo1',
        arancel_dos='$arancelDos',
		motivo_dos='$motivo2',
        arancel_tres='$arancelTres',
		motivo_tres='$motivo3'

        WHERE id='" . $id . "'
    ");

    $result = mysqli_query($prueba,$updateData);

    if (!$result) {
        die("Query Failed");
    }else {
        echo "<script>
        alert('Los cambios se registraron con éxito');
        location.href='cuotas.php';
        </script>";
        //header("Location: https://simbiosisdigitalsae.online/wp-admin/contabilidad/cuotas.php");
    }
}
?>

<?php include("includes/header.php");?>


<div class="container p-4">
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card-body">
            <form action="edit.php" class="row" method="POST">
            
            <!-- ---------------------------------------------------------------------------------------- -->
            <input type="number" name="idEdit" id="idEdit" value="<?php echo $id_cuot?>" hidden>

            <!-- ---------------------------------------------------------------------------------------- -->
                <div class="form-group col-md-4">
                    <label for="alum_name" class="form-label">Dni Alumno</label>
                    <input id="alum_name" type="text" value="<?php echo $al_dni?>" class="form-control" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="alum_apel" class="form-label">Nombre Alumno</label>
                    <input id="alum_apel" type="text" value="<?php echo $al_name?>" class="form-control"  disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="alum_dni" class="form-label">Apellido Alumno</label>
                    <input id="alum_dni" type="text" value="<?php echo $al_lname?>" class="form-control" disabled>
                </div>

                <div class="form-group col-md-6 mt-3">
                    <label for="mes" class="form-label">Cuota</label>
                    <input id="mes" type="text" value="<?php echo $mes?>" class="form-control" placeholder="Update Mes" disabled>
                </div>
                <div class="form-group col-md-6 mt-3">
                    <label for="valor" class="form-label">Monto a abonar</label>
                    <input id="valor" type="text" value="<?php echo $valor?>" class="form-control" disabled>
                </div>


                <div class="form-group col-md-6 mt-3">
                    <label for="motivo1" class="form-label">Concepto (Arancel, descuento, etc.)</label>
                    <input id="motivo1" type="text" value="<?php echo $motivo1?>" class="form-control" placeholder="Concepto" name="motivo1">
                    </div>
                <div class="form-group col-md-6 mt-3">
                    <label for="arancel1" class="form-label">Importe</label>
                    <input id="arancel1" type="number" value="<?php echo $arancelUno?>" class="form-control" placeholder="Agrege un arancel extra" name="arancel1">
                </div>
                <div class="form-group col-md-6 mt-3">
                    <label for="motivo2" class="form-label">Concepto (Arancel, descuento, etc.)</label>
                    <input id="motivo2" type="text" value="<?php echo $motivo2?>" class="form-control" placeholder="Concepto" name="motivo2">
                </div>
                <div class="form-group col-md-6 mt-3">
                    <label for="arancel2" class="form-label">Importe</label>
                    <input id="arancel2" type="number" value="<?php echo $arancelDos?>" class="form-control" placeholder="Agrege un arancel extra" name="arancel2">
                </div>
                <div class="form-group col-md-6 mt-3">
                    <label for="motivo3" class="form-label">Concepto (Arancel, descuento, etc.)</label>
                    <input id="motivo3" type="text" value="<?php echo $motivo3?>" class="form-control" placeholder="Concepto" name="motivo3">
                </div>
                <div class="form-group col-md-6 mt-3">
                    <label for="arancel3" class="form-label">Importe</label>
                    <input id="arancel3" type="number" value="<?php echo $arancelTres?>" class="form-control" placeholder="Agrege un arancel extra" name="arancel3">
                    
                </div>
                <button class="btn btn-success mt-4" name="update">
                    Guardar
                </button>

            </form>
        </div>

    </div>
</div>



<?php 

include("includes/footer.php");


}else{
    echo "El usuario no es administrador";
    }
}else{
    echo "El usuario no está registrado<br></br>";
    echo "Por favor ingresar como Administrador: ";
    include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/wpschoolpress/includes/wpsp-login.php');
}

?>