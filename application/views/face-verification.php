<!-- 
<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); ?> -->
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
<style>

.video-container {
    max-width: 640px;
    margin: 0 auto;
    border: 5px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

video {
    width: 100%;
    height: auto;
    display: block;
}

canvas {
    display: none;
    max-width: 100%;
    border: 5px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
</style>

    <style>
#loader {
    display: none;
    width: 85px;
    height: 85px;
    border: 5px dashed  #f3f3f3;
    border-top: 5px solid #007bff;
    border-radius: 50%;
    animation: spin 2s linear infinite;
    position: absolute;
    top: 53%;
    left: 34%;
    transform: translate(-50%, -50%);
	background-image: url('https://i.pinimg.com/originals/aa/e6/f2/aae6f26944b0c196fdf41e4d91e4eab3.jpg');
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>
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
	 <div class="video-container">
	   <video id="video" width="849" height="405" autoplay ></video>
	   <div id="loader"></div>
	 </div>
    <canvas id="canvas" width="550" height="550" style="display:none;"></canvas>
	 <!-- <img src="<?php echo base_url(); ?>img/login-image.jpg" style="width:auto;
height: 445px;
border-radius: 5px;">-->
	</div>
    <div class="page-content vertical-align-middle" style="width:400px;margin-top:50px">
      <div class="panel">
        <div class="panel-body">
          <div class="brand">
            
            <h2 class="brand-text font-size-18 text-center"><?php echo $this->config->item('exam')['center_name'];?></h2>
          </div>
		  <h3 class="text-center">Face Verification</h3>
			<?php if (isset($_SESSION['logouts'])) {
                        echo "<div class='alert alert-danger'>".$_SESSION['logouts']."</div>";
                    }
                     unset($_SESSION['logouts']);
                        ?>

		  <?php //if (isset($error)) {
                   //     echo "<div class='alert alert-danger'>".$error."</div>";
                  //  }
                        $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                        );
        ?>

<?php 
					//if (isset($success)) {
                      //  echo "<div class='alert alert-success'>".$success."</div>";
                    //}
                        $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                        );
						//echo $_SESSION['otp'];
        ?>
               
				<div class="form-group form-material floating" data-plugin="formMaterial">
				
		
				  <button onclick="startAnalysis()" class="btn btn-primary btn-block btn-lg mt-40">Verify Your Face</button>
				  <p >
				   <br><br>
				  <span id="peopleauth" style="font-size:12px;color:red">1) Please make sure no other people are in the background.</span>
				  <br>
				  <span id="comecloserauth" style="font-size:12px;color:red">2) Make sure to come closer to the webcam for a clear vision.</span>
				  <br>
				  <span id="mobileauth" style="font-size:12px;color:red">3) Please do not use your mobile phone during face recognition.</span>
				  <br>
				  <span id="faceauth" style="font-size:12px;color:red"></span>
				  </p>
				 
				    
				</div>
			   
             <form role="form" name='loginForm' onsubmit="return hashpass();"  action='' method='POST'>
            
            <input type="hidden" id="email_auth" name="email" value="<?=$email?>">                                                                                                                            
            <input type="hidden" id="role_auth" name="user_role" value="<?=$user_role?>">                                                                                 
		      	<input type="hidden" id="scrf_auth" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
			
           
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
  document.addEventListener("contextmenu", function(e){
        e.preventDefault();
    });
	document.onkeydown = function(e) {
        if (e.key === "F12" || e.keyCode === 123) {
            e.preventDefault();
        }
    };
	document.addEventListener("keydown", function(e) {
        if (e.ctrlKey || e.metaKey) {
            e.preventDefault();
        }
    });
	setInterval(function() {
        if (typeof console !== "undefined" && console.log) {
            console.clear();
            // You can take additional actions here
        }
    }, 1000);
    </script>
	 <script src="<?=base_url()?>js/aws-sdk.js"></script>
	 <?php $img = file_get_contents($profile_pic);
        // Encode the image string data into base64
        $data = base64_encode($img);
    ?>
<script>
    const sourceImages = "<?php echo $data?>";
    const baseUrl = "<?=base_url()?>";
		const csrfName="<?php echo $this->security->get_csrf_token_name(); ?>";
		const csrfHash="<?php echo $this->security->get_csrf_hash();?>";
		const url_path="<?php echo base_url("Login/faceVerification"); ?>"
		const logout_path="<?php echo base_url("login/face_session_out/"); ?>"
</script>
<script src="<?php echo base_url('js/afv.js')?>"></script>

</body>

</html>
