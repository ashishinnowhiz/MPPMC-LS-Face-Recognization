<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $this->config->item('exam')['exam_name'];?></title>
     <link rel="shortcut icon" href="<?php echo base_url(); ?>logo/favicon.ico">
         <!-- plugin css -->
        <link href="<?php echo base_url(); ?>assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
		
		<!-- Plugins css -->
        <link href="<?php echo base_url(); ?>assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/libs/clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
		
		<!-- third party css -->
        <link href="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- third party css end -->

        <!-- App css -->
        <link href="<?php echo base_url(); ?>assets/css/bootstrap-creative.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="<?php echo base_url(); ?>assets/css/app-creative.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

        <link href="<?php echo base_url(); ?>assets/css/bootstrap-creative-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
        <link href="<?php echo base_url(); ?>assets/css/app-creative-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />

        <!-- icons -->
        <link href="<?php echo base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="loading" data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}}'>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-right mb-0">

                   
    
                        
                        
                        <li class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                              
                             <?php echo $_SESSION[$this->config->item('exam')['exam_session']]['username']; ?><i class="mdi mdi-chevron-down"></i> 
                                
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>
								<!-- <a href="#" data-toggle="modal" data-target="#resetPasswordModal" class="dropdown-item notify-item">
                                    <i class="fa fa-key"></i>
                                    <span>Reset Password</span>
                                </a> -->
                                <!--Code Added By Vikas-->
                                <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] == 'Coordinator'){?>
                                <a href="<?=base_url()?>"  data-toggle="modal" data-target="#evalModal" class="dropdown-item notify-item">
                                    <i class="fe-settings"></i>
                                    <span>Evaluation Status</span>
                                </a>
                                <?php }?>
                                <!--Code End Here--->
								<!-- <a href="#" data-toggle="modal" data-target="#ftpModal" class="dropdown-item notify-item">
                                    <i class="fe-settings"></i>
                                    <span>FTP Setting</span>
                                </a> -->
				
                               
                                <div class="dropdown-divider"></div>
    
                                <!-- item-->
                                <a href="<?php echo base_url(); ?>logout" class="dropdown-item notify-item">
                                    <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                </a>
    
                            </div>
                        </li>
    
    
                    </ul>
    
                    <!-- LOGO -->
                    <div class="logo-box">
                        <a href="<?php echo base_url(); ?>" class="logo logo-dark text-center">
                            <span class="logo-sm">
                                <img src="<?php echo base_url(); ?>logo/favicon.ico" alt="" height="22">
                                <!-- <span class="logo-lg-text-light">UBold</span> -->
                            </span>
                            <span class="logo-lg">
                                <img src="<?php echo base_url(); ?>logo/logo.png" alt="" height="50">
                                <!-- <span class="logo-lg-text-light">U</span> -->
                            </span>
                        </a>
    
                        <a href="<?php echo base_url(); ?>" class="logo logo-light text-center">
                            <span class="logo-sm">
                                <img src="<?php echo base_url(); ?>logo/favicon.ico" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="<?php echo base_url(); ?>logo/logo.png" alt="" height="40">
                            </span>
                        </a>
                    </div>
					<div class="list-unstyled topnav-menu text-center topnav-menu-center m-0" >
                        
						<h1><?php echo $this->config->item('exam')['center_name'];?></h1>
						<h6>Odd Semester</h6>
					</div>
                    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                        <li>
                            <button class="button-menu-mobile waves-effect waves-light">
                                <i class="fe-menu"></i>
                            </button>
                        </li>

                        <li>
                            <!-- Mobile menu toggle (Horizontal Layout)-->
                            <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </li>   
            
                      
					
					</ul>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- end Topbar -->

            <div class="topnav shadow-lg">
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                             <!-- Code Written By Vikas-->
                             <?php
                            
                            if($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] == 'Evaluator') { ?>
                        
                                  <ul class="navbar-nav">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url('welcome'); ?>" id="topnav-dashboard" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-home mr-1"></i> Home
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?=base_url('welcome/summary')?>" id="topnav-dashboard" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-sidebar mr-1"></i> View Summary
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?=base_url('welcome/get_datewise')?>" id="topnav-dashboard" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-sidebar mr-1"></i> Date Wise
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown"> 
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>practice" id="topnav-ui" role="button"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fe-file mr-1"></i> Practice 
                                        </a>
                                    </li>

                            <!-- Code With Ubes Start -->
                                    <?php
                                // $email=$_SESSION[$this->config->item('exam')['exam_session']]['user_email'];
                                //  $bank_info =$this->BanksModel->getBankDetails($email);
                               
                             
                                //     if(count($bank_info) == 0){
                                ?>
                                <!-- <li class="nav-item dropdown">
                                        <button class="btn btn-link nav-link dropdown-toggle arrow-none" data-toggle='modal' data-target='#bankmodel' role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-plus mr-1"></i> Add Bank Details
                                        </button>
                                </li> -->
                                <?php //}
                                ?>
                            <!-- Code With Ubes End -->
                                <li class="nav-item dropdown">
                                        <button class="btn btn-link nav-link dropdown-toggle arrow-none" data-toggle='modal' data-target='#viewmodel' role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-eye mr-1"></i> View Bank Details
                                        </button>
                                </li>
                                  </ul>
                                                        
                                  <?php } ?>
                                
                            <!--Code End Here -->
						<?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Evaluator') { ?>
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>" id="topnav-dashboard" role="button"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-airplay mr-1"></i> Dashboards
                                    </a>
                                    
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-grid mr-1"></i> Base Data <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                        <!--if-else Condition Added By Vikas-->
                                        <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){?>

                                        <a href="<?php echo base_url();?>marker/deputy_head" class="dropdown-item">Deputy Head Marker</a>
                                        <a href="<?php echo base_url();?>marker/evaluators_info" class="dropdown-item">Markers</a><!--Code Added By Vikas-->
                                        <?php } else {?>
                                        <a href="<?php echo base_url(); ?>courses" class="dropdown-item">Courses</a><!--Code Added By Vikas-->
                                        <a href="<?php echo base_url(); ?>courses/branches" class="dropdown-item">Branches</a>
                                        <a href="<?php echo base_url(); ?>subjects" class="dropdown-item">Subjects</a>
                                       <a href="<?php echo base_url(); ?>regions" class="dropdown-item"> Regions</a>
									   <a href="<?php echo base_url(); ?>mediums" class="dropdown-item"> Mediums</a>
									   <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
                                        
                                            <a href="<?php echo base_url(); ?>examiners" class="dropdown-item">Head Markers</a>
                                        
										<?php } ?>
										<a href="<?php echo base_url(); ?>evaluators" class="dropdown-item"> Markers</a>
										<a href="<?php echo base_url(); ?>papers" class="dropdown-item"> Question Papers</a>
                                        <?php }?>    
                                    </div>
								</li>
 <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Head_Marker') { ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link arrow-none" href="<?php echo base_url(); ?>sheets" id="topnav-ui" role="button"
                                         aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-file mr-1"></i>Scripts 
                                    </a>

                                    
								</li>
 <?php } if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') { ?>
								 <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>recheck" id="topnav-ui" role="button"
                                         aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-briefcase mr-1"></i> Recheck 
                                    </a>
								</li>
                                <li class="nav-item dropdown"> 
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>practice" id="topnav-ui" role="button"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fe-file mr-1"></i> Practice 
                                        </a>
                                </li>
								<?php } ?>
								<?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>upload" id="topnav-ui" role="button"
                                         aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-briefcase mr-1"></i> Sync Script
                                    </a>

                                    
								</li>
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>download" id="topnav-ui" role="button"
                                         aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-briefcase mr-1"></i> Results 
                                    </a>
								</li>
                                <!--Code Added By Vikas-->
                                <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?=base_url('welcome/get_datewise')?>" id="topnav-dashboard" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-sidebar mr-1"></i> DateWise Marker
                                        </a>
                                    </li>
                                <!--Code End Here-->
								<?php } ?>
                                <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker') { ?>
								 <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>recheck_m" id="topnav-ui" role="button"
                                         aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-briefcase mr-1"></i> Recheck 
                                    </a>
								</li>
								 <li class="nav-item dropdown"> 
                                    <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>marker/subjectwise" id="topnav-ui" role="button"
                                         aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-file mr-1"></i> Subject Wise 
                                    </a>

                                    
								</li>
                               <!--Code Written By Vikas-->
                              
                               <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){?>
                                
                                <li class="nav-item dropdown"> 
                                    <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>marker/datewise" id="topnav-ui" role="button"
                                         aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-file mr-1"></i> Date Wise 
                                    </a>
								</li>
                                <li class="nav-item dropdown"> 
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>practice" id="topnav-ui" role="button"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fe-file mr-1"></i> Practice 
                                        </a>
                                    </li>
                                <?php }?>
                             
                              
                               <!--Code End Here-->
									<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-grid mr-1"></i> MIS <div class="arrow-down"></div>
                                    </a>
                                    <!--If Else Condition Added By Vikas-->
                                    <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker') { ?>
                                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                    
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ecommerce"
                                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Daily <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                              
                                                <a href="<?php echo base_url(); ?>marker/evaluators" class="dropdown-item"> Markers</a>
												 <a href="<?php echo base_url(); ?>marker/heads" class="dropdown-item"> Deputy Head Markers</a>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 Consolidated <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-email">
                                               
                                                <a href="<?php echo base_url(); ?>marker/dated_evaluators" class="dropdown-item"> Markers</a>
                                                 <a href="<?php echo base_url(); ?>marker/dated_heads" class="dropdown-item"> Deputy Head Markers</a>
                                               
                                            </div>
                                        </div>


                                    </div>
                                    <?php } else { ?>

                                    <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                    
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ecommerce"
                                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Daily <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                              
                                                <a href="<?php echo base_url(); ?>marker/evaluators" class="dropdown-item"> Markers</a>
												 <a href="<?php echo base_url(); ?>marker/heads" class="dropdown-item"> Head Markers</a>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 Consolidated <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-email">
                                               
                                                <a href="<?php echo base_url(); ?>marker/dated_evaluators" class="dropdown-item"> Markers</a>
                                                 <a href="<?php echo base_url(); ?>reports/dated_heads" class="dropdown-item"> Head Markers</a>
                                               
                                            </div>
                                        </div>


                                    </div>
                                    <?php }?>
                                </li>
								
								<?php }?>
                                 <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Head_Marker') { ?>
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-grid mr-1"></i> MIS <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ecommerce"
                                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Daily <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                              
                                                <a href="<?php echo base_url(); ?>reports/evaluators" class="dropdown-item"> Markers</a>
												<?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
                                                
                                                    <a href="<?php echo base_url(); ?>reports/heads" class="dropdown-item"> Head Markers</a>
                                               
                                                    <a href="<?php echo base_url(); ?>reports/atcs" class="dropdown-item"> Local Server  </a>
                                                
                                                    <a href="<?php echo base_url(); ?>subjects/evaluators" class="dropdown-item"> Subject Wise Markers  </a>
                                                
                                                    <a href="<?php echo base_url(); ?>subjects/examiners" class="dropdown-item">Subject Wise HM  </a>
                                                
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 Consolidated <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-email"> 
                                               
                                                <a href="<?php echo base_url(); ?>reports/dated_evaluators" class="dropdown-item"> Markers</a>
                                                
                                                <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
                                               
                                                    <a href="<?php echo base_url(); ?>reports/dated_heads" class="dropdown-item"> Head Markers</a>
                                               
                                                    <a href="<?php echo base_url(); ?>reports/dated_atcs" class="dropdown-item"> Local Server  </a>
                                               
                                                    <a href="<?php echo base_url(); ?>subjects/dated_evaluators" class="dropdown-item"> Subject Wise Markers  </a>
                                                
                                                    <a href="<?php echo base_url(); ?>subjects/dated_examiners" class="dropdown-item"> Subject Wise HM  </a>
                                               
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <a href="<?php echo base_url(); ?>Banks" class="dropdown-item"> Bank Details</a>
                                    </div>
                                </li>
								
								 <?php } if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-sidebar mr-1"></i> Statistics Scripts <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                        <a href="<?php echo base_url(); ?>reports/subjectwise" class="dropdown-item"> Subject Wise </a>
										<a href="<?php echo base_url(); ?>reports/reject" class="dropdown-item"> Rejected By Subject</a>
										<a href="<?php echo base_url(); ?>reports/reason" class="dropdown-item"> Rejected By Reason</a>
                                    </div>
                                </li>
								<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-sidebar mr-1"></i> Scripts Progress<div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                        <a href="<?php echo base_url(); ?>results" class="dropdown-item"> Checked Scripts</a>
                                         <a href="<?php echo base_url(); ?>sheets/rejected" class="dropdown-item"> Rejected Scripts</a>
										<div class="dropdown">
											<a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ecommerce"
													role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Datewise Counts <div class="arrow-down">
										</div></a>
                                            
                                            <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                              
                                               <a href="<?php echo base_url(); ?>sheets/datewise/checked" class="dropdown-item">Checked</a>
                                               <a href="<?php echo base_url(); ?>sheets/datewise/rechecked" class="dropdown-item"> Rechecked</a>
                                                <a href="<?php echo base_url(); ?>sheets/datewise/pending" class="dropdown-item"> Pending</a>
												<a href="<?php echo base_url(); ?>sheets/datewise/rejected" class="dropdown-item">Rejected</a>
                                            </div>
                                    </div>
                                </li>
                                <!-- <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?=base_url('auditlog')?>" id="topnav-dashboard" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-file mr-1"></i>Audit Logs
                                        </a>
                                </li> -->

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe-file mr-1"></i> Audit Logs<div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                        <a href="<?=base_url('auditlog')?>" class="dropdown-item"> Sheet Logs</a>
                                         <a href="<?= base_url("auditlog/usersLog"); ?>" class="dropdown-item">User Logs</a>
                                    </div>
                                </li>
								<?php  } ?>
							
							</ul> <!-- end navbar-->
						<?php } ?>
						

                        </div> <!-- end .collapsed-->
                    </nav>
                </div> <!-- end container-fluid -->
            </div> <!-- end topnav-->
			 <?php $csrf = array(
                    'name' => $this->security->get_csrf_token_name(),
                    'hash' => $this->security->get_csrf_hash()
            );?>
            <!--Code Added By Vikas-->
            <div class="modal fade" id="evalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabe2">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
				  <h4 class="modal-title" id="myModalLabe2">Evaluation Status</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				  </div>
				  <form name='evalForm' id='evalForm' role="form" action='<?php echo base_url(); ?>settings/evaluation_status' method='POST'>
				  <div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>Status</label><br>
							<label><input type="radio" name="status" value="1" <?= $status == 1 ? 'checked':'';?>>&nbsp;&nbsp;&nbsp;ON &nbsp;&nbsp;&nbsp;</label>
							<label><input type="radio" name="status" value="0" <?= $status == 0 ? 'checked':'';?>>&nbsp;&nbsp;&nbsp;OFf&nbsp;&nbsp;&nbsp;</label>
						</div>
					</div>
	              <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" /> 

				  </div>
				  <div class="modal-footer">
					<a class="btn btn-default" data-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-info">Submit</button>
				  </div>
				  </form>
				</div>
			  </div>
			</div>
            <!--Code End Here-->
			<div class="modal fade" id="ftpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
				  <h4 class="modal-title" id="myModalLabel">FTP Setting</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				  </div>
				  <form name='SubForm' id='SubForm' role="form" action='<?php echo base_url(); ?>welcome/ftp_setting' method='POST'>
				  <div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>FTP</label><br>
							<label><input type="radio" name="password" >&nbsp;&nbsp;&nbsp;True &nbsp;&nbsp;&nbsp;</label>
							<label><input type="radio" name="password">&nbsp;&nbsp;&nbsp;False&nbsp;&nbsp;&nbsp;</label>
						</div>
					</div>
					
				  </div>
				  <div class="modal-footer">
					<a class="btn btn-default" data-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-info">Submit</button>
				  </div>
				  </form>
				</div>
			  </div>
			</div>
			<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
				  <h4 class="modal-title" id="myModalLabel">Change Password</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
				  </div>
				  <form name='SubForm' id='SubForm' role="form" action='<?php echo base_url(); ?>welcome/reset_password' method='POST'>
				  <div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>New Password</label>
							<input type="password" name="password" placeholder='Password' class="form-control" data-validation="required length strength" data-validation-length="min8" data-validation-strength="2" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Confirm Password</label>
							<input type="password" placeholder='Confirm password' class="form-control" data-validation="confirmation" name="password_confirmation" required>
							<!--<input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" /> --> 
						</div>
					</div>	
					
				  </div>
				  <div class="modal-footer">
					<a class="btn btn-default" data-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-info">Submit</button>
				  </div>
				  </form>
				</div>
			  </div>
			</div>


			
			
			
			
			
			
			<div class="content-page">


            <!--Add Bank Form-->
<?php $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
);
?>

<div class="modal fade" id="bankmodel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="editModalLabel">Add Bank Details</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form name='editForm' id='editForm' role="form" action='<?php echo base_url('banks/addBankDetails'); ?>' method='POST' >
      <div class="modal-body" id='edithtml'>
                                     
        <div class="form-group">
            <label>Beneficiary Name *</label>
            <input name='beneName' placeholder="Enter Beneficiary Name" data-validation="required" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="Beneficiary Name is required" data-validation-error-msg-length="Beneficiary Name is not more than 50 chars" class="form-control">
         </div>

         <div class="form-group">
            <label>Bank Account Number *</label>
            <input name='account' placeholder="Enter Bank Account Number" data-validation="required" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="Bank Account Number is required" data-validation-error-msg-length="Bank Account Number is not more than 50 chars" class="form-control">
         </div>

         <div class="form-group">
            <label>Beneficiary Bank Name *</label>
            <input name='bene_bank_name' placeholder="Enter Beneficiary Bank Name" data-validation="required" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="Beneficiary Bank Name is required" data-validation-error-msg-length="Beneficiary Bank Name not more than 50 chars" class="form-control">
         </div>

         <div class="form-group">
            <label>Bank Branch Name *</label>
            <input name='branch_name' placeholder="Enter Bank Branch Name" data-validation="required" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="Bank Branch Name is required" data-validation-error-msg-length="Bank Branch Name not more than 50 chars" class="form-control">
         </div>

         <div class="form-group">
            <label>Bank IFSC Code *</label>
            <input name='ifsc_code' placeholder="Enter Bank IFSC Code" data-validation="required" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="Bank IFSC Code is required" data-validation-error-msg-length="Bank IFSC Code not more than 50 chars" class="form-control">
         </div>

         <div class="form-group">
            <label>Type of Account *</label>
            <input type="text" name="bank_type" placeholder="Enter Bank Type" id="" class="form-control" data-validation="required">
         </div>
         
         <div class="form-group">
            <label>City ( Bank Location ) *</label>
            <input name='city' placeholder="Enter City ( Bank Location )" data-validation="required" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="City ( Bank Location ) is required" data-validation-error-msg-length="City ( Bank Location ) not more than 50 chars" class="form-control">
         </div>

         <div class="form-group">
            <label>PAN No. *</label>
            <input name='pan' placeholder="Enter PAN No." data-validation="required" data-validation-allowing=" " data-validation-length="max50" data-validation-error-msg-required="PAN No. is required" data-validation-error-msg-length="PAN No. not more than 50 chars" class="form-control">
         </div>
         
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <input type="submit" class="btn btn-primary" name="submit" value='Save'>
      </div>
      <input type="hidden" id="<?php echo $csrf['name'];?>" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />

      </form>
    </div>
  </div>
</div>
            <!--Bank Form End Here-->


            <!--view Bank details-->
            
                            <!-- Code With Ubes Start -->
            <!-- <div class="modal fade" id="viewmodel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="editModalLabel">View Bank Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form id='editForm' role="form" action='<?php echo base_url('welcome/updateBankInfo'); ?>' method='POST'>
        <div class="modal-body" id='edithtml'>
          <?php 
        //   $banks = [];
        //   if(isset($_SESSION[$this->config->item('exam')['exam_session']]['user_role']) && $_SESSION[$this->config->item('exam')['exam_session']]['user_role'] != 'Coordinator') {
        //     $email = $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] ?? '';
        //     $banks =$this->BanksModel->getBankDetails($email);
        //   }
        //   if(!empty($banks)) {
        //     foreach($banks as $info) { 
                ?>
              <div class="form-group">
                <label>Beneficiary Name *</label>
                <input name='beneName' value="<?=htmlspecialchars($info['beneficiary_name'])?>" placeholder="Enter Beneficiary Name" class="form-control" disabled>
              </div>
              <div class="form-group">
                <label>Bank Account Number *</label>
                <input name='account' value="<?=htmlspecialchars($info['account_number'])?>" placeholder="Enter Bank Account Number" class="form-control" disabled>
              </div>
              <div class="form-group">
                <label>Beneficiary Bank Name *</label>
                <input name='bene_bank_name' value="<?=htmlspecialchars($info['beneficiary_bank_name'])?>" placeholder="Enter Beneficiary Bank Name" class="form-control" disabled>
              </div>
              <div class="form-group">
                <label>Bank Branch Name *</label>
                <input name='branch_name' value="<?=htmlspecialchars($info['branch_name'])?>" placeholder="Enter Bank Branch Name" class="form-control" disabled>
              </div>
              <div class="form-group">
                <label>Bank IFSC Code *</label>
                <input name='ifsc_code' value="<?=htmlspecialchars($info['ifsc_code'])?>" placeholder="Enter Bank IFSC Code" class="form-control" disabled>
              </div>
              <div class="form-group">
                <label>Type of Account *</label>
                <input type="text" value="<?=htmlspecialchars($info['account_type'])?>" name="bank_type" class="form-control" disabled>
              </div>
              <div class="form-group">
                <label>City (Bank Location) *</label>
                <input name='city' value="<?=htmlspecialchars($info['city'])?>" placeholder="Enter City (Bank Location)" class="form-control" disabled>
              </div>
              <div class="form-group">
                <label>PAN No. *</label>
                <input name='pan' value="<?=htmlspecialchars($info['pan'])?>" placeholder="Enter PAN No." class="form-control" disabled>
              </div>
            <?php //} 
          //} else { 
            ?>
            <div class="alert alert-info" style="text-align:center">
              Please Add Bank Details First.
            </div>
          <?php //} 
          ?>
        </div>
        <div class="modal-footer">
          <a class="btn btn-default" data-dismiss="modal">Close</a>
          <?php //if(!empty($banks)) { 
            ?>
            <button type="button" class="btn btn-info" id="edit-up" onclick="enableBtn()">Edit</button>
            <button type="submit" class="btn btn-success" id="update-form" style="display:none">Update</button>
          <?php //} 
          ?>
        </div>
        <input type="hidden" id="<?=htmlspecialchars($csrf['name'])?>" name="<?=htmlspecialchars($csrf['name'])?>" value="<?=htmlspecialchars($csrf['hash'])?>" />
      </form>
    </div>
  </div>
</div> -->

                            <!-- Code With Ubes End -->
<script>
    function enableBtn(){
        var formControls = document.getElementsByClassName("form-control");
    for (var i = 0; i < formControls.length; i++) {
        formControls[i].disabled = false;
    } 
    document.getElementById('update-form').style.display="block";
    document.getElementById('edit-up').style.display="none";
    
}
</script>
            <!--Bank Form End Here-->