<?php
include_once "../../cors.php";
include_once "../../funciones.php";

if (!isset($_GET["titulo"]) || !isset($_GET["nombre"])) {
    $usuarios = obtenerUsuarios();
    echo json_encode($usuarios);
} else {
    if ($_GET["titulo"] == "Cedula") {
        $usuarioFiltro = obtenerUsuarioCedula($_GET["titulo"], $_GET["nombre"]);
        echo json_encode($usuarioFiltro);
    }
}
