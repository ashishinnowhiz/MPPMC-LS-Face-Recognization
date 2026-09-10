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
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masters">Base Data</a></li>
                                            <li class="breadcrumb-item active">Mediums</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Mediums</h4>
                                </div>
                            </div>
                        </div>     
            <?php if (sizeof($mediums)>0) { ?>
             <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Mediums List</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Medium Name</th>
                                            <th>Medium Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($mediums as $medium) {
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$medium['medium_name']."</td>
													<td>".$medium['medium_code']."</td>";
                                            echo "</tr>";
                                        }
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
