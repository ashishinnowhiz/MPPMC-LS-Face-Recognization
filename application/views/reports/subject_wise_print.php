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


<?php
foreach ($subject_counts as $subcount) {
    $subject_count[$subcount['subject_code']][$subcount['sheet_status']]=$subcount['Total'];
}
?> 
 <div class="container" style='background-color:#fff;min-height:800px;'>

        <div class="row">

            <div class="col-lg-12 text-center">

                <h1>Marking Status Report</h1>

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
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Total AB</th>
                                            <th>Checked/Rechecked Today</th>
                                            <th>Checked Till Date</th>
                                            <th>Remaining AB</th>
                                            <th>Rejected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($subject_date as $row) {
                                            $today[$row['subject_code']]=$row['sheet_count'];
                                        }
                                            
                                        foreach ($subjects as $subject) {
                                            if (isset($subject_count[$subject['subject_code']])) {
                                                $pcc=$subject_count[$subject['subject_code']];
                                                if (isset($pcc['Pending'])) {
                                                    $pc['Pending']=$pcc['Pending'];
                                                } else {
                                                    $pc['Pending']=0;
                                                }
                                                if (isset($pcc['Assigned'])) {
                                                    $pc['Assigned']=$pcc['Assigned'];
                                                } else {
                                                    $pc['Assigned']=0;
                                                }
                                                if (isset($pcc['Checked'])) {
                                                    $pc['Checked']=$pcc['Checked'];
                                                } else {
                                                    $pc['Checked']=0;
                                                }
                                                if (isset($pcc['Rejected'])) {
                                                    $pc['Rejected']=$pcc['Rejected'];
                                                } else {
                                                    $pc['Rejected']=0;
                                                }
                                                if (isset($pcc['Rechecked'])) {
                                                    $pc['Rechecked']=$pcc['Rechecked'];
                                                } else {
                                                    $pc['Rechecked']=0;
                                                }
                                                unset($pcc);
                                            } else {
                                                $pc['Pending']=0;
                                                $pc['Assigned']=0;
                                                $pc['Checked']=0;
                                                $pc['Rejected']=0;
                                                $pc['Rechecked']=0;
                                            }
                                            $total=$pc['Pending']+$pc['Assigned']+$pc['Checked']+$pc['Rejected']+$pc['Rechecked'];
                                            $subject_count[$subject['subject_code']]['Pending']=$pc['Pending'];
                                            if (isset($today[$subject['subject_code']])) {
                                                $today_count=$today[$subject['subject_code']];
                                            } else {
                                                $today_count=0;
                                            }
                                            $checked=intval($pc['Checked'])+intval($pc['Rechecked']);
                                            $pending=intval($pc['Pending'])+intval($pc['Assigned']);
                                            echo "<tr>
													<td>".$subject['subject_code']."</td>
													<td>".$subject['subject_name']."</td>
													<td>".$total."</td>
													<td>".$today_count."</td>
													<td>".$checked."</td>
													<td>".$pending."</td>
													<td>".$pc['Rejected']."</td>
												</tr>";
                                            unset($pc);
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            
                </div>
            </div>
</div>



</body>

</html>
