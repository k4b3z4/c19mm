<?php

require_once("funciones.php");


if(!permiso($perfil,"perfil")){
  echo "Permisos insuficientes";
  exit;
}


if( $_POST["confirmar"] ){
   
   $nombre     = validar($_POST["nombre"]);
   $apellido   = validar($_POST["apellido"]);
   $telegram   = validar($_POST["telegram"]);
   $telefono   = validar($_POST["telefono"]);
   $impresoras = validar($_POST["impresoras"]);
   $direccion  = validar($_POST["direccion"]);
   $departamento_id = validar($_POST["departamento_id"]);

   $sql = "update users   set nombre = '$nombre',
                              apellido = '$apellido',
                              telegram = '$telegram',
                              telefono = '$telefono',
                              impresoras = '$impresoras',
                              direccion = '$direccion',
                              departamento_id = '$departamento_id'
                        where email = '$googleEmail';";

   $Result = mysqli_query($mysqli_link,$sql);
      
   if($Result){
      $mensajeok = "Datos registrados";
   }else{
      $mensaje = "Error: no fue posible registrar los datos";
   }
   
   
}
   

Mostrar();
exit;


// *******************************************************************************
// *******************************************************************************




function mostrar(){
   
   global $userid, $perfil;
   global $mysqli_link;
   global $mensaje, $mensajeok;
   global $googleEmail,$googleImage,$googleName;

   include("header.inc.php");

   $sql = "select nombre,
                  apellido,
                  telegram,
                  impresoras,
                  direccion,
                  telefono,
                  departamento_id 
             from users where email='$googleEmail'";
   $Result = mysqli_query($mysqli_link,$sql);
   $Reg = mysqli_fetch_row($Result);


?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Perfil</h1>
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
            

               <form role="form" action="perfil.php" method="post">
                <div class="card-body">
                  
                  <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" value="<?php echo $Reg["0"] ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="nombre">Apellido</label>
                    <input type="text" name="apellido" class="form-control" id="apellido" placeholder="Apellido" value="<?php echo $Reg["1"] ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="nombre">Usuario de Telegram</label>
                    <input type="text" name="telegram" class="form-control" id="telegram" placeholder="Telegram" value="<?php echo $Reg["2"] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="nombre">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Teléfono" value="<?php echo $Reg["5"] ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="nombre">Cantiad de Impresoras</label>
                    <input type="text" name="impresoras" class="form-control" id="impresoras" placeholder="impresoras" value="<?php echo $Reg["3"] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="nombre">Direccion</label>
                    <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Direccion" value="<?php echo $Reg["4"] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="nombre">Departamento</label>
                    <select class="custom-select" name="departamento_id" id="departamento_id" required>
                        <?php
                           $sql2 = "select id,nombre from departamentos order by nombre";
                           $Result2 = mysqli_query($mysqli_link,$sql2);
                           while( $Reg2 = mysqli_fetch_row($Result2) ) {
                              echo "<option value='".$Reg2[0]."' ";
                              if( $Reg2[0] == $Reg[6] ) echo " selected ";
                              echo ">".$Reg2[1]."</option>";
                           }
                        ?>                           
                    </select>
                  </div>
                  
                </div>
                <div class="card-footer">
                  <button type="submit" name="confirmar" value="1" class="btn btn-primary">Confirmar</button>
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

