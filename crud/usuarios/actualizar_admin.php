<?php
include_once "../../cors.php";
include_once "../../funciones.php";
// $mapa = json_decode(file_get_contents("php://input"));
$usuario = json_decode(file_get_contents('php://input'), true);
$resultado = actualizarAdmin($usuario);
echo json_encode($resultado);