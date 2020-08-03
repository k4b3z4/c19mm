<?php

require_once("funciones.php");


if(!permiso($perfil,"totales_usuario")){
  echo "Permisos insuficientes";
  exit;
}


$data = Array();

if(isset($_REQUEST["id"]))  
{
  $id = validar($_REQUEST["id"]);

  $sql = "select id,nombre from insumos";
  $Result = mysqli_query($mysqli_link,$sql);

  $data['entrada'] = "<div class='row'>";
  $data['salida'] = "<div class='row'>";
  
  while ($row = mysqli_fetch_row($Result)) {

    // ENTRADA
    $sql2 = "SELECT if(sum(cantidad) is NULL, 0, sum(cantidad)) from movimientos
                                  where confirmado is not null AND
                                  insumo_id = '".$row[0]."' AND
                                  user_entrada = '$id'; ";

    $Result2 = mysqli_query($mysqli_link,$sql2);
    $row2 = mysqli_fetch_row($Result2);

    if ($row2[0] != 0) {
      $data['entrada'] .= "<div class='col-6'>".$row[1].":</div><div class='col-6'>".($row2[0] * 1)."</div>";
    }

    // SALIDA
    $sql2 = "SELECT if(sum(cantidad) is NULL, 0, sum(cantidad)) from movimientos
                                  where confirmado is not null AND
                                  insumo_id = '".$row[0]."' AND
                                  user_salida = '$id'; ";

    $Result2 = mysqli_query($mysqli_link,$sql2);
    $row2 = mysqli_fetch_row($Result2);

    if ($row2[0] != 0) {
      $data['salida'] .= "<div class='col-6'>".$row[1].":</div><div class='col-6'>".($row2[0] * 1)."</div>";
    }


  }  
  
  $data['entrada'] .= "</div>";
  $data['salida'] .= "</div>";

  echo json_encode($data);  
}  



?>



