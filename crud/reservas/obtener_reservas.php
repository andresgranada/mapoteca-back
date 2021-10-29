<?php
include_once "../../cors.php";
include_once "../../funciones.php";

if (!isset($_GET["titulo"]) || !isset($_GET["nombre"])) {
    $reservas = obtenerReservas();
    echo json_encode($reservas);
} else {
    $reservaFiltro = filtroReservas($_GET["titulo"], $_GET["nombre"]);
    echo json_encode($reservaFiltro);
}
