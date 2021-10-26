<?php
include_once "Modelos/RespuestaLista.php";

function actualizarMapa($mapa)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("UPDATE mapoteca SET Titulo = ?, Tipo = ?, Empresa = ?, Escala = ?, Zona_Geografica = ? WHERE ID = ?");
    return $sentencia->execute([$mapa['Titulo'], $mapa['Tipo'], $mapa['Empresa'], $mapa['Escala'], $mapa['Zona_Geografica'], $mapa['ID']]);
}

function obtenerMapaId($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT * FROM mapoteca WHERE ID = ?");
    $sentencia->execute([$id]);
    return $sentencia->fetchObject();
}

function obtenerMapas()
{
    $bd = obtenerConexion();
    $sentencia = $bd->query("SELECT * FROM mapoteca");
    return $sentencia->fetchAll();

}

function crearMapa($mapa)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("INSERT INTO mapoteca (ID, Titulo, Tipo, Empresa, Escala, Zona_Geografica) VALUES (?, ?, ?, ?, ?, ?)");
    return $sentencia->execute([Null, $mapa['Titulo'], $mapa['Tipo'], $mapa['Empresa'], $mapa['Escala'], $mapa['Zona_Geografica']]);
}

function eliminarMapa($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("DELETE FROM mapoteca WHERE id = ?");
    return $sentencia->execute([$id]);
}

function obtenerConexion()
{
    $password = "";
    $user = "root";
    $dbName = "test";
    $database = new PDO('mysql:host=localhost;dbname=' . $dbName, $user, $password);
    $database->query("set names utf8;");
    $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $database;
}