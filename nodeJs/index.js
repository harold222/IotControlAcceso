//requiro dependencias
const mysql = require('mysql');
const mqtt = require('mqtt');

//creo la conexion de mysql a mi base de datos
var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "proyectoiot"
});

//creo la conexion a mqtt
var opciones = {//conexion directo por tcp
    port: 1883,
    host: 'proyectoiotuniminuto.tk',
    clientId: 'control_acceso_'+Math.round(Math.random()*(0-100)*-1),
    username: 'cliente_web',
    password: 'proyectoUniminuto',
    keepalive: 60,//cada tiempo hago envio
    reconnectPeriod: 1000,//si se cae la conexion reconecto cada x tiempo
    protocolId: 'MQIsdp',
    protocolVersion: 3,
    clean: true,
    encoding: 'utf8'
};

var cliente = mqtt.connect('mqtt://proyectoiotuniminuto.tk', opciones);

//realizo la conexion
cliente.on('connect', function(){
    console.log('conexion mqtt realizada!');
    client.subscribe('+/#', function (err) {//puedo recibir mensajes instantaneos
        console.log('subscripcion exitosa!');
    });
});

//al recibir un mensaje
cliente.on('message', function(topico, mensaje){
    console.log(`Mensaje recibido de ${topico} y mensaje: ${mensaje.toString()}`);
    if(topico == "values"){
        //como los valores los obtengo separados por comas, necesito partir el string
        //para poder guardarlos en la base de datos
        var msj = mensaje.toString();//transformo en string
        var particion = msj.split(",");

        var temp1 = particion[0];
        var temp2 = particion[1];
        var temp3 = particion[2];

        //realizo la consulta insertando los valores que obtengo
        var consulta = "INSERT INTO datos (temperatura1, temperatura2, voltios) VALUES ("+temp1+","+temp2+","+temp3+");";
        con.query(consulta, function(err, resultado, campos){
            if(err){
                console.log('Hubo un error'+err);
                throw err;
            }
        });
    }
});

//me conecto y si obtengo un error lo trato para que no me detenga la aplicacion
con.connect(function(err){
    if(err){
        console.log(err);
        throw err;
    }

    var consulta = 'SELECT * FROM users';
    //ejecuto mi consulta
    con.query(consulta, function (err, resultado, campos) {
        if (err) throw err;
        //si el resultado es mayor a 0 es porque si encontre datos en la tabla
        if (resultado.length > 0) console.log(resultado);
        
    });
});

//cada 5 segundos ejecuto esta funcion basica para que mysql
//no me cierre la conexion, ya que despues de cada cierto tiempo
//de no hacer consultas cierra la conexion
setInterval(function() {
    var consulta = 'SELECT 1 + 1 as result';
    
    con.query(consulta, function (err, resultados, campos) {
        if(err) throw err;
    });

}, 5000);



