<?php
//nos conectamos a la bd
$basedatos = "proyectoiot";
$usuario = "root";
$contra = "";

$conn = mysqli_connect("localhost", $usuario, $contra, $basedatos);

if ($conn==false){//si no se pudo conectar
  echo "Hubo un problema al conectarse a María DB";
  die();
}

//declaramos variables vacias servirán también para repoblar el formulario
$email = "";
$password = "";
$password_r = "";
$msg = "";

//si se envian todos los datos del formulario metodo post
if( isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_r'])) {
  //elimino etiquetas html de cada uno
  $email = strip_tags($_POST['email']);
  $password = strip_tags($_POST['password']);
  $password_r = strip_tags($_POST['password_r']);

  if ($password==$password_r){//si las contraseñas digitadas son iguales
    //busco si ya existe el email registrado en la tabala usuarios
    $result = $conn->query("SELECT * FROM `users` WHERE `emailUsuario` = '".$email."' ");
    $users = $result->fetch_all(MYSQLI_ASSOC);//convierto en array

    //cuento cuantos elementos tiene,
    $count = count($users);
    //si no hay un usuario con mismo mail, procedemos a insertar fila con nuevo usuario
    if ($count == 0){
      $password = sha1($password);//encripto la constraseña con sha1
      $conn->query("INSERT INTO `users` (`emailUsuario`, `passwordUsuario`) 
                    VALUES ('".$email."', '".$password."');");//ingreso los datos enviados a la tabla
      header('Location: login.php');
    }else{//existe mas de una posicion en el array
      $msg.="El mail ingresado ya existe <br>";
    }

  }else{//si son diferentes las claves del formulario
    $msg = "Las claves no coinciden";
  }

}

require_once('partials/header.php');
?>

  <div class="app" id="app">

  <div class="center-block w-xxl w-auto-xs p-y-md">
    <div class="navbar"></div>

    <div class="p-a-md box-color r box-shadow-z1 text-color m-a">
      <div class="m-b text-sm text-center font-weight-bold">
        Ingresar a la plataforma
      </div>

      <form method="post" target="register.php" name="form">
        <div class="md-form-group">
          <input name="email" type="email" class="md-input" value="<?php echo $email; ?>" required>
          <label>Correo</label>
        </div>
        <div class="md-form-group">
          <input name="password" type="password" class="md-input" required>
          <label>Contraseña</label>
        </div>
        <div class="md-form-group">
          <input name="password_r" type="password" class="md-input" required>
          <label>Repita la contraseña</label>
        </div>
        <button type="submit" class="btn primary btn-block p-x-md">Registrarse</button>
      </form>

      <div style="color:red" class="tet-center font-weight-bold">
        <?php echo $msg ?>
      </div>

    </div>

    <div class="p-v-lg text-center">
      <div>
        <a href="login.php" class="text-primary _600">
          Tiene una cuenta?
        </a>
      </div>
    </div>
  </div>

<?php
 require_once('partials/footer.php');
?>