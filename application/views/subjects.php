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
                                            <li class="breadcrumb-item active">Subjects</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Subjects</h4>
                                </div>
                            </div>
                        </div>     
            
            <?php if (sizeof($subjects)>0) { ?>
                   <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Subjects List</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Branch Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($serial)) {
                                            $serial=0;
                                        }
                                        foreach ($subjects as $subject) {
                                            $course_info = $this->CoursesModel->get_courses_by_code($subject['course_code']);
                                            $course_array = json_decode(json_encode($course_info),true);
                                            $course_name = $course_array[0]['course_name'];
                                            // $course_name = $course_info[0]['course_name'];
                                            $serial=$serial+1;
                                            echo "<tr>
													<td>".$serial."</td>
													<td>".$subject['subject_name']."</td>
													<td>".$subject['subject_code']."</td>
                                                    <td>".$subject['course_code'].'-'.$course_name."</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            
            <?php } else { ?>
            <div class="alert alert-danger">
                No Data/Records Found
            </div>
            <?php } ?>
		</div>
</div>
