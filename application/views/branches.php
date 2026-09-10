<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

 
<div style='padding:80px;padding-top:0'>				
<div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masters">Base Data</a></li>
                                            <li class="breadcrumb-item active">Branches</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Branches</h4>
                                </div>
                            </div>
                        </div>     
                <?php if ($this->session->flashdata('error') == true) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif;
                  if ($this->session->flashdata('success') == true) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
               <?php endif; ?>

          
            
           
             <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Branches List</h4>
                     
							<?php if (sizeof($courses)>0) { ?>
                                 <table id="basic-datatable" class="table dt-responsive nowrap w-100">
								    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Branches Name</th>
                                            <th>Branches Code</th>
                                            <th>Course Name</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($courses as $course) {
                                          $parent = $this->CoursesModel->get_courses_by_code($course['parent']);
                                          $parentName = json_decode(json_encode($parent),true);
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$course['course_name']."</td>
													<td>".$course['course_code']."</td>
													<td>".$course['parent']."-".$parentName[0]['course_name']."</td>";
                                           
                                            echo "</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
								<?php } else { ?>
								<div class="alert alert-danger">
									No Data/Records Found
								</div>
								<?php } ?>
                            </div>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            
             </div>
            
