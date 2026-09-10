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
                                            <li class="breadcrumb-item active">Head Evaluators</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Head Markers</h4>
                                </div>
                            </div>
                        </div>    
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            
  
            
            <?php if (sizeof($examiners)>0) { ?>
            <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Head Evaluators List</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Username</th>
                                            <th>Subject Name</th>
                                            <th class='text-center'>Branch Code</th>
                                            <th style='width:90px;'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($examiners as $examiner) {
                                          $subject_array = $this->SubjectsModel->get_subject_code_wise($examiner['subject_code']);
                                          $subject_name = $subject_array[0]['subject_name'];
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$examiner['examiner_name']."</td>
													<td>".$examiner['examiner_designation']."</td>
													<td>".$examiner['examiner_email']."</td>
													<td>".$examiner['examiner_phone']."</td>
													<td>".$examiner['examiner_username']."</td>
                          <td>".$examiner['subject_code']."-".$subject_name ."</td>
                          <td class='text-center'>".$examiner['course_code']."</td>
													<td>
                          <button type='button' class='btn btn-sm btn-info btn-circle' onclick=\"subjectList('".$examiner['examiner_id']."')\"  data-toggle='modal' data-target='#subjectModal'>All Subjects</button>
                          <!--	<button type='button' class='btn btn-info btn-circle' onclick=\"edit('".$examiner['examiner_id']."')\"  data-toggle='modal' data-target='#editModal'><i class='fa fa-pencil'></i></button>
										        	<button type='button' class='btn btn-danger btn-circle' onclick=\"deleteSub('".$examiner['examiner_id']."')\"><i class='fa fa-trash-o'></i></button> -->
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
           
            <?php } else { ?>
            <div class="alert alert-danger">
                No Data/Records Found
            </div>
            <?php } ?>
			 </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add  Head Evaluator</h4>
      </div>
      <form name='SubForm' id='SubForm' role="form" action='<?php echo base_url(); ?>examiners/add' method='POST'>
      <div class="modal-body">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input name='name' data-validation="required length" data-validation-length="max50" data-validation-error-msg-required="examiner name is required" data-validation-error-msg-length="examiner name is not more than 50 chars" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Designation</label>
                                            <input name='designation' data-validation="length" data-validation-optional="true" data-validation-length="max50"  data-validation-error-msg-length="designation is not more than 50 chars" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Username *</label>
                                            <input name='username' data-validation="required length  server" data-validation-url="<?php echo base_url();?>examiners/validate" data-validation-length="max50" data-validation-error-msg-required="Username is required" data-validation-error-msg-length="Username is not more than 50 chars" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input name='email' data-validation="required email" maxlength="50" class="form-control" placeholder="Email Id">
                                        </div>
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type='password' data-validation="required strength" 
         data-validation-strength="2" data-validation-length="max15" data-validation-error-msg-length="password is not more than 15 chars" name='password' class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input name='phone' data-validation-length="max10" data-validation="number length" data-validation-error-msg-length="phone number is not more than 10 chars" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label>Head Evaluator Center</label>
                                            <select name='center' class="form-control">
                                                <?php
                                                foreach ($centers as $center) {
                                                    echo "<option value='".$center['center_code']."'>".$center['center_code']."-".$center['center_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                       
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="submit" class="btn btn-primary" value='Add examiner'>
      </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="impModal" tabindex="-1" role="dialog" aria-labelledby="impModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="impModalLabel">Add Head Evaluator</h4>
      </div>
      <form name='impForm' id='impForm' role="form" method="post" action="<?php echo base_url() ?>examiners/importcsv" enctype="multipart/form-data">
      <div class="modal-body">
                                    
                                        <div class="form-group">
                                            <label>Select File</label>
                                            <input type="file" name="userfile" >
                                        </div>
                                        <div class="form-group">
                                            <a href='<?php echo base_url(); ?>downloads/examiners.csv'>Download CSV Format File</a>
                                        </div>
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="submit" class="btn btn-primary" name="submit" value='Import'>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="editModalLabel">Edit Head Evaluator</h4>
      </div>
      <form name='editForm' id='editForm' role="form" action='<?php echo base_url(); ?>examiners/update' method='POST'>
      <div class="modal-body" id='edithtml'>
                                     
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="submit" class="btn btn-primary" name="submit" value='Save'>
      </div>
      </form>
    </div>
  </div>
</div>

<!--Code Written By Vikas-->
<div class="modal fade" id="subjectModal" tabindex="-1" role="dialog" aria-labelledby="subjectModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title" id="subjectModalLabel">All Subjects List</h4>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='editForm' id='subjectForm' role="form" action='' method='POST'>
      <div class="modal-body" id='subjecthtml'>
                                     
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
      </div>
      </form>
    </div>
  </div>
</div>
<!--Code End Here-->

<script>
    function validateForm(FormName)
    { 
        var y=document.forms[FormName]["name"].value;
        if (y==null || y=="")
          {
          alert("Head evaluator name must be filled out");
            document.forms[FormName]["name"].focus();
          return false;
          }
        var x=document.forms[FormName]["email"].value;
        var atpos=x.indexOf("@");
        var dotpos=x.lastIndexOf(".");
        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
          {
          alert("Not a valid e-mail address");
          document.forms[FormName]["email"].focus();
          return false;
          }
        var y=document.forms[FormName]["username"].value;
        if (y==null || y=="")
          {
          alert("Head username must be filled out");
            document.forms[FormName]["username"].focus();
          return false;
          }
        var y=document.forms[FormName]["password"].value;
        if (y==null || y=="")
          {
          alert("Head password must be filled out");
            document.forms[FormName]["password"].focus();
          return false;
          }
  }
</script>
<script>
function deleteSub(str) {
    var txt;
    var r = confirm("Are you Sure!");
    if (r == true) {
        window.location='<?php echo base_url(); ?>examiners/remove/'+str;
    } 
}
</script>
<script>
function add() { 
    var strData=$('#SubForm').serialize();
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'POST',
            url: base_url+"examiners/add/",
            data: strData,
            dataType: 'json',           
            success:function(data)
            {   
                if(data.success){
                    alert(data.message);
                }else{
                    alert(data.message);
                }
            }
        });
}
function edit(subid) { 
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'GET',
            url: base_url+"examiners/edit/"+subid,      
            success:function(data)
            {   
                $('#edithtml').html(data);
            }
        });
}

//code written by vikas
function subjectList(subid) { 
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'GET',
            url: base_url+"examiners/viewSubjectList/"+subid,      
            success:function(data)
            {   
                $('#subjecthtml').html(data);
            }
        });
}
//code end here
</script>
