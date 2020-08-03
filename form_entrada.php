<?php

require_once("funciones.php");


if(!permiso($perfil,"realizar_pedido")){
  echo "Permisos insuficientes";
  exit;
}


Mostrar();
exit;


// *******************************************************************************
// *******************************************************************************




function mostrar(){
   
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
            <h1>Formulario de Pedido</h1>
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
            

               <form role="form" action="index.php" method="post">
                <div class="card-body">
                  

                  <div class="form-group">
                    <label>Remitente:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        
                        <select class="custom-select" name="remitente" id="remitente" required>
                        <?php

                          if($perfil>0){
                            // muestra todos los perfiles
                            $sql = "select id,
                                          if ( nombre != '' ,concat(nombre,' ',apellido,' (',telegram,')'),
                                          email) 
                                          from users order by nombre";
                          }else{
                            // solo perfiles 2 (admin de stock)
                            $sql = "select id,
                                          if ( nombre != '' ,concat(nombre,' ',apellido,' (',telegram,')'),
                                          email) 
                                          from users
                                          where perfil = 2 
                                          order by nombre";
                          }

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
                    <label>Insumo:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        
                        <select class="custom-select" name="insumo" id="insumo" required>
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
                    <label for="nombre">Unidades</label>
                    <input type="text" name="unidades" class="form-control" id="unidades" placeholder="Unidades"  required>
                  </div>

                  <div class="form-group">
                    <label for="nombre">Comentario</label>
                    <input type="text" name="comentario" class="form-control" id="comentario" placeholder="Comentario"  >
                  </div>

                </div>
                <div class="card-footer">
                  <button type="submit" name="confirmar-entrada" value="1" class="btn btn-primary">Confirmar</button>
                </div>
              </form>

            
            
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



<?php

   include("footer.inc.php");
   
   ?>
   <script src="AdminLTE/plugins/moment/moment.min.js"></script>
   <script src="AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
   <script src="AdminLTE/plugins/select2/js/select2.full.min.js"></script>
   <script src="AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
   
   <script>
  
    
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

