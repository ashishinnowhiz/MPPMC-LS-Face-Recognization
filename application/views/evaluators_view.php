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
                                            <li class="breadcrumb-item active">Markers</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Markers</h4>
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
                <div class="col-lg-12">
                    <p class='text-right'>
					
                        <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                            <i class="fe-plus"></i> Add Marker
                        </button>-->
                       
                    </p>
                </div>
            </div>

					<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Markers List</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                         
                                            
                                           
                                           
                                            <th style='width:90px;'>Action</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($evaluators as $evaluator) {
                                          $subject_array = $this->SubjectsModel->get_subject_code_wise($evaluator['subject_code']);
                                          $subject_name = $subject_array[0]['subject_name'];
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$evaluator['evaluator_name']."</td>
													<td>".$evaluator['evaluator_email']."</td>
													<td>".$evaluator['evaluator_phone']."</td>
													
												
                         ";
                                            
                                                echo "<td>
                                                      <button type='button' class='btn btn-xs btn-info' data-toggle='modal' data-target='#SubjecModal' onclick=\"subjectList('".$evaluator['evaluator_id']."')\"  >View Subjects</button>
													  <button type='button' class='btn btn-info btn-circle' onclick=\"addSubject('".$evaluator['evaluator_username']."')\"  data-toggle='modal' data-target='#addModal'><i class='fa fa-plus'></i></button>
													  ";
                                               ?>
											    
													
                                                <?php echo "</td>";
                                            }
                                                echo "</tr>";
                                        
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
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="myModalLabel">Add Marker</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='SubForm' id='SubForm' role="form"   action='<?php echo base_url(); ?>marker/markeradd' method='POST'>
      <div class="modal-body"> 
                                        <div class="form-group">
                                            <label>Marker Name *</label>
                                            <input name='name' data-validation="required length alphanumeric" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="evaluator name is required" data-validation-error-msg-length="evaluator name is not more than 50 chars" data-validation-error-msg-custom="evaluator name can only contain characters" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input name='email' data-validation="email" data-validation-optional="false" maxlength="50" class="form-control" placeholder="Email Id">
                                        </div>
                                        <div class="form-group">
                                            <label>Marker Username *</label>
                                            <input name='username' id='code' data-validation="required length alphanumeric" onblur="return validate('SubForm','evaluators');" data-validation-length="max50" data-validation-error-msg-required="Username is required"  data-validation-error-msg-length="Username is not more than 50 chars" class="form-control">
                                        </div>
                                       <!--Vikas Code 10/04/23-->
<?php 
    $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    ); ?>
                                       
                                        
                                        <div class="form-group">
                                            <label>Subject *</label>
                                            <select name='subject[]' id="subject" data-validation="required" data-validation-error-msg-required="Subject is required" class="form-control" multiple>
											 <?php  
                                                                                     
                                           
                                                  foreach($subjectlist as $subjects){        
                                                ?>
                                                <option value="<?=$subjects['subject_code']?>"><?=substr($subjects['subject_code'], 0, strlen($subjects['subject_code'])-4).'-'.$subjects['subject_name']?></option>
                                            <?php  
                                        } ?>
                                            </select>
                                        </div>
                                         <!--Code End Here-->
                                       <!-- <div class="form-group">
                                            <label>Medium *</label>
                                            <select name='medium' data-validation="required" data-validation-error-msg-required="Medium is required" class="form-control">
                                            <option value=''>Select</option>
                                                <?php
                                                foreach ($mediums as $medium) {
                                                    echo "<option value='".$medium['medium_code']."'>".$medium['medium_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>-->
                                        <div class="form-group">
                                            <label>Phone *</label>
                                            <input name='phone' data-validation-length="10" data-validation="number length" data-validation-error-msg-length="phone number is 10 chars" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type='password' data-validation="required strength" data-validation-strength="2" data-validation-length="max15" data-validation-error-msg-length="password is not more than 15 chars" name='password' class="form-control">
                                            <p class="help-block">Password should contain atleast 1 Capital letter,  1 Number</p>
                                        </div>
                                      <!--  <div class="form-group">
                                            <label>Region Code/Name *</label>
                                            <select name='region' onchange="getCenters(this.value,'add');" data-validation="required" class="form-control">
                                            <option value=''>Select</option>
                                                <?php
                                                foreach ($regions as $region) {
                                                    echo "<option value='".$region['region_code']."'>".$region['region_code']."-".$region['region_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Center *</label>
                                            <select name='center' onchange="getExaminers(this.value,'add');" data-validation="required" id="center" class="form-control">
                                            </select>
                                        </div>-->
                                        
                                        <!-- <div class="form-group">
                                            <label>Head Username *</label>
                                           <select name='head' id="head" data-validation="required" class="form-control">
                                            
                                                <?php
                                                /* foreach($examiners AS $examiner){
                                                    echo "<option value='".$examiner['examiner_username']."'>".$examiner['examiner_username']."-".$examiner['center_code']."</option>";
                                                } */
                                                ?>
                                            </select>
                                        </div> -->
                                      <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" /> 
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="submit" id="addBtn" class="btn btn-primary" value='Add Marker'>
      </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="editModalLabel">Edit Markers</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='editForm' id='editForm' role="form" action='<?php echo base_url(); ?>evaluators/update' method='POST'>
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
<?php if (sizeof($evaluators)>0) {
    $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    ); ?>
<div class="modal fade" id="limitModal" tabindex="-1" role="dialog" aria-labelledby="limitModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title" id="limitModalLabel">Edit Marker limit </h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='limitForm' class="has-validation-callback" id='limitForm' role="form" action='<?php echo base_url(); ?>evaluators/limit' method='POST'>
      <div class="modal-body" id='limithtml'>
                                     
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="hidden" id="<?=$csrf['name'];?>" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" /> 
        <input type="submit" class="btn btn-primary" name="submit" value='Save'>
      </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="editModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      
        <h4 class="modal-title" id="editModalLabel">Add Subjects</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="funDisable()"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='editForm' id='addsubject' role="form" action='<?php echo base_url(); ?>marker/add_subject' method='POST'>
      <div class="modal-body" >
           <?php 
    $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    ); ?>
										 <div class="form-group">
                                            <label>Branch Name *</label>
                                            <select  name="course" id="headcourse" onchange="head_Subjects()" data-validation="required" data-validation-error-msg-required="Course is required" class="form-control">
                                            <option value=''>--Select Branch--</option>
                                                 <?php
                                                foreach ($courses as $course) {
                                                    echo "<option value='".$course['course_code']."'>".$course['course_code'].'-'.$course['course_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                        <label>Subjects *</label>
										
									   
									   
                                            <select name="subject[]" id="headsubject" onchange="fun()" class="form-control" multiple>
                                           
                                                 
                                            </select>
                                        </div>              
                                    
      </div>
	  <input type="hidden" id="<?=$csrf['name'];?>" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" /> 
	  <input type="hidden" id="username" name="username" /> 
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal" onclick="funDisable()">Close</a>
        <input type="submit"  id="btn_subj" class="btn btn-primary" name="submit" value='Save' disabled>
      </div>
      </form>
    </div>
  </div>
</div>
<!--Code Written By vikas-->
<div class="modal fade" id="SubjecModal" tabindex="-1" role="dialog" aria-labelledby="SubjecModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title" id="SubjecModalLabel">All Subjects List </h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='subjectForm' class="has-validation-callback" id='limitForm' role="form" action='<?php echo base_url(); ?>evaluators/' method='POST'>
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
<?php } ?>

<script>

function head_Subjects(){
  let selectedValue = document.getElementById('headcourse').value;
 if(selectedValue!=''){
	 //alert(selectedValue);
  $.ajax({
      url: '<?=base_url("marker/get_subject/")?>'+selectedValue, // Replace with the actual URL of your controller method
      method: 'get',
      success: function(response) {
        var subject = JSON.parse(response);
        $('#headsubject').empty();
        for(var i=0 ; i < subject.length ; i++ ){
            $('#headsubject').append('<option value="' + subject[i]['subject_code'] + '">' +subject[i]['subject_code']+'-'+subject[i]['subject_name'] + '</option>');
        }
      },
      error: function(xhr, status, error) {
        console.error(error); // Handle any errors that occur
      }
    });
 }else{
	 alert("Please Select Branch..!");
 }
}

function funDisable(){
  let buttonElement = document.getElementById('btn_subj');
  buttonElement.disabled = true;
}


 function fun() {
  let selectElement = document.getElementById('headsubject');
  let buttonElement = document.getElementById('btn_subj');
  if (selectElement.value.length > 0) {
    buttonElement.disabled = false;
  } else {
    buttonElement.disabled = true;
  }
}
    function validateForm(FormName)
    { 
        var y=document.forms[FormName]["name"].value;
        if (y==null || y=="")
          {
          alert("evaluator name must be filled out");
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
          alert("evaluator username must be filled out");
            document.forms[FormName]["username"].focus();
          return false;
          }
        var y=document.forms[FormName]["password"].value;
        if (y==null || y=="")
          {
          alert("evaluator password must be filled out");
            document.forms[FormName]["password"].focus();
          return false;
          }
        var y=document.forms[FormName]["subject"].value;
        if (y==null || y=="")
          {
          alert("Please select subject");
            document.forms[FormName]["subject"].focus();
          return false;
          }
        var y=document.forms[FormName]["medium"].value;
        if (y==null || y=="")
          {
          alert("Please select medium");
            document.forms[FormName]["medium"].focus();
          return false;
          }
        var y=document.forms[FormName]["head"].value;
        if (y==null || y=="")
          {
          alert("Please select head evaluator");
            document.forms[FormName]["head"].focus();
          return false;
          }
  }
</script>

<script>
function addSubject(subid) { 
 // alert(subid);
  $('#username').val(subid);
   

}


function edit(subid) { 
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'GET',
            url: base_url+"evaluators/edit/"+subid,     
            success:function(data)
            {   
                $('#edithtml').html(data);
            }
        });
}
function limit(id) { 
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'GET',
            url: base_url+"evaluators/editlimit/"+id,       
            success:function(data)
            {   
                $('#limithtml').html(data);
            }
        });
}


//Code Written By vikas
function subjectList(id) {
//alert(id);	
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'GET',
            url: base_url+"evaluators/viewSubjectList/"+id,       
            success:function(data)
            {   
			//alert(data);
                $('#subjecthtml').html(data);
            }
        });
}
//Code End Here
</script>
