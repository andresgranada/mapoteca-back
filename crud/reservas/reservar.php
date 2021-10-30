<?php
include_once "../../cors.php";
include_once "../../funciones.php";

$reserva = json_decode(file_get_contents('php://input'), true);
$ReservasTotales = LimiteReservas($reserva);


if ($ReservasTotales->Activas<3){

    $Duplicado = Duplica_reserva($reserva);
    
    if ($Duplicado==false){

        $resultado = Reservar($reserva);
        echo json_encode($resultado);
    }
    else{
        echo json_encode('mapa reservado');
        return;
    }

}

else{
    echo json_encode('Supero el limite');
    return;
};

