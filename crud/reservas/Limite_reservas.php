<?php
include_once "../../cors.php";
if (!isset($_GET["Clave_Usuario"])) {
    echo json_encode(null);
    exit;
}
$Reserva = $_GET["Clave_Usuario"];
include_once "../../funciones.php";
$Limite = 'Hola';
echo json_encode($Limite);