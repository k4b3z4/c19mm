

  
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"></div>
          <div class="col-sm-6"></div>
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


<!--  MIS INGRESOS -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3>Mis Ingresos</h3>
            </div>
     
             <div class="card-body">
              <table id="tableingresos" class="display compact table-hover" style="width:100%">
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
                                    (select CONCAT(U.nombre,' ',U.apellido,' (',U.telegram,')') from users as U
                                            where id = M.user_salida ),
                                    (select CONCAT(U.nombre,' ',U.apellido,' (',U.telegram,')') from users as U
                                            where id = M.user_entrada ),
                                    IF (M.confirmado is NULL,'NO', M.confirmado) as confirm,
                                    M.user_id,
                                    M.user_entrada,
                                    M.user_salida,
                                    M.comentario
                                    
                               from movimientos as M,
                                    insumos as I                                  

                              where M.user_entrada = '$userid' AND
                                    M.insumo_id   = I.id 

                           order by M.fecha desc";

                     $Result = mysqli_query($mysqli_link,$sql);
                     
                     while( $Reg = mysqli_fetch_row($Result) ) {
                  
                  
                        echo "<tr data-toggle='tooltip' data-placement='top' title='".$Reg[10]."'>";
                        echo "<td>".$Reg[1]."</td>";
                        echo "<td>".$Reg[2]."</td>";
                        echo "<td>".($Reg[3] * 1)."</td>";
                        echo "<td>".$Reg[4]."</td>";
                        echo "<td>".$Reg[5]."</td>";
                        echo "<td>".$Reg[6]."</td>";

                        echo "<td><div class='btn-group btn-group-sm'>";

                        if($Reg[6] == "NO" and $Reg[7] == $userid){ 
                          // sin confirmar y es el creador del registro
                          echo "<button type='button' class='btn btn-danger del-ingreso' id='".$Reg[0]."' 
                                   data-toggle='modal' data-target='#modal-delete'> 
                                  <i class='fas fa-trash'></i></button>";
                        }

                        if($Reg[6] == "NO" and ( $Reg[7] != $userid                   OR 
                                                 ($perfil > 0 AND $Reg[8] != $Reg[9]) OR  
                                                 ($perfil > 0 AND $Reg[8] == $Reg[9] AND $Reg[7] != $userid)
                                               ) 
                          ){
                          // sin confirmar y no es el creador del registro o
                          //                 es admin y origen != destino  o
                          //                 as admin y origen = destino y no es el creador 
                          echo "<button type='button' class='btn btn-info conf-ingreso' id='".$Reg[0]."' 
                                   data-toggle='modal' data-target='#modal-confirm'> 
                                  <i class='fas fa-check'></i></button>";
                        }

                        if($Reg[6] != "NO"){
                          // confirmado
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

<!-- MIS ENVIOS -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header"><h3>Mis  Envíos</h3>
            </div>
             <div class="card-body">
              <table id="tableegresos" class="display compact table-hover" style="width:100%">
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

                     if( $perfil == 3 ) {   // coordinador de REPARTO
                                       
                     $sql = "select M.id,
                                    M.fecha,
                                    I.nombre,
                                    M.cantidad,
                                    (select CONCAT(U.nombre,' ',U.apellido,' (',U.telegram,')') from users as U
                                            where id = M.user_salida ),
                                    (select CONCAT(U.nombre,' ',U.apellido,' (',U.telegram,')') from users as U
                                            where id = M.user_entrada ),
                                    IF (M.confirmado is NULL,'NO',M.confirmado) as confirm,
                                    M.user_id,
                                    M.user_salida,
                                    M.user_entrada,
                                    M.comentario

                                    
                               from movimientos as M,
                                    insumos as I                                   

                              where (M.user_salida = '$userid'  OR
                                     M.user_salida in (select id from users where perfil = 2) ) AND
                                    
                                    M.insumo_id   = I.id 
                           order by M.fecha desc";

                     }else{   // todo el mundo menos coordinador reparto

                      $sql = "select M.id,
                                  M.fecha,
                                  I.nombre,
                                  M.cantidad,
                                  (select CONCAT(U.nombre,' ',U.apellido,' (',U.telegram,')') from users as U
                                            where id = M.user_salida ),
                                  (select CONCAT(U.nombre,' ',U.apellido,' (',U.telegram,')') from users as U
                                            where id = M.user_entrada ),
                                  IF (M.confirmado is NULL,'NO',M.confirmado) as confirm,
                                  M.user_id,
                                  M.user_salida,
                                  M.user_entrada,
                                  M.comentario
                                  
                            from movimientos as M,
                                  insumos as I
                                  
                            where M.user_salida = '$userid' AND
                                  M.insumo_id   = I.id 
                        order by M.fecha desc";


                     }

                     $Result = mysqli_query($mysqli_link,$sql);
                     
                     while( $Reg = mysqli_fetch_row($Result) ) {
                  
                        echo "<tr data-toggle='tooltip' data-placement='top' title='".$Reg[10]."'>";
                        echo "<td>".$Reg[1]."</td>";
                        echo "<td>".$Reg[2]."</td>";
                        echo "<td>".($Reg[3] * 1)."</td>";
                        echo "<td>".$Reg[4]."</td>";
                        echo "<td>".$Reg[5]."</td>";
                        echo "<td>".$Reg[6]."</td>";

                        echo "<td><div class='btn-group btn-group-sm'>";

                        if($Reg[6] == "NO" and $Reg[7] == $userid){
                          // no esta confirmada y es el creador
                          echo "<button type='button' class='btn btn-danger del-ingreso' id='".$Reg[0]."' 
                                   data-toggle='modal' data-target='#modal-delete'> 
                                  <i class='fas fa-trash'></i></button>";
                        }

                        if($Reg[6] == "NO" and ( $Reg[7] != $userid OR 
                                                 ($perfil > 0 AND $Reg[8] != $Reg[9]) OR
                                                 ($perfil > 0 AND $Reg[8] == $Reg[9] AND $Reg[7] != $userid)
                                               ) 
                          ){
                          // no esta confirmada y no es el creador o
                          //                      es admin y origen != destino o
                          //                      ad admin y origen = destino y no es el creador
                          echo "<button type='button' class='btn btn-info conf-ingreso' id='".$Reg[0]."' 
                                   data-toggle='modal' data-target='#modal-confirm'> 
                                  <i class='fas fa-check'></i></button>";
                        }

                        if($Reg[6] != "NO"){
                          // si esta confirmada
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


<!-- MIS ENTREGAS -->

<?php 

// Solo muestra esa seccion si hay entegas para el usuario
$sql = "SELECT count(id) from movimientos_entregas where user_salida='$userid' ";
$Result = mysqli_query($mysqli_link,$sql);
$Reg = mysqli_fetch_row($Result);                                  
if($Reg[0]>0){

?>

<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header"><h3>Mis  Entregas</h3>
            </div>
             <div class="card-body">
              <table id="tableentregas" class="display compact table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Fecha Acordada</th>
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
                                   (select CONCAT(U.nombre,' ',U.apellido,' (',U.telegram,')') from users as U
                                          where U.id = E.user_id ),
                                   E.entidad,
                                   E.cantidad,
                                   E.detalle,
                                   IF (E.confirmado is NULL,'NO',E.confirmado) as confirm,
                                   E.user_salida,
                                   E.fecha
                                
                                
                          from movimientos_entregas as E
                               
                          where E.user_salida = '$userid' 

                      order by E.fecha desc";

                     $Result = mysqli_query($mysqli_link,$sql);
                     
                     while( $Reg = mysqli_fetch_row($Result) ) {
                  
                  
                        echo "<tr>";
                        echo "<td>".$Reg[8]."</td>";
                        echo "<td>".$Reg[1]."</td>";
                        echo "<td>".$Reg[3]."</td>";
                        echo "<td>".($Reg[4] * 1)."</td>";
                        echo "<td>".$Reg[5]."</td>";
                        echo "<td>".$Reg[6]."</td>";
                        
                        echo "<td><div class='btn-group btn-group-sm'>";

                        if($Reg[6] == "NO" and $Reg[7] == $userid  ){
                          // No esta confirmada y el usuario es el destinatario
                          echo "<button type='button' class='btn btn-info conf-entrega' id='".$Reg[0]."' 
                                   data-toggle='modal' data-target='#modal-entrega'> 
                                  <i class='fas fa-check'></i></button>";
                        }

                        if($Reg[6] != "NO"){
                          // confirmada
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

<?php

  } // Fin entregas

?>

    </section>
    <!-- /.content -->


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
                <form role="form" action="index.php" method="post">
                  <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-outline" id="eliminar-ingreso" name="eliminar-ingreso" value="" >Eliminar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
    
        <div class="modal modal-info fade" id="modal-confirm">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <p>Confirma La operacion?<br>
                Esta operacion no puede revertirse</p>
              </div>
              <div class="modal-footer">
                <form role="form" action="index.php" method="post">
                  <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-outline" id="confirmar-ingreso" name="confirmar-ingreso" value="" >Confirmar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
  
        <div class="modal modal-info fade" id="modal-entrega">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <p>Confirma La operacion?<br>
                Esta operacion no puede revertirse</p>
              </div>
              <div class="modal-footer">
                <form role="form" action="index.php" method="post">
                  <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-outline" id="confirmar-entrega" name="confirmar-entrega" value="" >Confirmar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
     






