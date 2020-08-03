<?php

require_once("funciones.php");



if(!permiso($perfil,"insumos")){
  echo "Permisos insuficientes";
  exit;
}
   

if( $_POST["aceptar"] ){

  $insumo_id   = validar($_POST["insumo_id"]); 
  $cantidad    = validar($_POST["cantidad"]);
  $comentario  = validar($_POST["comentario"]);
  
  if ($cantidad > 0) {
    $sql = "Insert into movimientos_insumos (insumo_id,cantidad,user_id,comentario)
                                values   ('$insumo_id','$cantidad','$userid','$comentario')";
    $Result = mysqli_query($mysqli_link,$sql);
    if($Result){
        $mensajeok = "Ingreso de insumo registrado correctamente";
        $sql2 = "update insumos set cantidad = cantidad + $cantidad
                        where id = '$insumo_id' ";
        $Result2 = mysqli_query($mysqli_link,$sql2);
    }else{
        $mensaje = "Error registrando el insumo";
    }
  }else{
    $mensaje = "Error valor incorrecto para cantidad";
  }
}

if( $_POST["guardar"] ){

  $activo   = validar($_POST["activo"]);
  $id       = validar($_POST["id"]);

  if($activo == "on") {
     $activo = '1';
  }else{
     $activo = '0';
  }
  $sql = "update insumos set activo='$activo'
                       where id='$id'";
  $Result = mysqli_query($mysqli_link,$sql);
  if($Result){
     $mensajeok = "Insumo modificado correctamente";
  }else{
     $mensaje = "Error";
  }
}



Mostrar();
exit;


// *******************************************************************************
// *******************************************************************************




function mostrar(){
   
   global $perfil;
   global $mysqli_link;
   global $mensaje, $mensajeok;
   global $googleEmail,$googleImage,$googleName;

   $insumo_id = validar($_POST["insumo_id"]);

   include("header.inc.php");

   
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Insumos</h1>
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    
    
    <section class="content">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">

              <?php
              if(isset($mensaje)){
              echo '<div class="alert alert-danger alert-dismissable divider shake wow" id="alert" data-wow-duration="2s" >
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p><i class="fa fa-ban"></i> '.$mensaje.'</p>
                    </div>';
              }
              if(isset($mensajeok)){
              echo '<div class="alert alert-success alert-dismissable divider shake wow" id="alert" data-wow-duration="2s" >
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p><i class="fa fa-check"></i> '.$mensajeok.'</p>
                    </div>';
              }
              ?>

              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-nuevo">
                  Nuevo Ingreso de Insumo
              </button>
            
            </div>

            <div class="card-body">

              <table id="tablestock" class="display table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Insumo</th>
                  <th>Cantidad</th>
                  <th>Activo</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  <?php                  
                      $sql = "select id,nombre,cantidad,activo from insumos order by nombre";
                      $Result = mysqli_query($mysqli_link,$sql);
                      while( $Reg = mysqli_fetch_row($Result) ) {
                        echo "<tr>";
                        echo "<td>".$Reg[1]."</td>";
                        echo "<td>".($Reg[2] * 1)."</td>";
                        echo "<td>";
                          if($Reg[3]) {echo "Si"; }
                          else { echo "No";}
                        echo "</td>";
                        echo "<td><div class='btn-group btn-group-sm'>
                                    <button type='button' class='btn btn-info edit-insumo' id='".$Reg[0]."' data-toggle='modal' data-target='#modal-edicion'>
                                    <i class='fas fa-edit'></i></button>
                                    </div></td>";
                        echo "</tr>";
                      }
                  ?>
                </tbody>
              </table>
            
            </div><!-- /.card-body -->
          </div><!-- /.card -->     

        </div><!-- /.col -->
      </div><!-- /.row -->  

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3>Movimientos de insumos</h3>
            </div>
            <div class="card-body">
              <table id="tablemovimientos" class="display table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Insumo</th>
                  <th>Cantidad</th>
                  <th>Usuario</th>
                  <th>Comentario</th>
                </tr>
                </thead>
                <tbody>
                  <?php                  
                     $sql = "select M.id,
                                    M.fecha,
                                    I.nombre,
                                    M.cantidad,
                                    concat(U.nombre,' ',U.apellido,' (',U.telegram,')'),
                                    M.comentario 
                              from  movimientos_insumos as M,
                                    insumos as I,
                                    users as U
                              where M.insumo_id = I.id  and
                                    M.user_id = U.id      
                              order by M.fecha desc";

                     $Result = mysqli_query($mysqli_link,$sql);
                     while( $Reg = mysqli_fetch_row($Result) ) {
                        echo "<tr>";
                        echo "<td>".$Reg[1]."</td>";
                        echo "<td>".$Reg[2]."</td>";
                        echo "<td>".($Reg[3] * 1)."</td>";
                        echo "<td>".$Reg[4]."</td>";
                        echo "<td>".$Reg[5]."</td>";
                        echo "</tr>";
                     }
                  ?>
                </tbody>
              </table>
            </div><!-- /.card-body -->
          </div><!-- /.card -->     
        </div><!-- /.col -->
      </div><!-- /.row -->
    </section>

  </div>
  <!-- /.content-wrapper -->



  <div class="modal fade" id="modal-nuevo">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Ingreso de insumo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" action="stock.php" method="post">
              <div class="form-group">
                <label>Insumo:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <select class="custom-select" name="insumo_id" id="insumo_id" required>
                    <?php
                        $sql = "select id,nombre from insumos where activo = '1' order by nombre";
                        $Result = mysqli_query($mysqli_link,$sql);
                        while( $Reg = mysqli_fetch_row($Result) ) {
                          echo "<option value='".$Reg[0]."'>".$Reg[1]."</option>";
                        }
                    ?>                           
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="nombre">Cantidad</label>
                <input type="text" name="cantidad" class="form-control" id="cantidad" placeholder="Cantidad"  required >
              </div>
              <div class="form-group">
                <label for="nombre">Comentario</label>
                <input type="text" name="comentario" class="form-control" id="comentario" placeholder="Comentario"  >
              </div>
            </div>
            <!-- /.card-body -->
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
              <button type="submit" name="aceptar" value="1" class="btn btn-primary">Aceptar</button>
            </div>
          </form>               
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->



  <div class="modal fade" id="modal-edicion">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Editar Insumo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" action="stock.php" method="post">
                <input type="hidden" name="id" id="EditId">
                <div class="card-body">                 
                  <div class="form-check">
                    <input type="checkbox" name="activo" class="form-check-input" id="EditActivo">
                    <label class="form-check-label" for="EditActivo">Activo</label>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                  <button type="submit" name="guardar" value="1" class="btn btn-primary">Guardar Cambios</button>
                </div>
              </form>               
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->





<?php

   include("footer.inc.php");
   
   ?>
   <script src="AdminLTE/plugins/moment/moment.min.js"></script>
   <script src="AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
   <script src="AdminLTE/plugins/select2/js/select2.full.min.js"></script>
   <script src="AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
   <script src="AdminLTE/plugins/datatables/jquery.dataTables.js"></script>
   <script src="AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
   
  <script>
  $(function () {

      $('#tablestock').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": true,
    });

    $('#tablemovimientos').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": true,
    });

  });


  (function() {
  'use strict';
   window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
  })();


  $(document).ready(function(){
   $(document).on('click', '.edit-insumo', function(){  
            var id = $(this).attr("id");  
            $.ajax({  
                 url:"fetch_insumo.php",  
                 method:"POST",  
                 data:{id:id},  
                 dataType:"json",  
                 success:function(data){  
                      $('#EditId').val(data.id);  
                      if(data.activo == '1'){
                         $('#EditActivo').attr('checked',true);
                      }else{
                         $('#EditActivo').attr('checked',false);
                      }
                 }  
            });  
       });   
   });

  </script>
  
   
  </body>
  </html>
  <?php
   
}

?>

