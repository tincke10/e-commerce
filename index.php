<?php
session_start();
require_once 'db.php';
include 'count.php';

// Verifico si el usuario inicio sesion correctamente, si no lo redirijo a login.php
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

if (!isset($_SESSION['rol'])) {
  echo "rol_id no está definido en la sesión";
  exit;
}

$rol_id = $_SESSION['rol'];

$sql = "SELECT * FROM roles WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $rol_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result -> num_rows > 0) {
  $rol = $result -> fetch_assoc()['rol'];
  $_SESSION['rol'] = $rol;
}
            
 ?>

<!DOCTYPE html>
<html lang="en">  
  <head>
    <!-- Open Graph Meta-->    
    <title>Panel Admin ISSD TP2</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo" href="index.php">Mi Panel</a>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <li class="app-search">
          <input class="app-search__input" type="search" placeholder="Search">
          <button class="app-search__button"><i class="fa fa-search"></i></button>
        </li>
        
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="#"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
            <li><a class="dropdown-item" href="#"><i class="fa fa-user fa-lg"></i> Profile</a></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <!-- traigo el componente de perfil de usuario -->
    <?php include 'profileuser.php'; ?>
        <ul class="app-menu">
            <li><a class="app-menu__item active" href="index.php"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Inicio</span></a></li>
    
            <li><a class="app-menu__item" href="carga.php"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Registro</span></a></li>
    
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Listados</span><i class="treeview-indicator fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a class="treeview-item" href="listado.php"><i class="icon fa fa-circle-o"></i> Listado de solicitudes</a></li>
              </ul>
            </li>
          </ul>
      </aside>
    <!-- fin Sidebar menu-->

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Bienvenidos</h1>
          <p>Este es nuestro panel de administración. 
            Por favor selecciona alguna opción del menú lateral izquierdo</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
        </ul>
      </div>


      <div class="row">
        <div class="col-md-3">
          <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
            <div class="info">
              <h4>Usuarios</h4>
              <p><b><?php echo ($usuarioscreados) ?></b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
            <div class="info">
              <h4>Solicitudes Desarrollo</h4>
              <p><b><?php echo ($developer) ?></b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
            <div class="info">
              <h4>Solicitudes de Soporte Tecnico</h4>
              <p><b><?php echo ($support) ?></b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small danger coloured-icon"><i class="icon fa fa-star fa-3x"></i>
            <div class="info">
              <h4>Reportes de errores</h4>
              <p><b><?php echo ($errores)  ?></b></p>
            </div>
          </div>
        </div> 
      </div>
     
    </main>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    
  </body>
</html>