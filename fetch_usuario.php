<?php

require_once("funciones.php");


if(!permiso($perfil,"usuarios")){
  echo "Permisos insuficientes";
  exit;
}

if(isset($_REQUEST["usuario"]))  
{
      $usuario = validar($_REQUEST["usuario"]);
      $sql = "select * from users where id='$usuario'";
      $Result = mysqli_query($mysqli_link,$sql);
      $row = mysqli_fetch_array($Result);  
      echo json_encode($row);  
}  



?>



