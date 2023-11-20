<?php
require_once 'db.php';

$email = $_POST['email'];
$password = md5($_POST['password']); 

$sql = "SELECT * FROM users WHERE email = ? AND clave = ? AND active = 1";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $email, $password);

$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // inicializo la sesion y guardo los atributos que necesito en login.php
    session_start();
    $_SESSION['user'] = $user;
    $_SESSION['name'] = $user['name'];
    $_SESSION['surname'] = $user['surname'];
    $_SESSION['image'] = $user['image'];
    $_SESSION['rol'] = $user['rol'];
    // actualizo la ultima fecha de acceso
    $sql = "UPDATE users SET fecha_acceso = NOW() WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    // si los datos son correctos ingresa a  index.php
    header("Location: index.php");
    exit;
} else {
    // si los datos ingresados son incorrectos redirigo a login.php
    header("Location: login.php?error=Invalid email or password");
    exit;
}

?>