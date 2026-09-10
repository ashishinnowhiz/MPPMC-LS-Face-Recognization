<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
	foreach($paper_counts AS $pc){
		$paper_count[$pc['paper_code']][$pc['sheet_status']]=$pc['Total'];
	}
?> 
<div id="page-wrapper">
	<div class='row'>
		<div class="col-lg-12">
			<br/>
			<ol class = "breadcrumb">
			   <li><a href = "<?php echo base_url(); ?>">Home</a></li>
			   <li class = "active">Allocation Center</li>
			</ol>
		</div>
	</div>
             <?php if ($this->session->flashdata('error') == TRUE): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
			
	<!-- /.col-lg-6 -->
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-adjust fa-fw"></i> Allocation Center</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

			<?php
				foreach($center_counts AS $count){
					$center_count[$count['center_code']][$count['sheet_status']]=$count['Total'];
				}
			?>
			<div class='row'>
				<div class="col-lg-5">
					
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        Total sheets (EXAM - MPSOSEB2016 Specific)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
				
							<div class="table-responsive">
                                <table class="table table-striped table-hover">
									<thead>
                                        <tr>
											<th>Paper Code</th>
											<th>Paper Set</th>
											<th>Unallocated</th>
											<th>Allocated</th>
											<th>Checked</th>
											<th>Rejected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											foreach($papers AS $paper){
												if(isset($paper_count[$paper['paper_code']])){
													$pcc=$paper_count[$paper['paper_code']];
													if(isset($pcc['Scanned'])){ $pc['Scanned']=$pcc['Scanned']; }else{ $pc['Scanned']=0; }
													if(isset($pcc['Allocated'])){ $pc['Allocated']=$pcc['Allocated']; }else{ $pc['Allocated']=0; }
													if(isset($pcc['Checked'])){ $pc['Checked']=$pcc['Checked']; }else{ $pc['Checked']=0; }
													if(isset($pcc['Rejected'])){ $pc['Rejected']=$pcc['Rejected']; }else{ $pc['Rejected']=0; }
													
													unset($pcc);
												}else{
													$pc['Scanned']=0;
													$pc['Allocated']=0;
													$pc['Checked']=0;
													$pc['Rejected']=0;
												}
												$paper_count[$paper['paper_code']]['Scanned']=$pc['Scanned'];
												echo "<tr>
													<td>".$paper['paper_code']."</td>
													<td>".$paper['paper_set']."</td>
													<td>".$pc['Scanned']."</td>
													<td>".$pc['Allocated']."</td>
													<td>".$pc['Checked']."</td>
													<td>".$pc['Rejected']."</td>
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
							
				<div class="col-lg-7">
					
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Sheets
							<?php if(isset($center_count['0']['Scanned'])){ echo "Unallocated Total: <b>".$center_count['0']['Scanned']."</b>"; } ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
								<table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Center Name</th>
                                            <th>Code</th>
											<th>Pending</th>
											<th>Checked</th>
											<th>Rejected</th>
											<th style='width:130px;'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											
											if(!isset($serial)){ $serial=0; }
											foreach($centers AS $center){
												$serial=$serial+1;
												if(isset($center_count[$center['center_code']])){
													$cc=$center_count[$center['center_code']];
													if(isset($cc['Allocated'])){ $count['Allocated']=$cc['Allocated']; }else{ $count['Allocated']=0; }
													if(isset($cc['Checked'])){ $count['Checked']=$cc['Checked']; }else{ $count['Checked']=0; }
													if(isset($cc['Rejected'])){ $count['Rejected']=$cc['Rejected']; }else{ $count['Rejected']=0; }
												}else{
													$count['Allocated']=0;
													$count['Checked']=0;
													$count['Rejected']=0;
												}
												echo "<tr>
													<td>".$serial."</td>
													<td>".$center['center_name']."</td>
													<td>".$center['center_code']."</td>
													<td>".$count['Allocated']."</td>
													<td>".$count['Checked']."</td>
													<td>".$count['Rejected']."</td>
													<td>
													<button type='button' onclick=\"edit('".$center['center_code']."','view')\"  data-toggle='modal' data-target='#editModal' class='btn btn-info btn-circle'><i class='fa fa-eye'></i></button> 
													<button type='button' onclick=\"edit('".$center['center_code']."','plus')\"  data-toggle='modal' data-target='#editModal' class='btn btn-primary btn-circle'><i class='fa fa-plus'></i></button> 
													<button type='button' onclick=\"edit('".$center['center_code']."','minus')\"  data-toggle='modal' data-target='#editModal' class='btn btn-danger btn-circle'><i class='fa fa-minus'></i></button>
													</td>
												</tr>";
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
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="editModalLabel">Allocate</h4>
      </div>
	  <form name='editForm' id='editForm' role="form"  onsubmit="return validateForm('editForm');" action='<?php echo base_url(); ?>allocate/update' method='POST'>
      <div class="modal-body" id='edithtml'>
				  
							<div class="table-responsive">
                                <table class="table table-striped table-hover">
									<thead>
                                        <tr>
                                            
											<th colspan='6' style='border-right:1px solid silver;'>EXAM - MPSOSEB2016 Specific</th>
											<th colspan='4'><span id='center_code'></span> Center Specific</th>
											
                                        </tr>
                                        <tr>
											<th>Paper Code</th>
											<th>Paper Set</th>
											<th>Unallocated</th>
											<th>Allocated</th>
											<th>Checked</th>
											<th style='border-right:1px solid silver;'>Rejected</th>
											<th>Pending</th>
											<th>Checked</th>
											<th>Rejected</th>
											<th><span id='option'>Allocate</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											foreach($papers AS $paper){
												if(isset($paper_count[$paper['paper_code']])){
													$pcc=$paper_count[$paper['paper_code']];
													if(isset($pcc['Scanned'])){ $pc['Scanned']=$pcc['Scanned']; }else{ $pc['Scanned']=0; }
													if(isset($pcc['Allocated'])){ $pc['Allocated']=$pcc['Allocated']; }else{ $pc['Allocated']=0; }
													if(isset($pcc['Checked'])){ $pc['Checked']=$pcc['Checked']; }else{ $pc['Checked']=0; }
													if(isset($pcc['Rejected'])){ $pc['Rejected']=$pcc['Rejected']; }else{ $pc['Rejected']=0; }
													
													unset($pcc);
												}else{
													$pc['Scanned']=0;
													$pc['Allocated']=0;
													$pc['Checked']=0;
													$pc['Rejected']=0;
												}
												$paper_count[$paper['paper_code']]['Scanned']=$pc['Scanned'];
												echo "<tr>
													<td>".$paper['paper_code']."</td>
													<td>".$paper['paper_set']."</td>
													<td>".$pc['Scanned']."</td>
													<td>".$pc['Allocated']."</td>
													<td>".$pc['Checked']."</td>
													<td style='border-right:1px solid silver;'>".$pc['Rejected']."</td>
													<td><span id='Allocated".$paper['paper_code']."'>0<span></td>
													<td><span id='Checked".$paper['paper_code']."'>0<span></td>
													<td><span id='Rejected".$paper['paper_code']."'>0<span></td>
													<td >
													<input type='text' class='plus' size='6' name='v".$paper['paper_code']."'>
													<input type='checkbox' class='minus' name='minus[]' value='".$paper['paper_code']."'>
													</td>
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
		<input type='hidden' name='center_code' value=''>
        <button id='submit' type='submit' class='btn btn-primary' name='submit'><i class='fa fa-save'></i> Save</button>
		<a class='btn btn-primary disabled' id='loading' style='display:none;' >Loading</a>
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
<script>
function edit(center_code,option) { 
	document.getElementById("editForm").reset();
	if(option=='minus'){
		$('#option').html('Select');
		$('.plus').hide();
		$('.minus').show();
		$('#submit').html('Unallocate');
	}else if(option=='view'){
		$('#option').html('');
		$('.plus').hide();
		$('.minus').hide();
	}else{
		$('#option').html('Allocate');
		$('.minus').hide();
		$('.plus').show();
		$('#submit').html('Allocate');
	}
	$('#submit').hide();
	$('#loading').show();
	$('#center_code').html(center_code);
	document.forms["editForm"]['center_code'].value=center_code;
	<?php
		foreach($papers AS $paper){
			echo "$('#Allocated".$paper['paper_code']."').html('0');\n";
			echo "$('#Checked".$paper['paper_code']."').html('0');\n";
			echo "$('#Rejected".$paper['paper_code']."').html('0');\n";
		}
	?>
	var base_url='<?php echo base_url(); ?>';
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: base_url+"allocate/center/"+center_code,		
			success:function(data)
			{	
				for (i = 0; i < data.length; i++) {
					var obj=data[i];
					$('#'+obj.sheet_status+obj.paper_code).html(obj.Total);
				} 
			}
		});
	$('#loading').hide();
	if(option=='view'){
		$('#submit').hide();
	}else{
		$('#submit').show();
	}
}
</script>

<script>

	function validateForm(FormName)
	{ 	var x=0;
		if($('#submit').html()=='Allocate'){
		<?php
		foreach($papers AS $paper){
			echo "var y=document.forms[FormName]['v".$paper['paper_code']."'].value;
			
			if(y!=''){
			if (notint(y) || y>".$paper_count[$paper['paper_code']]['Scanned']." || y>99)
			  {
			  alert('Value for papercode ".$paper['paper_code']."  must be integer and less than limit/available');
				document.forms[FormName]['v".$paper['paper_code']."'].focus();
			  return false;
			  }else{ x=y; }
			  }\n";
			
		}
		?>
			if(x==0){
				alert('we need atleast one input to allocate');
				return false;
			}
		}else{
			$('input[type=checkbox]').each(function () {
				if(this.checked){
					x=x+1;
				}
			});
			if(x==0){
				alert('we need atleast one paper code selected to deallocate');
				return false;
			}
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