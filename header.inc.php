<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="AdminLTE/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="AdminLTE/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <link rel="stylesheet" href="AdminLTE/plugins/daterangepicker/daterangepicker.css">

  <style type="text/css" class="init">
    td.details-control {
	    background: url('details_open.png') no-repeat center center;
	    cursor: pointer;
      width: 30px;
    }
    tr.shown td.details-control {
	    background: url('details_close.png') no-repeat center center;
    }
	</style>

</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
   
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
   
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <img src="<?php echo $googleImage;?>" style="width:36px;border-radius:50%;">
        </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  <?php echo $googleEmail; ?>
                </h3>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="login.php?logout=1" class="dropdown-item dropdown-footer">Cerrar Sesi&oacute;n</a>
        </div>
      </li>
    </ul>
    
    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="index.php" class="logo" style="display:block; text-align:center; padding:8px;">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="redondo_covid.png" ></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <?php if(permiso($perfil,"inicio")){ ?>
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>Inicio</p>
            </a>
          </li>
          <?php } ?>
          
          <?php if(permiso($perfil,"realizar_pedido")){ ?>
          <li class="nav-item">
            <a href="form_entrada.php" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>Realizar Pedido</p>
            </a>
          </li>
          <?php } ?>

          <?php if(permiso($perfil,"realizar_envio")){ ?>
          <li class="nav-item">
            <a href="form_salida.php" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>Realizar Envío</p>
            </a>
          </li>
          <?php } ?>

          <?php if(permiso($perfil,"totales_usuario")){ ?>
          <li class="nav-item">
            <a href="totales.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Totales/Usuario</p>
            </a>
          </li>
          <?php } ?>
          
          <?php if(permiso($perfil,"historial")){ ?>
            <li class="nav-item">
             <a href="movimientos.php" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>Historial</p>
             </a>
            </li>
          <?php } ?>  

          <?php if(permiso($perfil,"insumos")){ ?>
            <li class="nav-item">
              <a href="stock.php" class="nav-link">
                <i class="nav-icon fas fa-archive"></i>
                <p>Insumos</p>
              </a>
            </li>
          <?php } ?>

          <?php if(permiso($perfil,"usuarios")){ ?>
            <li class="nav-item">
              <a href="usuarios.php" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>Usuarios</p>
              </a>
            </li>
          <?php } ?>

          <?php if(permiso($perfil,"entregas")){ ?>
            <li class="nav-item">
              <a href="entregas.php" class="nav-link">
                <i class="nav-icon fas fa-shipping-fast"></i>
                <p>Entregas</p>
              </a>
            </li>
          <?php } ?>
          
          <?php if(permiso($perfil,"perfil")){ ?>
          <li class="nav-item">
            <a href="perfil.php" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>Perfil</p>
            </a>
          </li>
          <?php } ?>

          <?php if(permiso($perfil,"graficos")){ ?>
            <li class="nav-item">
              <a href="graficos.php" class="nav-link">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>Graficos</p>
              </a>
            </li>
          <?php } ?>
          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
