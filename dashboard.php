<?php
  session_start();
  $logged = $_SESSION['logged'];

  if(!$logged){
    header('Location: login.php');
    die();
  }
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

    <div id="content" class="app-content box-shadow-z0" role="main">
      <div class="app-header dark box-shadow">
        <div class="navbar navbar-toggleable-sm flex-row align-items-center">
          <!-- Open side - Naviation on mobile -->
          <a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">
            <i class="material-icons">&#xe5d2;</i>
          </a>

          <div class="mb-0 h5 no-wrap" ng-bind="$state.current.data.title" id="pageTitle"></div>

          <!-- BARRA DE LA DERECHA -->
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

        <!-- SECCION CENTRAL -->
        <div class="padding">

          <!-- VALORES EN TIEMPO REAL -->
          <div class="row">

            <div class="col-xs-12 col-sm-4">
                <div class="pull-left m-r">
                  <span class="w-48 rounded  accent">
                    <i class="fa fa-sun-o fa-2x mt-2 fa-spin"></i>
                  </span>
                </div>
                <div class="clear p-3">
                  <small class="text-muted" style="font-size: 18px;">Temperatura 1: 
                    <span id="display_temp1">-</span>C
                  </small>
                </div>
            </div>

            <div class="col-xs-6 col-sm-4">
                <div class="pull-left m-r">
                  <span class="w-48 rounded primary">
                    <i class="fa fa-power-off fa-2x mt-2"></i>
                  </span>
                </div>
                <div class="clear p-3">
                  <small class="text-muted" style="font-size: 18px;">Temperatura 2: 
                    <span id="display_temp2">-</span>C
                  </small>
                </div>
            </div>

            <div class="col-xs-6 col-sm-4">
                <div class="pull-left m-r">
                  <span class="w-48 rounded warn">
                    <i class="fa fa-plug fa-2x mt-2 fa-spin"></i>
                  </span>
                </div>
                <div class="clear p-3">
                  <small class="text-muted" style="font-size: 18px;">Tensión: 
                    <span id="display_volt">-</span>V
                  </small>
                </div>
            </div>
            
          </div>

          <!-- SWItCH1 y 2 -->
          <div class="row">
            <!-- SWItCH1 -->
            <div class="col-xs-12 col-sm-6">
              <div class="box p-a dark">
                <div class="form-group row">
                  <label class="col-sm-2 form-control-label text-center">LED1</label>
                  <div class="col-sm-10">
                    <label class="ui-switch ui-switch-md info m-t-xs">
                      <input id="input_led1" onchange="process_led1()"  type="checkbox" >
                      <i></i>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- SWItCH2 -->
              <div class="col-xs-12 col-sm-6">
                <div class="box p-a dark">
                  <div class="form-group row">
                    <label class="col-sm-2 form-control-label text-center">LED2</label>
                    <div class="col-sm-10">
                      <label class="ui-switch ui-switch-md info m-t-xs">
                        <input id="input_led2" onchange="process_led2()" type="checkbox"  >
                        <i></i>
                      </label>
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
</div>

</div>

<script src="libs/jquery/jquery/dist/jquery.js"></script>
<script src="libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="libs/jquery/bootstrap/dist/js/bootstrap.js"></script>

<script src="libs/app.js"></script>
<script src="libs/ajax.js"></script>

<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
<script type="text/javascript">

  function update_values(temp1, temp2, volts){
    $("#display_temp1").html(temp1);
    $("#display_temp2").html(temp2);
    $("#display_volt").html(volts);
  }

  function process_msg(topic, message){
    // ej: "10,11,12"
    if (topic == "values"){
      var msj = message.toString();
      var particion = msj.split(",");
      
      var temp1 = particion[0];
      var temp2 = particion[1];
      var volts = particion[2];
      update_values(temp1,temp2,volts);
    }
  }

  function process_led1(){
    if ($('#input_led1').is(":checked")){
      console.log("Encendido");

      client.publish('led1', 'on', (error) => {
        console.log(error || 'Mensaje enviado!!!')
      })
    }else{
      console.log("Apagado");
      client.publish('led1', 'off', (error) => {
        console.log(error || 'Mensaje enviado!!!')
      })
    }
  }

  function process_led2(){
    if ($('#input_led2').is(":checked")){
      console.log("Encendido");

      client.publish('led2', 'on', (error) => {
        console.log(error || 'Mensaje enviado!!!')
      })
    }else{
      console.log("Apagado");
      client.publish('led2', 'off', (error) => {
        console.log(error || 'Mensaje enviado!!!')
      })
    }
  }

/*
 CONEXION 
*/

  const options = {
        connectTimeout: 4000,

        clientId: 'iotmc',
        username: 'web_client',
        password: '121212',

        keepalive: 60,
        clean: true,
  }

  var connected = false;

  // WebSocket connect url
  const WebSocket_URL = 'wss://cursoiot.ga:8094/mqtt'

  const client = mqtt.connect(WebSocket_URL, options)

client.on('connect', () => {
    console.log('Mqtt conectado por WS! Exito!')

    client.subscribe('values', { qos: 0 }, (error) => {
      if (!error) {
        console.log('Suscripción exitosa!')
      }else{
        console.log('Suscripción fallida!')
      }
    })

    // publish(topic, payload, options/callback)
    client.publish('fabrica', 'esto es un verdadero éxito', (error) => {
      console.log(error || 'Mensaje enviado!!!')
    })
})

  client.on('message', (topic, message) => {
    console.log('Mensaje recibido bajo tópico: ', topic, ' -> ', message.toString())
    process_msg(topic, message);
  })

  client.on('reconnect', (error) => {
      console.log('Error al reconectar', error)
  })

  client.on('error', (error) => {
      console.log('Error de conexión:', error)
  })

</script>

</body>
</html>
