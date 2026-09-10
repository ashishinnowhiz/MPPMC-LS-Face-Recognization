<!-- 
<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); ?> -->

<?php //print_r($_SESSION); ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $this->config->item('exam')['exam_name'];?></title>
    <!-- Stylesheets -->
	<link rel="shortcut icon" href="<?php echo base_url(); ?>logo/favicon.ico">
  <link rel="stylesheet" href="<?php echo base_url(); ?>global/css/bootstrap.minfd53.css?v4.0.1">
  <link rel="stylesheet" href="<?php echo base_url(); ?>global/css/bootstrap-extend.minfd53.css?v4.0.1">
  <link rel="stylesheet" href="<?php echo base_url(); ?>global/assets/css/site.minfd53.css?v4.0.1">
  <!-- Fonts -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>global/fonts/material-design/material-design.minfd53.css?v4.0.1">
  <link rel="stylesheet" href="<?php echo base_url(); ?>global/fonts/brand-icons/brand-icons.minfd53.css?v4.0.1">
  <link rel='stylesheet' href="https://fonts.googleapis.com/css?family=Roboto:400,400italic,700">
  
</head>
<body class="animsition dashboard">


  <nav class="site-navbar navbar navbar-default navbar-inverse navbar-fixed-top navbar-mega"
    role="navigation">

    <div class="navbar-header">
      <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
        data-toggle="menubar">
        <span class="sr-only">Toggle navigation</span>
        <span class="hamburger-bar"></span>
      </button>
      <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
        data-toggle="collapse">
        <i class="icon md-more" aria-hidden="true"></i>
      </button>
      <div class="navbar-brand navbar-brand-center">
        <img class="navbar-brand-logo" src="<?php echo base_url(); ?>logo/logo.png" title="DigiMaker">
        <span class="navbar-brand-text hidden-xs-down"> </span>
      </div>
      <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search"
        data-toggle="collapse">
        <span class="sr-only">Toggle Search</span>
        <i class="icon md-search" aria-hidden="true"></i>
      </button>
    </div>
	<div class="navbar-container container-fluid">
      <!-- Navbar Collapse -->
      <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
		<ul class="nav navbar-toolbar navbar-left navbar-toolbar-right">
          
          <li class="nav-item">
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <a href="#"><span class="site-menu-title">Home</span></a> &nbsp;&nbsp;&nbsp;
          </li>
		  <li class="nav-item">
            <a href="#"><span class="site-menu-title">About</span></a>&nbsp;&nbsp;&nbsp;
          </li>
		  <li class="nav-item">
            <a href="#"><span class="site-menu-title">Intruction</span> </a>&nbsp;&nbsp;&nbsp;
          </li>
		  <li class="nav-item">
            <a href="#"><span class="site-menu-title"> Help</span></a>&nbsp;&nbsp;&nbsp;
          </li>
       
		</ul>
       </div>
      </div>
    </nav>
 </div>
      </div>
    </div>
  </div>
  <!-- Page -->
  <div class="page page-login vertical-align" data-animsition-in="fade-in" data-animsition-out="fade-out">
	 <div class="page-content" style="width: 68%;float: left;">
	<img src="<?php echo base_url(); ?>img/login-image.jpg" style="width:auto;height: 445px;border-radius: 5px;">
	</div>
    <div class="page-content vertical-align-middle" style="width:400px;margin-top:50px">
      <div class="panel">
        <div class="panel-body">
          <div class="brand">
            
            <h2 class="brand-text font-size-18 text-center"><?php echo $this->config->item('exam')['center_name'];?></h2>
          </div>
			<?php if (isset($_SESSION['logouts'])) {
                        echo "<div class='alert alert-danger'>".$_SESSION['logouts']."</div>";
                    }
                     unset($_SESSION['logouts']);
                        ?>

		  <?php if (isset($error)) {
                        echo "<div class='alert alert-danger'>".$error."</div>";
                    }
                        $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                        );
        ?>

<?php if (isset($success)){
                        echo "<div class='alert alert-success'>".$success."</div>";
                        }
                        $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                        );
						//echo $_SESSION['otp'];
        ?>
                        
             <form role="form" name='loginForm' onsubmit="return hashpass();"  action='<?php echo base_url(); ?>login/otpVerification' method='POST'>
            <div class="form-group form-material floating" data-plugin="formMaterial">
              <input type="text" class="form-control" name="otp" value="<?=$otp?>"   required   autofocus />
              <label class="floating-label">OTP</label>
            </div>
            <input type="hidden" name="email" value="<?=$email?>">                                                                                                                            
            <input type="hidden" name="user_role" value="<?=$user_role?>">                                                                                 
			   	  <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
			
            <button type="submit" value="Login" class="btn btn-primary btn-block btn-lg mt-40">Verify</button>
          </form>

          <form role="form" name='otpresend' onsubmit="return hashpass();"  action='<?php echo base_url(); ?>login/resend_otp' method='POST'>
          <input type="hidden" name="email" value="<?=$email?>">                                                                                                                            
          <input type="hidden" name="user_role" value="<?=$user_role?>"> 
          <button type="submit" name="resend" value="resend" class="btn btn-primary btn-block btn-lg mt-40">Resend OTP</button>
          <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
  
        </form>
         
        </div>
      </div>


    </div>
  </div>
  <footer class="site-footer page-login">
    <div class="row">
                            <div class="col-md-6">
                                2019-<script>document.write(new Date().getFullYear())</script> &copy;DigiMarker  
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-right footer-links d-none d-sm-block">
                                    <a href="javascript:void(0);">About Us</a>&nbsp;&nbsp;
                                    <a href="javascript:void(0);">Contact </a>&nbsp;&nbsp;
                                    <a href="javascript:void(0);">Policy </a>&nbsp;&nbsp;
                                    <a href="javascript:void(0);">Terms & Conditions </a>&nbsp;&nbsp;
                            </div>
                            </div>
                        </div>
  </footer>
    <!-- /#wrapper -->

    <!-- jQuery -->

   <script src="<?php echo base_url(); ?>global/vendor/babel-external-helpers/babel-external-helpersfd53.js?v4.0.1"></script>
  <script src="<?php echo base_url(); ?>global/vendor/jquery/jquery.minfd53.js?v4.0.1"></script>

  <script src="<?php echo base_url(); ?>global/vendor/bootstrap/bootstrap.minfd53.js?v4.0.1"></script>

  <script src="<?php echo base_url(); ?>global/vendor/mousewheel/jquery.mousewheel.minfd53.js?v4.0.1"></script>
  


  <!-- Scripts -->
  
  <script src="<?php echo base_url(); ?>global/js/Component.minfd53.js?v4.0.1"></script>
  <script src="<?php echo base_url(); ?>global/js/Plugin.minfd53.js?v4.0.1"></script>
  <script src="<?php echo base_url(); ?>global/js/Base.minfd53.js?v4.0.1"></script>

  <!-- Page -->
  <script src="<?php echo base_url(); ?>global/assets/js/Site.minfd53.js?v4.0.1"></script>
  
  <script src="<?php echo base_url(); ?>global/assets/examples/js/dashboard/v1.minfd53.js?v4.0.1"></script>

  <script src="<?php echo base_url(); ?>global/js/material.minfd53.js?v4.0.1"></script>


 <!-- SHA256 -->
    <script src="<?php echo base_url(); ?>js/HmacSHA256.js"></script>
    
    <script>
        function hashpass(){
            var FormName='loginForm';
            //document.forms[FormName]["pass"].value=SHA256_hash(document.forms[FormName]["pass"].value);
            document.forms[FormName]["pass"].value=HMAC_SHA256_MAC("aSm0$i_20eNh3os", document.forms[FormName]["pass"].value);
            return true;
        }
    </script>

</body>

</html>
