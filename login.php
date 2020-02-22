<?php
//incluyo la cabecera de la aplicacion
require_once('partials/header.php');

session_start();
$_SESSION['logged'] = false;

$msg="";
$email="";

//Si se envia por metodo post el email y password
if(isset($_POST['email']) && isset($_POST['password'])) {
  //si esta vacio
  if ($_POST['email']==""){
    $msg.="Debe ingresar un email <br>";
  }else if ($_POST['password']=="") {
    $msg.="Debe ingresar la clave <br>";
  }else {//si los datos enviados no estan vacios
    //elimino las etiquetas o tags del html
    $email = strip_tags($_POST['email']);
    //elimino etiquetas y cifro la contraseña con el algoritmo sha1
    $password= sha1(strip_tags($_POST['password']));

    //Me conecto a la base de datos
    $basedatos = "proyectoiot";
    $usuario = "root";
    $contra = "";

    $conn = mysqli_connect("localhost", $usuario, $contra, $basedatos);

    //si no me pude conectar a la base de datos
    if ($conn==false){
      echo "Hubo un problema al conectarse a María DB";
      die();//termino
    }

    //si me conecto a la BD, hago la consulta de traer todos los usarios donde el email y password coincidan
    //con los enviados por metodo post
    
    $result = $conn->query("SELECT * FROM `users` WHERE `emailUsuario` 
                            = '".$email."' AND  `passwordUsuario` = '".$password."' ");
    $users = $result->fetch_all(MYSQLI_ASSOC);//para obtener la fila en forma de array

    //cuento cuantos elementos tiene
    $count = count($users);

    if ($count == 1){
      //cargo datos del usuario en variables de sesión
      $_SESSION['user_id'] = $users[0]['idUsuario'];
      $_SESSION['users_email'] = $users[0]['emailUsuario'];

      $msg .= "..Ingresando..";
      $_SESSION['logged'] = true;
      echo '<meta http-equiv="refresh" content="2; url=dashboard.php">';
    }else{
      $msg .= "Acceso denegado!!!";
      $_SESSION['logged'] = false;
    }
  }
}

?>

<div class="app" id="app">
  <div class="center-block w-xxl w-auto-xs p-y-md">
    <div class="navbar"></div>

    <div class="p-a-md box-color r box-shadow-z1 text-color m-a">
      <div class="m-b text-sm text-center font-weight-bold">
        Ingresar a la plataforma
      </div>

      <form target="" method="post" name="form">
        <div class="md-form-group float-label">
          <input name="email" type="email" class="md-input" value="<?php echo $email ?>" required>
          <label>Correo</label>
        </div>
        <div  class="md-form-group float-label">
          <input name="password" type="password" class="md-input" required>
          <label>Contraseña</label>
        </div>

        <button type="submit" id="butonn" class="btn primary btn-block p-x-md">
          Ingresar
        </button>
      </form>

      <div style="color:red" class="mt-2">
        <?php echo $msg ?>
      </div>

    </div>

    <div class="p-v-lg text-center">

      <div>
        <a href="register.php" class="text-primary _600">
          Crear una cuenta
        </a>
      </div>

    </div>
  </div>

</div>

<?php
  require_once('partials/footer.php');
?>