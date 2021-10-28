<?php
include_once "../../cors.php";
include_once "../../funciones.php";
$reserva = json_decode(file_get_contents('php://input'), true);
$resultado = Reservar($reserva);
echo json_encode($resultado);