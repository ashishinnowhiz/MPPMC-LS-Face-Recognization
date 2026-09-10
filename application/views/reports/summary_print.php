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

                <h1>Summary Report</h1>

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
                    <tr><td><b>Class</b></td><td></td></tr>

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
                                            <th>Evaluator Username</th>
                                            <th>Sheets Checked</th>
                                            <th>Sheets Rejected</th>
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



</body>

</html>
