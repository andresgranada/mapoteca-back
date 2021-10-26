<?php
include_once "../cors.php";
include_once "../funciones.php";
// $mapa = json_decode(file_get_contents("php://input"));
$mapa = json_decode(file_get_contents('php://input'), true);
$resultado = actualizarMapa($mapa);
echo json_encode($resultado);