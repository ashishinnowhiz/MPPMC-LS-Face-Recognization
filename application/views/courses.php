<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
  <div style="margin-left:80px;margin-right:80px">
    
  <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masters">Base Data</a></li>
                                            <li class="breadcrumb-item active">Courses</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Courses</h4>
                                </div>
                            </div>
                        </div>     

          
            
             <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Courses List</h4>
                     
							<?php if (sizeof($courses)>0) { ?>
                                 <table id="basic-datatable" class="table dt-responsive nowrap w-100">
								    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Course Name</th>
                                            <th>Course Code</th>
                                            <?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Admin') { ?>
                                            <th>Action</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($courses as $course) {
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$course['course_name']."</td>
													<td>".$course['course_code']."</td>";
                                            if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Admin') {
                                                echo "<td>
													<button type='button' class='btn btn-info btn-circle' onclick=\"edit('".$course['course_id']."')\"  data-toggle='modal' data-target='#editModal'><i class='fa fa-pencil-alt '></i></button>
													<button type='button' class='btn btn-danger btn-circle' onclick=\"deleteSub('".$course['course_id']."')\"><i class='fa fa-trash-alt'></i></button>
													</td>";
                                            }
                                            echo "</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
								<?php } else { ?>
								<div class="alert alert-danger">
									No Data/Records Found
								</div>
								<?php } ?>
                            </div>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            
            


<?php $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
);
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title" id="myModalLabel">Add Course</h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='SubForm' id='SubForm' role="form" action='<?php echo base_url(); ?>courses/add' method='POST'>
      <div class="modal-body">
                                    
                                        <div class="form-group">
                                            <label>Course Name *</label>
                                            <input name='name' data-validation="required length alphanumeric" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="course name is required" data-validation-error-msg-length="course name is not more than 50 chars" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Course Code *</label>
                                            <input name='code' id='code' data-validation="required length alphanumeric"  data-validation-length="max20"   data-validation-error-msg-length="course code is not more than 20 chars" data-validation-error-msg-required="course code is required" class="form-control" placeholder="Enter Code" onblur="return validate('SubForm','subjects');">
                                        </div>
                                        <input type="hidden" id="<?php echo $csrf['name'];?>" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />

                                       
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="submit" id="addBtn" class="btn btn-primary" value='Add Course'>
      </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="impModal" tabindex="-1" role="dialog" aria-labelledby="impModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title" id="impModalLabel">Add Subject</h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='impForm' id='impForm' role="form" method="post" action="<?php echo base_url() ?>courses/import" enctype="multipart/form-data">
      <div class="modal-body">
                                    
                                        <div class="form-group">
                                            <label>Select File</label>
                                            <input type="file" name="userfile" >
                                        </div>
                                        <div class="form-group">
                                            <a href='<?php echo base_url(); ?>downloads/subjects.xls'>Download Xls Format File</a>
                                        </div>
         <input type="hidden" id="<?php echo $csrf['name'];?>" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />                           
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
        
        <h4 class="modal-title" id="editModalLabel">Edit Subject</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='editForm' id='editForm' role="form" action='<?php echo base_url(); ?>courses/update' method='POST' >
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


<script>
    /* function validateForm(FormName)
    { 
        var y=document.forms[FormName]["name"].value;
        if (y==null || y=="")
          {
          alert("subject name must be filled out");
            document.forms[FormName]["name"].focus();
          return false;
          }
        var y=document.forms[FormName]["code"].value;
        if (y==null || y=="")
          {
           alert("subject code must be filled out");
            document.forms[FormName]["code"].focus();
          return false;
          }
  } */
</script>
<script>
function deleteSub(str) {
    var txt;
    var r = confirm("Are you Sure!");
    if (r == true) {
        window.location='<?php echo base_url(); ?>courses/remove/'+str;
    } 
}
</script>
<script>
function add() { 
    var strData=$('#SubForm').serialize();
    
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'POST',
            url: base_url+"courses/add/",
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
            url: base_url+"courses/edit/"+subid,       
            success:function(data)
            {   
                $('#edithtml').html(data);
            }
        });
}
</script>

  </div>