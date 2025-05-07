<?php
session_start();
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos generales
    $nombre_completo   = $_POST['nombre_completo'] ?? '';
    $cedula            = $_POST['cedula'] ?? '';
    $correo            = $_POST['correo'] ?? '';
    $celular           = $_POST['celular'] ?? '';
    $pais              = $_POST['pais'] ?? '';
    $ciudad            = $_POST['ciudad'] ?? '';
    $direccion         = $_POST['direccion'] ?? '';
    $fecha_nacimiento  = $_POST['fecha_nacimiento'] ?? null;
    $genero            = $_POST['genero'] ?? '';
    $contrasena        = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $rol_id            = $_POST['rol_id'] ?? '';

    try {
        $pdo->beginTransaction();

        // Insertar en la tabla usuarios
        $sql_usuario = "INSERT INTO usuarios (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, fecha_nacimiento, genero, contraseña) 
                        VALUES (:nombre_completo, :cedula, :correo, :celular, :pais, :ciudad, :direccion, :fecha_nacimiento, :genero, :contrasena)";
        $stmt = $pdo->prepare($sql_usuario);
        $stmt->execute([
            ':nombre_completo'  => $nombre_completo,
            ':cedula'           => $cedula,
            ':correo'           => $correo,
            ':celular'          => $celular,
            ':pais'             => $pais,
            ':ciudad'           => $ciudad,
            ':direccion'        => $direccion,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':genero'           => $genero,
            ':contrasena'       => $contrasena
        ]);
        $usuario_id = $pdo->lastInsertId();

        // Insertar en la tabla usuario_roles
        $sql_usuario_roles = "INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (:usuario_id, :rol_id)";
        $stmt = $pdo->prepare($sql_usuario_roles);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':rol_id'     => $rol_id
        ]);

        // Según el rol, insertar en la tabla correspondiente
        if ($rol_id == "1") { // Paciente
            $ocupacion                 = $_POST['ocupacion'] ?? '';
            $estado_civil              = $_POST['estado_civil'] ?? '';
            $contacto_emergencia       = $_POST['contacto_emergencia'] ?? '';
            $telefono_emergencia       = $_POST['telefono_emergencia'] ?? '';
            $antecedentes_familiares   = $_POST['antecedentes_familiares'] ?? '';
            $antecedentes_personales   = $_POST['antecedentes_personales'] ?? '';
            $antecedentes_psiquiatricos = $_POST['antecedentes_psiquiatricos'] ?? '';
            $estado_actual             = $_POST['estado_actual'] ?? '';
            $riesgos                   = $_POST['riesgos'] ?? '';
            $motivo_consulta           = $_POST['motivo_consulta'] ?? '';

            $sql_paciente = "INSERT INTO pacientes (
                                usuario_id, 
                                ocupacion, 
                                estado_civil, 
                                contacto_emergencia, 
                                telefono_emergencia, 
                                antecedentes_familiares, 
                                antecedentes_personales, 
                                antecedentes_psiquiatricos, 
                                estado_actual, 
                                riesgos, 
                                motivo_consulta
                             ) VALUES (
                                :usuario_id, 
                                :ocupacion, 
                                :estado_civil, 
                                :contacto_emergencia, 
                                :telefono_emergencia, 
                                :antecedentes_familiares, 
                                :antecedentes_personales, 
                                :antecedentes_psiquiatricos, 
                                :estado_actual, 
                                :riesgos, 
                                :motivo_consulta
                             )";
            $stmt = $pdo->prepare($sql_paciente);
            $stmt->execute([
                ':usuario_id'              => $usuario_id,
                ':ocupacion'               => $ocupacion,
                ':estado_civil'            => $estado_civil,
                ':contacto_emergencia'     => $contacto_emergencia,
                ':telefono_emergencia'     => $telefono_emergencia,
                ':antecedentes_familiares' => $antecedentes_familiares,
                ':antecedentes_personales' => $antecedentes_personales,
                ':antecedentes_psiquiatricos' => $antecedentes_psiquiatricos,
                ':estado_actual'           => $estado_actual,
                ':riesgos'                 => $riesgos,
                ':motivo_consulta'         => $motivo_consulta
            ]);
            $rolNombre = "Paciente";

        } elseif ($rol_id == "2") { // Profesional
            $numero_tarjeta_profesional = $_POST['numero_tarjeta_profesional'] ?? '';
            $especialidad               = $_POST['especialidad'] ?? '';
            $anios_experiencia          = $_POST['años_experiencia'] ?? 0;

            // Asignar valores por defecto para certificaciones y pacientes_atendidos
            $certificaciones            = ''; // Deja vacío el campo de certificaciones
            $pacientes_atendidos        = 0;  // Valor predeterminado para pacientes atendidos

            $sql_profesional = "INSERT INTO profesionales (
                                    usuario_id, 
                                    numero_tarjeta_profesional, 
                                    especialidad, 
                                    años_experiencia, 
                                    certificaciones, 
                                    pacientes_atendidos
                                ) VALUES (
                                    :usuario_id, 
                                    :numero_tarjeta_profesional, 
                                    :especialidad, 
                                    :anios_experiencia, 
                                    :certificaciones, 
                                    :pacientes_atendidos
                                )";
            $stmt = $pdo->prepare($sql_profesional);
            $stmt->execute([
                ':usuario_id'                 => $usuario_id,
                ':numero_tarjeta_profesional' => $numero_tarjeta_profesional,
                ':especialidad'               => $especialidad,
                ':anios_experiencia'          => $anios_experiencia,
                ':certificaciones'            => $certificaciones,
                ':pacientes_atendidos'        => $pacientes_atendidos
            ]);
            $rolNombre = "Profesional";

        } elseif ($rol_id == "3") { // Familiar
            $paciente_id = $_POST['paciente_id'] ?? '';
            $parentesco  = $_POST['parentesco'] ?? '';

            $sql_familiar = "INSERT INTO familiares (usuario_id, paciente_id, parentesco) 
                             VALUES (:usuario_id, :paciente_id, :parentesco)";
            $stmt = $pdo->prepare($sql_familiar);
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':paciente_id'=> $paciente_id,
                ':parentesco' => $parentesco
            ]);
            $rolNombre = "Familiar";
        } else {
            $rolNombre = "Usuario";
        }

        $pdo->commit();

        // Mostrar mensaje de éxito y redirigir a login mediante JavaScript
        echo "<script>
                alert('¡Registro exitoso! Se registró como: $rolNombre. Inicie sesión.');
                window.location.href = 'login.php';
              </script>";
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error al registrar: " . $e->getMessage());
    }
}
?>
