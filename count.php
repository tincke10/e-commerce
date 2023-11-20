<?php 
require_once 'db.php';

$result = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM users");
$row = mysqli_fetch_assoc($result);
$usuarioscreados = $row['count'];

  
$result2 = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM solicitudes WHERE tipo_solicitud = 1");
$row2 = mysqli_fetch_assoc($result2);
$developer = $row2['count'];


$result3 = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM solicitudes WHERE tipo_solicitud = 3");
$row3 = mysqli_fetch_assoc($result3);
$support = $row3['count'];

$result4 = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM solicitudes WHERE tipo_solicitud = 2");
$row4 = mysqli_fetch_assoc($result4);
$errores = $row4['count'];


?>