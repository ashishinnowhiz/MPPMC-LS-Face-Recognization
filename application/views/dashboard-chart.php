<style>

canvas {
    border-radius: 10px;
    height:208px;
    width: 1658px;
}
</style>
<div class="content bg-white" style="margin-bottom:40px">

                        <!-- end row -->
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                    
                     
                        <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
                </div>

               
        
<div class="container-fluid">
<div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                          <?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
											
											<strong>Logged In Users : <?php echo sizeof($logged_in); ?>
											</strong>&nbsp;&nbsp;&nbsp;			
															<button class='btn btn-danger'  data-toggle='modal' data-target='#resetModal'><i class="fe-log-out"></i> Reset Login</button>
											
										<?php } ?>

                                    </div>
                                    <h4 class="page-title">Dashboard</h4>
                                    <?php if ($this->session->flashdata('success') == true) : ?>
                                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

                        <div class="row">
                            <div class="col-md-3 col-xl-3">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-sm bg-soft-success rounded">
                                                <i class="fe-bar-chart-2 avatar-title font-22 text-success"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
										<?php  
										 $allocation=$this->db->query("SELECT sum(`allocation_quantity`) as allocated,sum(`allocation_synced_files`) as syncFile,(allocation_quantity-allocation_synced_files) as pendingDownload FROM `allocation_log` WHERE `allocation_sync_status` ='Pending' ")->result_array();
										?>
                                            <div class="text-right">
                                                <h3 class="text-dark my-1"><span data-plugin="counterup"><?php echo $allocation[0]['pendingDownload']; ?>	</span></h3>
                                                <p class="text-muted mb-1 text-truncate">Download Pending Script </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-uppercase">Download Pending Script <span class="float-right"><?php echo $allocation[0]['pendingDownload']; ?></span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $papers==0?0:100; ?>%">
                                                <span class="sr-only">100% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                            <div class="col-md-3 col-xl-3">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-sm bg-soft-info rounded">
                                                <i class="fe-bar-chart-2 avatar-title font-22 text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <h3 class="text-dark my-1"><span data-plugin="counterup"><?php echo sizeof($logged_in); ?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Active Marker</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-uppercase"><?php echo sizeof($logged_in); ?> Active Marker out of 10 <span class="float-right"><?php echo sizeof($logged_in); ?>%</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="49" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo sizeof($logged_in); ?>">
                                                <span class="sr-only"><?php echo sizeof($logged_in); ?>% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                            
							
							<div class="col-md-3 col-xl-3">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-sm bg-soft-info rounded">
                                                <i class="fe-bar-chart-2 avatar-title font-22 text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <h3 class="text-dark my-1"><span data-plugin="counterup"><?php echo $sheets; ?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Allocated Scripts</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-uppercase">Allocated Scripts <span class="float-right"><?php echo $sheets; ?></span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="49" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $sheets==0?0:100; ?>%">
                                                <span class="sr-only">16% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                            <div class="col-md-3 col-xl-3">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-sm bg-soft-warning rounded">
                                                <i class="fe-bar-chart-2 avatar-title font-22 text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <h3 class="text-dark my-1"><?php echo $checked; ?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Scripts Checked</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-uppercase">Script checked out of <?php echo $sheets; ?> <span class="float-right"><?php if($sheets !=0 ){echo round($checked*100/$sheets);}else{echo $sheets ;} ?>%</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100" style="width: <?php if($sheets !=0 ){echo round($checked*100/$sheets);}else{echo $sheets ;} ?>%">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                           
						</div>
</div>

<!-- <div class="container shadow border mb-2" style="background-color:whitesmoke;padding:20px">
    <div class="row">
        <div class="col-lg-12">
            <center>
                <div style="height:350px;width:600px;padding:20px;background:#ffff" class="shadow">
                    <h4>
                        Completed: <span id="mainResultTotalSheet"></span> &nbsp;&nbsp;&nbsp;
                        Pass: <span id="mainResultPassSheet" style="color:Green;"></span> &nbsp;&nbsp;&nbsp;
                        Fail: <span id="mainResultFailSheet" style="color:Red;"></span>
                    </h4>
                    <canvas id="myChart" style="max-width: 500px;"></canvas>
                </div>
            </center>
        </div>
    </div>
</div> -->

<table style="display:flex;justify-content:center;">
    <?php 
    $index = 0;
    if(isset($results)){
    for ($i = 0; $i < count($results) / 3; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 3; $j++) {
            if(isset($results[$index]['Total'])){
                echo "<td style='padding:20px'>
                        <div class='text-center shadow-lg' style='padding:10px'>
                            <div style='height:208px;width:350px;'>
                                <canvas id='pieChart{$index}'></canvas>
                            </div>";


                echo "<h5>Total Sheets : {$results[$index]['Total']}</h5>
                <h5>Subject Code : {$results[$index]['subject_code']}</h5>
                        </div>
                    </td>";
                $index++;
            }
        }
        echo "</tr>";
    }
}
    ?>
</table>

                
    </div>


    <?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
 <div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
	   <h4 class="modal-title" id="impModalLabel">Reset login</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <form name='resetForm' id='resetForm' role="form" method="post" onsubmit="return logout()" action="<?php echo base_url() ?>welcome/reset_login">
      <div class="modal-body">
                                    
                                        <div class="form-group">
                                            <label>Marker Username *</label>
                                            <select name='username' id='username' class='form-control'>
                                            <option value=''>Select</option>
                                            <option value="All">All</option>
                                            
                                            <?php
                                            foreach ($logged_in as $user) {
                                                echo "<option value='".$user['user_name']."'>".$user['user_name']."</option>";
                                            }
                                            ?>
                                            </select>
                                        </div>
										<div class="form-group">
                                        
                                            <label>
                                                <input  id='unassign' name="unassign" type="checkbox" value="true"> Unassign answerscripts 
                                            </label>
                                       
                                        </div>
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <?php
        $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                        );
        ?>
        <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
        <input type="submit" class="btn btn-primary" name="submit" value='Reset'>
      </div>
      </form>
    </div>
  </div>
</div>


<script>
        function logout(){
            var username=$('#username').val();
            if(username==''){
                alert('username must be selected');
                return false;
            }else{
                var r = confirm("Are you sure to reset login for "+username);
            }
            return r;
        }
</script>

<?php } ?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var results = JSON.parse('<?=$json_result?>');
    
    var pass = 0 ;
    var fail = 0 ;
    var total = 0 ;

    for(var i = 1 ; i < results.length ; i++){
		
        if(results[i]['Result'] == "PASS"){
            pass = pass + 1 ;
        }else{
            fail = fail + 1;
        }
		
    }
	total=pass+fail;
	total=pass+fail;
	var PassPer=pass==0?0:((pass*100)/total).toFixed(2);
	var FailPer=fail==0?0:((fail*100)/total).toFixed(2);
	document.getElementById('mainResultTotalSheet').innerHTML = total;
	document.getElementById('mainResultPassSheet').innerHTML = pass+"("+PassPer+"%)";
	document.getElementById('mainResultFailSheet').innerHTML = fail+"("+FailPer+"%)";
	
var ctx = document.getElementById("myChart").getContext('2d');
var finalResult = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["PASS", "FAIL"],
    datasets: [{
      data: [pass,fail],
      label : "Result",
      backgroundColor: [
        'rgba(75, 192, 192, 0.2)',
        'rgba(255, 99, 132, 0.2)',
        // 'rgba(54, 162, 235, 0.2)',
      ],
      borderColor: [
        'rgba(75, 192, 192, 1)',
        'rgba(255,99,132,1)',
        // 'rgba(54, 162, 235, 1)',
      ],
      borderWidth: 2,
    }]
  },
  options: {
    scales: {
       
      yAxes: [{
        ticks: {
         beginAtZero: false, 
         min: 0,
          max: 6000,
          stepSize: 10
        }
      }]
    },
   
    barPercentage: 0.4, // Adjust this value to set the width of the bars
    categoryPercentage: 0.6 // Adjust this value to set the spacing between bars
  }
});


  //Code end for bar chart
</script>

<script>
    var reports_array = '<?=$json_report?>';
    var jsonObject = JSON.parse(reports_array);
    document.addEventListener("DOMContentLoaded", function() {
var ctxArray = [];
var dataArray = [];
var optionsArray = [];
console.log("vikas",jsonObject);
for(var i = 0; i < jsonObject.length; i++) {
    var total = parseInt(jsonObject[i]['Total']);
    // var avail = 1;

    // if(parseInt(jsonObject[i]['available']) != 0){
        pending = parseInt(jsonObject[i]['Pending']);
    // }
    // var once = parseInt(jsonObject[i]['once']);
    // var relook = parseInt(jsonObject[i]['relook']);
    var rechecked =parseInt(jsonObject[i]['Rechecked']);
    var checked = parseInt(jsonObject[i]['Checked']);
    var rejected = parseInt(jsonObject[i]['Rejected']);

    var ctx = document.getElementById('pieChart' + i).getContext('2d');
    var data = {
       // labels: ['Pending', '1AE/EE', 'Relook', 'Review', 'Completed'],
        labels: ['Pending', 'Checked', 'Rechecked','Rejected'],
        datasets: [{
           // data: [avail, once, relook/2, review, checked + seeding],
            data: [pending, checked ,rechecked, rejected],
            backgroundColor: ['#28a745', '#007bff', '#007bff','#dc3545']
        }]
    };

    var options = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Attractive Pie Chart'
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    };

    ctxArray.push(ctx);
    dataArray.push(data);
    optionsArray.push(options);

    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });
}
   
});

//Code Written By vikas
function subjectResult(subject_code,totalSheet) { 
//alert(totalSheet);
    document.getElementById('resultModalLabel').innerHTML = subject_code+" - Result";
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'GET',
            url: base_url+"welcome/getResultData/"+subject_code,       
     // ...
success:function(data) {   
    var Final_result = JSON.parse(data);
    var pass = 0 ;
    var fail = 0 ;
	var total=0;
	
    for(var i = 1 ; i < Final_result.length ; i++){
    console.log(Final_result[i]['Result']);
        if(Final_result[i]['Result'] == "PASS"){
            pass = pass + 1 ;
        }else{
            fail = fail + 1;
        }
    }
	total=pass+fail;
	var PassPer=pass==0?0:((pass*100)/total).toFixed(2);
	var FailPer=fail==0?0:((fail*100)/total).toFixed(2);
	document.getElementById('resultTotalSheet').innerHTML = total;
	document.getElementById('resultPassSheet').innerHTML = pass+"("+PassPer+"%)";
	document.getElementById('resultFailSheet').innerHTML = fail+"("+FailPer+"%)";
    // Get the canvas element by id
    var ctx = document.getElementById("resultChart");

    // Check if a chart already exists, and destroy it if it does
    if (window.myChart instanceof Chart) {
        window.myChart.destroy();
    }

    // Create a new chart
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["PASS", "FAIL"],
            datasets: [{
                data: [pass,fail],
                label : "Result",
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255,99,132,1)',
                ],
                borderWidth: 2,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false, 
                        min: 0,
                        max: 6000,
                        stepSize: 10
                    }
                }]
            },
            barPercentage: 0.4,
            categoryPercentage: 0.6
        }
    });

    // Set the new chart to window.myChart
    window.myChart = myChart;
}
// ...

// ...

                        });
                }
//Code End Here


</script>