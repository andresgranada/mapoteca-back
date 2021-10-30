<?php
include_once "../../cors.php";
include_once "../../funciones.php";

$reserva = json_decode(file_get_contents('php://input'), true);
$ReservasTotales = LimiteReservas($reserva);


if ($ReservasTotales->Activas<3){
    echo json_encode('Valido');
    
    $Duplicado = Duplica_reserva($reserva);
    
    if ($Duplicado==false){
        echo json_encode('Disponible');

        $Repetido = Repetido($reserva);

        if ($Repetido==null){

            echo json_encode('Unico');
            
            // $resultado = Reservar($reserva);
            // echo json_encode($resultado);
        }
        else{
            echo json_encode('Repetido');
        }


    }
    else{
        echo json_encode('No disponible');
    }

}

else{
    echo json_encode('Supero el limite');
};

