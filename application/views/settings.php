<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="page-wrapper">
    <div class='row'>
        <div class="col-lg-12">
            <br/>
            <ol class = "breadcrumb">
               <li><a href = "<?php echo base_url(); ?>">Home</a></li>
               <li class = "active">Settings</li>
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
                    <h1 class="page-header"><i class="fa fa-gear fa-fw"></i> Settings</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class='row'>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Center Settings
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body"> 
                        ...
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
</div>


<script>
    function namestructure(){
        var y=parseInt(document.forms['SubForm']["length"].value);
        var str='';
        for (i = 0; i < y; i++) { 
             str += "X";
        }
        document.getElementById('structure').innerHTML=str;
    }
    function validateForm(FormName)
    { 
        var y=document.forms[FormName]["length"].value;  
        if (y==null || y=="" || notint(y))
          {
          alert("filename length must be integer");
            document.forms[FormName]["length"].focus();
          return false;
          }
          
        var y=document.forms[FormName]["roll_no_start"].value;
        if (y==null || y=="" || notint(y))
          {
          alert("paper roll_no start position must be integer");
            document.forms[FormName]["roll_no_start"].focus();
          return false;
          }
        var y=document.forms[FormName]["roll_no_length"].value;
        if (y==null || y=="" || notint(y))
          {
          alert("paper roll_no length must be integer");
            document.forms[FormName]["roll_no_length"].focus();
          return false;
          }
          
        var y=document.forms[FormName]["paper_code_start"].value;
        if (y==null || y=="" || notint(y))
          {
          alert("paper paper_code start position must be integer");
            document.forms[FormName]["paper_code_start"].focus();
          return false;
          }
          var y=document.forms[FormName]["paper_code_length"].value;
        if (y==null || y=="" || notint(y))
          {
          alert("paper paper_code length must be integer");
            document.forms[FormName]["paper_code_length"].focus();
          return false;
          }
          
        var y=document.forms[FormName]["medium_code_start"].value;
        if (y==null || y=="" || notint(y))
          {
          alert("paper medium_code start position must be integer");
            document.forms[FormName]["medium_code_start"].focus();
          return false;
          }
          var y=document.forms[FormName]["medium_code_length"].value;
        if (y==null || y=="" || notint(y))
          {
          alert("paper medium_code length must be integer");
            document.forms[FormName]["medium_code_length"].focus();
          return false;
          }
          
         

  }
  
  function notint(str){
      var numbers = /^[0-9]+$/;  
      if(str.match(numbers))  
      { 
        return false;
      }else{
        return true;
      }
  }
</script>
