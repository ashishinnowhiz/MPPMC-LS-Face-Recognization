<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
foreach ($subject_counts as $subcount) {
    $subject_count[$subcount['subject_code']][$subcount['sheet_status']]=$subcount['Total'];
}
?> 
			<div class="content">
                  <!-- Start Content-->
			<div class="container-fluid">
						<div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
										   <li class="breadcrumb-item active">Subject Wise Reports</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Subject Wise Reports</h4>
                                </div>
                            </div>
                        </div>
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
     
          
            <div class='row'>
                <div class="col-lg-10">
                    <!--Code Writen By Vikas on 06/04/23-->
 <?php $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
);
?>
                        <form action="<?=base_url('reports/courseWise');?>" method="post">
                            <div class="row">
                                <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Select Course Wise</label>
                                            <select name="course" id="course" data-validation-error-msg-required="Course is required" class="form-control">
                                                <option value="All">All</option>
                                                <?php
                                                    $courses = $this->SubjectsModel->get_courses();
                                                    for($i=0;$i<count($courses);$i++){?>

                                                       <option value="<?=$courses[$i]['course_code']?>" <?=$course ==$courses[$i]['course_code']?'selected':'';?> ><?=$courses[$i]['course_name']?></option>
                                                 <?php  }?>
                                            </select>
                                        </div>
                                </div>
                                <div class="col-lg-3">
                                    <button class="btn btn-sm btn-primary" name="search" type="submit" style="margin-top:33px">Search</button>
                                </div>
                            </div>
                             <input type="hidden" id="<?php echo $csrf['name'];?>" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                        </form>
                    <!--Code End Here-->
                </div>
                <div class="col-lg-2" style="margin-top:30px">
                    <p class='pull-right'>
                        <!--Code Alter by Vikas on 06/04/23-->
                        <a href='<?php echo base_url(); ?>reports/<?=!empty($course)?'courseWise':'subjectwise';?>?export=<?=!empty($course)?$course:'html';?>' type="button" class="btn btn-info">
                            <span class="fe-print"></span>  Print 
                        </a>
                        <a href='<?php echo base_url(); ?>reports/<?=!empty($course)?'courseWise':'subjectwise';?>?<?=!empty($course)?'exportxls':'export';?>=<?=!empty($course)?$course:'xls';?>' type="button" class="btn btn-info">
                            <span class="fe-export"></span>  Export 
                        </a>
                         <!--Code Alter by Vikas end here on 06/04/23-->
                    </p>
                </div>
            </div>
            
            
  <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Reports</h4>
                                       
                          
								<table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Total AB</th>
                                            <th>Checked/Rechecked Today</th>
                                            <th>Checked Till Date</th>
                                            <th>Remaining AB</th>
                                            <th>Rejected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($subject_date as $row) {
                                            $today[$row['subject_code']]=$row['sheet_count'];
                                        }
                                            
                                        foreach ($subjects as $subject) {
                                            if (isset($subject_count[$subject['subject_code']])) {
                                                $pcc=$subject_count[$subject['subject_code']];
                                                if (isset($pcc['Pending'])) {
                                                    $pc['Pending']=$pcc['Pending'];
                                                } else {
                                                    $pc['Pending']=0;
                                                }
                                                if (isset($pcc['Assigned'])) {
                                                    $pc['Assigned']=$pcc['Assigned'];
                                                } else {
                                                    $pc['Assigned']=0;
                                                }
                                                if (isset($pcc['Checked'])) {
                                                    $pc['Checked']=$pcc['Checked'];
                                                } else {
                                                    $pc['Checked']=0;
                                                }
                                                if (isset($pcc['Rejected'])) {
                                                    $pc['Rejected']=$pcc['Rejected'];
                                                } else {
                                                    $pc['Rejected']=0;
                                                }
                                                if (isset($pcc['Rechecked'])) {
                                                    $pc['Rechecked']=$pcc['Rechecked'];
                                                } else {
                                                    $pc['Rechecked']=0;
                                                }
                                               //Code Written By Vikas
                                               if (isset($pcc['ReMarking'])) {
                                                $pc['ReMarking']=$pcc['ReMarking'];
                                                } else {
                                                    $pc['ReMarking']=0;
                                                }
                                               //Code End Here
                                                unset($pcc);
                                            } else {
                                                $pc['Pending']=0;
                                                $pc['Assigned']=0;
                                                $pc['Checked']=0;
                                                $pc['Rejected']=0;
                                                $pc['Rechecked']=0;
                                                $pc['ReMarking']=0;

                                            }
                                            $total=$pc['Pending']+$pc['Assigned']+$pc['Checked']+$pc['Rejected']+$pc['Rechecked']+$pc['ReMarking'];
                                            $subject_count[$subject['subject_code']]['Pending']=$pc['Pending'];
                                            if (isset($today[$subject['subject_code']])) {
                                                $today_count=$today[$subject['subject_code']];
                                            } else {
                                                $today_count=0;
                                            }
                                            $checked=intval($pc['Checked'])+intval($pc['Rechecked'])+intval($pc['ReMarking']);
                                            $pending=intval($pc['Pending'])+intval($pc['Assigned']);
                                            echo "<tr>
													<td>".$subject['subject_code']."</td>
													<td>".$subject['subject_name']."</td>
													<td>".$total."</td>
													<td>".$today_count."</td>
													<td>".$checked."</td>
													<td>".$pending."</td>
													<td>".$pc['Rejected']."</td>
												</tr>";
                                            unset($pc);
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
var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date"]);
    myCalendar.attachEvent("onClick", function(d){
        var dateVal = $("#date").val();
        window.location=baseurl+"reports/evaluators/"+myCalendar.getFormatedDate(null,d);
    });
}
doOnLoad();
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

