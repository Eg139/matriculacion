<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

////////////////////////////////// COMPROBAR LOGIN
$user = wp_get_current_user();
$current_user_role = $user->roles[0];

if ($user->ID != 0 or $user->ID != null) {
    if ($current_user_role == 'administrator') {


include("includes/header.php");
include("db.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

////////////////////////////////// COMPROBAR LOGIN
/*$user = wp_get_current_user();
$current_user_role = $user->roles[0];

if ($user->ID != 0 or $user->ID != null) {

    if ($current_user_role == 'administrator') {

*/
        ////////////////////////////////// COMPROBAR LOGIN

$query = mysqli_query($prueba, "SELECT sid, s_fname,s_lname,s_rollno from wp_wpsp_student");

////////////////////////////////////////////VARIABLES DE CONSULTA///////////////////////////////////////////////////////////////////////////////////////////
$where = "";
$hoy = date("Y-m-d");
$fechaRecibida = $_POST['fechaVencida'];
$vencimiento = date("Y-m-d", strtotime($fechaRecibida));

$comprobarEstado = "";

////////////////////////////////////////////Boton Filtro///////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['fechaVencida'])) {

    if (!empty($_POST['fechaVencida'])) {
        echo $vencimiento;
        $where = "AND  fecha_pago = '" . $vencimiento . "'";
        

    } 
}


////////////////////////////////////////////Consultas Base de datos///////////////////////////////////////////////////////////////////////////////////////////

?>


    <div class="container-fluid">
    <div class="row">


        <div class="col-md-8 m-3">

            <form class="row mt-4" action="print.php" method="POST" target="_blank">

                <div class="form-group col-auto ">
                    <input type="date" name="fechaVencida" value="<?php echo $hoy?>">

                </div>

                <div class="col-auto">
                <input type="submit" class="btn btn-success btn-block " name="filtro" value="Generar pdf">
                </div>

            </form>

        </div>

        <div class="col-md-12 m-3">

            <section>
                <table class="table">
                    <thead>
                        <th scope="col">DNI</th>
                        <th scope="col">Nombre y Apellido</th>
                        <th scope="col">Mes Cuota</th>
                        <th scope="col">Importe</th>
                        <th scope="col">Descuento</th>
                        <th scope="col">Vencimiento</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Fecha de Pago</th>
                    </thead>
                    <tbody>
                        <?php
                        require_once("db.php");
                        $estado = "";
                        $acumulador = 0;
                        $query = "SELECT s_rollno, s_fname, s_lname,
                        Cuot.id,Cuot.mes,Cuot.valor,Cuot.estado,Cuot.descuento,Cuot.id_alumno,Cuot.fecha_vencimiento,
                        Cuot.arancel_uno,Cuot.motivo_uno,Cuot.arancel_dos,Cuot.motivo_dos,Cuot.arancel_tres,Cuot.motivo_tres,fecha_pago
                        FROM  cuotas_alumnos Cuot
                        INNER JOIN wp_wpsp_student ON Cuot.id_alumno = sid WHERE Cuot.estado = 1 ";
                        $consulta = $conexion->query($query);


                        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {

                        $valor_cuota_total = $fila['valor'] + $fila['arancel_uno'] +$fila['arancel_dos'] +$fila['arancel_tres'];

                            if ($fila['estado'] == 0) {
                                $estado = "En Fecha";
                            } else if($fila['estado'] == 1){
                                $acumulador = $acumulador + $valor_cuota_total;
                                $estado = "Abonado";
                            }else {
                                $estado = "Vencido";
                                
                            }
                            

                            echo '
            
                <tr>
                    <td>' . $fila['s_rollno'] . '</td>
                    <td>' . $fila['s_fname'] .' '. $fila['s_lname'] . '</td>
                    <td>' . $fila['mes'] . '</td>
                    <td>' . $valor_cuota_total . '</td>
                    <td>' . $fila['descuento'] . '</td>
                    <td>' . $fila['fecha_vencimiento'] . '</td>
                    <td>' . $estado . '</td>
                    <td>'.$fila['fecha_pago'].'</td>
                    </td>
                </tr>
            
            
            ';
                        }
                        echo '
                        <td>El Total Ingresado es: </td>
                        <td>' . $acumulador . '</td>
                        
                        
                        
                        
                        ';
                        ?>
                    </tbody>
                </table>
            </section>
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