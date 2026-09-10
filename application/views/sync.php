<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="page-wrapper">
    <div class='row'>
        <div class="col-lg-12">
            <br/>
            <ol class = "breadcrumb">
               <li><a href = "<?php echo base_url(); ?>">Home</a></li>
               <li class = "active">Sync Answerscript</li>
            </ol>
        </div>
    </div>
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
            <?php if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            
    <!-- /.col-lg-6 -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-copy fa-fw"></i> Sync Answerscript</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='pull-right'>
                    <a data-toggle="modal" data-target="#myModal" target='iframe_a' class='btn btn-info'><i class='fa fa-circle-o-notch fa-fw'></i> Scan & Sync</a><br/><br/>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class="col-lg-5">
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                    Stats
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">    
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Region</th>
                                            <th>Synced</th>
                                            <th>Error</th>
                                            <th>Duplicate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($stats as $region) {
                                        ?>
                                            <tr>
                                            <td>
                                        <?php
                                        if (!in_array($region['region_code'], $list)) {
                                            echo "<span title='folder not found' class='fa-stack' style='font-size:70%;'>
													  <i class='fa fa-folder fa-stack-1x'></i>
													  <i class='fa fa-ban fa-stack-2x text-danger'></i>
													</span>";
                                                $notfound=true;
                                        } else {
                                            echo "<i class='fa fa-folder fa-fw'></i>";
                                        }
                                        ?>
                                        <?php echo $region['region_name']."-".$region['region_code']; ?>
                                            
                                            </td>
                                            <td><a href=''><span id='scanned-<?php echo $region['region_code']; ?>'>0</span></a></td>
                                            <td><a href=''><span id='error-<?php echo $region['region_code']; ?>'>0</span></a></td>
                                            <td><a href=''><span id='duplicate-<?php echo $region['region_code']; ?>'>0</span></a></td>
                                            </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php if (isset($notfound)) {
                                    echo "<div class='pull-right'><span title='folder not found' class='fa-stack' style='font-size:70%;'>
													  <i class='fa fa-folder fa-stack-1x'></i>
													  <i class='fa fa-ban fa-stack-2x text-danger'></i>
													</span> - * Folder not found</div>";
                                }
?>
                                
                            </div>
                            <!-- /.panel -->
                        </div>
                </div>
                <div class="col-lg-7">
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Sync Status
                        </div>
                        <!-- /.panel-heading -->
                        <div id='sync-log' class="panel-body" style='height:300px;'>
                            <!--<iframe src="" name="iframe_a" style='height:100%;width:100%;border:0px;'>
                                <p>Your browser does not support iframes.</p>
                            </iframe>-->
                        </div>
                        <div id='result' class="panel-footer">
                                &nbsp;
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
</div>

<script>
    function sync(){
        $('#sync-log').html('');
        $.ajax({
            type: 'GET',
            async: false,
            url: "<?php echo base_url(); ?>sync/ajax/<?php echo time(); ?>",
            dataType: 'json',           
            success:function(data)
            {   
                if(data.success){
                    var status=data.data;
                    for (i = 0; i < status.length; i++) {
                        var obj=status[i];
                        $('#sync-log').append(obj.file+"-"+obj.message+"<br/>");
                        if(obj.error=='none'){
                            var old=document.getElementById('scanned-'+obj.region).innerHTML;
                            var inc=parseInt(old)+1;
                            $('#scanned-'+obj.region).html(inc);
                        }
                        if(obj.error=='error'){
                            var old=document.getElementById('error-'+obj.region).innerHTML;
                            var inc=parseInt(old)+1;
                            $('#error-'+obj.region).html(inc);
                        }
                        if(obj.error=='duplicate'){
                            var old=document.getElementById('duplicate-'+obj.region).innerHTML;
                            var inc=parseInt(old)+1;
                            $('#duplicate-'+obj.region).html(inc);
                        }
                    }
                }else{
                    //next=false;
                    $('#sync-log').html(data.message);
                }
            }
        });
    }
</script>
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
        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
      </div>
      <form name='SubForm' id='SubForm' role="form"  onsubmit="return validateForm('SubForm');" action='<?php echo base_url(); ?>papers/add' method='POST'>
      <div class="modal-body">
            Files will be scanned from /uploads/ folder named with region codes.<br/>
            Filename Structure Will be based on settings updated in admin panel
            
            <h3>Example : 00111234.pdf</h3>
            Filepath :  /uploads/63/00111234.pdf
            <!--
            File name length : <?php echo $file_structure->length; ?><br/>
            Roll No start : <?php echo $file_structure->roll_no_start; ?><br/>
            Roll No length : <?php echo $file_structure->roll_no_length; ?><br/>
            Paper code start : <?php echo $file_structure->paper_code_start; ?><br/>
            Paper code length : <?php echo $file_structure->paper_code_length; ?><br/>
            Medium code start : <?php echo $file_structure->medium_code_start; ?><br/>
            Medium codelength : <?php echo $file_structure->medium_code_length; ?><br/>
            -->
            
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <a class="btn btn-default"  onclick="sync();" data-dismiss="modal">Yes, Sync</a>
      </div>
      </form>
    </div>
  </div>
</div>
<script>
function refresh() {
    $.ajax({
            type: 'GET',
            url: "<?php echo base_url(); ?>sync/stats/",
            dataType: 'json',           
            success:function(data)
            {   
                var fLen = data.length;
                for (i = 0; i < fLen; i++) {
                    var obj=data[i];
                    $('#scanned-'+obj.region_code).html(obj.scanned);
                } 
            }
        });
}
</script>

<script>
  window.addEventListener("load", function(event) {
    refresh();
  });
</script>
