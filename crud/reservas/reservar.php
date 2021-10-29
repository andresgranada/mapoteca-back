<?php
include_once "../../cors.php";
include_once "../../funciones.php";

$ReservasTotales = json_decode(file_get_contents('php://input'), true);
$ReservasTotales = LimiteReservas($Reserva);
echo json_encode($ReservasTotales);

if ($ReservasTotales='"Activas": 0' || $ReservasTotales='"Activas": 1' || $ReservasTotales='"Activas": 2'){
    echo json_encode(' Valido');

    // $Duplicado = json_decode(file_get_contents('php://input'), true);
    // $Duplicado = Duplica_reserva($Reserva);
    // echo json_encode($Duplicado);

    $reserva = json_decode(file_get_contents('php://input'), true);
    $resultado = Reservar($reserva);
    echo json_encode($resultado);

}

else{
    echo json_encode('Supero el limite');
};