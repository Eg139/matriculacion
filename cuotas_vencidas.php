<?php include("includes/header.php");
include("db.php");
$query = mysqli_query($prueba, "SELECT sid, s_fname,s_lname,s_rollno from wp_wpsp_student");

////////////////////////////////////////////VARIABLES DE CONSULTA///////////////////////////////////////////////////////////////////////////////////////////
$where = "";
$nombre = $_POST['alumnos'];
$cuota = $_POST['cuota'];



////////////////////////////////////////////Boton Filtro///////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['filtro'])) {

    if (empty($_POST['cuota'])) {
        $where = "and s_fname = '" . $nombre . "'";
    } elseif (empty($_POST['alumnos'])) {
        $where = "and mes ='" . $cuota . "'";
    } else {
        $where = "and s_fname = '" . $nombre . "' and mes ='" . $cuota . "'";
    }
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
                    <select class="form-select" name="alumnos">
                        <option value="">Alumno</option>
                        <?php
                        while ($datos = mysqli_fetch_array($query)) {
                        ?>

                            <option value="<?php echo $datos['s_fname'] ?>"><?php echo $datos['s_rollno'] . " " . $datos['s_fname'] . " " . $datos['s_lname']  ?></option>
                        <?php
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
                        <option value="enero">Enero</option>
                        <option value="febrero">Febrero</option>
                        <option value="marzo">marzo</option>
                        <option value="abril">abril</option>
                        <option value="mayo">mayo</option>
                        <option value="junio">junio</option>
                        <option value="julio">julio</option>
                        <option value="agosto">agosto</option>
                        <option value="septiembre">septiembre</option>
                        <option value="octubre">octubre</option>
                        <option value="noviembre">noviembre</option>
                        <option value="diciembre">diciembre</option>
                    </select>

                </div>

                <div class="col">
                    <button type="submit" class="btn btn-success btn-block" name="filtro">Filtrar</button>

                </div>

            </form>

        </div>




        <div class="col-md-12 m-3">

            <section>
                <table class="table">
                    <thead>
                        <th scope="col">ID Alumno</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Cuota</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Descuento</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </thead>
                    <tbody>
                        <?php
                        require_once("db.php");
                        $estado = "";

                        $query = "SELECT s_rollno, s_fname, s_lname,
                        Cuot.id,Cuot.mes,Cuot.valor,Cuot.estado,Cuot.descuento
                FROM  cuotas_alumnos Cuot
                INNER JOIN wp_wpsp_student ON Cuot.id_alumno = sid WHERE Cuot.estado = -1 $where";
                        $consulta = $conexion->query($query);


                        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {

                            if ($fila['estado'] == 0) {
                                $estado = "En Fecha";
                            } else if($fila['estado'] == 1){
                                $estado = "Abonado";
                            }else {
                                $estado = "Vencido";
                            }

                            echo '
            
                <tr>
                    <td>' . $fila['s_rollno'] . '</td>
                    <td>' . $fila['s_fname'] . '</td>
                    <td>' . $fila['s_lname'] . '</td>
                    <td>' . $fila['mes'] . '</td>
                    <td>' . $fila['valor'] . '</td>
                    <td>' . $fila['descuento'] . '</td>
                    <td>' . $estado . '</td>
                    <td>
                    <a class="btn btn-success" href="https://simbiosisdigitalsae.online/wp-admin/contabilidad/edit.php?id='.$fila['id'].'"><i class="bi bi-currency-dollar"></i></a>
                    <a class="btn btn-secondary" href="https://simbiosisdigitalsae.online/wp-admin/contabilidad/print.php?id='.$fila['id'].'"><i class="bi bi-receipt"></i></a>
                    </td>
                </tr>
            
            
            ';
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </div>



    </div>
</div>





<?php include("includes/footer.php") ?>