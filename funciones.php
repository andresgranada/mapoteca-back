<?php
include_once "Modelos/RespuestaLista.php";

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

function actualizarMapa($mapa)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("UPDATE mapoteca SET Titulo = ?, Tipo = ?, Empresa = ?, Escala = ?, Zona_Geografica = ?, URL_Imagen = ? WHERE ID = ?");
    return $sentencia->execute([$mapa['Titulo'], $mapa['Tipo'], $mapa['Empresa'], $mapa['Escala'], $mapa['Zona_Geografica'], $mapa['URL_Imagen'], $mapa['ID']]);
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
    $sentencia = $bd->prepare("INSERT INTO mapoteca (ID, Titulo, Tipo, Empresa, Escala, Zona_Geografica, URL_Imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $sentencia->execute([Null, $mapa['Titulo'], $mapa['Tipo'], $mapa['Empresa'], $mapa['Escala'], $mapa['Zona_Geografica'], $mapa['URL_Imagen']]);
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

function obtenerUsuarioCedula($titulo, $cedula)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT * FROM usuario WHERE Cedula LIKE(CONCAT('%',?,'%'))");
    $sentencia->execute([$cedula]);
    return $sentencia->fetchAll();
}

function actualizarUsuario($usuario)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("UPDATE usuario SET Cedula = ?, Nombre = ?, ApellidoP = ?, ApellidoM = ?, Direccion = ?, Usuarios = ?, Password = ? WHERE ID = ?");
    return $sentencia->execute([$usuario['Cedula'], $usuario['Nombre'], $usuario['ApellidoP'], $usuario['ApellidoM'], $usuario['Direccion'], $usuario['Usuarios'], $usuario['Password'], $usuario['ID']]);
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

    $sentencia2 = $bd->prepare("UPDATE mapoteca SET Disponible = ? WHERE ID = ?");
    $sentencia2->execute([0, $Reserva['ID_mapa']]);

    return $sentencia->execute([$Reserva['ID_mapa'], $Reserva['ID_usuario'], $Reserva['Fecha_devolucion']]);
}

function obtenerReservas()
{
    $bd = obtenerConexion();
    $sentencia = $bd->query("SELECT p.ID, p.ID_libro, m.Titulo as Nombre_mapa, p.Fecha_Prestamo, p.Fecha_Devolucion, p.Clave_Usuario, u.Nombre as Nombre_usuario, u.ApellidoP as Apellido_usuario, p.Estatus from prestamo p INNER JOIN mapoteca m on m.ID = p.ID_libro INNER JOIN usuario u ON u.ID = p.Clave_Usuario ORDER by Fecha_Prestamo");
    return $sentencia->fetchAll();

}

function actualizarReserva($reserva)
{
    if (isset($reserva['Estatus'])) {
        $bd = obtenerConexion();
        $sentencia = $bd->prepare("UPDATE prestamo SET ID_libro = ?, Clave_Usuario = ?, Fecha_devolucion = ?, Estatus = ? WHERE ID = ?");

        $sentencia2 = $bd->prepare("UPDATE mapoteca SET Disponible = ? WHERE ID = ?");
        $sentencia2->execute([1, $reserva['ID_mapa']]);

        return $sentencia->execute([$reserva['ID_mapa'], $reserva['ID_usuario'], $reserva['Fecha_devolucion'], $reserva['Estatus'], $reserva['ID']]);
    } else {

        $bd = obtenerConexion();
        $sentencia = $bd->prepare("UPDATE prestamo SET ID_libro = ?, Clave_Usuario = ?, Fecha_devolucion = ? WHERE ID = ?");
        return $sentencia->execute([$reserva['ID_mapa'], $reserva['ID_usuario'], $reserva['Fecha_devolucion'], $reserva['ID']]);
    }

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
    $sentencia = $bd->prepare("INSERT INTO  usuario (Cedula, Nombre, ApellidoP, ApellidoM, Direccion, Usuarios, Password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $sentencia->execute([$Datos['Cedula'], $Datos['Nombre'], $Datos['ApellidoP'], $Datos['ApellidoM'],$Datos['Direccion'], $Datos['Usuarios'], $Datos['Password']]);
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

        if ($titulo == "Titulo") { 
            $sentencia = $bd->prepare("SELECT B.ID, u.ID as Clave_Usuario, u.Nombre as Nombre_usuario, ApellidoP as Apellido_usuario,
            B.Clave_Usuario as Cedula_Usuario, B.Fecha_Devolucion, B.Estatus, A.ID AS ID_libro, A.Titulo as Nombre_mapa, A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro join usuario u on B.Clave_Usuario = u.ID
            WHERE (B.Estatus<> 'Entregado') and A.Titulo LIKE(CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Empresa") {

            $sentencia = $bd->prepare("SELECT B.ID, u.ID as Clave_Usuario, u.Nombre as Nombre_usuario, ApellidoP as Apellido_usuario,
            B.Clave_Usuario as Cedula_Usuario, B.Fecha_Devolucion, B.Estatus, A.ID AS ID_libro, A.Titulo as Nombre_mapa, A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro join usuario u on B.Clave_Usuario = u.ID
            WHERE B.Estatus<> 'Entregado' and A.Empresa LIKE (CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Tipo") {

            $sentencia = $bd->prepare("SELECT B.ID, u.ID as Clave_Usuario, u.Nombre as Nombre_usuario, ApellidoP as Apellido_usuario,
            B.Clave_Usuario as Cedula_Usuario, B.Fecha_Devolucion, B.Estatus, A.ID AS ID_libro, A.Titulo as Nombre_mapa, A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro join usuario u on B.Clave_Usuario = u.ID
            WHERE B.Estatus<> 'Entregado' and A.Tipo LIKE (CONCAT('%',?,'%'))order by B.Fecha_Prestamo DESC");

        } elseif ($titulo == "Zona_Geografica") {
            $sentencia = $bd->prepare("SELECT B.ID,,u.ID as Clave_Usuario, u.Nombre as Nombre_usuario, ApellidoP as Apellido_usuario,
            B.Clave_Usuario as Cedula_Usuario, B.Fecha_Devolucion, B.Estatus, A.ID AS ID_libro, A.Titulo as Nombre_mapa, A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro join usuario u on B.Clave_Usuario = u.ID
            WHERE B.Estatus<> 'Entregado' and A.Zona_Geografica LIKE (CONCAT('%',?,'%'))");

        }elseif ($titulo == "Cedula") {
            $sentencia = $bd->prepare("SELECT B.ID,,u.ID as Clave_Usuario, u.Nombre as Nombre_usuario, ApellidoP as Apellido_usuario,
            B.Clave_Usuario as Cedula_Usuario, B.Fecha_Devolucion, B.Estatus, A.ID AS ID_libro, A.Titulo as Nombre_mapa, A.Zona_Geografica,A.Escala, 
            B.Fecha_Prestamo from mapoteca A join prestamo B on A.ID=B.ID_libro join usuario u on B.Clave_Usuario = u.ID
            WHERE B.Estatus<> 'Entregado' and u.ID LIKE(CONCAT('%',?,'%')) order by B.Fecha_Prestamo DESC");

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
    return $sentencia->fetchAll();
}

function filtroHistorialMapa($id, $titulo, $nombre)
{
    $bd = obtenerConexion();
    if ($titulo == "Titulo") {
        $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, B.Fecha_Prestamo, A.URL_Imagen from mapoteca A join prestamo B on A.ID=B.ID_libro WHERE B.Clave_Usuario = ? AND A.Titulo LIKE (CONCAT('%',?,'%')) ");
    } elseif ($titulo == "Empresa") {
        $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, B.Fecha_Prestamo, A.URL_Imagen from mapoteca A join prestamo B on A.ID=B.ID_libro WHERE B.Clave_Usuario = ? AND A.Empresa LIKE (CONCAT('%',?,'%')) ");
    } elseif ($titulo == "Tipo") {
        $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, B.Fecha_Prestamo, A.URL_Imagen from mapoteca A join prestamo B on A.ID=B.ID_libro WHERE B.Clave_Usuario = ? AND A.Tipo LIKE (CONCAT('%',?,'%')) ");
    } elseif ($titulo == "Zona_Geografica") {
        $sentencia = $bd->prepare("SELECT B.Clave_Usuario, A.ID AS ID_Mapa,A.Titulo,A.Titulo,A.Zona_Geografica,A.Escala, B.Fecha_Prestamo, A.URL_Imagen from mapoteca A join prestamo B on A.ID=B.ID_libro WHERE B.Clave_Usuario = ? AND A.Zona_Geografica LIKE (CONCAT('%',?,'%')) ");
    }

    $sentencia->execute([$id, $nombre]);
    return $sentencia->fetchAll();
}



function LimiteReservas($reserva)
{

    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT count('*') as Activas from prestamo where Clave_Usuario=? and (Estatus='A tiempo' or Estatus='Tardio');");
    $sentencia->execute([$reserva['ID_usuario']]);
    return $sentencia->fetch();
    
}


function Duplica_reserva($reserva)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT B.ID as Identificador from prestamo A join mapoteca B on A.ID_libro=B.ID where (A.Estatus='A tiempo' or A.Estatus='Tardio')and B.ID=? LIMIT 1;");
    $sentencia->execute([$reserva['ID_mapa']]);
    return $sentencia->fetch();
}

function Repetido($reserva)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT Titulo FROM prestamo A join mapoteca B on A.ID_libro=B.ID where B.Titulo=? AND A.Clave_Usuario=?;");
    $sentencia->execute([$reserva['Titulo'],$reserva['ID_usuario']]);
    return $sentencia->fetch();
}


function mapasUsuario($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("SELECT B.URL_Imagen, B.ID as ID_Mapa, B.Titulo,A.Estatus,A.Fecha_Prestamo, A.Fecha_Devolucion, A.Clave_Usuario  FROM prestamo A join mapoteca B on A.ID_libro=B.ID WHERE A.Clave_Usuario= ? AND (A.Estatus='A tiempo' OR A.Estatus='Tardio');");
    $sentencia->execute([$id]);
    return $sentencia->fetchAll();
}