<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
); ?>

	<div class="content">
                  <!-- Start Content-->
			<div class="container-fluid">
						<div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
											 <li class="breadcrumb-item active">Checked Scripts</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Checked Scripts</h4>
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
                                    <form id='filter_form' class='form-inline' action='<?php echo base_url(); ?>results' method='GET'>
                                        <div class="form-group">
                                            <!--<label>Paper</label>-->
                                            <select name='paper' class="form-control">
                                            <option value=''>Select Paper</option>
                                                <?php
                                                foreach ($papers as $paper) {
                                                    echo "<option value='".$paper['paper_code']."'";
                                                    if ($this->input->get('paper')==$paper['paper_code']) {
                                                        echo " selected='selected'";
                                                    }
                                                    echo ">".$paper['paper_code']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <div class='form-group'>
                                            <!--<label>Date</label>-->
                   <input type="text" class="form-control" name="date_from" id='basic-datepicker' placeholder="From Date" value="<?php if($this->input->get('date_from')){echo $this->input->get('date_from');}else{echo date('Y-m-d');}?>" onclick="setSens();" readonly="true"/>&nbsp;&nbsp;&nbsp;
                   <input type="text" class="form-control" name="date_to" id='minmax-datepicker' placeholder="To Date" value="<?php if($this->input->get('date_to')){echo $this->input->get('date_to');}else{echo date('Y-m-d');}?>"   readonly="true"/>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <input type='hidden' id='export' name='export' value=''>
                                        
										<button type="button" class="btn btn-primary" onclick="filter()"><i class="fe-search"></i> Search</button> &nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-info" href='<?php echo base_url(); ?>results'><i class="fe-reset"></i> Clear</a> 
                                    </form>
                    <!--
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        -->
                </div>
            </div>
            
           

            <?php if (sizeof($sheets)>0) { ?>
            <div class='row'>
                <div class="col-lg-12">
                    <p class='pull-right'>
                        <a href='<?php echo base_url(); ?>results/csv' type="button" class="btn btn-primary">
                            <span class="glyphicon glyphicon-export"></span>  Export to CSV
                        </a>
                        <!--
                        <a href='<?php echo base_url(); ?>results/download' type="button" class="btn btn-info">
                            <span class="glyphicon glyphicon-export"></span>  Export 
                        </a>
                        -->
                    </p>
                </div>
            </div>

								
			<div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <!-- <h4 class="header-title">Answer Sheets Results ( Total : <?php echo $total; ?>)</h4> -->
                        <h4 class="header-title">Answer Sheets Results ( Total : <?php echo count($sheets); ?>)</h4>
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
							<thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Paper Code</th>
                                            <th>Medium Code</th>
                                            <th>Head Marker</th>
                                            <th>Marker</th>
                                            <th>Marks</th>
                                            <th>HM Marks</th>
                                            <th>Final Marks</th>
                                            <th>Duration</th>
                                            <th>Marking Date</th>
                                            <th>Recheck Duration</th>
                                            <th>Recheck Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($sheets as $sheet) {
                                            $serial=$serial+1;
                                            //$diff=strtotime($sheet['evaluation_time'])-strtotime($sheet['sheet_assign_time']);
                                            //$min=intval($diff/60);
                                            //$sec=$diff%60;
                                            if (($sheet['recheck_time']==null) or ($sheet['recheck_time']=='0000-00-00 00:00:00')) {
                                                $recheck_duration='NA';
                                                $recheck_time='NA';
                                            } else {
                                                $recheck_duration=$sheet['recheck_duration'];
                                                $recheck_time=$sheet['recheck_time'];
                                                //$re_diff=strtotime($sheet['recheck_time'])-strtotime($sheet['recheck_assign_time']);
                                                //$re_min=intval($re_diff/60);
                                                //$re_sec=$diff%60;
                                                //$recheck_duration=$re_min.":".$re_sec." minutes";
                                            }
                                                
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$sheet['paper_code']."</td>
													<td>".$sheet['medium_code']."</td>
													<td>".$sheet['examiner_username']."</td>
													<td>".$sheet['evaluator_code']."</td>
													<td>".$sheet['evaluation_marks']."</td>
													<td>".$sheet['head_evaluation_marks']."</td>
													<td>".$sheet['final_marks']."</td>
													<td>".$sheet['check_duration']."</td>
													<td>".$sheet['evaluation_time']."</td>
													<td>".$recheck_duration."</td>
													<td>".$recheck_time."</td>
													<td>".$sheet['sheet_status']."</td>
												</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                                <!-- <?php echo $pagination; ?> -->
                                <br/>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
            <?php } else { ?>
            <div class="alert alert-danger">
                No Data/Records Found
            </div>
            <?php } ?>
			</div>
</div>


<script>

var baseurl = "<?php echo base_url(); ?>";
function setSens(){
var minDate = $("#basic-datepicker").val();

$("#minmax-datepicker").flatpickr({minDate:minDate,maxDate:""});
}
/* var myCalendar;
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
