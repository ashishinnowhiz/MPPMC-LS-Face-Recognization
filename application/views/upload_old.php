<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
foreach ($paper_counts as $pc) {
    $paper_count[$pc['paper_code']][$pc['sheet_status']]=$pc['Total'];
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
                                            <li class="breadcrumb-item active">Sync Scripts</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Sync Scripts </h4>
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
                         <div class="col-6">
                             <div class="card">
                                <div class="card-body">
								<h4 class="header-title">Upload  Data & Scripts Downloaded from CS</h4>
                        
                        
                        <?php
                            $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                            );
                            ?>

                                        <form id='upForm' method='POST' action="<?php echo base_url(); ?>upload/files/"  enctype="multipart/form-data" class="form-inline">
                                            <div id='form-group' class="form-group">
                                                <input id='file' name="file" type="file"/>
                                            </div>
                                            <div id='form-group' class="form-group">
                                                <input class='btn btn-primary' type='submit' value='Upload'>
                                            </div>
                                        <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                                        </form>
                                        <div class="progress" style='display:none;margin-top:10px;'>
                                          <div id='bootprogress' class="progress-bar" role="progressbar">
                                            0%
                                          </div>
                                        </div>
                                        <div id='summary'></div>
                        
                    </div>
                    </div>
                    </div>
                                        
                
                 <div class="col-6">
                                <div class="card">
                                    <div class="card-body">
									<h4 class="header-title">Online request</h4>
                        
                            <?php if ($online) { ?>
                            <button type="button" onclick="getAllocations()" class="btn btn-primary">
                            <i class="fe-copy"></i> Check/Request New Allocation</button>
                            <button type="button" onclick="updateMaster();" class="btn btn-info">
                            <i class="fe-refresh-cw"></i> Sync Basedata</button>
                            <button type="button" onclick="updateBankMaster()" class="btn btn-warning">
                            <i class="fe-refresh-cw"></i> Sync Bank Details</button>
                            <?php } else {
    echo "Offline";
                            } ?>
                            <!--
                            <a type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-copy"></i> Request Deallocation</a>
                            -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Allocation Update Log</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                       
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Paper code</th>
                                            <th>File</th>
                                            <th>Updated Time</th>
                                            <th>Mode</th>
                                            <th>Type</th>
                                            <th>Total Script</th>
                                            <th>Files Synced</th>
                                            <th>Image convert</th>
                                            <th>Data Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($allocation as $row) {
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$row['paper_code']."</td>
													<td>".$row['allocation_file']."</td>
													<td>".$row['allocation_time']."</td>
													<td>".$row['allocation_mode']."</td>
													<td>".$row['allocation_type']."</td>
													<td>".$row['allocation_quantity']."</td>
													<td><span id='synced".$row['allocation_id']."'>".$row['allocation_synced_files']."";
                                            if ($row['allocation_quantity']>$row['allocation_synced_files']) {  // && $row['allocation_sync_status']=='Pending'
                                                echo " <a class='btn btn-default btn-circle' onclick=\"syncfiles('".$row['allocation_id']."',".$row['allocation_synced_files'].",".$row['allocation_quantity'].")\"'><i class='fe-refresh-cw'></i></a>";
                                            }
                                                echo "</span></td>";
												?>
												<td><span id="convert<?php echo $row['allocation_id'];?>">
												<?php
												$cn=0;
												foreach($convertImage as $ciResult){
                                            if ($row['allocation_id']==$ciResult['allocation_id']) {
												echo $row['allocation_synced_files']-$ciResult['unConvertedTotal'];
												$cn=1;
                                                ?><a class='btn btn-default btn-circle' onclick="convertImage(<?php echo $ciResult['allocation_id'];?>)"><i class='fe-refresh-cw'></i></a><?php
                                            }
											
												}  
												if($cn==0){echo $row['allocation_synced_files']; }
												echo "</span></td>
													<td><span id='sync".$row['allocation_id']."'>".$row['allocation_sync_status']."</span></td>
													</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div id='test'>
                                
                            </div>  
                        </div>
                    </div>
                </div>
            </div>  
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="myModalLabel">Request New Allocation</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='editForm' id='editForm' role="form"  onsubmit="return validateForm('editForm');" action='<?php echo base_url(); ?>request/update' method='POST'>
      <div class="modal-body">
            <div id='notice'></div>
            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Paper Code</th>
                                            <th>Paper Set</th>
                                            <th>Pending</th>
                                            <th>Checked</th>
                                            <th>Rejected</th>
                                            <th>Unallocated</th>
                                            <th><span id='option'>Request</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $required_evalutor=1;
                                        if ($multieval->multieval=='1') {
                                            if ($multieval->third!='0') {
                                                $required_evalutor=3;
                                            } else {
                                                $required_evalutor=2;
                                            }
                                        }

                                        foreach ($papers as $paper) {
                                            if (isset($paper_count[$paper['paper_code']])) {
                                                $pcc=$paper_count[$paper['paper_code']];
                                                if (isset($pcc['Pending'])) {
                                                    $pc['Pending']=$pcc['Pending'];
                                                } else {
                                                    $pc['Pending']=0;
                                                }
                                                if (isset($pcc['Checked'])) {
                                                    $pc['Checked']=$pcc['Checked'];
                                                    if (isset($pcc['Rechecked'])) {
                                                        $pc['Checked']=$pcc['Checked']+$pcc['Rechecked'];
                                                    }
                                                } else {
                                                    $pc['Checked']=0;
                                                }
                                                if (isset($pcc['Rejected'])) {
                                                    $pc['Rejected']=$pcc['Rejected'];
                                                } else {
                                                    $pc['Rejected']=0;
                                                }
                                                if (isset($pcc['Unallocated'])) {
                                                    $pc['Unallocated']=$pcc['Unallocated'];
                                                } else {
                                                    $pc['Unallocated']=0;
                                                }
                                                    
                                                unset($pcc);
                                            } else {
                                                $pc['Pending']=0;
                                                $pc['Checked']=0;
                                                $pc['Rejected']=0;
                                                $pc['Unallocated']=0;
                                            }
                                            echo "<tr>
													<td>".$paper['paper_code']."</td>
													<td>".$paper['paper_set']."</td>
													<td>".$pc['Pending']."</td>
													<td>".$pc['Checked']."</td>
													<td>".$pc['Rejected']."</td>
													<td>".$pc['Unallocated']."</td>
													<td>";
                                          /*  if ($paper_evaluators[$paper['paper_code']]<$required_evalutor) {
                                            } else {*/
                                                echo "<input type='text' value='99' class='plus' size='6' name='v".$paper['paper_code']."'>";
                                          //  }
                                                echo "</td>
												</tr>";
                                                unset($pc);
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>                      
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <button id='submit' type='submit' class='btn btn-primary' name='submit'><i class='fa fa-plus'></i> Request</button>
      </div>
        <?php
                $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                );
                ?>
        <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="syncModal" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Sync File Progress</h4>
      </div>
      <div class="modal-body" id='sync_status' style='max-height:400px; overflow-y: scroll;'>
            <div id='noticesync'>Please wait while we sync all files</div>
      </div>
      <div class="modal-footer">
	  <span id='syncProcess'><i class='fa fa-spinner fa-pulse fa-fw'></i> syncing</span>
            <span id='closesync' style='display:none;'><a class="btn btn-default" onclick="closeModel()">Close</a></span>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="convertModal" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Convert File Pdf To Image Progress</h4>
      </div>
      <div class="modal-body" id='sync_status' style='max-height:400px; overflow-y: scroll;'>
            <div id='noticeconvert'>Please wait while we convert all files</div>
      </div>
      <div class="modal-footer">
			<span id='convertProcess'><i class='fa fa-spinner fa-pulse fa-fw'></i> Converting Image</span>	
            <span id='closeconvert' style='display:none;'><a class="btn btn-default" data-dismiss="modal">Close</a></span>
      </div>
    </div>
  </div>
</div>
<script>

    function validateForm(FormName)
    {   var x=0;
        <?php
        foreach ($papers as $paper) {
            echo "var y=document.forms[FormName]['v".$paper['paper_code']."'].value;
			
			if(y!=''){
			if (notint(y) || y>99)
			  {
			  alert('Value for papercode ".$paper['paper_code']."  must be integer and less than limit/available');
				document.forms[FormName]['v".$paper['paper_code']."'].focus();
			  return false;
			  }else{ x=y; }
			  }\n";
        }
        ?>
            if(x==0){
                alert('we need atleast one input to Request');
                return false;
            }
    }
  
   function notint(str){
      var numbers = /^[0-9]+$/;  
      if(str.match(numbers))  
      { 
        return false;
      }else{
        return true;
      }
    }
    
</script>


<script>

function updateBankMaster() {
    const check = confirm("are you sure ! want to sync bank details");
  if(check){
    $.ajax({
        url: "<?= base_url('banks/syncBankDetails') ?>",  
        type: "GET", 
        success: function(response) {
            alert(response.message);
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", status, error);
        }
    });
  }
}


function details(input){
    var file = input.files[0];
    var name = file.name;
    var size = file.size;
    var type = file.type;
    //Your validation
    var pos = name.lastIndexOf('.')+1; 
    var len=name.length;
    var ext = name.substring(pos, len);
    if(ext!='zip'){
        alert('file must be zip');
    }else{
        $('#form-group').hide();
        $('.progress').show();
        formUpload();
        $('#form-group').show();
    }
}
function formUpload(){
    var formData = new FormData($('form')[0]);
    $.ajax({
        url: '<?php echo base_url(); ?>upload/files/',  //Server script to process data
        type: 'POST',
        dataType : 'json',
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){ // Check if upload property exists
                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
            }
            return myXhr;
        },
        //Ajax events
        //beforeSend: beforeSendHandler,
        success:function(data)
            {
                if(data.success){
                    location.reload();
                }else{
                    alert(data.message);
                }
            },
        //error: errorHandler,
        // Form data
        data: formData,
        //Options to tell jQuery not to process data or worry about content-type.
        cache: false,
        contentType: false,
        processData: false
    });
}
function progressHandlingFunction(e){
    if(e.lengthComputable){
        //$('progress').attr({value:e.loaded,max:e.total});
        var p=Math.floor((e.loaded*100)/e.total);
        $('#bootprogress').css('width', p+'%');
        $('#bootprogress').html(p+'%');
    }
}

<?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_token']!='') { ?>
    function getAllocations() {
    $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>upload/online/",
            dataType: 'json',           
            success:function(data)
            {   
                if(data.count>0){
                    var msg='';
                    if(data.allocation_count > 0){ msg=data.allocation_count+" Allocation";}
                    if(data.count < data.unallocation_count){ msg=msg+" & "}
                    if(data.unallocation_count>0){ msg=msg+data.unallocation_count+" Unallocation";}
                    alert(msg+' found on server');
                    location.reload();
                }else{
                    var r = confirm("No allocation found on CS server. Click 'OK' to continue request allocation");
                    if (r == true) {
                        $('#myModal').modal('show'); 
                    } 
                }
            }
        });
    }

    /*
    window.onload = function () {
       getAllocations();
    }
    */
    
<?php } ?>
function convertImage(id) {
    $('#convertModal').modal({
      backdrop: 'static',
      keyboard: false
    });
    
    $('#noticeconvert').html('Please wait while we convert all files');
    $('#convertModal').modal('show');
     $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>ReConvertImage/convertfile/"+id,
            dataType: 'json',           
            success:function(data)
            {    
			
                               if(data.success){
                                    
                                    $('#convert'+id).html(data.count);
                                    $('#noticeconvert').html(data.message);
                                }else{
									$('#convert'+id).html(data.count);
                                    $('#noticeconvert').html(data.message);
                                } 
                        
                    $('#closeconvert').show();
                    $('#convertProcess').hide();
            }
        }); 
    }
function closeModel() {
	$('#convertModal').modal('hide');
	$('#syncModal').modal('hide');
	location.reload();
}
function syncfiles(id,count,total) {
    $('#syncModal').modal({
      backdrop: 'static',
      keyboard: false
    });
    
    $('#noticesync').html('Please wait while we sync all files');
    $('#syncModal').modal('show');
    $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>upload/json_sheets/"+id,
            dataType: 'json',           
            success:function(data)
            {    
					var timeD=0;
					
                    for(var i in data)
                        {
                            if(data[i].sheet_url==''){
								var startTime,endTime;
								startTime=new Date();
                                var obj=syncsheet(data[i].sheet_file,id);
								
								
                                if(obj.success){
									endTime=new Date();
									var tdeff=Math.abs(startTime-endTime);
									timeD=tdeff+timeD;
									
									var t=timeDeff(timeD);
                                    count=count+1;
                                    $('#synced'+id).html(count);
                                    $('#noticesync').html(count+' out of '+total+' files synced.Time:'+t);
                                }else{
                                    alert(data[i].sheet_file+' download failed , '+obj.message);
                                }
                            }
                        }
                    update_synced(id,total,timeD);
            }
        });
    }
    function timeDeff(time){
		var msec = time;
		var hh = Math.floor(msec / 1000 / 60 / 60);
		msec -= hh * 1000 * 60 * 60;
		var mm = Math.floor(msec / 1000 / 60);
		msec -= mm * 1000 * 60;
		var ss = Math.floor(msec / 1000);
		msec -= ss * 1000;
		return hh + ":" + mm + ":" + ss;
	}
	function syncsheet(file,id) {
        var obj;
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>upload/syncfile/"+file+"/"+id,
            dataType: 'json',           
            success:function(data)
            {    
                obj=data;
            },
            async: false
        });
        return obj;
    }
    function update_synced(id,total,timeD) {
		var t=timeDeff(timeD);
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>upload/update_synced/"+id,
            dataType: 'json',           
            success:function(data)
            {    
                if(total==data.count){
                    $('#synced'+id).html(data.count);
                    $('#sync'+id).html('Synced');
                }else{
                    $('#synced'+id).html(data.count+' <a class="btn btn-default btn-circle" onclick="syncfiles(\''+id+'\','+data.count+','+total+')"><i class="fe-refresh-cw"></i></a>');
                }
                $('#noticesync').html('Sync process completed. Total '+data.count+' files synced.Time:'+t);
				$('#syncProcess').hide();
                $('#closesync').show();
            },
            async: false
        });
    }
    
    function updateMaster() {
        var r = confirm("Are you Sure!");
        if (r == true) {
            window.location='<?php echo base_url(); ?>upload/master/';
        } 
    }
</script>

