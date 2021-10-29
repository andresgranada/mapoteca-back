<?php
include_once "../../cors.php";
include_once "../../funciones.php";

if (!isset($_GET["usuario"]) || !isset($_GET["password"])) {
    json_decode(null);
} else {
    $usuarioFiltro = filtroUsuario($_GET["usuario"], $_GET["password"]);
    echo json_encode($usuarioFiltro);
}
