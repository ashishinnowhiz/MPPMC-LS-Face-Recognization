<?php defined('BASEPATH') or exit('No direct script access allowed');
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);?>
<div id="page-wrapper">
    <div class='row'>
        <div class="col-lg-12">
            <br/>
            <ol class = "breadcrumb">
               <li><a href = "<?php echo base_url(); ?>">Home</a></li>
               <li class = "active">Activities</li>
            </ol>
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
                    <h1 class="page-header"><i class="fa fa-file-text -o fa-fw"></i> Activities Log</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            <?php if (sizeof($activities)>0) { ?>
            <div class='row'>
                <div class="col-lg-12">
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Activities Log Total : <?php echo $count; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Activity</th>
                                            <th>User</th>
                                            <th>URL</th>
                                            <th>Data</th>
                                            <th>Details</th>
                                            <th>IP</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($activities as $activity) {
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$activity['activity_type']."</td>
													<td>".$activity['user_name']."</td>
													<td>".$activity['activity_url']."</td>
													<td>";
                                            if ($activity['activity_data']=='') {
                                                echo "NA";
                                            } else {
                                                $obj=json_decode($activity['activity_data']);
                                                foreach ($obj as $key => $val) {
                                                    echo $key."-".$val."<br/>";
                                                }
                                            }
                                                    
                                                echo "</td>
													<td>".$activity['activity_detail']."</td>
													<td>".$activity['activity_ip']."</td>
													<td>".$activity['activity_time']."</td>
												</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                                <?php echo $pagination; ?>
                            </div>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
            <?php } else { ?>
            <div class="alert alert-danger">
                No Data/Records Found
            </div>
            <?php } ?>
</div>


