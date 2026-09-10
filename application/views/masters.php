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
											<li class="breadcrumb-item active">Base Data</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Base Data</h4>
                                </div>
                            </div>
                        </div>


            <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Subjects List</h4>
                                       
                            
								<table id="basic-datatable1" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($subjects as $subject) {
                                            echo "<tr>
													<td>".$subject['subject_id']."</td>
													<td>".$subject['subject_name']."</td>
													<td>".$subject['subject_code']."</td>
												</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <div class="col-6">
                    
                    <div class="card">
                       <div class="card-body">
                            <h4 class="header-title">Regions List</h4>
                				
								
								<table id="basic-datatable1" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Region Id</th>
                                            <th>Region Name</th>
                                            <th>Region Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($regions as $region) {
                                            echo "<tr>
													<td>".$region['region_id']."</td>
													<td>".$region['region_name']."</td>
													<td>".$region['region_code']."</td>
												</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            
                            
                            <!-- /.table-responsive 
                            <style>
                            .pagenums a,.pagenums strong{display:block;float:left;padding:6px;}
                            </style>
                            <div class='pagenums'><?php echo $pagination; ?></div>-->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
            <div class='row'>
                <div class="col-6">
                    
                    <div class="card">
                       <div class="card-body">
                            <h4 class="header-title">Mediums List</h4>
                				<table id="basic-datatable1" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Medium Id</th>
                                            <th>Medium Name</th>
                                            <th>Medium Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($mediums as $medium) {
                                            echo "<tr>
													<td>".$medium['medium_id']."</td>
													<td>".$medium['medium_name']."</td>
													<td>".$medium['medium_code']."</td>
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

<script>
$('#myModal').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
</script>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Subject</h4>
      </div>
      <form name='SubForm' id='SubForm' role="form"  onsubmit="return validateForm();" >
      <div class="modal-body">
                                    
                                        <div class="form-group">
                                            <label>Subject Name</label>
                                            <input name='name' class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Subject Code</label>
                                            <input name='code' class="form-control" placeholder="Enter Code">
                                        </div>
                                       
                                    
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="submit" class="btn btn-primary" value='Add Subject'>
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
        <h4 class="modal-title" id="impModalLabel">Add Subject</h4>
      </div>
      <form name='impForm' id='impForm' role="form" method="post" action="<?php echo base_url() ?>subjects/importcsv" enctype="multipart/form-data">
      <div class="modal-body">
                                    
                                        <div class="form-group">
                                            <label>Select File</label>
                                            <input type="file" name="userfile" >
                                        </div>
                                        <div class="form-group">
                                            <a href='<?php echo base_url(); ?>downloads/subjects.csv'>Download CSV Format File</a>
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
        <h4 class="modal-title" id="editModalLabel">Edit Subject</h4>
      </div>
      <form name='editForm' id='editForm' role="form">
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
    function validateForm()
    { 
        var y=document.forms["SubForm"]["name"].value;
        if (y==null || y=="")
          {
          alert("subject name must be filled out");
            document.forms["SubForm"]["name"].focus();
          return false;
          }
        var y=document.forms["SubForm"]["code"].value;
        if (y==null || y=="")
          {
           alert("subject code must be filled out");
            document.forms["SubForm"]["code"].focus();
          return false;
          }

    add();  
    return false;
  }
</script>
<script>
function deleteSub(str) {
    var txt;
    var r = confirm("Are you Sure!");
    if (r == true) {
        window.location='<?php echo base_url(); ?>subjects/remove/'+str;
    } 
}
</script>
<script>
function add() { 
    var strData=$('#SubForm').serialize();
    var base_url='<?php echo base_url(); ?>';
        $.ajax({
            type: 'POST',
            url: base_url+"subjects/add/",
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
            url: base_url+"subjects/edit/"+subid,       
            success:function(data)
            {   
                $('#edithtml').html(data);
            }
        });
}
</script>
