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
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masters">Base Data</a></li>
                                            <li class="breadcrumb-item active">Papers</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Question Papers</h4>
                                </div>
                            </div>
                        </div> 
						
  
            
            <?php if (sizeof($papers)>0) { ?>
         <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Papers List</h4>
                                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Paper Code</th>
                                            <th>Subject Name</th>
                                            <th class='text-center'>Branch Code</th>
                                            <th class='text-center'>Medium</th>
                                            <th class='text-center'>Paper Set</th>
                                           
											<th class='text-center'>Marking Scheme File</th>
                                          
                                            <th>Model Answer Paper</th>
                                            
                                            <th class='text-center'>Total Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($papers as $paper) {
                                            $subject_array = $this->SubjectsModel->get_subject_code_wise($paper['subject_code']);
                                          $subject_name = $subject_array[0]['subject_name'];
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$paper['paper_code']."</td>
													<td>".$paper['subject_code']."-".$subject_name."</td>
                                                    <td class='text-center'>".$paper['course_code']."</td>
													<td class='text-center'>".$paper['medium_code']."</td>
													<td class='text-center'>".$paper['paper_set']."</td>
													
													<td class='text-center'>";
                                            if ($paper['marking_scheme_json']!='') {
                                                echo "<button type='button' class='btn btn-default btn-circle' onclick=\"view('".$paper['paper_code']."')\"  data-toggle='modal' data-target='#viewMarks'><i class='fe-file-text'></i></button>";
                                            }
                                                echo "</td>";?>
													
													<td><?php 
													$file=explode("/",$paper['paper_model_answer']);
														  $folderPath = "answersheets/uploads/model/".$file[2]."img";
														   $countFile=$folderPath.'/count.txt';
															if (!file_exists($folderPath)) {
																$count=0;
																mkdir($folderPath, 0777, true);
															}else{
																$count=file_get_contents($countFile);
																
															}
													
													?>
													<a href="<?php echo base_url()."upload2/extractNewModel/".$file[2]; ?>" class='btn btn-info' target="_blank">Convert(<?php echo $count; ?>)</a>
													
													</td>
													
													<?php echo "<td class='text-center'>".$paper['paper_total_marks']."</td>";
                                            echo "
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
           
            <?php } else { ?>
            <div class="alert alert-danger">
                No Data/Records Found
            </div>
            <?php } ?>
			 </div>
</div>

<div class="modal fade" id="viewMarks" tabindex="-1" role="dialog" aria-labelledby="viewMarksLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="viewMarksLabel">View Marking Scheme</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div><div class="modal-body" id='markviewhtml'>
                                     
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
      </div>
    </div>
  </div>
</div>
<script>
function view(paperid) { 
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'GET',
            url: base_url+"papers/marktable/"+paperid,      
            success:function(data)
            {   
                $('#markviewhtml').html(data);
            }
        });
}
</script>
