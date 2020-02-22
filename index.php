<?php 
  require_once('partials/header.php');
?>
  <div class="page-content mt-4" id="home">
    <div class="pos-rlt" id="features">
      <div class="h-v-5 row-col text-center">
        <div class="col-sm-6 deep-purple v-m">
          <div class="p-a-lg">
            <h2 class=" _700 l-s-n-1x m-y m-b-md">AWS EC2</h2>
            <p class="h5 text-muted l-h">
              Es un servicio web que proporciona capacidad de computo de tamaño ajustable en la nube,
              Este servicio se uso para poder crear un VPS propio.
            </p>
          </div>
        </div>
        <div class="col-sm-6 red-700 v-m">
          <div class="p-a-lg">
            <h2 class=" _700 l-s-n-1x m-y m-b-md">VestaCP</h2>
            <p class="h5 text-muted l-h">
              Es un panel de control web pensado para alojar páginas web en un servidor,
              se uso para tener este panel en el vps creado con aws, permitiendome trabajar con
              apache-php-mysql
            </p>
          </div>
        </div>
      </div>
      <div class="h-v-5 row-col text-center">
        <div class="col-sm-6 primary v-m">
          <div class="p-a-lg">
            <h2 class=" _700 l-s-n-1x m-y m-b-md">PlatformIO</h2>
            <p class="h5 text-muted l-h">
              Es un ecosistema Open Source de desarrollo para entornos IoT, se uso como plugin en atom
              para desarrollar firmaware con el modulo esp32 y arduino uno.
            </p>
          </div>
        </div>
        <div class="col-sm-6 warn v-m">
          <div class="p-a-lg">
            <h2 class=" _700 l-s-n-1x m-y m-b-md">NodeJs</h2>
            <p class="h5 l-h">
              Es un entorno JavaScript de lado de servidor que utiliza un modelo asíncrono y 
              dirigido por eventos lo usamos, ya que puede manejar datos en tiempo real, a 
              diferencia de php este se ejecuta de manera constante para que el algoritmo 
              no pare y se ejecute al instante distintos acontecimientos.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php 
  require_once('partials/footer.php');
?>