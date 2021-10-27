<?php
include_once "../cors.php";
include_once "../funciones.php";
// $mapa = json_decode(file_get_contents("php://input"));
$Reserva = json_decode(file_get_contents('php://input'), true);
$resultado = Reservar($Reserva);
echo json_encode($resultado);