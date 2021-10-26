<?php
include_once "../cors.php";
include_once "../funciones.php";
$mapas = obtenerMapas();
echo json_encode($mapas);
