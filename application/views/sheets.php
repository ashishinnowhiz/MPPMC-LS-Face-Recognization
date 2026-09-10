<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
 <?php $csrf = array(
                    'name' => $this->security->get_csrf_token_name(),
                    'hash' => $this->security->get_csrf_hash()
            );?>
<div class="content">
                  <!-- Start Content-->
			<div class="container-fluid">
						<div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Answer Scripts</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Answer Scripts</h4>
                                </div>
                            </div>
                        </div>
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            
   
			
            <?php if (sizeof($sheets)>0) { ?>
			  <div class='row'>
                <div class="col-lg-12">
                    <p class='text-right'>
					
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#sheetSearch">
                            <span class="fe-search"></span> Search 
                        </button>
                       
                       
                        <a href="<?php echo base_url()."Upload2/extractScript"; ?>" class='btn btn-info'>Convert Sheet</a>
                         
                    </p>
                </div>
            </div>
			 <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Answer Scripts Records</h4>
										
                                        <table id="" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Paper Code</th>
                                            <th>Medium Code</th>
                                            <th>Region Code</th>
                                            <th>Script file</th>
                                            <th>Status</th>
                                            <th>Added Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($sheets as $sheet) {
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$sheet['paper_code']."</td>
													<td>".$sheet['medium_code']."</td>
													<td>".$sheet['region_code']."</td>
													<td>".$sheet['sheet_file']."</td>
													<td>".$sheet['sheet_status']."</td>
													<td>".$sheet['sheet_time']."</td>
												</tr>";
                                        }
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
            
            <?php } else { ?>
            <div class="alert alert-danger">
                No Data/Records Found
            </div>
            <?php } ?>
			</div>
</div>


<div class="modal fade" id="sheetSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
				  <h4 class="modal-title" id="myModalLabel">Search Sheet</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				  </div>
				  <form name='SubForm' id='SubForm' role="form" action='<?php echo base_url(); ?>sheets/sheetSearch' method='GET'>
				  <div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>Search</label>
							
							<input type="text" name="txtSearch" placeholder='Enter value...' class="form-control"  required>
						</div>
					</div>
					
				  </div>
				  <div class="modal-footer">
					<a class="btn btn-default" data-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-info">Submit</button>
				  </div>
				  </form>
				</div>
			  </div>
			</div>