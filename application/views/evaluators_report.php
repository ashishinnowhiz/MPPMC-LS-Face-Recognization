<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
     <title><?php echo $this->config->item('exam')['exam_name'];?> Reports</title>
<link rel="shortcut icon" href="<?php echo base_url(); ?>logo/favicon.ico">
   <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">  
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<style>
    td{padding:6px 10px 6px 0px;}
</style>
</head>
<body>
    <div class="container" style='background-color:#fff;min-height:800px;'>
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Marker Daily Report</h1>
            </div>
        </div>
        <br/><br/>
        <div class="row">
            <div class="col-lg-6">
                <table class="">
                   <tr><td><b>Marker Id</b></td><td><?php echo $evaluator['evaluator_username']; ?></td></tr>                    
                    <tr><td><b>Name</b></td><td><?php echo $evaluator['evaluator_name']; ?></td></tr>
                   <tr><td><b>Email</b></td><td><?php echo $evaluator['evaluator_email']; ?></td></tr>
                   <tr><td><b>Mobile</b></td><td><?php echo $evaluator['evaluator_phone']; ?></td></tr>
                </table>
            </div>
            <div class="col-lg-6">
                <table class="">
                   <tr><td><b>Exam</b></td><td><?php echo $this->config->item('exam')['exam_code']; ?></td></tr>
                   <tr><td><b>Center</b></td><td><?php echo $evaluator['center_code']; ?></td></tr>
                   <tr><td><b>Date</b></td><td><?php echo  $date; ?></td></tr>
                </table>
            </div>
        </div>
        <br/><br/>
        <div class="row">
            <div class="col-lg-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Script File</th>
                                            <th>Score</th>
                                            <th>Marking Status</th>
                                            <th>Marking Type</th>
                                            <!--<th>Final Status</th>-->
                                            <th>Marking Time</th>
                                            <!--<th>Remarks</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($reports as $report) {
                                            $serial=$serial+1;                                                
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$report['sheet_file']."</td>
													<td>".$report['evaluation_marks']."</td>
													<td>Checked</td>
													<td>".$report['evaluation_type']."</td>
													<td>".$report['check_duration']."</td>
													
                                                </tr>";
                                                //<td>".$report['sheet_status']."</td> <td>".$report['sheet_remarks']."</td>
                                        }
                                        ?>        
                                    </tbody>
                                </table>
            </div>
        </div>
        <br/><br/>
        <div class="row">
            <div class="col-lg-6">
               <b>Total Scripts Marked Today</b> : <?php echo $stats['sheet_count']; ?><br/>
                <table class="">
                   <tr><td><b>Average Score</b></td><td><?php echo $stats['sheet_avg']; ?></td></tr>
                   <tr><td><b>Min Score</b></td><td><?php echo $stats['sheet_min']; ?></td></tr>
                   <tr><td><b>Max Score</b></td><td><?php echo $stats['sheet_max']; ?></td></tr>
                </table>
            </div>
            <div class="col-lg-6">
                <table class="">
                    <tr><td><b>Name</b></td></tr>
                    <tr><td><b>Sign</b></td></tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
