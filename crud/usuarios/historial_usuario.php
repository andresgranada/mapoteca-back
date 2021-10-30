<?php
include_once "../../cors.php";
if (!isset($_GET["id"])) {
    echo json_encode(null);
    exit;
}
$id = $_GET["id"];
include_once "../../funciones.php";


if (!isset($_GET["titulo"]) || !isset($_GET["nombre"])) {
    $mapas = historialUsuario($id);
    echo json_encode($mapas);
} else {
    $mapasFiltro = filtroHistorialMapa($_GET["id"], $_GET["titulo"], $_GET["nombre"]);
    echo json_encode($mapasFiltro);
}