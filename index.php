<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

////////////////////////////////// COMPROBAR LOGIN
$user = wp_get_current_user();
$current_user_role = $user->roles[0];

if ($user->ID != 0 or $user->ID != null) {
    if ($current_user_role == 'administrator') {
      

include("includes/header.php");
include("db.php");

$query=mysqli_query($prueba, "SELECT sid, s_fname,s_lname,s_rollno from wp_wpsp_student");

?>

<div class="container">
    <div class="row mt-4">
    <div class="col-md-6 mx-auto p-4">
        <div class="card">
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
