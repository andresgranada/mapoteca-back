<?php
include_once "../../cors.php";
include_once "../../funciones.php";

if (!isset($_GET["titulo"]) || !isset($_GET["nombre"])) {
    $usuarios = obtenerUsuarios();
    echo json_encode($usuarios);
} else {
    $usuarioFiltro = LoginAdmin($_GET["titulo"], $_GET["nombre"]);
    echo json_encode($usuarioFiltro);
}
