<?php

require_once("funciones.php");


if(!permiso($perfil,"usuarios")){
  echo "Permisos insuficientes";
  exit;
}


$sql = "select U.id as id,
               U.nombre as nombre,
               U.apellido as apellido,
               concat(U.nombre,' ',U.apellido) as nombreapellido,
               U.email as email,
               U.telegram as telegram,
               U.telefono as telefono,
               U.impresoras as impresoras,
               U.fecha_login as ultimo_ingreso,
               if(activo=1,'Si','No') as activo,
               U.activo as activo_id,
               P.nombre_abr as perfil,
               U.perfil as perfil_id,
               U.direccion as direccion,
               D.nombre as departamento,
               U.departamento_id as departamento_id
          from users as U,
               departamentos as D,
               perfiles as P
         where U.perfil = P.perfil AND
               U.departamento_id = D.id;";

$Result = mysqli_query($mysqli_link,$sql);
$rows = mysqli_fetch_all($Result,MYSQLI_ASSOC);  

$array = Array("data" => $rows);

echo json_encode($array);  


?>



