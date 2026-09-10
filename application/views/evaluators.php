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
                                            <th>Username</th>
                                            <th>Subject Name</th>
                                            <th class='text-center'>Branch Code</th>
                                            <th class='text-center'>Medium Code</th>
                                            <!-- <th>Head Username</th> -->
                                            <?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
                                            <th style='width:90px;'>Action</th>
                                            <?php } ?>
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
													<td>".$evaluator['evaluator_username']."</td>
													<td>".$evaluator['subject_code']."-".$subject_name."</td>
                          <td class='text-center'>".$evaluator['course_code']."</td>
													<td class='text-center'>".$evaluator['medium_code']."</td>";
													// <td>".$evaluator['examiner_username']."</td>";
                                            if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') {
                                                echo "<td><button type='button' class='btn btn-xs btn-info' data-toggle='modal' data-target='#limitModal' onclick=\"limit('".$evaluator['evaluator_id']."')\"  > Update Limit</button>
                                                      <button type='button' class='btn btn-xs btn-info' data-toggle='modal' data-target='#SubjecModal' onclick=\"subjectList('".$evaluator['evaluator_id']."')\"  >View Subjects</button>";
                                                if ($evaluator['evaluator_name']=='') {
                                                    echo "<button type='button' class='btn btn-info btn-circle' onclick=\"edit('".$evaluator['evaluator_id']."')\"  data-toggle='modal' data-target='#editModal'></i></button>";
                                                }
                                                echo "</td>";
                                            }
                                                echo "</tr>";
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
        <input type="hidden" id="<?=$csrf['name'];?>" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" /> 
      </div>
      </form>
    </div>
  </div>
</div>
<!--Code End Here-->
<?php } ?>

<script>
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
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'GET',
            url: base_url+"evaluators/viewSubjectList/"+id,       
            success:function(data)
            {   
                $('#subjecthtml').html(data);
            }
        });
}
//Code End Here
</script>
