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
											 <li class="breadcrumb-item"><a href = "<?php echo base_url(); ?>masters">Base Data</a></li>
                                            <li class="breadcrumb-item active">LS Daily Reports</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">LS Daily Reports</h4>
                                </div>
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
                <div class="col-lg-2">
                    <p class="pull-right">
                    <input type="text" class="form-control" name="date" placeholder="Select Date" value="<?php echo $date;?>" onchange="onSelectDate()" id="basic-datepicker"  />
                    </p>
                </div> 
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Reports</h4>
                                       
                            
								<table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Center Name</th>
                                            <th>Scripts Checked</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        
                                                $serial=$serial+1;
                                                echo "<tr>
													<td>".$serial."</td>
													<td>".$_SESSION[$this->config->item('exam')['exam_session']]['user_center']."</td>
													<td>".$report['sheet_count']."</td>
													<td><a target='_blank' href='".base_url()."reports/atc/".$date."'>View</a>&nbsp;|&nbsp;<a href='".base_url()."reports/downloadAtc/".$date."'>Download Excel</a></td>
												</tr>";
                                            
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                         
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
</div>
<script>
var baseurl = "<?php echo base_url(); ?>";
function onSelectDate(){
	var dateVal = $("#basic-datepicker").val();
	window.location=baseurl+"reports/atcs?date="+dateVal;
  
}

</script>
