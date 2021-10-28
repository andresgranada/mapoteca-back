<?php
include_once "../../cors.php";
include_once "../../funciones.php";

if (!isset($_GET["titulo"]) || !isset($_GET["nombre"])) {
    $mapas = obtenerMapas();
    echo json_encode($mapas);
} else {
    $mapasFiltro = filtroMapa($_GET["titulo"], $_GET["nombre"]);
    echo json_encode($mapasFiltro);
}
