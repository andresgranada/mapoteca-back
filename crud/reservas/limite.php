<?php
include_once "../../cors.php";
include_once "../../funciones.php";


$ReservasTotales = LimiteReservas();
echo json_encode($ReservasTotales);