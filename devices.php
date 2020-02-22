<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  header('Location: login.php');
  die();
}

//declaro variables
$alias="";
$serie="";
//guardo en session el id del usuario que hizo el login
$user_id = $_SESSION['user_id'];

//Conexion a base de datos
$basedatos = "proyectoiot";
$usuario = "root";
$contra = "";

$conn = mysqli_connect("localhost", $usuario, $contra, $basedatos);

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

//si se desea eliminar un dispositivo desde el select y es diferente a nulo
if( isset($_POST['id_to_delete']) && $_POST['id_to_delete']!="") {
  $borrar = $_POST['id_to_delete'];//paso a una variable el id de ese elemento
  $conn->query("DELETE FROM `dispositivos` WHERE  `idDispositivo`=$borrar");
  //elimino el id de ese dispositivo de mi tabla
}

//si se envian datos del formulario para agregar dispositivos
if( isset($_POST['serie']) && isset($_POST['alias'])) {
  //elimino posibles etiquetas
  $alias = strip_tags($_POST['alias']);
  $serie = strip_tags($_POST['serie']);

  $info = $conn->query("SELECT * FROM `dispositivos` WHERE `nombreDispositivo`= '".$alias."' 
                        AND `serieDispositivo`= '".$serie."'");
  $dispositivoss = $info->fetch_all(MYSQLI_ASSOC);
  //busco si el dispositivo ya fue agregado, de ser asi no agrego nada a la tabla
  $count = count($dispositivoss);

  if($count == 0){
    //inserto en la tabla lo enviado desde el formulario
    $conn->query("INSERT INTO `dispositivos` (`nombreDispositivo`, `serieDispositivo`, `idUsuarioDispositivo`)
                  VALUES ('".$alias."', '".$serie."', '".$user_id."');");
    //guardo que id de usuario registrado agrego un nuevo dispositivo
  }
}

//traigo todos los dispositivos agregados por ese id de la persona que se registro
//esto significa que solo el usuario registrado podra ver sus propios dispositivos agregados
$result = $conn->query("SELECT * FROM `dispositivos` WHERE `idUsuarioDispositivo` = '".$user_id."'");
//convierto ese resultado a array
$devices = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Panel administrativo IOT</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/material-design-icons/material-design-icons.css" type="text/css" />
  <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
</head>

<body>
  <div class="app" id="app">

    <div id="aside" class="app-aside modal nav-dropdown">
      <!-- fluid app aside -->
      <div class="left navside dark dk" data-layout="column">
        <div class="navbar no-radius">

          <a class="navbar-brand">
            <span class="hidden-folded inline">Proyecto IoT</span>
          </a>

        </div>
        <div class="hide-scroll" data-flex>
          <nav class="scroll nav-light">

            <ul class="nav" ui-nav>
              
              <li>
                <a href="dashboard.php">
                  <span class="nav-icon">
                    <i class="fa fa-building-o"></i>
                  </span>
                  <span class="nav-text">Panel</span>
                </a>
              </li>

              <li>
                <a href="devices.php" >
                  <span class="nav-icon">
                    <i class="fa fa-cogs"></i>
                  </span>
                  <span class="nav-text">Dispositivos</span>
                </a>
              </li>

            </ul>
          </nav>
        </div>
      </div>
    </div>

    <!-- content -->
    <div id="content" class="app-content box-shadow-z0" role="main">
      <div class="app-header dark box-shadow">
        <div class="navbar navbar-toggleable-sm flex-row align-items-center">
          <!-- Open side - Naviation on mobile -->
          <a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">
            <i class="material-icons">&#xe5d2;</i>
          </a>

          <ul class="nav navbar-nav ml-auto flex-row">
            <li class="nav-item dropdown">
              <a class="nav-link p-0 clear" href="logout.php">
                Salir
              </a>
            </li>
          </ul>

        </div>
      </div>

      <div ui-view class="app-body" id="view">

        <div class="padding">

          <div class="row">
            <div class="col-md-6 dark">
              <div class="box">
                <div class="box-header">

                  <h2 class="text-center">Agregar Dispositivo</h2>
                  <small class="text-center">Ingresa el nombre y número de serie del dispositivo.</small>

                </div>

                <div class="box-divider m-0"></div>

                <div class="box-body">
                  <form role="form" method="post" target="">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Nombre</label>
                      <input name="alias" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Serie</label>
                      <input name="serie" type="texzt" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-outline-warning btn-block">Registrar</button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-6 dark">
              <div class="box">
                <div class="box-header">
                  <h2 class="text-center">Dispositivos</h2>
                </div>
                <table class="table table-striped b-t">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Fecha</th>
                      <th>Serie</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    if($devices == NULL){
                      ?>
                      <td>No se han agregado dispositivos...!</td>
                      <?php
                    }else{
                      foreach ($devices as $device) {?>
                        <tr>
                          <td><?php echo $device['nombreDispositivo'] ?></td>
                          <td><?php echo $device['dateDispositivo'] ?></td>
                          <td><?php echo $device['serieDispositivo'] ?></td>
                        </tr>
                      <?php 
                      } 
                    }?>
                  </tbody>
                </table>
              </div>
            </div>
          
          <div class="col-12 p-2" >
            <h5 class="text-center">Eliminar Dispositvos</h5>

            <form method="post">
              <div class="form-group">
                <select  name="id_to_delete" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                  <?php foreach ($devices as $device ) { ?>
                    <option value="<?php echo  $device['idDispositivo']?>">
                      <?php echo "Nombre: ".$device['nombreDispositivo']." Serie: ".$device['serieDispositivo'] ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              <button type="submit" class="btn btn-fw danger">Eliminar</button>
            </form>

          </div>
          </div>

        </div>

      </div>

    </div>

  </div>

</div>
</div>
</div>
</div>

</div>

<script src="libs/jquery/jquery/dist/jquery.js"></script>
<script src="libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="libs/jquery/bootstrap/dist/js/bootstrap.js"></script>

<script src="libs/app.js"></script>
<script src="libs/ajax.js"></script>
</body>
</html>
