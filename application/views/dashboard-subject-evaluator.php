<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="content">

                    <!-- Start Content-->
	<div class="container-fluid">
           <!-- <div class="row">
                <div class="col-lg-12">
				 <div class="page-title-box">
                    <h1 class="page-header">Welcome <?php echo $_SESSION[$this->config->item('exam')['exam_session']]['user_name']; ?></h1>
					</div>
                </div>
           
            </div>-->

              <!--Code Written By vikas-->
              <div class="page-title-box">
                   <h4 class="page-title">Home</h4>
            </div>
            <!--Code End Here-->
           <div >
           <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
           </div>
         
            <br>
            <?php 
                $username = $evaluator['evaluator_username'];
                $subject_code = $evaluator['subject_code'];
               $subjects = $this->SubjectsModel->get_subject_by_username($username);
            ?>
	    <div class='row' style="margin-top:-10px">
				<div class="col-lg-2">
              <?php
               $csrf = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            ); 
              ?>
                     <form action="<?=base_url('evaluators/update_default')?>" method="post" >
                        <input type="hidden" name="username" value="<?=$username?>">
                        <div class="form-group">
                        <label for="">Current Subject</label>
                        <select name="code" class="form-control" >
                        <?php for($i = 0 ; $i < count($subjects) ; $i++){
                            $subject_name = $this->SubjectsModel->get_subject_code_wise($subjects[$i]['subject_code']);
                            
                        ?>
                            <option value="<?=$subjects[$i]['subject_code']?>" <?=$subjects[$i]['subject_code']==$subject_code?'selected':'';?>><?=$subjects[$i]['subject_code']."-".$subject_name[0]['subject_name']?></option>
                        <?php }?>
                        </select>
                        
                        </div>
                        </div>
                        <div class="col-lg-2">
                        <button class="btn btn-info" style="margin-top:28px">Change</button>

                        </div>  
                        <input type="hidden" id="<?=$csrf['name'];?>" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" /> 
                        <div class="col-lg-8">
                        <?php if($status == 1){?>
                            <div style="float: right;margin-top:30px"> 
                                <a href="<?php echo base_url(); ?>welcome/marking" type="submit" class="btn btn-primary">
                                    Start Marking
                                </a> 
                            </div>
                        <?php }?>
                        </div>
                     </form>
             
                <div class="col-lg-10">
                    <p class='text-right'>
						
                        <!-- <a href="<?php echo base_url(); ?>welcome/marker_print?date=<?php echo $date;?>" type="button" class="btn btn-info" target="_black">
                            <span class="fe-printer"></span>  Print 
                        </a> -->
                        
                    </p>
                </div>
            </div>
                       
      <div class="row" style="margin-top:10px;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                               <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Pending Sheets</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        for($j = 0 ; $j < count($subjects) ; $j++){
                                            $subject_name = $this->SubjectsModel->get_subject_code_wise($subjects[$j]['subject_code']);
                                            $sheets_count = $this->SheetsModel->get_sheets_subject_wise($subjects[$j]['subject_code']);
                                       
                                            $serial=$serial+1;                                                
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$subject_name[0]['subject_name']."</td>
													<td>".$subjects[$j]['subject_code']."</td>
													<td>".$sheets_count."</td>
													
                                                </tr>";
                                            }
                                        ?>        
                                    </tbody>
                                </table>
            </div>
            </div>
            </div>
        </div>
</div>
