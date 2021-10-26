<?php
include("includes/header.php");
include("db.php");
$query=mysqli_query($prueba, "SELECT sid, s_fname,s_lname,s_rollno from wp_wpsp_student");

?>

<div class="container">
    <div style="  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 80vh;">
        <div class="card mb-3" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4">
      <img src="includes/logo.png" class="img-fluid rounded-start" alt="...">
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title">Sistema De Matriculas</h5>
        <p class="card-text">Bienvenido al Sistema para gestionar las cuotas de los alumnos del Colegio Nuestra Señora de Lujan</p>
      </div>
    </div>
  </div>
</div>
    </div>


</div>


    

<?php
include("includes/footer.php");
?>
