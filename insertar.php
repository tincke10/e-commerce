<?php
session_start();
require_once 'db.php';
var_dump($_POST);
$usuario = $_SESSION['name'] . ' ' . $_SESSION['surname'];

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$tipo_solicitud = isset($_POST['tipo_solicitud']) ? $_POST['tipo_solicitud'] : '';

if (empty($titulo) || empty($descripcion) || empty($tipo_solicitud)) {
    echo 'Por favor, rellena todos los campos.';
    exit;
}

if (strlen($titulo) < 5 || strlen($descripcion) < 5) {
    echo 'El título y la descripción deben contener al menos 5 caracteres cada uno.';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $tipo_solicitud = $_POST['tipo_solicitud'];
    $fecha_carga = date("Y-m-d H:i:s");

    switch ($tipo_solicitud) {
        case 1: // Desarrollo de nuevas funcionalidades
            $fecha_resolucion = date('Y-m-d', strtotime($fecha_carga. ' + 7 days'));
            break;
        case 2: // Reporte de errores
            $fecha_resolucion = date('Y-m-d', strtotime($fecha_carga. ' + 3 days'));
            break;
        case 3: // Soporte técnico
            $fecha_resolucion = date('Y-m-d', strtotime($fecha_carga. ' + 1 day'));
            break;
    }
    
    $sql = "INSERT INTO solicitudes (titulo, descripcion, tipo_solicitud, usuario, fecha_carga, fecha_resolucion) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssisss", $titulo, $descripcion, $tipo_solicitud, $usuario, $fecha_carga, $fecha_resolucion);
        if ($stmt->execute()) {
            echo 'Carga exitosa.';
        } else {
            echo 'Error en la carga.';
        }
        exit;
        }
    }
?>