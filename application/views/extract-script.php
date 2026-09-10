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
                                            <li class="breadcrumb-item"><a href="<?php echo base_url('sheets'); ?>">Scripts</a></li>
                                            <li class="breadcrumb-item active">All Scripts</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Answer Scripts</h4>
                                </div>
                            </div>
                        </div>
      
            
   
			
            <?php if (sizeof($sheets)>0) { ?>
			
			 <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Answer Scripts Records</h4>
										
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Paper Code</th>
                                            <th>Allocation</th>
                                            <th>SIZE</th>
                                            <th>Script file</th>
                                            <th>Status</th>
                                            <th>File Count</th>
                                            <th>Checked Time</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($sheets as $sheet) {
                                            $serial=$serial+1;
                                            $time = "-";
                                           
                                             $link="upload2/extractNew/".$sheet['sheet_file']."/".$sheet['allocation_id'];
										   $scdir="answersheets/".$sheet['allocation_id']."/".$sheet['sheet_file']."img";
										  
										// echo  $filecount=scandir($scdir)-3;
										$count=0;
										   if(is_dir($scdir)){
										   $filecount=sizeof(scandir($scdir))-3;
										  $count=file_get_contents($scdir."/count.txt");
										   }else{
											   $filecount=0;
										   }
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$sheet['paper_code']."</td>
													<td>".filesize("answersheets/".$sheet['allocation_id']."/".$sheet['sheet_file'])."</td>
													<td>".$sheet['paper_code']."-".$sheet['allocation_id']."-".$filecount."</td>
													<td>".$sheet['sheet_file']."</td>
													<td>".$filecount."</td>";?>
													<td><?php echo $filecount."-".$count; ?></td>
													<td><a href="<?php echo base_url().$link; ?>" target="_blank" class="btn btn-info">Convert<a>
													<?php //if($filecount==0){?><a href="<?php echo base_url()."Upload2/reject/".$sheet['sheet_file']; ?>"  class="btn btn-info">Reject<a>
													<?php //}?><a href="<?php echo base_url()."answersheets/".$sheet['allocation_id']."/".$sheet['sheet_file']; ?>" target="_blank" class="btn btn-info">View<a></td>
													
												</tr>
                                        <?php }
                                        ?>
                                        
                                    </tbody>
                                </table>
								
								
                                
                            </div>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            
            <?php } else { ?>
            <div class="alert alert-danger">
                No Data/Records Found
            </div>
            <?php } ?>
			</div>
</div>

