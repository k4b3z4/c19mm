<?php

require_once("funciones.php");


if(!permiso($perfil,"inicio")){
  echo "Permisos insuficientes";
  exit;
} 


// CONFIRMAR REGISTRO DE ENTREGA
if( validar($_POST["confirmar-entrega"]) ) {
  $id = validar($_POST["confirmar-entrega"]);

  $sql="UPDATE  movimientos_entregas  set confirmado = NOW(),
                                          user_confirma = '$userid' 
                                      where id = '$id' AND
                                            user_salida = '$userid'; ";

  $Result = mysqli_query($mysqli_link,$sql);

  if(mysqli_affected_rows($mysqli_link)>0){
    $mensajeok = "Registro confirmado correctamente<br>";
  }else{
    $mensaje = "Error confirmando registro";
  } 
} 



// ELIMINAR REGISTRO ENTRADA/SALIDA
if( validar($_POST["eliminar-ingreso"]) ){
  $id = validar($_POST["eliminar-ingreso"]);
  $sql="DELETE from movimientos where id='$id' AND
                                      confirmado is NULL AND
                                      user_id='$userid' ";                                  
  $Result = mysqli_query($mysqli_link,$sql);
  if(mysqli_affected_rows($mysqli_link)>0){
    $mensajeok = "Registro eliminado correctamente<br>";
  }else{
    $mensaje = "Error eliminando registro";
  }
}

// CONFIRMAR REGISTRO ENTRADA/SALIDA
if( validar($_POST["confirmar-ingreso"]) ){
  $id = validar($_POST["confirmar-ingreso"]);
  if($perfil>0){
    // Admin
    $sql="UPDATE  movimientos set   confirmado = NOW(),
                                    user_confirma = '$userid' 
                              where id = '$id' AND
                                    IF(user_entrada = user_salida, user_id != $userid, 1); ";
  }else{
    // user
    $sql="UPDATE  movimientos set   confirmado = NOW(),
                                    user_confirma = '$userid' 
                              where id = '$id' AND
                                    user_id != '$userid'  ";
  }

  $Result = mysqli_query($mysqli_link,$sql);

  if(mysqli_affected_rows($mysqli_link)>0){
    $mensajeok = "Registro confirmado correctamente<br>";

    $sql = "select U.perfil,M.insumo_id,M.user_entrada,M.cantidad 
              from movimientos as M,
                   users as U
             where M.id='$id' and
                   M.user_salida = U.id ";

    $Result = mysqli_query($mysqli_link,$sql);
    $Reg = mysqli_fetch_row($Result);

    if( $Reg[0] == 1 or $Reg[0] == 2 or $Reg[0] == 3 ) {

      // 1 = Zonal
      // 2 = PERFIL administrador de stock
      // 3 = reparto

      // genero registro salida de insumo
      $sql2 = "insert into movimientos_insumos (insumo_id,user_id,cantidad,comentario)
                                        values ('".$Reg[1]."','".$Reg[2]."','-".$Reg[3]."','Salida de insumo')";
      $Result2 = mysqli_query($mysqli_link,$sql2);
      
      // descuento insumo del stok
      $sql3 = "update insumos set   cantidad = cantidad - ".$Reg[3]." 
                              where id='".$Reg[1]."';";
      $Result3 = mysqli_query($mysqli_link,$sql3);

      $mensajeok.= "El insumo fue descontado del STOCK.";
    }
  }else{
    $mensaje = "Error, no se puede confirmar el registro";
  }
}

// AGREGA REGISTRO DE ENTRADA

if( validar($_POST["confirmar-entrada"]) ){
   
  $remitente        = validar($_POST["remitente"]);
  $insumo           = validar($_POST["insumo"]);  
  $unidades         = validar($_POST["unidades"]);
  $comentario       = validar($_POST["comentario"]);

  if( ($remitente == $userid) and $perfil < 1 ){
      $mensaje = "Error: usuario origen y destino no pueden ser iguales";
  }
  if( floatval($unidades) == 0 ){
      $mensaje = "Error: unidades debe ser mayor que cero";
  }

  if(!isset($mensaje)){
    $sql = "INSERT into movimientos (user_id,user_entrada,user_salida,insumo_id,cantidad,confirmado,comentario)
                             values('$userid','$userid','$remitente','$insumo','$unidades',NULL,'$comentario')   ";

    $Result = mysqli_query($mysqli_link,$sql);
      
    if($Result){
      $mensajeok = "Datos registrados";
    }else{
      $mensaje = "Error: no fue posible registrar los datos";
    }
  }
}
   
// AGREGA REGISTRO DE SALIDA

if( $_POST["confirmar-salida"] ){
   
  $destinatario     = validar($_POST["destinatario"]);
  $insumo           = validar($_POST["insumo"]);
  $unidades         = validar($_POST["unidades"]);
  $comentario       = validar($_POST["comentario"]);

  if( ($destinatario == $userid) and $perfil < 1 ){
      $mensaje = "Error: usuario origen y destino no pueden ser iguales";
  }
  if( floatval($unidades) == 0 ){
      $mensaje = "Error: unidades debe ser mayor que cero";
  }

  if(!isset($mensaje)){
    $sql = "INSERT into movimientos (user_id,user_entrada,user_salida,insumo_id,cantidad,confirmado,comentario)
                             values('$userid','$destinatario','$userid','$insumo','$unidades',NULL,'$comentario')   ";

    $Result = mysqli_query($mysqli_link,$sql);
    if($Result){
      $mensajeok = "Datos registrados";
    }else{
      $mensaje = "Error: no fue posible registrar los datos";
    }
  }
}
   


Mostrar();
exit;

// *******************************************************************************
// *******************************************************************************

function mostrar(){
   
   global $userid, $perfil;
   global $mysqli_link;
   global $googleEmail,$googleImage,$googleName;
   global $mensajeok,$mensaje;

   include("header.inc.php");

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  
  <?php include("principal.php"); ?>
    
  </div>
  <!-- /.content-wrapper -->

<?php
   include("footer.inc.php");
?>

<script src="AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="AdminLTE/plugins/datatables/jquery.dataTables.js"></script>
<script src="AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="AdminLTE/plugins/pdfmake/pdfmake.min.js"></script>
<script src="AdminLTE/plugins/pdfmake/vfs_fonts.js"></script>
<script src="AdminLTE/plugins/jszip/jszip.min.js"></script>

<script>
  
  $(function () {
		$('#tableingresos').DataTable({
       "ordering": true,
			 dom: 'Bftip',
     /*   buttons: [
		 				'excelHtml5',
						'csvHtml5',
            'pdfHtml5'
        ]
      */
    });
    $('#tableegresos').DataTable({
       "ordering": true,
			 dom: 'Bftip',
      /*  buttons: [
						'excelHtml5',
						'csvHtml5',
            'pdfHtml5'
        ]
      */  
    });
  });
  
  $(document).ready(function(){

    $(document).on('click', '.del-ingreso', function(){  
      var id = $(this).attr("id");
      $('#eliminar-ingreso').val(id);
    });  

    $(document).on('click', '.conf-ingreso', function(){  
      var id = $(this).attr("id");
      $('#confirmar-ingreso').val(id);
    });   

    $(document).on('click', '.conf-entrega', function(){  
      var id = $(this).attr("id");
      $('#confirmar-entrega').val(id);
    });  
   
  });
  
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });


</script>


<?php
}
?>


