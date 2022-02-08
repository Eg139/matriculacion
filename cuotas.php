<?php 
session_start();


require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

////////////////////////////////// COMPROBAR LOGIN
$user = wp_get_current_user();
$current_user_role = $user->roles[0];

if ($user->ID != 0 or $user->ID != null) {
    if ($current_user_role == 'administrator') {


include("includes/header.php");
include("db.php");


$query = mysqli_query($prueba, "SELECT sid, s_fname,s_lname,s_rollno,reg_cuota from wp_wpsp_student");


    


////////////////////////////////////////////VARIABLES DE CONSULTA///////////////////////////////////////////////////////////////////////////////////////////
$where = "";
$nombre = $_POST['alumnos'];
$cuota = $_POST['cuota'];
$stat = $_POST['estado'];
$comprobarEstado = "";




////////////////////////////////////////////Boton Filtro///////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['filtro'])) {

    if ($stat == 0) {
        $comprobarEstado = "En Fecha";
    } else if($stat == 1){
        $comprobarEstado = "Abonado";
    }else if($stat == -1){
        $comprobarEstado = "Vencido";
    }

    if (empty($_POST['cuota']) && !empty($_POST['alumnos'])) {

        if ($comprobarEstado == "En Fecha" || $comprobarEstado == "Abonado" || $comprobarEstado == "Vencido") {
            $where = "where s_fname = '" . $nombre . "'  and estado ='". $stat . "'";
        }else{
            $where = "where s_fname = '" . $nombre . "'";
        }
        

    } else if (empty($_POST['alumnos']) && !empty($_POST['cuota'])) {

        if ($comprobarEstado == "En Fecha" || $comprobarEstado == "Abonado" || $comprobarEstado == "Vencido") {
            $where = "where mes ='" . $cuota . "' and estado ='". $stat . "'";
        }else{
            $where = "where mes = '" . $cuota . "'";
        }
        
    } else if(empty($_POST['cuota'])){
        if(empty($_POST['alumnos'])){
            if ($comprobarEstado == "En Fecha" || $comprobarEstado == "Abonado" || $comprobarEstado == "Vencido") {
                $where = "where estado ='" . $stat . "'";
            }else {
                $where = "";
            }
            
        }
        
    }else if(!empty($_POST['alumnos']) && !empty($_POST['cuota']) && $comprobarEstado ==""){
        $where = "where s_fname = '" . $nombre . "'  and mes ='". $cuota . "'";
    }else {
        $where = "where s_fname = '" . $nombre . "'  and mes ='". $cuota . "' and estado ='".$stat."'";
    }

    $_SESSION['nombre'] = $nombre;
$_SESSION['cuota'] = $cuota;
$_SESSION['stat'] = $stat;
$_SESSION['where'] = $where;


}else if (isset($_SESSION['where'])) {
    $where = $_SESSION['where'];
    session_reset();
}

////////////////////////////////////////////Consultas Base de datos///////////////////////////////////////////////////////////////////////////////////////////
/*$alumnos = "SELECT * FROM wp_wpsp_student $where";
$cuots = "SELECT s_rollno, s_fname, s_lname,
Cuot.mes,Cuot.valor,Cuot.estado,Cuot.descuento
FROM wp_wpsp_student
INNER JOIN cuotas_alumnos Cuot ON Cuot.id_alumno = sid";
$resAlumnos = $prueba->query($alumnos);
$resCuotas = $prueba->query($alumnos);*/
?>


    <div class="container-fluid">
    <div class="row">


        <div class="col-md-8 m-3">

            <form class="row" method="POST">

                <div class="form-group col">
                    <select id="alumno" class="form-select" name="alumnos">
                        <option value="">Alumno</option>
                        <?php
                        while ($datos = mysqli_fetch_array($query)) {
                            if($datos['reg_cuota']==1){
                        ?>
                            <option value="<?php echo $datos['s_fname'] ?>"><?php echo $datos['s_rollno'] . " " . $datos['s_fname'] . " " . $datos['s_lname']  ?></option>
                        <?php
                            }
                        }
                        $name = $datos['s_fname'];
                        $lastname = $datos['s_lname'];
                        $rollno = $datos['s_rollno'];
                        ?>

                    </select>
                </div>

                <div class="form-group col">
                    <select class="form-select" name="cuota">
                    <option value="">Mes</option>
                        <option value="Matricula">Matricula</option>
                        <option value="Materiales">Materiales</option>
                        <option value="febrero">Febrero</option>
                        <option value="marzo">Marzo</option>
                        <option value="abril">Abril</option>
                        <option value="mayo">Mayo</option>
                        <option value="junio">Junio</option>
                        <option value="julio">Julio</option>
                        <option value="agosto">Agosto</option>
                        <option value="septiembre">Septiembre</option>
                        <option value="octubre">Octubre</option>
                        <option value="noviembre">Noviembre</option>
                    </select>

                </div>

                <div class="form-group col">
                    <select class="form-select" name="estado" id="estado">
                        <option value="-10">Estado de Pago</option>
                        <option value="-1">Vencido</option>
                        <option value="0">En fecha</option>
                        <option value="1">Abonado</option>
                    </select>

                </div>


                <div class="col">
                    <button type="submit" class="btn btn-success btn-block" name="filtro" id="filtro">Filtrar</button>
                </div>

            </form>

        </div>




        <div class="col-md-12 m-3">
<!--<?php
/*echo $where."<br>";
echo $nombre."<br>";
echo $cuota."<br>";
echo $stat."<br>";
*/
?>-->
            <section>
                <table class="table">
                    <thead>
                        <th scope="col">DNI</th>
                        <th scope="col">Nombre y Apellido</th>
                        <th scope="col">Mes Cuota</th>
                        <th scope="col">Descuento</th>
                        <th scope="col">Vencimiento</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Importe</th>
                        <th scope="col">Acciones</th>
                        <th scope="col">Pagos Multiples</th>
                    </thead>
                    <tbody>
                        <?php
                        require_once("db.php");
                        $estado = "";
                        $acumulador = 0;

                        //Consulta para determinar si la cuota está vencida
                        $query2 = "SELECT estado, fecha_vencimiento,id FROM cuotas_alumnos";
                        $consulta2 = $prueba->query($query2);
                        while($row = mysqli_fetch_array($consulta2)) {
                            //Agregar if (fecha_de_hoy > fecha_vencimiento && estado!=1(Abonado)) 
                            //entonces=> cambiar estado en base de datos {estado=-1(Vencido)}
                            $fecha_actual = strtotime(date('Y-m-d'));
                            $fecha_entrada = strtotime($row["fecha_vencimiento"]);
                            $id=$row["id"];
                            if($fecha_actual > $fecha_entrada && $row["estado"]!=1){
                                $query = "UPDATE cuotas_alumnos SET estado = '-1' WHERE id = $id";
                                $consulta = $conexion->query($query);
                            }
                        }


                        $valor_cuota_total = 0;
                        $color = "";
                        $checkArray = [];

                        $query = "SELECT s_rollno, s_fname, s_lname,
                        Cuot.id,Cuot.mes,Cuot.valor,Cuot.estado,Cuot.descuento,Cuot.id_alumno,Cuot.fecha_vencimiento,
                        Cuot.arancel_uno,Cuot.motivo_uno,Cuot.arancel_dos,Cuot.motivo_dos,Cuot.arancel_tres,Cuot.motivo_tres
                        FROM  cuotas_alumnos Cuot
                        INNER JOIN wp_wpsp_student ON Cuot.id_alumno = sid $where";
                        $consulta = $conexion->query($query);


                        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {

                            $valor_cuota_total = $fila['valor'] + $fila['arancel_uno'] +$fila['arancel_dos'] +$fila['arancel_tres'];

                            if ($fila['estado'] == 0) {
                                $estado = "En Fecha";
                                $color = "#ffc107";
                            } else if($fila['estado'] == 1){
                                $estado = "Abonado";
                                $color = "#28a745";
                            }else {
                                $estado = "Vencido";
                                $color = "#dc3545";
                                $acumulador = $acumulador + $fila['valor'];
                            }
                            

                            echo '
            
                                <tr>
                                    <td>' . $fila['s_rollno'] . '</td>
                                    <td>' . $fila['s_fname'] .' '. $fila['s_lname'] . '</td>
                                    <td>' . $fila['mes'] . '</td>
                                    <td>' . $fila['descuento'] . '%</td>
                                    <td>' . $fila['fecha_vencimiento'] . '</td>
                                    <td style="color:'.$color.'; font-weight:bold;">' . $estado . '</td>
                                    <td>' . $valor_cuota_total . '</td>
                                    <td>
                                    <a class="btn btn-success" id="btnPagar'.$fila['id'].'" onclick="openPay('.$fila['id'].','.$fila['estado'].','.$fila['id_alumno'].')" title="Pagar cuota"><i class="bi bi-cash-coin"></i></a>
                                    <a class="btn btn-info" id="btnEditarPago'.$fila['id'].'" onclick="openEdit('.$fila['id'].','.$fila['estado'].','.$fila['id_alumno'].')" title="Editar cuota"><i class="bi bi-pencil-fill"></i></a>
                                    <a class="btn btn-secondary" onclick="printCuota('.$fila['id'].','.$fila['id_alumno'].','.$fila['estado'].')" title="Imprimir comprobante"><i class="bi bi-printer"></i></a>
                                    <a class="btn btn-danger id="btnCancelarPago'.$fila['id'].'" onclick="openCancel('.$fila['id'].','.$fila['estado'].','.$fila['id_alumno'].')" title="Cancelar pago"><i class="bi bi-trash"></i></a>
                                    </td>
                                    <td><input class="form-check-input" type="checkbox" value="" id="check'.$fila['id'].'"></td>
                                </tr>
            
            
                            ';

                            $valor_cuota_total = 0;
                            $color = "";
                            array_push ($checkArray , 'check'.$fila['id']);
                        }

                        echo '
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total: </td>
                        <td>' . $acumulador . '</td>
                        <button class="btn btn-success mr-2" id="pagoMasivo" style="position: relative;right:-1280px;" title="Pagar cuotas seleccionadas"><i class="bi bi-cash-coin"></i> Registrar Pagos</button>
                        
                        
                        
                        
                        ';
                        ?>
                    </tbody>
                </table>
            </section>
        </div>



    </div>
</div>

<!-- Modal de Confirmar editar -->
<div class="modal fade" id="confirmEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cancelar Pago de cuota</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="edit.php" method="POST">
        <div class="modal-body">

            <h5>¿Seguro que desea modificar el alumno?</h5></br>
            
            <input type="text" name="idEdit" id="idEdit" value="" hidden>
            <input type="number" name="idAlumnoEdit" id="idAlumnoEdit" value="" hidden>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            <input type="submit" class="btn btn-success" id="enviar" value="Confirmar">
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de Confirmar Pago -->
<div class="modal fade" id="confirmPayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Pago de cuota</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="confirmaPay.php" method="POST">
        <div class="modal-body">

            <h5>¿Confirma el pago de la cuota?</h5></br>
            <h6>Se enviará un mail de aviso al alumno y al administrador del colegio</h6></br>
            
            <input type="text" name="idPagar" id="idPagar" value="" hidden>
            <input type="number" name="idAlumno" id="idAlumno" value="" hidden>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            <input type="submit" class="btn btn-success" id="enviar" value="Confirmar">
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de Confirmar Pago Masivo -->
<div class="modal fade" id="confirmMasivePayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Pago de cuota</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">

            <h5>¿Confirma el pago de la cuota?</h5></br>
            <h6>Se enviará un mail de aviso al alumno y al administrador del colegio</h6></br>
            
            <input type="text" name="idPagar" id="idPagar" value="" hidden>
            <input type="number" name="idAlumno" id="idAlumno" value="" hidden>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="pagoMasivoConfirm">Confirmar</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal Cuota Pagada -->
<div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mensajeModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 id="mensaje"></h5></br>
      </div>
      <div class="modal-footer" id="modalFooter">
        
      </div>
    </div>
  </div>
</div>

<!-- Modal de Confirmar cancelacion de cuota -->
<div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cancelar Pago de cuota</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="delete_alumnos.php" method="POST">
        <div class="modal-body">

            <h5>¿Confirma la cancelacion de la cuota?</h5></br>
            
            <input type="text" name="idCancel" id="idCancel" value="" hidden>
            <input type="number" name="idAlumno" id="idAlumno" value="" hidden>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            <input type="submit" class="btn btn-success" id="enviar" value="Confirmar">
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Cuota sin pagar -->
<div class="modal fade" id="cuotaSinPagadaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cuota Sin Abonar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Esta cuota no ha sido abonada con anterioridad</h5></br>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
    $('#alumnos').select2();
});

$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});

function openCancel(idCancel, estado, idAlumno){
    if(estado==0 || estado == -1){
        $("#cuotaSinPagadaModal").modal("show");
    }else{ 
        $("#confirmCancelModal").modal("show");
        $("#idCancel").attr("value",idCancel);
        $("#idAlumno").attr("value",idAlumno);
    }
}

function openEdit(idEdit, estado, idAlumno){

        $("#confirmEditModal").modal("show");
        $("#idEdit").attr("value",idEdit);
        $("#idAlumnoEdit").attr("value",idAlumno);
}

var checkArray = <?php echo json_encode($checkArray);?>;

$("#pagoMasivoConfirm").click(function(){
    
    //document.getElementById('loader').classList.remove("hidden");

    var checkBoxSelected = [];
    for(var i=0;i<checkArray.length;i++){
        if(document.getElementById(checkArray[i]).checked){
            var idCuota =checkArray[i].replace("check", "");
            checkBoxSelected.push(idCuota);
        }
    }

    $.ajax({
    method: "POST",
    url: "pagoMasivo.php",
    data: { arrayChecks: checkBoxSelected },
    success: function(response) {
            console.log(response);
            $("#confirmMasivePayModal").modal("hide");
            location.href='cuotas.php';
            $("#mensajeModal").modal("show");
            $("#mensajeModalLabel").html("Cuota Abonada con exito");
            $("#mensaje").html("Los pagos se registraron con EXITO");
            //document.getElementById('loader').classList.add('hidden');
            /*if(response==="exito"){
                $("#mensajeModal").modal("show");
                $("#mensajeModalLabel").html("Cuota Abonada con exito");
                $("#mensaje").html("Los pagos se registraron con EXITO");
            }else{
                $("#mensajeModal").modal("show");
                $("#mensajeModalLabel").html("Cuotas NO Abonadas");
                $("#mensaje").html("Los pagos NO SE REGISTRARON");
            }*/
        }
    });

});

//Pago masivo
$("#pagoMasivo").click(function(){
    //Mostrar Popup
    $("#confirmMasivePayModal").modal("show");
});



function openPay(idPagar, estado, idAlumno){
    if(estado==1){
        $("#mensajeModal").modal("show");
        $("#mensajeModalLabel").html("Cuota Abonada");
        $("#mensaje").html("Esta cuota ya ha sido abonada.");
    }else{ 
        $("#confirmPayModal").modal("show");
        $("#idPagar").attr("value",idPagar);
        $("#idAlumno").attr("value",idAlumno);
    }
}

function printCuota(idCuota, idAlumno, estado){
    if(estado==1){
        //Abrir archivo comprobante2.php 
        window.open("http://simbiosisdigitalsae.online/wp-admin/contabilidad/comprobante2.php?id=" + idCuota + "&sid=" + idAlumno, "Comprobante - S.A.E.", "width=900, height=600");
    }else{
        $("#mensajeModal").modal("show");
        $("#mensajeModalLabel").html("Cuota No Abonada");
        $("#mensaje").html("Esta cuota no ha sido abonada aún. No existe comprobante para la misma");
    }
}

function editCuota(idCuota, idAlumno, estado){
    if(estado==1){
        $("#mensajeModal").modal("show");
        $("#mensajeModalLabel").html("Cuota Abonada");
        $("#mensaje").html("No se puede editar una cuota Abonada");
    }else{
        //Abrir pagina de editar
        window.open("http://simbiosisdigitalsae.online/wp-admin/contabilidad/edit.php?id="+idCuota);
    }
}

jQuery(document).ready(function($){
    $(document).ready(function() {
        //$('#alumno').select2();
    });
});
</script>


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