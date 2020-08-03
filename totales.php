<?php

require_once("funciones.php");


if(!permiso($perfil,"totales_usuario")){
  echo "Permisos insuficientes";
  exit;
}

if ($perfil > 0){
  Mostrar_admin();
}else{
  mostrar_usuario();
}
exit;


// *******************************************************************************
// *******************************************************************************

function mostrar_usuario(){
   
  global $perfil,$userid;
  global $mysqli_link;
  global $mensaje, $mensajeok;
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
         </div>
         <div class="col-sm-6">
         </div>
       </div>
     </div><!-- /.container-fluid -->
   </section>
   
   
   <section class="content">
     <div class="row">
       <div class="col-12">
         
           

           <?php

              $sql = "select id,nombre from insumos";
              $Result = mysqli_query($mysqli_link,$sql);

              $data['entrada'] = "<div class='row'>";
              $data['salida'] = "<div class='row'>";
              
              while ($row = mysqli_fetch_row($Result)) {

                // ENTRADA
                $sql2 = "SELECT if(sum(cantidad) is NULL, 0, sum(cantidad)) from movimientos
                                              where confirmado is not null AND
                                              insumo_id = '".$row[0]."' AND
                                              user_entrada = '$userid'; ";

                $Result2 = mysqli_query($mysqli_link,$sql2);
                $row2 = mysqli_fetch_row($Result2);

                if ($row2[0] != 0) {
                  $data['entrada'] .= "<div class='col-6'>".$row[1].":</div><div class='col-6'>".($row2[0] * 1)."</div>";
                }

                // SALIDA
                $sql2 = "SELECT if(sum(cantidad) is NULL, 0, sum(cantidad)) from movimientos
                                              where confirmado is not null AND
                                              insumo_id = '".$row[0]."' AND
                                              user_salida = '$userid'; ";

                $Result2 = mysqli_query($mysqli_link,$sql2);
                $row2 = mysqli_fetch_row($Result2);

                if ($row2[0] != 0) {
                  $data['salida'] .= "<div class='col-6'>".$row[1].":</div><div class='col-6'>".($row2[0] * 1)."</div>";
                }


              }  
              
              $data['entrada'] .= "</div>";
              $data['salida'] .= "</div>";
           
           ?>

            <div class="card">
              <div class="card-header"><h3>Insumos Recibidos</h3></div>
              <div class="card-body"><?php echo $data["entrada"]; ?></div>
            </div>

            <div class="card">
              <div class="card-header"><h3>Elementos Entregados</h3></div>
              <div class="card-body"><?php echo $data["salida"]; ?></div>
            </div>
         
          
        </div>
       <!-- /.col -->
     </div>
     <!-- /.row -->     
   </section>
   
 
 </div>
 <!-- /.content-wrapper -->

<?php

  include("footer.inc.php");
  
  ?>

  <script src="AdminLTE/plugins/moment/moment.min.js"></script>
  <script src="AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
  <script src="AdminLTE/plugins/select2/js/select2.full.min.js"></script>
  <script src="AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
  

  </body>
  </html>

  <?php
}


function mostrar_admin(){
   
   global $perfil,$userid;
   global $mysqli_link;
   global $mensaje, $mensajeok;
   global $googleEmail,$googleImage,$googleName;

   include("header.inc.php");

   
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    
    <section class="content">
      <div class="row">
        <div class="col-12">

          <?php

          $sql = "select id,nombre from insumos";
          $Result = mysqli_query($mysqli_link,$sql);

          $data['entrada'] = "<div class='row'>";
          $data['salida'] = "<div class='row'>";

          while ($row = mysqli_fetch_row($Result)) {

            // ENTRADA
            $sql2 = "SELECT if(sum(cantidad) is NULL, 0, sum(cantidad)) from movimientos
                                          where confirmado is not null AND
                                          insumo_id = '".$row[0]."' AND
                                          user_entrada = '$userid'; ";

            $Result2 = mysqli_query($mysqli_link,$sql2);
            $row2 = mysqli_fetch_row($Result2);

            if ($row2[0] != 0) {
              $data['entrada'] .= "<div class='col-6'>".$row[1].":</div><div class='col-6'>".($row2[0] * 1)."</div>";
            }

            // SALIDA
            $sql2 = "SELECT if(sum(cantidad) is NULL, 0, sum(cantidad)) from movimientos
                                          where confirmado is not null AND
                                          insumo_id = '".$row[0]."' AND
                                          user_salida = '$userid'; ";

            $Result2 = mysqli_query($mysqli_link,$sql2);
            $row2 = mysqli_fetch_row($Result2);

            if ($row2[0] != 0) {
              $data['salida'] .= "<div class='col-6'>".$row[1].":</div><div class='col-6'>".($row2[0] * 1)."</div>";
            }


          }  

          $data['entrada'] .= "</div>";
          $data['salida'] .= "</div>";

          ?>

          <div class="card">
            <div class="card-header"><h3>Insumos Recibidos</h3></div>
            <div class="card-body"><?php echo $data["entrada"]; ?></div>
          </div>

          <div class="card">
            <div class="card-header"><h3>Elementos Entregados</h3></div>
            <div class="card-body"><?php echo $data["salida"]; ?></div>
          </div>


        </div>
      </div><!-- /.row -->


      <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header"><h3>Totales por Usuarios</h3></div>
              <div class="card-body">
                <table id="tabletotales" class="display table-hover" width="100%">
                  <thead>
                    <tr>
                      <th>Nombre Apellido</th>
                      <th>Email</th>
                      <th>Telegram</th>
                      <th>Teléfono</th>
                      <th>Impresoras</th>
                      <th>Departamento</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                  
                    <?php
                                       
                     $sql = "select U.id,
                                    U.nombre,
                                    U.apellido,
                                    U.telegram,
                                    U.telefono,
                                    U.impresoras,
                                    U.direccion,
                                    D.nombre,
                                    U.email
                              from  users as U,
                                    departamentos as D
                             where  U.departamento_id = D.id      
                              order by U.nombre,U.apellido   ";   

                     $Result = mysqli_query($mysqli_link,$sql);
                     
                     while( $Reg = mysqli_fetch_row($Result) ) {
                  
                        echo "<tr>";
                        echo "<td>".$Reg[1]." ".$Reg[2]."</td>";
                        //echo "<td>".$Reg[2]."</td>";
                        echo "<td>".$Reg[8]."</td>";
                        echo "<td>".$Reg[3]."</td>";
                        echo "<td>".$Reg[4]."</td>";
                        //echo "<td>".substr($Reg[6],0,10)."</td>";
                        echo "<td>".$Reg[5]."</td>";
                        echo "<td>".$Reg[7]."</td>";
                        echo "<td><div class='btn-group btn-group-sm'>";
                                  
                                    echo "<button type='button' class='btn btn-info view-modal' id='".$Reg[0]."' data-toggle='modal' data-target='#modal'>
                                          <i class='fas fa-eye'></i></button>";
                                  
                        echo "</div></td>";
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



      <div class="modal fade" id="modal">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
                <h4 class="modal-title">Insumos Recibidos</h4>
                <div class="card-body" id="View-entrada"></div>  
                <h4 class="modal-title">Elementos Entregados</h4>
                <div class="card-body" id="View-salida"></div>  
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
   <script src="AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
   <script src="AdminLTE/plugins/select2/js/select2.full.min.js"></script>
   <script src="AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
   
   <script>
  
    
  $(function () {
    $('#tabletotales').DataTable({
      "columns": [
            {  },
            {  },
            {  },
            {  },
            {  },
            {  },
            { "orderable":      false, },
        ],
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    });
  });

  $(document).ready(function(){
   $(document).on('click', '.view-modal', function(){  
            var id = $(this).attr("id");  
            $.ajax({  
                 url:"fetch_totales.php",  
                 method:"POST",  
                 data:{id:id},  
                 dataType:"json",  
                 success:function(data){  
                  $('#View-entrada').html(data.entrada);
                  $('#View-salida').html(data.salida);
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

