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
                                            <li class="breadcrumb-item active">Results</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Results<?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
                        echo "- ".$_SESSION[$this->config->item('exam')['exam_session']]['user_center'];
                                                                                                      } ?></h4>
                                </div>
                            </div>
                        </div>
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            

            
            <!--
            <div class='row'>
                <div class="col-lg-4">
                <?php if ($total>0) { ?>
                    <div class="panel panel-info">
                        <div class="panel-heading">Download allocated data & sheets for <?php echo $_SESSION[$this->config->item('exam')['exam_session']]['user_center']; ?></div>
                        <div class="panel-body text-center">
                                <a href='<?php echo base_url(); ?>download/json' type="button" class="btn btn-success">
                                    <i class="fa fa-download fa-fw"></i> Download data & sheets
                                </a>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="panel panel-danger">
                        <div class="panel-heading">There is no allocation for center</div>
                        <div class="panel-body text-center">
                                <a href='<?php echo base_url(); ?>request' type="button" class="btn btn-primary">
                                    <i class="fa fa-copy fa-fw"></i> Request Anwsersheets
                                </a>
                        </div>
                    </div>
                <?php } ?>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">Upload  Data & Sheets Checked from BHO</div>
                        <div class="panel-body text-center">
                                        <div class="form-group text-center">
                                            <input type="file"> 
                                        </div>
                                
                        </div>
                    </div>
                </div>
            </div>
            -->
            
           <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Result Data</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date</th>
                                           
                                            <th>Pending Sync</th><!--Line Added By Vikas-->
											 <th>Total Checked/Rejected</th>
                                            <th>Result Data</th>
                                            <!--<th>Checked PDF sheets</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                            $total=0;
                                        foreach ($results as $row) {
                                            $serial=$serial+1;
                                            $total=$total+$row['Total'];
                                                
                                            if (isset($downloaded[$row['evaluation_date']])) {
                                                $class='default';
                                                $dt=$time[$row['evaluation_date']];
                                                $sc = intval($downloaded[$row['evaluation_date']]);
                                            } else {
                                                $class='primary';
                                                $dt='-';
                                                $sc= 0;
                                            }
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$row['evaluation_date']."</td>
													<td>".(intval($row['Total'])-$sc)."</td>
													<td>".$row['Total']."</td>
													<td>
													<button type='button' href='".base_url()."download/json/".$row['evaluation_date']."' class='btn btn-".$class." btn-circle'><i class='fe-download'></i></button>";
                                            if ($online) {
                                                echo " <button type='button' onclick=\"startSync('".$row['evaluation_date']."')\"' class='btn btn-success btn-circle' data-toggle='tooltip' title='Sync' ><i class='fe-refresh-cw'></i></button> ";
                                            } else {
                                                echo " Offline ";
                                            }
                                                    
                                            echo "<span id='dt_".$row['evaluation_date']."' style='font-size:80%;'><i>".$dt."</i></span>
													</td></tr>";
                                                /*
                                                    <td>
                                                    <a type='button' href='".base_url()."download/files/".$row['evaluation_date']."' class='btn btn-".$class." btn-circle'><i class='fa fa-file-pdf-o'></i></a><!--
                                                    <a type='button' href='".base_url()."download/sync/".$row['evaluation_date']."' class='btn btn-success btn-circle' data-toggle='tooltip' title='Sync' ><i class='fe-refresh-cw'></i></a>-->

                                                    <span style='font-size:80%;'><i>".$dt."</i></span>
                                                    </td>
                                                */
                                        }
                                        ?>
                                        
                                                <!--
                                                <tr>
                                                    <td></td>
                                                    <td class='text-right'><b>Total</b></td>
                                                    <td><?php echo $total; ?></td>
                                                    <td>
                                                    <a type='button' href='<?php echo base_url(); ?>"download/json/' class='btn btn-primary'><i class='fa fa-cloud-download'></i> All Data</a>
                                                    </td>
                                                    <td>
                                                    <a type='button' href='<?php echo base_url(); ?>download/json/' class='btn btn-primary'><i class='fa fa-file-pdf-o'></i> All Files</a>
                                                    </td>
                                                </tr> 
                                                -->
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
            
</div>

<div class="modal fade" id="syncModal" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Sync result Progress</h4>
      </div>
      <div class="modal-body" id='sync_status' style='max-height:400px; overflow-y: scroll;'>
            <div id='noticesync'>Please wait while we sync all result</div>
      </div>
      <div class="modal-footer">
            <span id='closesync' style='display:none;'><a class="btn btn-default" data-dismiss="modal">Close</a></span>
      </div>
    </div>
  </div>
</div>

<script>
function syncresult(date) {
    $('#syncModal').modal({
      backdrop: 'static',
      keyboard: false
    });
    
    $('#noticesync').html('Please wait while we sync all result');
    $('#syncModal').modal('show');
    
    $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>download/syncresult/"+date,
            dataType: 'json',           
            success:function(data)
            {   
                if(data.success){
                    $('#noticesync').html(data.message);
                    $('#dt_'+date).html('Synced Just Now');
                }else{
                    $('#noticesync').html(data.message);
                }
                $('#closesync').modal('show');
            }
        });
        
    }
window.onload = function() {
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>sync/activities/",
            dataType: 'json',           
            success:function(data)
            {   
                if(data.success){
                    //alert(data.message);
                }else{
                    alert(data.message);
                }
            }
        });
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>sync/limit_updates/",
            dataType: 'json',           
            success:function(data)
            {   
                if(data.success){
                    //alert(data.message);
                }else{
                    alert(data.message);
                }
            }
        });
}

function startSync(date) {
    $('#syncModal').modal({
      backdrop: 'static',
      keyboard: false
    });
    
    $('#noticesync').html('Collecting data to sync');
    $('#syncModal').modal('show');
    $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>download/createJson/"+date,
            dataType: 'json',           
            success:function(data)
            {    
                var errorCount=0;
                if(data.success){
                    $('#noticesync').append('<br/>Attendance <span id="attendanceCount">0</span>/'+data.attendance);
                    $('#noticesync').append('<br/>Sheets <span id="sheetsCount">0</span>/'+data.sheets);
                    $('#noticesync').append('<br/>Evaluations <span id="evaluationsCount">0</span>/'+data.evaluations);
                    var filename=data.file;
                    if(data.attendance>0){
                        var attendance = syncData(filename, 'attendance',data.attendance);
                        errorCount = errorCount + (data.attendance - attendance);
                    }
                    if(data.sheets>0){
                        var sheets = syncData(filename, 'sheets',data.sheets);
                        errorCount = errorCount + (data.sheets - sheets);
                    }
                    if(data.evaluations>0){
                        var evaluations = syncData(filename, 'evaluations',data.evaluations);
                        errorCount = errorCount + (data.evaluations - evaluations);
                    }
                }else{
                    $('#noticesync').html(data.message);
                }
                if(errorCount==0){
                    updateSynced(date,sheets);
                }else{
                    $('#noticesync').append('Sync process completed. '+errorCount+' records not synced');
                }
                $('#closesync').modal('show');
                    //update_synced(id,total);
            }
        });
    }//[{"sheet_id":"21","paper_id":"0","paper_set":"","sheet_file":"00211078.pdf","sheet_scan_url":"","paper_code":"002","sheet_marks":"0","examiner_id":"0","examiner_username":"","evaluator_code":"","center_id":"0","medium_code":"1","region_code":"24","subject_code":"002","roll_no":"0","exam_code":"0","center_code":"BHO","sheet_status":"Pending","sheet_time":"2017-02-03 03:51:09","sheet_updated_time":"0000-00-00 00:00:00","sheet_allocation_time":"0000-00-00 00:00:00","sheet_updated_by":"0","evaluation_marks":"0","evaluation_time":"0000-00-00 00:00:00","evaluation_date":"0000-00-00","sheet_deleted":"0","allocation_id":"12"}]
    function syncData(filename, name, count) {
        var successCount=0;
        for(var i=0;i<count;i++){
            var response=syncOne(filename, name, i);
if(response){
            if (response.success) {
                successCount=successCount+1;
                $('#'+name+'Count').html(successCount);
            }
}
            //$('#noticesync').append('<br/> '+name+'-'+JSON.stringify(response));
        }
        return successCount;
    }
    function syncOne(file,name,row){
        var obj;
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>download/syncJson/"+file+"/"+name+"/"+row,
            dataType: 'json',   
            success:function(data)
            {    
                obj=data;
            },
            async: false
        });
        return obj;
    }
    function updateSynced(date,count) {
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>download/updateSync/"+date+"/"+count,
            dataType: 'json',           
            success:function(data)
            {    
                $('#noticesync').append('<br/>Sync process completed. Total '+count+' result scripts synced');
                $('#dt_'+date).html('Synced Just Now');
            },
            async: false
        });
    }
</script>
