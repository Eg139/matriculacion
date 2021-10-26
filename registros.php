<?php
include("includes/header.php");
include("db.php");
$query=mysqli_query($prueba, "SELECT sid, s_fname,s_lname,s_rollno from wp_wpsp_student");

?>




    <div class="row">
        <?php if (isset($_SESSION['message'])) { ?>
            
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            
        <?php
        }
            
        ?>
        
        <div class="card card-body">
            <form action="save_alumnos.php" method="POST">

            <div class="form-group">
                <select name="alumnos">
                    <?php
                        while ($datos = mysqli_fetch_array($query)) {
                            ?>
                            
                            <option value="<?php echo $datos['s_rollno']?>"><?php echo $datos['s_rollno'] ." ".$datos['s_fname']." ".$datos['s_lname']  ?></option>
                    <?php
                        }
                        $name = $datos['s_fname'];
                        $lastname = $datos['s_lname'];
                        $rollno = $datos['s_rollno'];
                    ?>
                    
                </select>
            </div>

                <div class="form-group">
                    <select name="descuento">
                    <option value="familiar">Familiar</option>
                    <option value="beca">Beca</option>
                    <option value="ninguna">ninguna</option>
                    </select>

                </div>
                <div class="form-group">
                    <input type="number" name="valor" class="form-control" placeholder="valor">

                </div>

                <input type="submit" class="btn btn-success btn-block" name="save_alumno" value="Registrar Cuota">
            </form>
        </div>
    </div>



    

<?php
include("includes/footer.php");
?>