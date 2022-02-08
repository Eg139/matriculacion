<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

////////////////////////////////// COMPROBAR LOGIN
$user = wp_get_current_user();
$current_user_role = $user->roles[0];

if ($user->ID != 0 or $user->ID != null) {
    if ($current_user_role == 'administrator') {

include("includes/header.php");
include("db.php");
$query=mysqli_query($prueba, "SELECT sid, s_fname,s_lname,s_rollno,reg_cuota from wp_wpsp_student");

?>




<div class="container p-4">

    <div class="row">
        <?php if (isset($_SESSION['message'])) { ?>
            
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            
        <?php
        }
            
        ?>
        <div class="col-md-6 mx-auto">
        
        <div class="card card-body">
            <form action="save_alumnos.php" method="POST" class="row">

            <div class="col-md-6">
                
                <label for="alumnosSelect" class="form-label mt-3">Alumnos</label>
                    <select name="alumnos" class="form-select" id="alumnosSelect">
                        <?php
                            while ($datos = mysqli_fetch_array($query)) {
                                //Comprueba si el alumno ya esta registrado en el sistema de cuotas
                                // if($datos['reg_cuota']!=1){
                                // ?>
                                <option value="<?php echo $datos['s_rollno']?>"><?php echo $datos['s_rollno'] ." ".$datos['s_fname']." ".$datos['s_lname']  ?></option>
                                <?php   
                                // }
                            }
                            $name = $datos['s_fname'];
                            $lastname = $datos['s_lname'];
                            $rollno = $datos['s_rollno'];
                        ?>
                        
                    </select>

            </div>

            <div class="col-md-6">
                    <label for="descuentoSelect" class="form-label mt-3">Descuento</label>
                    <select name="descuento" class="form-select" id="descuentoSelect">
                    <option selected value="0">Ninguno</option>
                    <option value="25">25%</option>
                    <option value="50">50%</option>
                    <option value="75">75%</option>
                    <option value="100">100%</option>
                    </select>
            </div>

            <div class="col-md-6">
            <div class="form-group">
                    <label for="valor" class="form-label mt-3">Valor de Cuota</label>
                    <input id="valor" type="number" name="valor" class="form-control" placeholder="valor">
                </div>

            </div>

            <div class="col-md-6">

            <label for="diaVencimiento" class="form-label mt-3">Día de Vencimiento</label>
                    <select name="diaVencimiento" class="form-select" id="diaVencimiento">
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>
                </div>            
            
                <div class="col-md-6">

<label for="anioVencimiento" class="form-label mt-3">Año</label>
        <select name="anioVencimiento" class="form-select" id="anioVencimiento">
            <?php
            $html = "";
            for ($i=2000; $i < 2050; $i++) { 
                $html .= "<option value=".$i.">$i</option>";
            }
            echo $html;
            ?>
        </select>
    </div>  





                <input type="submit" class="btn btn-success btn-block mt-4" name="save_alumno" value="Registrar Cuota">
            </form>
            </div>
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