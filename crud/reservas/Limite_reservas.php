<?php
include_once "../../cors.php";
if (!isset($_GET["id"])) {
    echo json_encode(null);
    exit;
}
$Reserva = $_GET["id"];
include_once "../../funciones.php";
$Limite = 'Hola';
echo json_encode($Limite);