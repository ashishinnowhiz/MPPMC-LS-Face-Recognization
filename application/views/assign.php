<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="page-wrapper">
    <div class='row'>
        <div class="col-lg-12">
            <br/>
            <ol class = "breadcrumb">
               <li><a href = "<?php echo base_url(); ?>">Home</a></li>
               <li class = "active">Allocation Center(HE)</li>
            </ol>
        </div>
    </div>
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-share fa-fw"></i> Allocation Center(HE)</h1>
                </div>
            </div>
            
            <?php
            foreach ($paper_counts as $pc) {
                $paper_count[$pc['paper_code']][$pc['sheet_status']]=$pc['Total'];
            }
                $dropdown="<option value=''>Select</option>";
            foreach ($examiners as $he) {
                $dropdown.="<option value='".$he['examiner_username']."'>".$he['examiner_name']."</option>";
            }
            foreach ($examiner_counts as $count) {
                $examiner_count[$count['examiner_username']][$count['sheet_status']]=$count['Total'];
            }
            ?> 
            <div class='row'>
                <div class="col-lg-12">
                    <p class='pull-right'>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-plus"></i> New Allocation
                        </button>
                    </p>
                </div>
            </div>
            <div class='row'>
                <div class="col-lg-12">
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Head Evaluators 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Evaluator Name</th>
                                            <th>Username</th>
                                            <th>Pending</th>
                                            <th>Checked</th>
                                            <th>Rejected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($examiners as $examiner) {
                                            $serial=$serial+1;
                                            if (isset($examiner_count[$examiner['examiner_username']])) {
                                                $cc=$examiner_count[$examiner['examiner_username']];
                                                if (isset($cc['Allocated'])) {
                                                    $count['Allocated']=$cc['Allocated'];
                                                } else {
                                                    $count['Allocated']=0;
                                                }
                                                if (isset($cc['Checked'])) {
                                                    $count['Checked']=$cc['Checked'];
                                                } else {
                                                    $count['Checked']=0;
                                                }
                                                if (isset($cc['Rejected'])) {
                                                    $count['Rejected']=$cc['Rejected'];
                                                } else {
                                                    $count['Rejected']=0;
                                                }
                                            } else {
                                                $count['Allocated']=0;
                                                $count['Checked']=0;
                                                $count['Rejected']=0;
                                            }
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$examiner['examiner_name']."</td>
													<td>".$examiner['examiner_username']."</td>
													<td>".$count['Allocated']."</td>
													<td>".$count['Checked']."</td>
													<td>".$count['Rejected']."</td>
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


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Allocation to HE</h4>
      </div>
      <form action='<?php echo base_url(); ?>assign/update' method='POST'>
      <div class="modal-body"> 
                                      
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Paper Code</th>
                                            <th>Unallocated</th>
                                            <th>Allocated</th>
                                            <th>Checked</th>
                                            <th>Rejected</th>
                                            <th>Allocate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($papers as $paper) {
                                            if (isset($paper_count[$paper['paper_code']])) {
                                                $pcc=$paper_count[$paper['paper_code']];
                                                if (isset($pcc['Scanned'])) {
                                                    $pc['Scanned']=$pcc['Scanned'];
                                                } else {
                                                    $pc['Scanned']=0;
                                                }
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
                                                $pc['Scanned']=0;
                                                $pc['Allocated']=0;
                                                $pc['Checked']=0;
                                                $pc['Rejected']=0;
                                            }
                                            $paper_count[$paper['paper_code']]['Scanned']=$pc['Scanned'];
                                            echo "<tr>
													<td>".$paper['paper_code']."-".$paper['paper_set']."</td>
													<td>".$pc['Scanned']."</td>
													<td>".$pc['Allocated']."</td>
													<td>".$pc['Checked']."</td>
													<td>".$pc['Rejected']."</td>
													<td><input type='text' data-validation='number length' data-validation-error-msg-length='phone number is not more than 2 chars' data-validation-optional='true' size='4' name='qty".$paper['paper_code']."'>
													<select name='v".$paper['paper_code']."'>".$dropdown."</select></td>
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
        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Allocate
                        </button>
      </div>
      </form>
    </div>
  </div>
</div>
