<?php

include("config.php");
session_set_cookie_params($config['session_time']); 
session_name($config['session_name']);
session_start();


if (!Conectar()) {
  exit;
}

if($_GET["token"]){
  $_SESSION["token"] = $_GET["token"];
}

if( $_SESSION["token"] ) {
  $userDetail = file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=".$_SESSION["token"]);
  $userData = json_decode($userDetail);
  if(!empty($userData)){
    $googleEmail = $userData->email;
    $googleImage = $userData->picture;
    $googleName  = $userData->name;
    $sql = "select id,perfil from users where email='$googleEmail' and activo ='1' ";
    $Result = mysqli_query($mysqli_link,$sql);
    $Reg = mysqli_fetch_row($Result);
    $userid = $Reg[0];
    $perfil = $Reg[1];
    if (mysqli_num_rows($Result) == 0) {
      echo "El usuario no esta activo, contacte con el Administrador<br>";
      echo "<a href='login.php?logout=1'>volver</a>";
      exit;
    }else{
      $sql = "update users set fecha_login=NOW() where email='$googleEmail'; ";
      $Result = mysqli_query($mysqli_link,$sql);
    }
  }else{
    echo "Login fail<br>";
    echo "<a href='login.php?logout=1'>volver</a>";
    exit;
  }
}else{
  header('Location: login.php');
  exit;
}




function Conectar(){

  global $config, $mensaje, $mysqli_link;

  $mysqli_link=mysqli_connect($config['db_servidor'],$config['db_usuario'],$config['db_password']);

  if(!$mysqli_link){
    $mensaje = "Error: error de conexion con mysql";
    return False;
  }
  if( mysqli_select_db($mysqli_link,$config['db_db']) ){
    mysqli_set_charset($mysqli_link,'utf8');
    return $mysqli_link;
  }
  $mensaje = "Error: error accediendo a la base de datos";
  return False;

}

function validar($string){
  // filtro de caracteres enviados por POST

  $allowed = "/[^a-zA-Z0-9\\.\\-\\_ \\,\\)\\(\\[\\]\\@\\á\\é\\í\\ó\\ú\\ñ\\Ñ\\!\\?\\$\\:]/i";
  return preg_replace($allowed," ",$string);
}

function permiso($perfil,$permiso){
  // determina si el perfil tiene permisos para el menu

  global $mysqli_link;

  $sql = "select $permiso from perfiles_permisos where perfil_id='$perfil' ";
  $Result = mysqli_query($mysqli_link,$sql);
  if(!$Result){
    return false;
  }else{
    $Reg = mysqli_fetch_row($Result);
    if($Reg[0] == '1'){
      return true;
    }else{
      return false;
    }
  }

}



 ?>
