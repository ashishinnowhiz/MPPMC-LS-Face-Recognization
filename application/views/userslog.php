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
                                            <li class="breadcrumb-item">Audit Logs</li>
                                            <li class="breadcrumb-item active">User Log</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">User's Audit Logs</h4>
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
                 <div class="col-lg-12">
                 <p class='text-right'>
                        <button type="button" class="btn btn-success" style="width:140px"  data-toggle="modal" data-target="#myModal">
                         <i class="fe-plus mr-1"></i> Create Log</button>
                 </p>
                </div>
            </div>
            
            <?php
         if(isset($audits)){
 
            if (sizeof($audits)>0) { ?>
                   <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">User's Audits List</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Created By</th>
                                            <th>Sheet File</th>
                                            <th>Time</th>
                                            <!-- <th>File Path</th> -->
                                            <th class='text-center'>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                            foreach (array_reverse($audits) as $audit) {
                                                $serial=$serial+1;
                                                echo "<tr >
                                                        <td>".$serial."</td>
                                                        <td>".$audit['user_name']."</td>
                                                        <td>".$audit['sheet_file']."</td>
                                                        <td>".$audit['audit_time']."</td>
                                                       
                                                        <td class='text-center'>
                                                            <a href=".base_url('auditlog/downloadAudit/'.$audit['audit_id'])." class='btn btn-success btn-sm'>
                                                            Download
                                                            </a>
                                                        </td>
                                                      </tr>";
                                            }
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            
            <?php } else { ?>
            <div class="alert alert-danger">
                No Data/Records Found
            </div>
            <?php } ?>
		</div>
</div>

<?php $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
);
?>

<!--Student Model-->
<!-- Modal -->
<div class="modal fade" id="stModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title" id="myModalLabel">Student's Roll Numbers</h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

            <ul id="stList">
                
            </ul>
                          
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
      </div>
    </div>
  </div>
</div>
<!--Student Model Ends Here-->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title" id="myModalLabel">Create Audit</h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='SubForm' id='SubForm' role="form" action='<?php echo base_url(); ?>courses/add' method='POST'>
      <div class="modal-body">
      
            <div class="form-group" id="selectBox">
                <label for="paper_code">Sheet File*</label>
                <select name="paper_code" id="paper_code" class="form-control" onChange = "sheetCode()">
                <!-- <select name="paper_code" id="paper_code" class="form-control"> -->
                    <option value="">-- Select Sheet File --</option>
                    <?php foreach($sheets as $sheet){ ?>
                        <option value="<?=$sheet['sheet_file']?>"><?=$sheet['sheet_file']?></option>
                    <?php } ?>
                </select>
            </div>
      
          
            <input type="hidden" id="<?php echo $csrf['name'];?>" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />                            
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="button" id="addBtn" class="btn btn-primary" value='Create Audit' onclick="validation()">
      </div>
      </form>
    </div>
  </div>
</div>

<script>

function sheetCode(){
    const existingError = document.getElementById("error");
    if (existingError) {
        existingError.remove();
    }
}
 


function validation(){
    const paperCode = document.getElementById("paper_code");
    if(!paperCode.value){
        const newChild = document.createElement('span');
        newChild.textContent = "Sheet File Required";
        newChild.style.color = "#ff0000c2";
        newChild.id = "error";
        const parent = paperCode.parentNode;
        $(`span`).html("");
        parent.appendChild(newChild);
    }else{
        console.log(paperCode.value);
         finalSubmit(paperCode.value);
    }
}

function finalSubmit(paperCode){
   
         $.ajax({
            type: "GET",
            url: '<?=base_url("auditlog/addAudit")?>',
            data: { sheet_file : paperCode }, 
            success: function(response) {
                if(response){
                    window.location.href = '<?=base_url("auditlog/resMessage")?>/success';
                }else{
                    window.location.href = '<?=base_url("auditlog/resMessage")?>/error';
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    
}
</script>

