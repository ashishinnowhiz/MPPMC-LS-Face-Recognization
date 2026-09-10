<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
foreach ($paper_counts as $pc) {
    $paper_count[$pc['paper_code']][$pc['sheet_status']]=$pc['Total'];
}
?> 
<div id="page-wrapper">
    <div class='row'>
        <div class="col-lg-12">
            <br/>
            <ol class = "breadcrumb">
               <li><a href = "<?php echo base_url(); ?>">Home</a></li>
               <li class = "active">Request</li>
            </ol>
        </div>
    </div>
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            
    <!-- /.col-lg-6 -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-plus fa-fw"></i> Request</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            <form name='editForm' id='editForm' role="form"  onsubmit="return validateForm('editForm');" action='<?php echo base_url(); ?>request/update' method='POST'>

            <div class='row'>
                <div class="col-lg-12">
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Sheets
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Paper Code</th>
                                            <th>Paper Set</th>
                                            <th>Pending</th>
                                            <th>Checked</th>
                                            <th>Rejected</th>
                                            <th><span id='option'>Request</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($papers as $paper) {
                                            if (isset($paper_count[$paper['paper_code']])) {
                                                $pcc=$paper_count[$paper['paper_code']];
                                                if (isset($pcc['Allocated'])) {
                                                    $pc['Allocated']=$pcc['Allocated'];
                                                } else {
                                                    $pc['Allocated']=0;
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
                                                    
                                                unset($pcc);
                                            } else {
                                                $pc['Allocated']=0;
                                                $pc['Checked']=0;
                                                $pc['Rejected']=0;
                                            }
                                            echo "<tr>
													<td>".$paper['paper_code']."</td>
													<td>".$paper['paper_set']."</td>
													<td>".$pc['Allocated']."</td>
													<td>".$pc['Checked']."</td>
													<td>".$pc['Rejected']."</td>
													<td >
													<input type='text' class='plus' size='6' name='v".$paper['paper_code']."'>
													</td>
												</tr>";
                                            unset($pc);
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                </div>
                    
            </div>
            <div class='row'>
                <div class="col-lg-12">
                    <p class='pull-right'>
                        <button id='submit' type='submit' class='btn btn-primary' name='submit'><i class='fa fa-plus'></i> Request</button>
                    </p>
                </div>
            </div>
            </form>
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
