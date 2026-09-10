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
                                            <li class="breadcrumb-item active">Subject Wise Marker Consolidated Reports</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Subject Wise Marker Consolidated Reports</h4>
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
                                            
                                            <input type="text" class="form-control" name="date_from" id='basic-datepicker' placeholder="Select Date" value="<?php echo $date_from; ?>" onchange="setSens();" readonly="true"/>
											</div>&nbsp;&nbsp;&nbsp;
											 <div class='form-group'>
                                            <input type="text" class="form-control" name="date_to" id='minmax-datepicker' placeholder="Select Date" value="<?php echo $date_to; ?>"   readonly="true"/>
                                        </div>&nbsp;&nbsp;&nbsp;
                                         <!--Code written By vikas on 26/04/23-->

                                         <div class='form-group'>
                                            <select name="course" id="subj_course" class="form-control" readonly="true">
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
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Course Code</th>
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
                                                    <td>".$report['course_code']."</td>
													<td>".$report['medium_name']."</td>
													<td>".$report['evaluator_username']."</td>
													<td>".$report['evaluator_name']."</td>
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
<script>
var baseurl = "<?php echo base_url(); ?>";


function setSens(){
var minDate = $("#basic-datepicker").val();

$("#minmax-datepicker").flatpickr({minDate:minDate,maxDate:""});
}

function onCklickBtn(){
	 var minDate = $("#basic-datepicker").val();
	var maxDate = $("#minmax-datepicker").val();
    var course = $('#subj_course').val();
	window.location=baseurl+"subjects/dated_evaluators?date_from="+minDate+"&date_to="+maxDate+"&course="+course; //course field added by vikas on 26/04/23;
  
}
/* var baseurl = "<?php echo base_url(); ?>";
var myCalendar;
        function doOnLoad() {
            myCalendar = new dhtmlXCalendarObject(["date_from","date_to"]);
            //myCalendar.setDate("<?php echo date('Y-m-d', strtotime('-1 week', time())); ?>");
            myCalendar.hideTime();
            myCalendar.showToday();
            // init values
            var t = new Date();
            //byId("date_from").value = "<?php echo date('Y-m-d', strtotime('-1 week', time())); ?>";
            //byId("date_to").value = "<?php echo date('Y-m-d', time()); ?>";
        }
        
        function setSens(id, k) {
            // update range
            if (k == "min") {
                myCalendar.setSensitiveRange(byId(id).value, null);
            } else {
                myCalendar.setSensitiveRange(null, byId(id).value);
            }
        }
        function byId(id) {
            return document.getElementById(id);
        }
doOnLoad(); */


    function exportcsv(){
        $('#export').val('csv');
        $('#filter_form').submit();
    }
    function filter(){
        $('#export').val('');
        $('#filter_form').submit();
    }
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

