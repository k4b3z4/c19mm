<?php

require_once("funciones.php");


if(!permiso($perfil,"usuarios")){
  echo "Permisos insuficientes";
  exit;
}



if( $_POST["aceptar"] and validar($_POST["email"]) ){
   
   
  $email    = validar($_POST["email"]);
  if($_POST["activo"] == 'on') $activo = '1';
  else $activo = '0';
  
  $sql = "Insert into users (email,perfil,activo)
                    values   ('$email','0','$activo')";
  $Result = mysqli_query($mysqli_link,$sql);
  if($Result){
      $mensajeok = "Usuario agregado correctamente";
  }else{
      $mensaje = "No puede agregarse el usuario $email <br>";
  }
   
   
}



if( $_POST["guardar"] ){
  
  $nombre   = validar($_POST["nombre"]);
  $apellido = validar($_POST["apellido"]);
  $telegram = validar($_POST["telegram"]);
  $email    = validar($_POST["email"]);
  $perfil_  = validar($_POST["perfil"]);
  $activo   = validar($_POST["activo"]);
  $direccion= validar($_POST["direccion"]);
  $telefono = validar($_POST["telefono"]);
  $departamento_id = validar($_POST["departamento_id"]);
  $id       = validar($_POST["id"]);

  if($activo == "on") {
     $activo = '1';
  }else{
     $activo = '0';
  }
  
  $sql = "update users set nombre='$nombre',
                           apellido='$apellido',
                           telegram='$telegram',
                           email='$email',
                           perfil='$perfil_',
                           activo='$activo',
                           direccion='$direccion',
                           telefono='$telefono',
                           departamento_id='$departamento_id' 
                     where id='$id'";
  $Result = mysqli_query($mysqli_link,$sql);

  if($Result){
     $mensajeok = "Usuario modificado correctamente";
  }else{
     $mensaje = "Error modificando el usuario";
  }
  
}



Mostrar();
exit;


// *******************************************************************************
// *******************************************************************************



function mostrar(){
   
   global $userid, $perfil, $mysqli_link;
   global $mensaje;
   global $mensajeok;
   global $googleEmail,$googleImage,$googleName;

   

   include("header.inc.php");

?>
  <style type="text/css" class="init">
    td.edit-control { 
      background: url('Edit_font_awesome.svg') no-repeat center center;
	    cursor: pointer;
      width: 30px;
    }
	</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Administraci&oacute;n de Usuarios</h1>
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


 <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-12">
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
        </div>
      </div>


      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
               <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-nuevo">
                  Nuevo Usuario
               </button>
            </div>

             <div class="card-body">
              <table id="tableusuarios" class="display table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th></th>
                    <th>Nombre Apellido</th>
                    <th>Email</th>
                    <th>Telegram</th>
                    <th>Imp</th>
                    <th>Ultimo Ingreso</th>
                    <th>Perfil</th>
                    <th>Activo</th>
                    <th></th>
                  </tr>
                </thead>
                
                  
                  <?php
                  
                    //    echo "<td><div class='btn-group btn-group-sm'>
                    //              <button type='button' class='btn btn-info edit-user' id='".$Reg["0"]."' data-toggle='modal' data-target='#modal-edicion'>
                    //              <i class='fas fa-edit'></i></button>
                    //              </div></td>";
                    //    echo "</tr>";
                  
                  
                  ?>
                
                
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->     
         </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


      <div class="modal fade" id="modal-nuevo">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nuevo Usuario</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" action="usuarios.php" method="post">
                  <div class="form-group">
                    <label for="InputEmail">Email</label>
                    <input type="email" name="email" class="form-control" id="InputEmail" placeholder="email">
                  </div>
                  <div class="form-check">
                    <input type="checkbox" name="activo" class="form-check-input" id="InputCheck">
                    <label class="form-check-label" for="InputCheck">Activo</label>
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
              <h4 class="modal-title">Modificar Usuario</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" action="usuarios.php" method="post">
                <input type="hidden" name="id" id="EditId">

                <div class="card-body">
                  
                  <div class="form-group">
                    <label for="EditNombre">Nombre</label>
                    <input type="text" name="nombre" class="form-control" id="EditNombre" placeholder="nombre">
                  </div>

                  <div class="form-group">
                    <label for="EditApellido">Apellido</label>
                    <input type="text" name="apellido" class="form-control" id="EditApellido" placeholder="apellido">
                  </div>

                  <div class="form-group">
                    <label for="EditTelegram">Telegram</label>
                    <input type="text" name="telegram" class="form-control" id="EditTelegram" placeholder="telegram">
                  </div>

                  <div class="form-group">
                    <label for="EditEmail">Email</label>
                    <input type="text" name="email" class="form-control" id="EditEmail" placeholder="email">
                  </div>

                  <div class="form-group">
                    <label for="EditTelefono">Telefono</label>
                    <input type="text" name="telefono" class="form-control" id="EditTelefono" placeholder="telefono">
                  </div>

                  <div class="form-group">
                    <label for="EditDireccion">Direccion</label>
                    <input type="text" name="direccion" class="form-control" id="EditDireccion" placeholder="direccion">
                  </div>

                  <div class="form-group">
                    <label for="EditDepartamento">Departamento</label>
                    <select class="custom-select" name="departamento_id" id="EditDepartamento">
                       <?php
                           $sql = "select id,nombre from departamentos order by nombre";
                           $Result = mysqli_query($mysqli_link,$sql);
                           while( $Reg = mysqli_fetch_row($Result) ) {
                              echo "<option value='".$Reg[0]."' ";
                              echo ">".$Reg[1]."</option>";
                           }
                        ?>                           
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="EditPerfil">Perfil</label>
                    <select class="custom-select" name="perfil" id="EditPerfil">
                       <?php
                           $sql = "select perfil,nombre from perfiles order by perfil";
                           $Result = mysqli_query($mysqli_link,$sql);
                           while( $Reg = mysqli_fetch_row($Result) ) {
                              echo "<option value='".$Reg[0]."' ";
                              echo ">".$Reg[1]."</option>";
                           }
                        ?>                           
                    </select>
                  </div>

                  
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

<script src="AdminLTE/plugins/datatables/jquery.dataTables.js"></script>
<script src="AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<script>
  

  function format(d) {
    // `d` is the original data object for the row
      return '<table cellpadding="9" cellspacing="0" border="0" style="padding-left:50px;">'+
          '<tr>'+
              '<td>Direccion:</td>'+
              '<td>'+d.direccion+' ('+d.departamento+')</td>'+
          '</tr>'+
          '<tr>'+
              '<td>Teléfono:</td>'+
              '<td>'+d.telefono+'</td>'+
          '</tr>'+
      '</table>';
    }  
  
  $(document).ready(function(){

    var table = $('#tableusuarios').DataTable({
      "ajax": "fetch_usuarios.php",
      "columns": [
          {   "className":      'details-control',
              "orderable":      false,
              "data":           null,
              "defaultContent": ''},
          { "data": "nombreapellido" },
          { "data": "email" },
          { "data": "telegram" },
          { "data": "impresoras" },
          { "data": "ultimo_ingreso" },
          { "data": "perfil" },
          { "data": "activo",
            "orderable": false },
          { "className": 'edit-control',
            "orderable": false,
            "data": null,
            "defaultContent": ''}
      ],
      "order": [[5, 'desc']],
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": true,
    });
   

    $('#tableusuarios tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    });
   
    $('#tableusuarios tbody').on('click', 'td.edit-control', function(){  

      var tr = $(this).closest('tr');
      var data = table.row(tr).data();
      
      
      $('#EditId').val(data.id);  
      $('#EditNombre').val(data.nombre); 
      $('#EditApellido').val(data.apellido); 
      $('#EditTelegram').val(data.telegram); 
      $('#EditEmail').val(data.email);  
      $('#EditPerfil').val(data.perfil_id);  
      $('#EditDireccion').val(data.direccion);
      $('#EditTelefono').val(data.telefono);
      $('#EditDepartamento').val(data.departamento_id);
      if(data.activo_id == '1'){
          $('#EditActivo').attr('checked',true);
      }else{
          $('#EditActivo').attr('checked',false);
      }
        

      $('#modal-edicion').modal('toggle');

    });   
  
  
  });
  
</script>

</body>
</html>

<?php

}

?>



