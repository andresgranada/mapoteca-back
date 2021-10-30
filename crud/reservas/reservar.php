<?php
include_once "../../cors.php";
include_once "../../funciones.php";

$reserva = json_decode(file_get_contents('php://input'), true);
$ReservasTotales = LimiteReservas($reserva);


if ($ReservasTotales->Activas<3){
    echo json_encode('Valido');

    // $Duplicado = json_decode(file_get_contents('php://input'), true);
    // $Duplicado = Duplica_reserva($Reserva);
    // echo json_encode($Duplicado);


        $resultado = Reservar($reserva);
        echo json_encode($resultado);

}

else{
    echo json_encode('Supero el limite');
};

