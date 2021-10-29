<?php
include_once "../../cors.php";
include_once "../../funciones.php";
$Datos = json_decode(file_get_contents('php://input'), true);
$resultado = AltaAdmin($Datos);
echo json_encode($resultado);