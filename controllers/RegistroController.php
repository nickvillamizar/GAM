<?php
// controllers/RegistroController.php

require_once '../config/db.php';
require_once '../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario($conn);
    $usuario->nombre_completo   = $_POST['nombre_completo'];
    $usuario->cedula            = $_POST['cedula'];
    $usuario->correo            = $_POST['correo'];
    $usuario->celular           = $_POST['celular'];
    $usuario->pais              = $_POST['pais'];
    $usuario->ciudad            = $_POST['ciudad'];
    $usuario->direccion         = $_POST['direccion'];
    $usuario->fecha_nacimiento  = $_POST['fecha_nacimiento'];
    $usuario->genero            = $_POST['genero'];
    $usuario->contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);


    if($usuario->registrar()){
        header("Location: ../views/login.php?mensaje=Registro exitoso");
    } else {
        header("Location: ../views/registro.php?error=Error en el registro");
    }
}
?>
