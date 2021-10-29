<?php
include_once "../../cors.php";
include_once "../../funciones.php";

if (!isset($_GET["usuario"]) || !isset($_GET["password"])) {
    $usuarios = obtenerUsuarios();
    echo json_encode($usuarios);
} else {
    $usuarioFiltro = LoginAdmin($_GET["usuario"], $_GET["password"]);
    echo json_encode($usuarioFiltro);
}
