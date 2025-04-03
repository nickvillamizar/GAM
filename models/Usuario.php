<?php
// models/Usuario.php

class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id;
    public $nombre_completo;
    public $cedula;
    public $correo;
    public $celular;
    public $pais;
    public $ciudad;
    public $direccion;
    public $fecha_nacimiento;
    public $genero;
    public $contraseña;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar usuario
    public function registrar() {
        $query = "INSERT INTO " . $this->table . " 
            (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, fecha_nacimiento, genero, contraseña) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error en la preparación: " . $this->conn->error);
        }
        
        // Encriptar la contraseña
        $passHash = password_hash($this->contraseña, PASSWORD_BCRYPT);

        $stmt->bind_param(
            "ssssssssss",
            $this->nombre_completo,
            $this->cedula,
            $this->correo,
            $this->celular,
            $this->pais,
            $this->ciudad,
            $this->direccion,
            $this->fecha_nacimiento,
            $this->genero,
            $passHash
        );

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // Autenticación de usuario
    public function autenticar($correo, $password) {
        $query = "SELECT id, nombre_completo, contraseña FROM " . $this->table . " WHERE correo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();
            
            if (password_verify($password, $usuario['contraseña'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre_completo'];
                header("Location: ../index.php?url=dashboard");
                exit();
            }
        
            return false; // Contraseña incorrecta
        }
        
        return false; // Usuario no encontrado
        
}
}
?>
