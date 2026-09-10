<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

			<div class="content">
                  <!-- Start Content-->
			<div class="container-fluid">
						<div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
											 <li class="breadcrumb-item"><a href = "<?php echo base_url(); ?>masters">Base Data</a></li>
                                            <li class="breadcrumb-item active">Subject Wise Marker Daily Reports</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Subject Wise Marker Daily Reports</h4>
                                </div>
                            </div>
                        </div>
	
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-lg-12">
                                    <form class='form-inline' action='' method='GET'>
                                        <div class='form-group'>
                                            
                                        <input type="text" class="form-control" name="date" placeholder="Select Date" value="<?php echo $date;?>" id="basic-datepicker"  />
											</div>&nbsp;&nbsp;&nbsp;
					

                                         <!--Code written By vikas on 26/04/23-->

                                         <div class='form-group'>
                                            <select name="course" id="course" class="form-control" readonly="true">
                                            <option value=''>All</option>
                                                <?php
                                                foreach ($courses as $course) {
                                                   ?>
                                                   <option value="<?=$course['course_code']?>" <?=$course['course_code']==$courseCode?'selected':'';?>><?=$course['course_code']?>-<?=$course['course_name']?></option>
                                                   <?php
                                                }
                                                ?>
                                            </select>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <!--Code End Here-->
                                        <!--<input type='hidden' id='export' name='export' value=''>-->
                                        <button type="button" class="btn btn-primary" onclick="onCklickBtn()"><i class="fe-search"></i> Search</button> 
                                    </form>
                    
                </div> 
                <!-- /.col-lg-12 -->
            </div>
            <br>
         <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Reports</h4>
                                       
                            <?php if (sizeof($reports)>0) { ?>
								<table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Medium</th>
                                            <th>Marker Id</th>
                                            <th>Marker Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                            //print_r($reports);
                                        foreach ($reports as $report) {
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$report['subject_name']."</td>
													<td>".$report['subject_code']."</td>
													<td>".$report['medium_name']."</td>
													<td>".$report['evaluator_username']."</td>
													<td>".$report['evaluator_name']."</td>
												</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            
                            <?php } else { ?>
                                <div class='alert alert-danger'>No records found</div>
                            <?php } ?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
</div>
<script>
var baseurl = "<?php echo base_url(); ?>";
function onCklickBtn(){
	var dateVal = $("#basic-datepicker").val();
    var course = $('#course').val();
	window.location=baseurl+"subjects/evaluators?date="+dateVal+"&course="+course;
  
}
/* var baseurl = "<?php echo base_url(); ?>";
var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date"]);
    myCalendar.attachEvent("onClick", function(d){
        var dateVal = $("#date").val();
        window.location=baseurl+"subjects/evaluators/"+myCalendar.getFormatedDate(null,d);
    });
}
doOnLoad(); */
</script>
<style>
    #date {
        border: 1px solid #dfdfdf;
        font-family: Roboto, Arial, Helvetica;
        font-size: 14px;
        color: #404040;
    }
    #dateIcon {
        vertical-align: middle;
        cursor: pointer;
    }
</style>

