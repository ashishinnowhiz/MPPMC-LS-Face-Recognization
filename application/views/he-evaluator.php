<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="content">

                    <!-- Start Content-->
	<div class="container-fluid">
           <!--<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Welcome <?php echo $_SESSION[$this->config->item('exam')['exam_session']]['user_name']; ?></h1>
                </div>
                
            </div>-->
              <br>
	  <div class='row'>
				<div class="col-lg-2">
                    <p class='text-right'>
						<input type="text"  name='date' class="form-control" placeholder="Select Date" value="<?php echo $date;?>" onchange="onSelectDate()" id="basic-datepicker" />
                      
                        
                    </p>
                </div>
                <div class="col-lg-10">
                    <p class='text-right'>
                        <a href='<?php echo base_url(); ?>welcome/hm_print?date=<?php echo $date;?>' type="button" class="btn btn-info" target="_black">
                            <span class="fe-printer"></span>  Print 
                        </a>
                        
                    </p>
                </div>
            </div>
            <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            

        <div class="row" style="margin-top:10px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
           		       <div style="float: right;">
                            <?php 
                                // if(
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'mech.chamoli@gmail.com' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'drbhattsandeepmaths@gmail.com' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'gireeshkumarkapil@gmail.com' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'hemantkumar777@gmail.com' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'rajesh.gangwar111@gmail.com' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'hodcivil2017@gmail.com' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'vmvidyarthi@tulas.edu.in' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'papendra1@yahoo.co.in' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'prashant@tulas.edu.in' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'tripuresh.joshi@coolcog.in' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'nehaamitgairola@gmail.com' || 
                                //     $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] == 'shashank.s@techsumsolution.com'){ 
                            ?>
                				<!-- <a href="<?php echo base_url(); ?>welcome/marking" type="submit" class="btn btn-primary">Start Marking </a> -->
                            <?php 
                        // }else{ 
                                ?>
                                <a href="<?php echo base_url(); ?>welcome/marking" type="submit" class="btn btn-primary" style="display: block;">Start Marking </a>
                            <?php 
                        // } 
                        ?>
			             </div>
            <div><h4>Summary for Today <?php echo  $date; ?></h4>
             <b>Total Scripts Marked Today</b> : <?php echo $stats['sheet_count']; ?> | 
             <b>Average Score</b> : <?php echo $stats['sheet_avg']; ?> | 
             <b>Min Score</b> : <?php echo $stats['sheet_min']; ?> | 
             <b>Max Score</b> : <?php echo $stats['sheet_max']; ?>
            <br/><br/></div>

                               <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            
                                            <th>Score</th>
                                            <th>Marking Time</th>
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
													<td>".$report['recheck_duration']."</td>
													
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
        </div>
        
   <script>
var baseurl = "<?php echo base_url(); ?>";
function onSelectDate(){
	var dateVal = $("#basic-datepicker").val();
	window.location=baseurl+"?date="+dateVal;
  
}


</script>      
