<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="content">

                    <!-- Start Content-->
			<div class="container-fluid">
						<div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masters">Base Data</a></li>
                                            <li class="breadcrumb-item active">Answer Scripts</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Answer Scripts</h4>
                                </div>
                            </div>
                        </div> 
  
             <?php if ($this->session->flashdata('error') == TRUE): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
			<!--<div class="alert alert-success hide" id="success"></div>
			<div class="alert alert-danger hide" id="error"></div>-->
	
			
			<?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){ ?>
			<div class='row'>
				
					<div class="col-2">
					
					 <div class="page-title-box">
							<!--
							<div class="btn-group">
								<?php 
									if(isset($_GET['day']) && ($_GET['day']=='yesterday')){
										echo '<a type="button" class="btn btn-default" onclick="SelectDay(\'today\')">Today</a>
										<a type="button" class="btn btn-primary">Yesterday</a>';
									}else{
										echo '<a type="button" class="btn btn-primary">Today</a>
										<a type="button" class="btn btn-default" onclick="SelectDay(\'yesterday\')" >Yesterday</a>';
									}
								?>
							</div>
							-->
			<input type="text"  name='date' class="form-control" placeholder="Select Date" value="<?php echo $date;?>" onchange="onSelectDate()" id="basic-datepicker" />
			</div>
					</div>
					<div class="col-2" >
					 <div class="page-title-box">
							<select name='Evaluator' class='form-control' onchange="SelectEvaluator(this.value)" id="Evaluator">
								<option value=''>All</option>
								<?php
									if(isset($_GET['evaluator'])){ $evalr=$_GET['evaluator']; }else{ $evalr=''; }
									foreach($evaluators AS $evaluator){
										echo "<option value='".$evaluator['examiner_username']."'";
										if($evaluator['examiner_username']==$evalr){
											echo " selected='selected'";
										}
										echo ">".$evaluator['examiner_name']."</option>";
									}
								?>
							</select>
							</div>
					</div>
					 <div class="col-8">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                          
											
											
													
														<a href="<?php echo base_url(); ?>welcome/marking">	<button class='btn btn-info'  data-toggle='modal' data-target=''> Start Marking</button></a>
											
										

                                    </div>
                                  
                                </div>
                            </div>
				
			</div>
			<br/>
			<?php } ?>

			
			
		 <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Answer Scripts Records</h4>
                            <div class="table-responsive">
							<?php if(sizeof($sheets)>0){ ?>
							<form name="sheetFrm" id="sheetFrm" method="POST" action="<?php echo base_url();?>/sheets/updateSheet">
							
                                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
											<th>Paper Code</th>
											<th>Medium Code</th>
											<th>Region Code</th>
											<th>Marker</th>
											<th>Score</th>
                                            
											<th>Status</th>
											
											<?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){ ?>
											<th>Recheck <span class="text-danger">*</span></th>
											<?php } ?>
											<th>Marking Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
										#var_dump($sheets);
											if(!isset($serial)){ $serial=0; }
											foreach($sheets AS $sheet){
												$serial=$serial+1;
												echo "<tr>
													<td>".$serial."</td>
													<td>".$sheet['paper_code']."</td>
													<td>".$sheet['medium_code']."</td>
													<td>".$sheet['region_code']."</td>
													<td>".$sheet['examiner_username']."</td>
													<td>".$sheet['final_marks']."</td>
													
													<td>".$sheet['sheet_status']."</td>";
													if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){ 
														if($sheet['remark'] == 1){
																$checked = "Rechecked";
															}else{
																$checked = "";
															}
													echo "<td> <input data-validation='required length alphanumeric' data-validation-allowing=' ' name='recheck_".$sheet['sheet_id']."_remarks' maxlength='100' id='recheck_".$sheet['sheet_id']."_remarks' value='".$sheet['remark_remarks']."'  ".$checked." type='text' placeholder=\"Enter Remarks\"  /> <input name='recheck_".$sheet['sheet_id']."' id='recheck_".$sheet['sheet_id']."' value='1' ".$checked." type='checkbox' onclick=\"return updateRecheck(this)\" />  </td>";
													} 
													echo "<td>".$sheet['evaluation_time']."</td>
												</tr>";
											}
										?>
                                    </tbody>
                                </table>
								<!--
								<input class='btn btn-sm btn-primary' name='save' id='save' value='Save'  type='submit'  />
								-->
								</form>
								<?php echo $pagination; ?>
								<?php }else{ ?>
								<div class="alert alert-danger">
									No Data/Records Found
								</div>
                            </div>
							
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
				</div>
			</div>
			
			<?php } ?>
</div>
</div>
</div>
</div>

<script type="text/javascript">
function updateRecheck(ele){
	
	

	var eleid = ele.id;
	var idArr = eleid.split('_');
	var id = idArr[1];
	var status = $(ele).prop("checked");


		if(status == true ){
			var remarksVal = $("#"+eleid+"_remarks").val();
			if(remarksVal == "") {
				alert("Please enter remarks");
				$("#"+eleid+"_remarks").focus();
				return false;
			}
			var val = 1;

		} else {
			var remarksVal = "";
			var val = 0;
		}
		
		
		var base_url='<?php echo base_url(); ?>';
		var strData="id="+id+"&val="+val+"&remarks="+remarksVal;
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: base_url+"recheck_m/updateSheet/",
			data: strData,		
			success:function(data)
			{	
				if(data.success == true){
					$("#success").removeClass('hide');
					$("#success").html('Script recheck value updated successfully.');
					return false;
				} else {
					$("#error").removeClass('hide');
					$("#error").html('There seems to be some error while updating Script.');
					return false;
				}
			}
		});
}

var evaluator='';
var date='';
function SelectDay(day){
	<?php if(isset($_GET['evaluator'])){ echo  "evaluator='".$_GET['evaluator']."';"; } ?>
	window.location='<?php echo base_url(); ?>recheck_m?evaluator='+evaluator+'&day='+day;
}
function SelectEvaluator(evaluator){
	date= $("#basic-datepicker").val();
	
	window.location='<?php echo base_url(); ?>recheck_m?evaluator='+evaluator+'&date='+date;
}
</script>

<script>
var baseurl = "<?php echo base_url(); ?>";
function onSelectDate(){
	var dateVal = $("#basic-datepicker").val();
	var evaluator = $("#Evaluator").val();
	window.location=baseurl+"recheck_m?evaluator="+evaluator+"&date="+dateVal;
  
}
/* var baseurl = "<?php echo base_url(); ?>";
var myCalendar;
function doOnLoad() {
	myCalendar = new dhtmlXCalendarObject(["date"]);
	myCalendar.attachEvent("onClick", function(d){
		var dateVal = $("#date").val();
		<?php if(isset($_GET['evaluator'])){ echo  "evaluator='".$_GET['evaluator']."';"; } ?>
		window.location=baseurl+"recheck?evaluator="+evaluator+"&date="+myCalendar.getFormatedDate(null,d);
	});
}
doOnLoad(); */
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