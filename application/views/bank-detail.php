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
                                            <li class="breadcrumb-item"><a href="#">MIS</a></li>
                                            <li class="breadcrumb-item"><a href="#">Bank Details</a></li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Bank Details of Examiners</h4>
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

              <div class="col-lg-6">
                    <p class='text-left'>
					
                        <form action="<?=base_url('Banks')?>" method="GET" style="display:flex">
                            <select name="filter" class="form-control" require style="width:300px;margin-right:20px">
                                <option value="">All</option>
                                <?php 
                                foreach($subjects as $subject){
                                    ?>
                                  <option value="<?=$subject['subject_code']?>" <?= $subject_code==$subject['subject_code']?'selected':'';?> ><?=$subject['subject_code']."-".$subject['subject_name']?></option>
                                    <?php
                                }                                
                                ?>
                            </select>
                            <!-- <input type="hidden" id="<?php echo $csrf['name'];?>" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" /> -->
                            <button type="submit" class="btn btn-info">Search</button>
                       </form>
                      
                    </p>
                </div>

                <div class="col-lg-6">
                    <p class='text-right'>
					
                        <a href="<?=$subject_code !=''?base_url('banks/exportBankDetails').'/'.$subject_code:base_url('banks/exportBankDetails');?>" type="button" class="btn btn-success" >
                            <span class="fe-download"></span> Export 
                        </a>
                       
                        <?php /* if(sizeof($evaluators)>0){
                        <button type='button' class='btn btn-info' data-toggle='modal' data-target='#limitModal'><i class='fa fa-cog'></i> Limit</button>
                         } */?>
                    </p>
                </div>
            </div>
			 <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title"><?=$type?> Bank Details Records</h4>
										
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Examiner Name</th>
                                            <th>Designation</th>
                                            <th>Email</th>
                                            <th>Beneficiary Name</th>
                                            <th>Account No.</th>
                                            <th>Bank Name</th>
                                            <th>Branch Name</th>
                                            <th>IFSC Code</th>
                                            <th>Account Type</th>
                                            <th>City</th>
                                            <th>PanCard Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=1;
                                        }
                                       

                                        // echo "<pre>";
                                        // print_r($banks);
                                        
                                        foreach ($banks as $bank) {
                   
                                                
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$bank['examiner_name']."</td>
													<td>".$bank['role']."</td>
													<td>".$bank['user_email']."</td>
													<td>".$bank['beneficiary_name']."</td>
													<td>********".(substr($bank['account_number'],-4))."</td>
													<td>".$bank['beneficiary_bank_name']."</td>
													<td>".$bank['branch_name']."</td>
													<td>".$bank['ifsc_code']."</td>
													<td>".$bank['account_type']."</td>
													<td>".$bank['city']."</td>
													<td>".$bank['pan']."</td>";
                                                $serial=$serial+1;

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