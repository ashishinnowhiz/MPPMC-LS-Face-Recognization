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
                                            <li class="breadcrumb-item active">Summary Reports</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Summary Reports</h4>
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
                    <p class='pull-right'>
                        <a href='<?php echo base_url(); ?>reports/summary?export=html' type="button" class="btn btn-info">
                            <span class="glyphicon glyphicon-print"></span>  Print 
                        </a>
                        <a href='<?php echo base_url(); ?>reports/summary?export=xls' type="button" class="btn btn-info">
                            <span class="glyphicon glyphicon-export"></span>  Export 
                        </a>
                    </p>
                </div>
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
                                            <th>Marker Username</th>
                                            <th>Scrits Checked</th>
                                            <th>Scrits Rejected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        foreach ($rejects as $reject) {
                                            $rejected[$reject['evaluator_code']]=$reject['sheet_count'];
                                        }
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($reports as $report) {
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$report['evaluator_code']."</td>
													<td>".$report['sheet_count']."</td>
													<td>";
                                            if (isset($rejected[$report['evaluator_code']])) {
                                                echo $rejected[$report['evaluator_code']];
                                            } else {
                                                echo "0";
                                            }
                                                echo "</td>
												</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
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
var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date"]);
    myCalendar.attachEvent("onClick", function(d){
        var dateVal = $("#date").val();
        window.location=baseurl+"reports/evaluators/"+myCalendar.getFormatedDate(null,d);
    });
}
doOnLoad();
</script>
<style>
    #date {
        border: 1px solid #dfdfdf;
        font-family: Roboto, Arial, Helvetica;
        font-size: 14px;
        color: #404040;
    }
    #dateIcon {
        vertical-align: middle;
        cursor: pointer;
    }
</style>

