<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="content">

                    <!-- Start Content-->
	<div class="container-fluid">
           <!-- <div class="row">
                <div class="col-lg-12">
				 <div class="page-title-box">
                    <h1 class="page-header">Welcome <?php echo $_SESSION[$this->config->item('exam')['exam_session']]['user_name']; ?></h1>
					</div>
                </div>
           
            </div>-->
            <!--Code Written By vikas-->
            <div class="page-title-box">
                   <h4 class="page-title">Summary</h4>
            </div>
            <!--Code End Here-->
            <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <br>
			 
	  <div class='row'>
				<!-- <div class="col-lg-2">
                    <p class='text-right'>
						<input type="text"  name='date' class="form-control" placeholder="Select Date" value="<?php echo $date;?>" onchange="onSelectDate()" id="basic-datepicker" />
                      
                        
                    </p>
                </div> -->
                <!--Code Written By Vikas-->
              
                <div class="col-lg-8">
             
                
                                    <form class='form-inline' action='' method='GET'>
                                        <div class='form-group'>
                                            
                                            <input type="text" class="form-control" name="date_from" id='basic-datepicker' placeholder="Select Date" value="<?php echo $date_from; ?>"  readonly="true"/>
											</div>&nbsp;&nbsp;&nbsp;
											 <div class='form-group'>
                                            <input type="text" class="form-control" name="date_to" id='minmax-datepicker' placeholder="Select Date" value="<?php echo $date_to; ?>"   readonly="true"/>
                                        </div>&nbsp;&nbsp;&nbsp;

                                     
                                        <button type="button" class="btn btn-primary" onclick="onSelectDate()"><i class="fe-search"></i> Search</button> 
                                    </form>
            
                </div>
                <!--Code End Here-->
                <div class="col-lg-4">
                    <p class='text-right'>
						
                       <a href='<?php echo base_url(); ?>welcome/summary_export' type="button" class="btn btn-info" >
                            <span class="fe-download"></span> Export 
                        </a>

                        <a href="<?php echo base_url(); ?>welcome/marker_print?date_from=<?php echo $date_from;?>&date_to=<?=$date_to?>" type="button" class="btn btn-info" target="_black">
                            <span class="fe-printer"></span>  Print 
                        </a>
                        
                    </p>
                </div>
            </div>
      <div class="row" style="margin-top:10px;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body"><form>
                                    <?php if($status == 1){?>
									<div style="float: right;"> 
                                            <a href="<?php echo base_url(); ?>welcome/marking" type="submit" class="btn btn-primary">
                                            Start Marking
                                           </a> 
                                    </div>
                                    <?php }?>
           <div> <!-- <h4>Summary for Today <?php echo  $date; ?></h4> -->
             <b>Total Scripts Markered Today</b> : <?php echo $stats['sheet_count']; ?> | 
             <b>Average Score</b> : <?php echo $stats['sheet_avg']; ?> | 
             <b>Min Score</b> : <?php echo $stats['sheet_min']; ?> | 
             <b>Max Score</b> : <?php echo $stats['sheet_max']; ?>
            <br/><br/></div>

                               <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            
                                            <th>Score</th>
                                            <th>Evaluation Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($reports as $report) {
                                            $serial=$serial+1;                                                
                                            echo "<tr>
													<td>".$serial."</td>
													
													<td>".$report['evaluation_marks']."</td>
													<td>".$report['check_duration']."</td>
													
                                                </tr>";
                                            }
                                        ?>        
                                    </tbody>
                                </table>
            </div>
            </div>
            </div>
        </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
        <div class="modal-body">
           <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator' && isset($eval)) { ?>
                <br/><br/>
                        <!-- /.panel-heading -->
                        <div class="panel-body text-justify">
                        Once you are all set to start the evaluation. Click the marker button. 
                        System will load the answerscript<br/><br/>
                            <!--<a id='evalButton' onclick="getSheet();" class="btn btn-md btn-primary btn-block"">
                                Marker
                            </a>-->
							
                           
                        </div>
						<hr>
               <span id='evalMessage'></span>

                <?php } ?>
        </div>
        <div class="modal-footer">
		 <button type="button" id='evalButton' onclick="getSheet();" class="btn btn-primary">
                                Start
                            </button>
                            
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <script>
var baseurl = "<?php echo base_url('welcome/Summary'); ?>";//cod alter by vikas on 04/05/2023 
function onSelectDate(){
	var date_from = $("#basic-datepicker").val();
    var date_to = $("#minmax-datepicker").val();
	window.location=baseurl+"?date_from="+date_from+"&date_to="+date_to;
  
}


</script>