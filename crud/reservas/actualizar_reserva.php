<?php
include_once "../../cors.php";
include_once "../../funciones.php";
// $mapa = json_decode(file_get_contents("php://input"));
$reserva = json_decode(file_get_contents('php://input'), true);
$resultado = actualizarReserva($reserva);
echo json_encode($resultado);