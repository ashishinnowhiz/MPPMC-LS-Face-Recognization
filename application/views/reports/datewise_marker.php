<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

	
		<div style="margin-left:80px;margin-right:80px">
        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            
                                            <li class="breadcrumb-item active">Date Wise </li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Date Wise Report</h4>
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
                <div class="col-lg-8">
                                    <form class='form-inline' action='' method='GET'>
                                        <div class='form-group'>
                                            <input type="text" class="form-control" name="date_from" id='basic-datepicker' placeholder="Select Date" value="<?php echo $date_from; ?>" onchange="setSens();" readonly="true"/>
											</div>&nbsp;&nbsp;&nbsp;
											 <!-- <div class='form-group'>
                                            <input type="text" class="form-control" name="date_to" id='minmax-datepicker' placeholder="Select Date" value="<?php echo $date_to; ?>"   readonly="true"/>
                                        </div>&nbsp;&nbsp;&nbsp; -->

                                         <!--Code written By vikas on 17/04/23-->

                                        <!-- <div class='form-group'>
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
                                        </div>&nbsp;&nbsp;&nbsp; -->
                                        <!--Code End Here-->
                                        <!--<input type='hidden' id='export' name='export' value=''>-->
                                        <button type="button" class="btn btn-primary" onclick="onCklickBtn()"><i class="fe-search"></i> Search</button> 
                                    </form>
                    
                </div> 
                <div class="col-lg-4" >
                       <a href='<?php echo base_url(); ?>welcome/marker_export' type="button" class="btn btn-info" style="float:right">
                            <span class="fe-download"></span> Export 
                        </a>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <br/>
            <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Reports</h4>
                                       
                          
								<table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Marker Name</th>
                                            <th>Marker Username</th>
                                            <th>Phone No.</th>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th>Scripts Checked</th>
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
													<td>".$report['evaluator_name']."</td>
													<td>".$report['evaluator_username']."</td>
													<td>".$report['evaluator_phone']."</td>
													<td>".$report['subject_name']."</td>
                                                    <td>".$report['evaluation_date']."</td>
													<td>".$report['data_count']."</td>													
													
												</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
</div>
        </div>
<script>

var baseurl = "<?php echo base_url(); ?>";


function setSens(){
var minDate = $("#basic-datepicker").val();

$("#minmax-datepicker").flatpickr({minDate:minDate,maxDate:""});
}

function onCklickBtn(){
	 var date = $("#basic-datepicker").val();
	// var maxDate = $("#minmax-datepicker").val();
    var course = $('#course').val();
	// window.location=baseurl+"reports/datewise_marker?date_from="+minDate+"&date_to="+maxDate+"&course="+course; //course field added by vikas on 17/04/23
	// window.location=baseurl+"reports/get_datewise?date_from="+date+"&course="+course; //course field added by vikas on 17/04/23
    window.location=baseurl+"welcome/get_datewise?date_from="+date; //course field added by vikas on 17/04/23

}

</script>

