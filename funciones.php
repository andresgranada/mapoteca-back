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

function filtroMapa($titulo, $nombre)
{
    if (isset($titulo) && isset($nombre)) {
        $bd = obtenerConexion();
        if ($titulo == "Titulo") {
            $sentencia = $bd->prepare("SELECT * from mapoteca where Titulo LIKE (CONCAT('%',?,'%'))");
        } elseif ($titulo == "Empresa") {
            $sentencia = $bd->prepare("SELECT * from mapoteca where Empresa LIKE (CONCAT('%',?,'%'))");
        } elseif ($titulo == "Tipo") {
            $sentencia = $bd->prepare("SELECT * from mapoteca where Tipo LIKE (CONCAT('%',?,'%'))");
        } elseif ($titulo == "Zona_Geografica") {
            $sentencia = $bd->prepare("SELECT * from mapoteca where Zona_Geografica LIKE (CONCAT('%',?,'%'))");
        }
        $sentencia->execute([$nombre]);
        return $sentencia->fetchAll();
    }

    obtenerMapas();
}

function obtenerUsuarios()
{
    $bd = obtenerConexion();
    $sentencia = $bd->query("SELECT * FROM usuario");
    return $sentencia->fetchAll();

}

function Reservar($Reserva)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("CALL Reserva (?,?,?);");
    return $sentencia->execute([$Reserva['ID_mapa'], $Reserva['ID_usuario'], $Reserva['Fecha_devolucion']]);
}

function obtenerReservas()
{
    $bd = obtenerConexion();
    $sentencia = $bd->query("SELECT p.ID, p.ID_libro, m.Titulo as Nombre_mapa, p.Fecha_Prestamo, p.Fecha_Devolucion, p.Clave_Usuario, u.Nombre as Nombre_usuario, u.ApellidoP as Apellido_usuario, p.Estatus from prestamo p INNER JOIN mapoteca m on m.ID = p.ID_libro INNER JOIN usuario u ON u.ID = p.Clave_Usuario");
    return $sentencia->fetchAll();

}

function LoginAdmin($Datos)
{
    $bd = obtenerConexion();
    $sentencia = $bd->query("SELECT * from administrador where usuarios=? and PASSWORD=?;");
    return $sentencia->execute([$Datos['usuarios'], $Datos['PASSWORD']]);
}

function LoginUsuario($Datos)
{
    $bd = obtenerConexion();
    $sentencia = $bd->query("SELECT * from usuario where usuarios=? and PASSWORD=?;");
    return $sentencia->execute([$Datos['usuarios'], $Datos['PASSWORD']]);
}

function filtroReservas($titulo, $nombre)
{
    if (isset($titulo) && isset($nombre)) {
        $bd = obtenerConexion();
        if ($titulo == "Titulo") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro 
            WHERE A.Titulo LIKE(CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Empresa") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro
            where A.Empresa LIKE (CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Tipo") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro
            where A.Tipo LIKE (CONCAT('%',?,'%'))order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Zona_Geografica") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro
            where A.Zona_Geografica LIKE (CONCAT('%',?,'%'))");

        }elseif ($titulo == "Cedula") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro 
            WHERE A.Titulo LIKE(CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        }elseif ($titulo == "Cedula") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro 
            WHERE A.ID LIKE(CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        $sentencia->execute([$nombre]);
        return $sentencia->fetchAll();
    }

    obtenerMapas();
}


}