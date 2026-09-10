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
                                            <li class="breadcrumb-item active">Rejected Reports by Subject</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Rejected Reports by Subject</h4>
                                     <!--Code Written By Vikas on 24/04/23-->
                                      
 <?php $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
);
?>
                                    <form action="<?=base_url('Reports/reject_course_wise');?>" method="post">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                <div class="form-group">
                                                <label for="">Select Course Wise</label>
                                                <select name="course" id="" class="form-control">
                                                    <option value="All">All</option>
                                                    <?php
                                                    $courses = $this->SubjectsModel->get_courses();
                                                    for($i=0;$i<count($courses);$i++){?>

                                                       <option value="<?=$courses[$i]['course_code']?>" <?=$selected_course ==$courses[$i]['course_code']?'selected':'';?> ><?=$courses[$i]['course_name']?></option>
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
                            </div>
                        </div>
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>


          
              <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Reports</h4>
                                       
                          
								<table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Subject</th>
                                            <th>Scripts Rejected</th>

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
													<td>".$report['subject_code']."</td>
													<td><a href=\"".base_url()."reports/rejectedSheets/subject/".$report['subject_code']."\">".$report['sheet_count']."</a></td>
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

