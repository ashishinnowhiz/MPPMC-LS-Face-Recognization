<?php
defined('BASEPATH') or exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ApMark Admin</title>
    <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">  

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url(); ?>vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<br/>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Web Based Evaluation</h1>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-4 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Instructions</h3>
                    </div>
                    <div class="panel-body">
                        <?php if ($config->wbe=='0' && $config->otp=='0') { ?>
                            <h3>Downloading Software(EXE) file</h3>
                        <ol>
                            <li>Click Generate download link</li>
                            <li>link will be generated</li>
                            <li>use 'Click to download'</li>
                        </ol>
                        <?php } ?>
                        <?php if ($config->wbe=='1') { ?>
                        <h3>Downloading Software(EXE) file</h3>
                        <ol>
                            <li>Click Generate download link</li>
                            <li>You will receive OTP on mobile</li>
                            <li>Enter OTP and click Verify</li>
                        </ol>
                        <?php }
                        if ($config->otp=='1') { ?>
                        <h3>Password for login in EXE</h3>
                        <ol>
                            <li>Click Generate One Time Password</li>
                            <li>You will receive details on registered Mobile</li>
                            <li>Use the password received this will work for once</li>
                            <li>For each login at EXE, you have to Generate Password</li>
                        </ol>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">User Detail</h3>
                    </div>
                    <div class="panel-body">
                        Username : <?php echo $_SESSION[$this->config->item('exam')['exam_session']]['user_name']; ?><br/><br/>
                        <?php if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Evaluator') { ?>
                            <a class='btn sm-btn btn-success' href="<?php echo base_url(); ?>">
                                Dashboard
                            </a>
                        <?php } ?>
                            <a class='btn sm-btn btn-danger' href="<?php echo base_url(); ?>logout">
                                Logout
                            </a>
                    </div>
                </div>
                
                <?php if ($config->wbe=='1' || $_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Download Software(EXE)</h3>
                    </div>
                    <div class="panel-body">
                        <div id='linkMsg'></div>
                        <div id='generateLink'>
                            <a onclick="generate()" class="btn btn-md btn-success btn-block">
                                Generate Download Link
                            </a>
                        </div>
                        <div id='verifyLink' style='display:none;'>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Enter OTP" name="otp" id='otpPin' type="text">
                                    </div>
                                    <a onclick="verifyOtp()" class="btn btn-md btn-success btn-block">Verify</a>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($config->otp=='1' && $_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator') { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Generate Password</h3>
                    </div>
                    <div class="panel-body">
                        Username : <?php echo $_SESSION[$this->config->item('exam')['exam_session']]['user_name']; ?><br/><br/>
                        <div id='passMsg'>
                        </div>
                        <div id='passDiv'>
                            <a onclick="sendPass()" class="btn btn-md btn-success btn-block">
                                Generate One Time Password
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url(); ?>vendor/bootstrap/js/bootstrap.min.js"></script>
    <script>
        function generate() {
            $('#generateLink').hide();
            <?php if ($config->otp=='0' || $_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') { ?>
                var base_url='<?php echo base_url(); ?>';
                $.ajax({
                    type: 'GET',
                    url: base_url+'webotp/verify/1',
                    dataType: 'json',           
                    success:function(data)
                    {
                        if(data.success){
                            $('#verifyLink').hide();
                            $('#linkMsg').html('<a href="'+data.link+'">Click to Download</a>');
                        }else{
                            alert(data.message);
                        }
                    }
                });
            <?php }else{ ?>
            $('#linkMsg').html('Sending OTP..');
            var base_url='<?php echo base_url(); ?>';
                $.ajax({
                    type: 'GET',
                    url: base_url+'webotp/link',
                    dataType: 'json',           
                    success:function(data)
                    {
                        if(data.success){
                            $('#linkMsg').html('OTP sent to registered mobile number');
                            $('#verifyLink').show();
                        }else{
                            $('#linkMsg').html('Sending OTP failed. Contact coordinator');
                            $('#generateLink').show();
                        }
                    }
                });
            <?php } ?>
        }
        function sendPass() {
            $('#passDiv').hide();
            $('#passMsg').html('Sending password..');
            var base_url='<?php echo base_url(); ?>';
                $.ajax({
                    type: 'GET',
                    url: base_url+'webotp/password',
                    dataType: 'json',           
                    success:function(data)
                    {
                        if(data.success){
                            $('#passMsg').html(data.message+"<br/><br/>");
                        }else{
                            $('#passMsg').html('Sending password failed. Contact coordinator');
                            $('#passDiv').show();
                        }
                    }
                });
        }
        function verifyOtp(){
            if( document.getElementById('otpPin').value==''){
                alert('Please enter OTP');
            }else{
                var base_url='<?php echo base_url(); ?>';
                $.ajax({
                    type: 'GET',
                    url: base_url+'webotp/verify/'+ document.getElementById('otpPin').value,
                    dataType: 'json',           
                    success:function(data)
                    {
                        if(data.success){
                            $('#verifyLink').hide();
                            $('#linkMsg').html('<a href="'+data.link+'">Click to Download</a>');
                        }else{
                            alert(data.message);
                        }
                    }
                });
            }
        }
    </script>

</body>

</html>
