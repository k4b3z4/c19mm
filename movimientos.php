<?php

require_once("funciones.php");



if(!permiso($perfil,"historial")){
  echo "Permisos insuficientes";
  exit;
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
            <h1>Historial movimientos</h1>
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
            

               <form role="form" name="form1" action="movimientos.php" method="post">
                <div class="card-body">
                <label>Insumo:</label>
                <select class="custom-select" name="insumo_id" id="insumo_id" 
                        onchange="javascript: document.forms['form1'].submit();" required>
                        <?php
                           $sql = "select id,nombre from insumos order by nombre";
                           $Result = mysqli_query($mysqli_link,$sql);
                           echo "<option value=''></option>";
                           while( $Reg = mysqli_fetch_row($Result) ) {
                              echo "<option value='".$Reg[0]."' ";
                              if($insumo_id == $Reg[0]) echo " selected "; 
                              echo ">".$Reg[1]."</option>";
                           }
                        ?>                           
                </select>
                  
                </div><!--
                <div class="card-footer">
                  <button type="submit" name="confirmar" value="1" class="btn btn-primary">Confirmar</button>
                </div> -->
              </form>


              <table id="tablemovimientos" class="display compact table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Insumo</th>
                  <th>Cantidad</th>
                  <th>Origen</th>
                  <th>Destino</th>
                  <th>Confirmado</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  
                  <?php
                                       
                     $sql = "select M.id,
                                    M.fecha,
                                    I.nombre,
                                    M.cantidad,
                                    (select CONCAT(nombre,' ',apellido,' (',telegram,')') from users
                                            where M.user_salida = id ) as destino,
                                    (select CONCAT(nombre,' ',apellido,' (',telegram,')') from users
                                            where M.user_entrada = id ) as origen,
                                    IF (M.confirmado is NULL,'NO',M.confirmado) as confirm,
                                    M.user_id,
                                    M.comentario
                                    
                               from movimientos as M,
                                    insumos as I
                                    

                              where M.insumo_id  = '$insumo_id' AND
                                    M.insumo_id   = I.id 
                           order by M.fecha desc";

                     $Result = mysqli_query($mysqli_link,$sql);
                     
                     while( $Reg = mysqli_fetch_row($Result) ) {
                  
                  
                        echo "<tr>";
                        echo "<td>".$Reg[1]."</td>";
                        echo "<td>".$Reg[2]."</td>";
                        echo "<td>".($Reg[3] * 1)."</td>";
                        echo "<td>".$Reg[4]."</td>";
                        echo "<td>".$Reg[5]."</td>";
                        echo "<td>".$Reg[6]."</td>";
                        echo "<td><div class='btn-group btn-group-sm'>";
                                  if($Reg[8]){
                                    echo "<button type='button' class='btn btn-info view-comentario' 
                                          id='".$Reg["0"]."' data-toggle='modal' data-target='#modal-comentario'>
                                          <i class='fas fa-eye'></i></button>";
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
    
  
    
  </div>
  <!-- /.content-wrapper -->


  <div class="modal fade" id="modal-comentario">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Comentario</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="EditComentario"></div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cerrar</button>
          <!-- <button type="button" class="btn btn-outline-light">Save changes</button> -->
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

      $('#tablemovimientos').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "order": [[ 0, "desc" ]],
      "info": false,
      "autoWidth": true,
    });

  });

  $(document).ready(function(){
   $(document).on('click', '.view-comentario', function(){  
            var id = $(this).attr("id");  
            $.ajax({  
                 url:"fetch_movimiento.php",  
                 method:"POST",  
                 data:{id:id},  
                 dataType:"json",  
                 success:function(data){  
                   
                      $('#EditComentario').html(data.comentario);  
                      
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

