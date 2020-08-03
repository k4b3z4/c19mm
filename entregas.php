<?php

require_once("funciones.php");


if(!permiso($perfil,"entregas")){
  echo "Permisos insuficientes";
  exit;
}


if( $_POST["aceptar"] and validar($_POST["detalle"]) ){
  
  if($perfil == 4){

    $detalle      = validar($_POST["detalle"]);
    $fecha        = validar($_POST["fecha"]);
    $destinatario = validar($_POST["destinatario"]);
    $entidad      = validar($_POST["entidad"]);
    $cantidad     = validar($_POST["cantidad"]);
    
    $sql = "Insert into movimientos_entregas (fecha_acordada,detalle,user_id,user_salida,confirmado,entidad,cantidad)
                                    values   ('$fecha','$detalle','$userid','$destinatario',NULL,'$entidad','$cantidad')";

    $Result = mysqli_query($mysqli_link,$sql);
    if($Result){
        $mensajeok = "Registro agregado correctamente";
    }else{
        $mensaje = "No puede agregarse el registro";
    }

  }else{

    $mensaje = "Necesita Perfil nivel 4 (Coordinador Entregas) para realizar esta acción";
  }
   
}

// ELIMINAR REGISTRO ENTREGA
if( validar($_POST["eliminar-entrega"]) ){
  $id = validar($_POST["eliminar-entrega"]);
  $sql="DELETE from movimientos_entregas where id='$id' AND
                                         confirmado is NULL AND
                                         user_id='$userid' ";                                  
  $Result = mysqli_query($mysqli_link,$sql);
  if(mysqli_affected_rows($mysqli_link)>0){
    $mensajeok = "Registro eliminado correctamente<br>";
  }else{
    $mensaje = "Error eliminando registro";
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Registro de Entregas</h1>
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
                  Nueva Entrega
               </button>
            </div>

             <div class="card-body">
              <table id="tableentregas" class="display compact table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Fecha Acordada</th>
                  <th>Punto de Entrega</th>
                  <th>Entidad</th>
                  <th>Cantidad</th>
                  <th>Detalle</th>
                  <th>Confirmado</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  
                  <?php
                  
                     $sql = "select E.id,

                                    E.fecha_acordada,
                                    (select CONCAT(nombre,' ',apellido,' (',telegram,')') from users
                                           where E.user_salida = id ) as destino,
                                    E.entidad,
                                    E.cantidad,
                                    E.detalle,
                                    IF (E.confirmado is NULL,'NO',E.confirmado) as confirm,
                                    E.user_id,
                                    E.fecha
 
                                from movimientos_entregas as E
                               order by E.fecha desc";

                     $Result = mysqli_query($mysqli_link,$sql);
                     
                     while( $Reg = mysqli_fetch_row($Result) ) {
                        echo "<tr>";
                        echo "<td>".$Reg[8]."</td>";
                        echo "<td>".$Reg[1]."</td>";
                        echo "<td>".$Reg[2]."</td>";
                        echo "<td>".$Reg[3]."</td>";
                        echo "<td>".($Reg[4] * 1)."</td>";
                        echo "<td>".$Reg[5]."</td>";
                        echo "<td>".$Reg[6]."</td>";

                        echo "<td><div class='btn-group btn-group-sm'>";
                        if($Reg[6] == "NO" and $Reg[7] == $userid){
                          echo "<button type='button' class='btn btn-danger del-entrega' id='".$Reg["0"]."' 
                                   data-toggle='modal' data-target='#modal-delete'> 
                                  <i class='fas fa-trash'></i></button>";
                        }
                        if($Reg[6] == "NO" and $Reg[7] != $userid){
                          echo "<button type='button' class='btn btn-info' id='".$Reg["0"]."' 
                                   data-toggle='modal'> 
                                  <i class='fas fa-eye'></i></button>";
                        }
                        if($Reg[6] != "NO"){
                          echo "<button type='button' class='btn btn-success' > 
                                  <i class='fas fa-check'></i></button>";
                        }
                        echo "</div></td>";

                        echo "</tr>";
                     }
                  
                  ?>
                
                </tbody>
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




        <div class="modal modal-danger fade" id="modal-delete">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <p>Confirma Eliminar el Registro NO confirmado?</p>
              </div>
              <div class="modal-footer">
                <form role="form" action="entregas.php" method="post">
                  <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-outline" id="eliminar-entrega" name="eliminar-entrega" value="" >Eliminar</button>
                </form>
              </div>
            </div>
          </div>
        </div>



      <div class="modal fade" id="modal-nuevo">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nueva Entrega</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" action="entregas.php" method="post">


                <div class="form-group">
                  <label>Fecha Acordada:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="fecha" class="form-control float-right" id="datepicker">
                  </div>
                  <!-- /.input group -->
                </div>

                <div class="form-group">
                    <label>Punto de Entrega:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        
                        <select class="custom-select" name="destinatario" id="destinatario" required>
                        <?php

                            // solo perfiles > 0
                            $sql = "select id,
                                          if ( nombre != '' ,concat(nombre,' ',apellido,' (',telegram,')'),
                                               email) 
                                          from users
                                          where perfil > 0 
                                          order by nombre";

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
                  <label for="InputEntidad">Entidad</label>
                  <input type="text" name="entidad" class="form-control" id="InputEntidad" placeholder="Entidad" required>
                </div>

                <div class="form-group">
                  <label for="InputCantidad">Cantidad</label>
                  <input type="text" name="cantidad" class="form-control" id="InputCantidad" placeholder="Cantidad" required>
                </div>

                <div class="form-group">
                  <label for="InputDetalle">Detalle</label>
                  <textarea name="detalle" class="form-control" id="InputDetalle" placeholder="Detalle" required></textarea>
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

<?php

include("footer.inc.php");

?>

<script src="AdminLTE/plugins/datatables/jquery.dataTables.js"></script>
<script src="AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="AdminLTE/plugins/moment/moment.min.js"></script>
<script src="AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>

<script>
  
  $(function () {

    $(document).on('click', '.del-entrega', function(){  
      var id = $(this).attr("id");
      $('#eliminar-entrega').val(id);
    });  

    $('#tableentregas').DataTable({
      "columns": [
            {  },
            {  },
            {  },
            {  },
            {  },
            {  },
            {  },
            { "orderable":      false, }
        ],
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "order": [[ 1, "desc" ]],
      "info": false,
      "autoWidth": true,
    });

    $('#datepicker').daterangepicker({
     "singleDatePicker": true,
     "timePicker": true,
     "timePicker24Hour": true,
     "showDropdowns": true,
     "startDate": moment(),
     "minYear": 2000,
     "maxYear": parseInt(moment().format('YYYY'),10),
     "locale": {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
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


</script>

</body>
</html>

<?php

}

?>



