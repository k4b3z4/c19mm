<?php

require_once("funciones.php");



if(!permiso($perfil,"graficos")){
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
            <div class="card-header">Producción 
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="lineChart2" style="height:230px"></canvas>
              </div>
            </div><!-- /.box-body -->
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->     

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">Usuarios activos 
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="lineChart" style="height:230px"></canvas>
              </div>
            </div><!-- /.box-body -->
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->     
      
    </section>
    
  
    
</div>
  <!-- /.content-wrapper -->

<?php

  // ###################################################################################################################
  // GRAFICA USUARIOS ACTIVOS
  $d=0;
  $labels1="";
  $data1="";

  $sql = "SELECT DISTINCT user_id,date_format(fecha,'%d/%m/%Y') 
                  FROM movimientos
                  order by fecha";
  $Result = mysqli_query($mysqli_link,$sql);

  while ($Reg = mysqli_fetch_row($Result)){
    if( $d != $Reg[1] ){
      $labels1 .= "'".$Reg[1]."',";
      $users[$Reg[1]] = 1;
    }else{
      $users[$Reg[1]]++;
    }
    $d = $Reg[1];
  }

  foreach ($users as $dato){
    $data1 .= "'".$dato."',";
  }

  // ###################################################################################################################
  // GRAFICA PRODUCCION 
  $d=0;
  $labels2="";
  $data2="";

  $sql = "SELECT sum(cantidad),date_format(fecha,'%d/%m/%Y'),(select nombre from insumos where id=insumo_id) 
                    FROM movimientos 
                    WHERE confirmado is not NULL AND 
                          insumo_id in (2,9) AND
                          fecha > '2020-05-14 00:00:00'
                    GROUP by date_format(fecha,'%d/%m/%Y'),insumo_id
                    ORDER by fecha";

  $Result = mysqli_query($mysqli_link,$sql);


  while ($Reg = mysqli_fetch_row($Result)){
    if( $d != $Reg[1] ){
      $labels2 .= "'".$Reg[1]."',";
    }
    $d = $Reg[1];
    $data2[$Reg[2]][$Reg[1]] = $Reg[0];
  }

  $label2 = array_keys($data2);

  foreach( $data2[$label2[0]] as $val ){
    $serie2[0] .= "'".$val."',";
  }
  foreach( $data2[$label2[1]] as $val ){
    $serie2[1] .= "'".$val."',";
  }

  // ######################################################################################################################

   include("footer.inc.php");
   
   ?>
   <script src="AdminLTE/plugins/moment/moment.min.js"></script>
   <script src="AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
   <script src="AdminLTE/plugins/select2/js/select2.full.min.js"></script>
   <script src="AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
   <script src="AdminLTE/plugins/chart.js/Chart.js"></script>

   <script>

    $(function () {
    
    window.chartColors = {
      red: 'rgb(255, 99, 132)',
      orange: 'rgb(255, 159, 64)',
      yellow: 'rgb(255, 205, 86)',
      green: 'rgb(75, 192, 192)',
      blue: 'rgb(54, 162, 235)',
      purple: 'rgb(153, 102, 255)',
      grey: 'rgb(201, 203, 207)'
    };

    var ChartData = {
      labels  : [<?php echo $labels1; ?>],
      datasets: [
        {
          label               : 'Usuarios',
          backgroundColor     : window.chartColors.blue,
          borderColor         : window.chartColors.blue,
          fill: false,
          data                : [<?php echo $data1; ?>]
        }
      ]
    }

    var ChartData2 = {
      labels  : [<?php echo $labels2; ?>],
      datasets: [
        {
          label               : '<?php echo $label2[0] ?>',
          backgroundColor     : window.chartColors.orange,
          borderColor         : window.chartColors.orange,
          fill: false,
          data                : [<?php echo $serie2[0]; ?>]
        },
        {
          label               : '<?php echo $label2[1] ?>',
          backgroundColor     : window.chartColors.red,
          borderColor         : window.chartColors.red,
          fill: false,
          data                : [<?php echo $serie2[1]; ?>]
        }
      ]
    }

    var lineChartOptions = {
      scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: 'Fecha'
						}
					}],
					yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function(value) {if (value % 1 === 0) {return value;}}
            },
            display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						}
					}]
				},
      responsive  : true
    }

    var lineChartOptions2 = {
      scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: 'Fecha'
						}
					}],
					yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function(value) {if (value % 1 === 0) {return value;}}
            },
            display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						}
					}]
				},
      responsive  : true
    }

    var config = {
      type: 'line',
      data: ChartData,
      options: lineChartOptions 
    };

    var config2 = {
      type: 'line',
      data: ChartData2,
      options: lineChartOptions2 
    };


    var ctx = $('#lineChart').get(0).getContext('2d');
		window.myLine = new Chart(ctx, config);
    window.myLine.update();

    var ctx2 = $('#lineChart2').get(0).getContext('2d');
		window.myLine2 = new Chart(ctx2, config2);
    window.myLine2.update();

    
  });  
      

   </script>

   </body>
   </html>
   <?php
   
}

?>

