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
                                            <li class="breadcrumb-item active">LS Consolidated Reports</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">LS Consolidated Reports</h4>
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
                <div class="col-lg-12">
                                   <form class='form-inline' action='' method='GET'>
                                        <div class='form-group'>
                                            
                                            <input type="text" class="form-control" name="date_from" id='basic-datepicker' placeholder="Select Date" value="<?php echo $date_from; ?>" onchange="setSens();" readonly="true"/>
											</div>&nbsp;&nbsp;&nbsp;
											 <div class='form-group'>
                                            <input type="text" class="form-control" name="date_to" id='minmax-datepicker' placeholder="Select Date" value="<?php echo $date_to; ?>"   readonly="true"/>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <!--<input type='hidden' id='export' name='export' value=''>-->
                                        <button type="button" class="btn btn-primary" onclick="onCklickBtn()"><i class="fe-search"></i> Search</button> 
                                    </form>
                    
                </div> 
                <!-- /.col-lg-12 -->
            </div>
            <br/>
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
												</tr>";
                                            
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
var baseurl = "<?php echo base_url(); ?>";


function setSens(){
var minDate = $("#basic-datepicker").val();

$("#minmax-datepicker").flatpickr({minDate:minDate,maxDate:""});
}

function onCklickBtn(){
	 var minDate = $("#basic-datepicker").val();
	var maxDate = $("#minmax-datepicker").val();
	window.location=baseurl+"reports/dated_atcs?date_from="+minDate+"&date_to="+maxDate;
  
}



    function exportcsv(){
        $('#export').val('csv');
        $('#filter_form').submit();
    }
    function filter(){
        $('#export').val('');
        $('#filter_form').submit();
    }
</script>


