<?php

require_once("funciones.php");



if(!permiso($perfil,"historial")){
  echo "Permisos insuficientes";
  exit;
}

if(isset($_REQUEST["id"]))  
{
  $id = validar($_REQUEST["id"]);
  $sql = "select * from movimientos where id='$id'";
  $Result = mysqli_query($mysqli_link,$sql);
  $row = mysqli_fetch_array($Result);  
  echo json_encode($row);  
}  



?>



