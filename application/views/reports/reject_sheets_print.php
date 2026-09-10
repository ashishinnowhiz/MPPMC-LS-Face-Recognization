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



<body onload="window.print()">



 <div class="container" style='background-color:#fff;min-height:800px;'>

        <div class="row">

            <div class="col-lg-12 text-center">

                <h1>Rejected Sheets By <?php echo ucfirst($status);?></h1>

            </div>

        </div>

        <br/><br/>

        <div class="row">

            <div class="col-lg-6">

                <table class="">

                    <tr><td><b>Exam</b></td><td><?php echo $this->config->item('exam')['exam_code']; ?></td></tr>

                    <tr><td><b>Center</b></td><td><?php echo $_SESSION[$this->config->item('exam')['exam_session']]['user_center']; ?></td></tr>

                </table>

            </div>

            <div class="col-lg-6">

                <table class="">

                    <tr><td><b>Date</b></td><td><?php echo date('d-m-Y', time()); ?></td></tr>

                </table>

            </div>

        </div>

        <br/><br/>
          
            <div class='row'>
                <div class="col-lg-12">
                    
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Paper Code</th>
                                            <th>Medium Code</th>
                                            <th>Region Code</th>
                                            <th>Script file</th>
                                            <th>Status</th>
                                            <th>Reason</th>
                                            <th>Reject Time</th>
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
													<td>".$report['paper_code']."</td>
													<td>".$report['medium_code']."</td>
													<td>".$report['region_code']."</td>
													<td>".$report['sheet_file']."</td>
													<td>".$report['sheet_status']."</td>
													<td>".$report['sheet_remarks']."</td>
													<td>".$report['evaluation_time']."</td>
												</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            
                </div>
            </div>
</div>



</body>

</html>
