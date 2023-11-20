<?php 
session_start();
require_once 'db.php';
// Verifico si el usuario inicio sesion correctamente, si no lo redirijo a login.php
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;

}
$usuario = $_SESSION['name'] . " " . $_SESSION['surname'];
$rol = $_SESSION['rol'];
echo $rol;

// Iniciar la estructura de control switch
switch ($rol) {
  case 'administrador':
      $sql = "SELECT solicitudes.*, namesolicitudes.name AS nombre_solicitud FROM solicitudes LEFT JOIN namesolicitudes ON solicitudes.tipo_solicitud = namesolicitudes.id ORDER BY fecha_carga ASC";
      $countSql = "SELECT COUNT(*) as count FROM solicitudes";
      $titulo = "Listado total de Solicitudes";
      break;
  case 'usuario normal':
        $sql = "SELECT solicitudes.*, namesolicitudes.name AS nombre_solicitud FROM solicitudes LEFT JOIN namesolicitudes ON solicitudes.tipo_solicitud = namesolicitudes.id WHERE usuario = ? ORDER BY fecha_carga ASC";
        $countSql = "SELECT COUNT(*) as count FROM solicitudes WHERE usuario = ?";
        $titulo = "Listado de mis solicitudes cargadas";
        break;
  case 'soporte técnico':
      $sql = "SELECT solicitudes.*, namesolicitudes.name AS nombre_solicitud FROM solicitudes LEFT JOIN namesolicitudes ON solicitudes.tipo_solicitud = namesolicitudes.id WHERE tipo_solicitud = 3 ORDER BY fecha_carga ASC";
      $countSql = "SELECT COUNT(*) as count FROM solicitudes WHERE tipo_solicitud = 3";
      $titulo = "Listado de Solicitudes cargadas";
      break;
  case 'analista':
      $sql = "SELECT solicitudes.*, namesolicitudes.name AS nombre_solicitud FROM solicitudes LEFT JOIN namesolicitudes ON solicitudes.tipo_solicitud = namesolicitudes.id WHERE tipo_solicitud IN (1, 2) ORDER BY fecha_carga ASC";
      $countSql = "SELECT COUNT(*) as count FROM solicitudes WHERE tipo_solicitud IN (1, 2)";
      $titulo = "Listado de Solicitudes cargadas";
      break;
  default:
      echo "Rol no reconocido";
      exit;
}

$stmt = $mysqli->prepare($sql);

if ($rol == 'usuario normal') {
  $stmt->bind_param("s", $usuario); 
}

$stmt->execute();

$result = $stmt->get_result();

// Prepare and execute the count query
$countStmt = $mysqli->prepare($countSql);

if ($rol == 'usuario normal') {
  $countStmt->bind_param("s", $usuario); 
}

$countStmt->execute();

$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$count = $countRow['count'];

$titulo .= " (" . $count . ")";

$stmt->close();
$countStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
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
    <?php include 'profileuser.php'; ?>
      <ul class="app-menu">
        <li><a class="app-menu__item active" href="index.php"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Inicio</span></a></li>

        <li><a class="app-menu__item" href="carga.php"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Registro</span></a></li>

        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Listados</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
          </ul>
        </li>
      </ul>
    </aside>
    <!-- fin Sidebar menu-->
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Listados</h1>
          <!-- si es administrador vera este titulo-->
          <p>Listado total de solicitudes</p>
  
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Listado</li>
          <li class="breadcrumb-item active"><a href="#">Listado de Solicitudes</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title"><?php echo ($titulo)?></h3>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Registro</th>
                    <th>Fecha estimada</th>
                    <th>Solicitante</th>
                    <th>Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Recorrer cada registro
                  while ($row = $result->fetch_assoc())
                  {
                  // Determinar la clase CSS de la fila según el tipo de solicitud
                  switch ($row['nombre_solicitud']) {
                  case 'Desarrollo de nuevas funcionalidades':
                  $class = 'table-info';
                  break;
                  case 'Soporte técnico':
                  $class = 'table-danger';
                  break;
                  case 'Reporte de errores':
                  $class = 'table-warning';
                  break;
                  default:
                  $class = '';
                  break;
                  }
                  // Solo aplico la clase si $class no está vacío
                  if ($class !== '') {
                  echo '<tr class="' . $class . '">';
                  } else {
                  echo '<tr>';
                  }

                  echo '<tr class="' . $class . '">'; // Aplicar la clase CSS a la fila
                  echo '<td>' . $row['id'] . '</td>';
                  echo '<td>' . $row['titulo'] . '</td>';
                  echo '<td>' . $row['descripcion'] . '</td>';
                  echo '<td>' . $row['nombre_solicitud'] . '</td>'; // Mostrar el nombre de la solicitud en lugar del número
                  echo '<td>' . $row['fecha_carga'] . '</td>';
                  echo '<td>' . $row['fecha_resolucion'] . '</td>';
                  echo '<td>' . $row['usuario'] . '</td>';
                  echo '<td><a href="#">Ver detalles...</a><a href="#"><i class="app-menu__icon fa fa-cog"></i>Eliminar...</a></td>';
                  echo '</tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        
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