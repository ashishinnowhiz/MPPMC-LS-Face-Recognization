<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


 <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            
                
				
				<div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                          <?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
											
											<strong>Logged In Users : <?php echo sizeof($logged_in); ?>
											</strong>&nbsp;&nbsp;&nbsp;			
															<button class='btn btn-danger'  data-toggle='modal' data-target='#resetModal'><i class="fe-log-out"></i> Reset Login</button>
											
										<?php } ?>

                                    </div>
                                    <h4 class="page-title">Dashboard</h4>
                                    <?php if ($this->session->flashdata('success') == true) : ?>
                                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

                        <div class="row">
                            <div class="col-md-3 col-xl-3">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-sm bg-soft-success rounded">
                                                <i class="fe-bar-chart-2 avatar-title font-22 text-success"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
										<?php  
										 $allocation=$this->db->query("SELECT sum(`allocation_quantity`) as allocated,sum(`allocation_synced_files`) as syncFile,(allocation_quantity-allocation_synced_files) as pendingDownload FROM `allocation_log` WHERE `allocation_sync_status` ='Pending' ")->result_array();
										?>
                                            <div class="text-right">
                                                <h3 class="text-dark my-1"><span data-plugin="counterup"><?php echo $allocation[0]['pendingDownload']; ?>	</span></h3>
                                                <p class="text-muted mb-1 text-truncate">Download Pending Script </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-uppercase">Download Pending Script <span class="float-right"><?php echo $allocation[0]['pendingDownload']; ?></span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $papers==0?0:100; ?>%">
                                                <span class="sr-only">100% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                            <div class="col-md-3 col-xl-3">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-sm bg-soft-info rounded">
                                                <i class="fe-bar-chart-2 avatar-title font-22 text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <h3 class="text-dark my-1"><span data-plugin="counterup"><?php echo sizeof($logged_in); ?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Active Marker</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-uppercase"><?php echo sizeof($logged_in); ?> Active Marker out of 10 <span class="float-right"><?php echo sizeof($logged_in); ?>%</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="49" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo sizeof($logged_in); ?>">
                                                <span class="sr-only"><?php echo sizeof($logged_in); ?>% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                            
							
							<div class="col-md-3 col-xl-3">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-sm bg-soft-info rounded">
                                                <i class="fe-bar-chart-2 avatar-title font-22 text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <h3 class="text-dark my-1"><span data-plugin="counterup"><?php echo $sheets; ?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Allocated Scripts</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-uppercase">Allocated Scripts <span class="float-right"><?php echo $sheets; ?></span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="49" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $sheets==0?0:100; ?>%">
                                                <span class="sr-only">16% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                            <div class="col-md-3 col-xl-3">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-sm bg-soft-warning rounded">
                                                <i class="fe-bar-chart-2 avatar-title font-22 text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <h3 class="text-dark my-1"><?php echo $checked; ?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Scripts Checked</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-uppercase">Script checked out of <?php echo $sheets; ?> <span class="float-right"><?php if($sheets !=0 ){echo round($checked*100/$sheets);}else{echo $sheets ;} ?>%</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100" style="width: <?php if($sheets !=0 ){echo round($checked*100/$sheets);}else{echo $sheets ;} ?>%">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                           
						</div>
                        <!-- end row -->
   
			 <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Active Scripts Records</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Paper Code</th>
                                            <th>Marker</th>
                                            <th>Head Marker</th>
                                           
                                            <th>Status</th>
                                            <th>Unassign</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($sheetData as $sheet) {
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$sheet['paper_code']."</td>
													<td>".$sheet['evaluator_code']."</td>
													<td>".$sheet['examiner_username']."</td>
													
													<td>".$sheet['sheet_status']."</td>";?>
													<td><a onclick="return confirm('Are you sure Unassign this script?')" href="<?php echo base_url(); ?>welcome/reset_script?username=<?php echo $sheet['evaluator_code']; ?>"><button onclick="" class="btn btn-info">Unassign</button></a></td>
												</tr>
                                       <?php  }
                                        ?>
                                        
                                    </tbody>
                                </table>
								
								<div style="text-align:left;"><?php echo $pagination; ?></div>
                                
                            </div>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>

                      
						
                      





                    </div> <!-- container -->

                </div> <!-- content -->

                
       
        <!-- /#page-wrapper -->
<?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
 <div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
	   <h4 class="modal-title" id="impModalLabel">Reset login</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <form name='resetForm' id='resetForm' role="form" method="post" onsubmit="return logout()" action="<?php echo base_url() ?>welcome/reset_login">
      <div class="modal-body">
                                    
                                        <div class="form-group">
                                            <label>Marker Username *</label>
                                            <select name='username' id='username' class='form-control'>
                                            <option value=''>Select</option>
                                            <option value="All">All</option>
                                            
                                            <?php
                                            foreach ($logged_in as $user) {
                                                echo "<option value='".$user['user_name']."'>".$user['user_name']."</option>";
                                            }
                                            ?>
                                            </select>
                                        </div>
										<div class="form-group">
                                        
                                            <label>
                                                <input  id='unassign' name="unassign" type="checkbox" value="true"> Unassign answerscripts 
                                            </label>
                                       
                                        </div>
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <?php
        $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                        );
        ?>
        <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
        <input type="submit" class="btn btn-primary" name="submit" value='Reset'>
      </div>
      </form>
    </div>
  </div>
</div>


<script>
        function logout(){
            var username=$('#username').val();
            if(username==''){
                alert('username must be selected');
                return false;
            }else{
                var r = confirm("Are you sure to reset login for "+username);
            }
            return r;
        }
</script>

<?php } ?>
