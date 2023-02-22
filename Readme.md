API REST que precarga los datos de las mareas de los últimos dos meses (mes actual inclusive) o de los años que se quiera especificar

La tarea cron está en el directorio cron con el nombre de loaddata.php, para ejecutarla exitosamente se puede hacer de estas dos formas:

php loaddata.php nearmonths (carga los dos meses previos y anteriores con el mes actual inclusive)

php loaddata.php years <Y1>,<Y2>,<Yn> (los años se escriben numéricamente, como por ejemplo 2017,2018,...)

La api tiene un único endpoint llamado marea, solo admite peticiones GET y la atenticación se realiza mediante un token. El token
se envia mediante el encabezado HTTP personalizado X-Marea-Auth cuyo valor es el token que se haya proveído al consumidor de la API

La configuración de la base de datos se hace en el fichero dbconfig.php en el directorio config, este fichero no aparece en el respositorio
porque forma parte del gitignore. esto permitirá hacer commits y pushs desde entornos de desarrollo y producción sin desconfigurar
a ninguno de los dos

este es el formato que debe de seguir el dbconfig.php

<?php
$host = '<ip>';
$db   = '<nombre_de_la_db>';
$user = '<usuario>';
$pass = '<password>';
$port = "<puerto> (suele ser 3306)";
$charset = '<coficacion de la db> (suele ser utf8mb4)';

