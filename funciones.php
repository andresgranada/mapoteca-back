<?php
include_once "Modelos/RespuestaLista.php";

function obtenerConexion()
{
    $password = "";
    $user = "root";
    $dbName = "proyectoespejo";
    $database = new PDO('mysql:host=localhost;dbname=' . $dbName, $user, $password);
    $database->query("set names utf8;");
    $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $database;
}

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

function obtenerUsuarioId($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT * FROM usuario WHERE ID = ?");
    $sentencia->execute([$id]);
    return $sentencia->fetchObject();
}

function actualizarUsuario($usuario)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("UPDATE usuario SET Nombre = ?, ApellidoP = ?, ApellidoM = ?, Direccion = ?, Usuarios = ?, Password = ? WHERE ID = ?");
    return $sentencia->execute([$usuario['Nombre'], $usuario['ApellidoP'], $usuario['ApellidoM'], $usuario['Direccion'], $usuario['Usuarios'], $usuario['Password'], $usuario['ID']]);
}

function eliminarUsuario($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("DELETE FROM usuario WHERE id = ?");
    return $sentencia->execute([$id]);
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

function LoginAdmin($datos)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT * from administrador where usuarios = ? and Password = ?");
    $sentencia->execute([$datos['user'], $datos['password']]);
    return $sentencia->fetchObject();
}

function AltaAdmin($Datos)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("INSERT INTO  Administrador (Nombre, ApellidoP, ApellidoM, Usuario, Password) VALUES (?, ?, ?, ?, ?)");
    return $sentencia->execute([Null, $Datos['Nombre'], $Datos['ApellidoP'], $Datos['ApellidoM'], $Datos['Usuario'], $mapa['Password']]);
}

function obtenerAdminId($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT * FROM Administrador WHERE ID = ?");
    $sentencia->execute([$id]);
    return $sentencia->fetchObject();
}

function LoginUsuario($datos)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT * from usuario where usuarios = ? and Password = ?");
    $sentencia->execute([$datos['user'], $datos['password']]);
    return $sentencia->fetchObject();
}

function AltaUsuarios($Datos)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("INSERT INTO  usuario (Nombre, ApellidoP, ApellidoM, Direccion, Usuarios, Password) VALUES (?, ?, ?, ?, ?,?)");
    return $sentencia->execute([$Datos['Nombre'], $Datos['ApellidoP'], $Datos['ApellidoM'],$Datos['Direccion'], $Datos['Usuarios'], $Datos['Password']]);
}

function actualizarAdmin($usuario)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("UPDATE Administrador SET Nombre = ?, ApellidoP = ?, ApellidoM = ?, Usuarios = ?, Password = ? WHERE ID = ?");
    return $sentencia->execute([$usuario['Nombre'], $usuario['ApellidoP'], $usuario['ApellidoM'], $usuario['Usuarios'], $usuario['Password'], $usuario['ID']]);
}

function filtroReservas($titulo, $nombre)
{
    if (isset($titulo) && isset($nombre)) {
        $bd = obtenerConexion();

        if ($titulo == "Titulo") { //agregar as
            $sentencia = $bd->prepare("SELECT B.Clave_Usuario as Cedula_Usuario, A.ID AS ID_Mapa,A.Titulo as Titulo_Mapa,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro 
            WHERE (B.Estatus<> 'Entregado') and A.Titulo LIKE(CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Empresa") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuarioas Cedula_Usuario, A.ID AS ID_Mapa,A.Titulo as Titulo_Mapa,A.Zona_Geografica,A.Escala,  
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro
            where B.Estatus<> 'Entregado' and A.Empresa LIKE (CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Tipo") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuarioas Cedula_Usuario, A.ID AS ID_Mapa,A.Titulo as Titulo_Mapa,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro
            where B.Estatus<> 'Entregado' and A.Tipo LIKE (CONCAT('%',?,'%'))order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Zona_Geografica") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuarioas Cedula_Usuario, A.ID AS ID_Mapa,A.Titulo as Titulo_Mapa,A.Zona_Geografica,A.Escala,  
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro
            where B.Estatus<> 'Entregado' and A.Zona_Geografica LIKE (CONCAT('%',?,'%'))");

        }elseif ($titulo == "Cedula") {
            $sentencia = $bd->prepare("SELECT B.Clave_Usuarioas Cedula_Usuario, A.ID AS ID_Mapa,A.Titulo as Titulo_Mapa,A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro 
            WHERE B.Estatus<> 'Entregado' and A.Titulo LIKE(CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        } 
        $sentencia->execute([$nombre]);
        return $sentencia->fetchAll();
    }

    return obtenerReservas();
}

function historialUsuario($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, B.Fecha_Prestamo, A.URL_Imagen from mapoteca A join prestamo B on A.ID=B.ID_libro WHERE B.Clave_Usuario = ? order by B.Fecha_Prestamo DESC ");
    $sentencia->execute([$id]);
    return $sentencia->fetchObject();
}


function LimiteReservas($reserva)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT count('*') as Activas from prestamo where (Estatus='A tiempo' or Estatus='Tardio') and Clave_Usuario=?;");
    $sentencia->execute([$reserva['ID_usuario']]);
    return $sentencia->fetchObject();
}


function Duplica_reserva($reserva)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT * from prestamo A joinmapoteca B on A.ID_libro=B.ID where A.Estatus='A tiempo' and B.Titulo=? and A.Clave_Usuario=?");
    $sentencia->execute([$reserva['ID_mapa']],[$reserva['ID_usuario']]);
    return $sentencia->fetchObject();
}
