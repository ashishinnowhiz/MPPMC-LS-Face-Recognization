<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
            parent::__construct();
        if (isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect();
        }
            $this->load->model('UsersModel');
            $this->load->library('form_validation');
            $this->load->helper('captcha');
            $this->load->library("email");
			$this->sessionunset();
    }
	public function sessionunset()
    {
		
		$sessiondata=$this->db->get("login_user")->result_array();
		//print_r($sessiondata);die;
		foreach($sessiondata as $result){
			 $user=$result['user_name'];
				 $usertime=$result['user_time'];
			//echo "<br>";
			 $currenttime=date('Y-m-d H:i:s', time());
			$usertime = strtotime($usertime); 
			$currenttime = strtotime($currenttime); 
			$diff_minutes = ($currenttime - $usertime)/60;
			//echo "--".$diff_minutes."<br>";
			if($diff_minutes>60){
				
				$this->db->delete("login_user",array("user_name"=>$user));
				 if($user==$_SESSION[$this->config->item('exam')['exam_session']]['user_name']){
					redirect("logout");
				} 
				
			}
		}
    }
    public function index()
    {   //Code Alter BY Vikas

        $this->load->model('SettingsModel');
        $data = array();
        $settings = $this->SettingsModel->get_settings_for('general');
        $data['settings'] = json_decode($settings[0]['setting_json'],true);
        // echo "</pre>";
        // print_r(   $data['settings']);
        // die;
       $this->showform($data);

        
    }
   
	public function testsheet()
    {
		$data['sheet']=array('http://test.digimarker.online/rjn10bhopal/answersheets/1203/0.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/1.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/2.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/3.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/4.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/5.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/6.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/7.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/8.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/9.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/10.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/11.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/12.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/13.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/14.jpg','http://test.digimarker.online/rjn10bhopal/answersheets/1203/15.jpg');
		echo json_encode($data);
    }
	public function reset_session()
    {
			$this->load->model('UsersModel');
			$this->UsersModel->reset_login_session();
			redirect();
    }
    //Code Written By Vikas

    public function check_user(){
//error_reporting(E_ALL);
// ini_set('display_errors', 1);
               $this->db->where('user_email', $this->input->post('user'));
                $this->db->limit(1);
                $query = $this->db->get('users');
				$row=$query->row_array();
        
        if($row['user_role']!='Coordinator'){

            $this->load->model('SettingsModel');
            $email_info = $this->SettingsModel->get_setting('email');
            $sms_info = $this->SettingsModel->get_setting('sms');
            $data['Email_Setting'] = json_decode($email_info["setting_json"],true);
            $data['Sms_Setting'] = json_decode($sms_info["setting_json"],true);
           if($data['Email_Setting']['email_status'] == 1 || $data['Sms_Setting']['sms_status'] == 1){
            // $this->load->view("role-selection");
            // $data=array("role"=>"Marker");
            $this->otp_post();
           }else{
            $this->post();
           }
               
        }else{
            $this->post();
        }
    }

    public function otp_post()
    {   
		$_SESSION[$this->config->item('exam')['exam_session']]=array();
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $this->output->set_status_header(405);
        } else {
            $this->load->helper('form');
            $data['title'] = 'Login';
            $this->form_validation->set_rules('user', 'Email', 'required');
            $this->form_validation->set_rules('pass', 'Password', 'required');
            $this->form_validation->set_rules('userCaptcha', 'Captcha', 'required|callback_check_captcha');
            $userCaptcha = $this->input->post('userCaptcha');
            
            if ($this->form_validation->run() === false) {
                $this->showform($data);
            } else {
                $this->db->where('user_email', $this->input->post('user'));
                $this->db->limit(1);
                $query = $this->db->get('users');
				$row=$query->row_array();
              
                if(count($row)==0){
                    $data['error'] = "Please Enter Valid Mail ID.";
                    $data['role'] = "Marker";
                    $this->showform($data);

                }else{
                    if (password_verify($this->input->post('pass'), $row['user_password'])) { 
                        $this->send_otp($this->input->post('user'),$row["user_role"]);
                    } else {
                        echo $this->input->post('pass');
                        $data['error']="Invalid email/password ";
                        $data['role'] = "Marker";
                        $this->showform($data);
                    }

                }
              
            }
        }
    }
    public function resend_otp(){
        
        $email = $this->input->post('email');
        $role = $this->input->post('user_role');
        $msg = "OTP Successfully Send to Your Mail and Mobile Number.";
        $this->send_otp($email,$role,$msg);
    }
    public function send_otp($email,$role,$msg=""){

       if($msg !=""){
        $data['success'] = $msg;
       }
        $data['email'] = $email;
        $data['user_role'] = $role;
     
       
        $this->load->model("SettingsModel");
        $Email = $this->SettingsModel->get_setting("email");
        $Email_array = json_decode($Email['setting_json'],true);
        $email_status = $Email_array['email_status'];
       
        $sms = $this->SettingsModel->get_setting("sms");
        $sms_array = json_decode($sms['setting_json'],true);
        $sms_status = $sms_array['sms_status'];
       
        if($sms_status == 1 || $email_status == 1){
             
        $otp = rand(100000,999999);
        $this->session->set_userdata("otp", $otp);
        $email_values['email'] = $email;
        $email_values['host'] = $Email_array['host'];
        $email_values['username'] = $Email_array['username'];
        $email_values['password'] = $Email_array['password'];
        $email_values['port'] = $Email_array['port_number'];
        $email_values['type'] = $Email_array['multitype'];

        $sms_values['url'] = $sms_array['url'];
        $sms_values['apikey'] = $sms_array['apikey'];
        $sms_values['sender'] = $sms_array['sender'];
        $sms_values['username'] = $sms_array['username'];
        $sms_values['password'] = $sms_array['password'];
          
        if($role == "Evaluator"){
           
            $this->db->where('evaluator_email', $email);
            $this->db->limit(1);
            $query = $this->db->get('evaluators');
            $row = $query->row_array();         
            $email_values['name'] = $row['evaluator_name'] ;
            $email_values['phone'] = $row['evaluator_phone'];
            $sms_values['phone'] = $row['evaluator_phone'];

           if( $email_status == 1){
            $this->emailSend($email_values);
           
        }
            if($sms_status == 1){
                $this->smsSend($sms_values) ;

            }
            $this->load->view('otp-verification',$data);
        }

        if($role == "Head_Marker" || $role == "Head_Evaluator"){

            $this->db->where('examiner_email', $email);
            $this->db->limit(1);
            $query = $this->db->get('examiners');
            $row = $query->row_array();         
            $email_values['name'] = $row['examiner_name'] ;
            $email_values['phone'] = $row['examiner_phone'];
            $sms_values['phone'] = $sms_array['examiner_phone'];

            if( $email_status == 1){
                $this->emailSend($email_values);
    
               }
                if($sms_status == 1){
                    $this->smsSend($sms_values) ;
    
                }

                $this->load->view('otp-verification',$data);

        }
    }
}

public function otpVerification(){
        $email = $this->input->post('email');
        $data['email'] =  $email;
        $data['user_role'] = $this->input->post('user_role');

    $otp =   $this->session->userdata("otp");
    $get_otp = $this->input->post('otp');
    
    if($otp == $get_otp){
       $this->marker_login($email);
    }else{
        $data['error'] = "Invalid OTP.";
       $this->load->view('otp-verification',$data);
    //    $this->send_otp($email,$uer_role,$error);
    }
}

public function marker_login($email)
{
    $_SESSION[$this->config->item('exam')['exam_session']]=array();
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        $this->output->set_status_header(405);
    } else {
        $data['title'] = 'Login';
        
        
            $this->db->where('user_email', $email);
            $this->db->limit(1);
            $query = $this->db->get('users');
            $row=$query->row_array();
           
            if (isset($row)) {
                
                $this->load->model('ApiModel');
                if ($row['user_role']!='Coordinator') {
                    $this->ApiModel->add_login_attempt($row['user_name']);
                }
                if ($row['user_login_attempts']<=3) {
                    
                
                    if ($row['user_role']!='Coordinator') {
                        $this->db->where('user_email', $email);
                        $this->db->limit(1);
                        $loginUser = $this->db->get('login_user');
                        $loginUserRow=$loginUser->row_array();
                      //  if (count($loginUserRow)==0){     
                            if ($this->ApiModel->update_attendance($row['user_name'])) {
                                $response=$this->ApiModel->update_token_user($row);
                                if ($response['success']) {
                                    $_SESSION[$this->config->item('exam')['exam_session']]['auth_token'] = $response['user_token'];
                                    $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] = $response['user_email'];
                                } else {
                                    $data['error']="Cannot update login";
                                }

                            } else {
                                $data['error']="Cannot login";
                            }

                        /*} else {
                            $data['error']="Already Logged In.";
                        }*/
                    }
                    if (isset($data['error'])) {
                        $this->showform($data);
                    } else {


                        $_SESSION[$this->config->item('exam')['exam_session']]['user']=$row['user_id'];
                        $_SESSION[$this->config->item('exam')['exam_session']]['username']=$row['user_email'];
                        $_SESSION[$this->config->item('exam')['exam_session']]['user_name']=$row['user_name'];
                        $_SESSION[$this->config->item('exam')['exam_session']]['user_role']=$row['user_role'];
                        $_SESSION[$this->config->item('exam')['exam_session']]['user_center']=$row['center_code'];
                        $_SESSION[$this->config->item('exam')['exam_session']]['user_token']='';
                            
                        $this->load->library('curl');
                        if ($this->curl->is_connected()) {
                            $body['username']=$row['user_name'];
                            $body['password']=$this->input->post('pass');
                            //print_r($body);
                            $resp=$this->curl->call('login', 'POST', $body);
                            $obj=json_decode($resp);
                            //print_r($obj);
                            if ($obj->success) {
                                $_SESSION[$this->config->item('exam')['exam_session']]['user_token']=$obj->user_token;
                            }
                        }
                                $activity['activity_type']='Login';
                                $this->ActivitiesModel->add_activity($activity);
                     
                    if ($row['user_role'] == 'Evaluator') {
                        $_SESSION[$this->config->item('exam')['exam_session']]['evaluator']=$row['user_id'];
                        //unset($_SESSION[$this->config->item('exam')['exam_session']]['user']);
                        $_SESSION[$this->config->item('exam')['exam_session']]['user_id']=$row['user_id'];
                        //redirect('webotp');
                    }
                        
                    if ($this->input->post('redirect')) {
                        redirect($this->input->post('redirect'));
                    } else {
                        redirect();
                    }

                    }     
              
            }else{
                $data['error']="Login attempts exceeded";
                    $this->showform($data);
                }
            } else {
                    $data['error']="Invalid email/password";
                    $this->showform($data);
            }
        
    }
}
    //Code End Here
    public function post()
    {
		$_SESSION[$this->config->item('exam')['exam_session']]=array();
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $this->output->set_status_header(405);
        } else {
            $this->load->helper('form');
            $data['title'] = 'Login';
            $this->form_validation->set_rules('user', 'Email', 'required');
            $this->form_validation->set_rules('pass', 'Password', 'required');
            $this->form_validation->set_rules('userCaptcha', 'Captcha', 'required|callback_check_captcha');
            $userCaptcha = $this->input->post('userCaptcha');
            
            if ($this->form_validation->run() === false) {
                $this->showform($data);
            } else {
                $this->db->where('user_email', $this->input->post('user'));
                $this->db->limit(1);
                $query = $this->db->get('users');
                $row=$query->row_array();
               
                if (isset($row)) {
					
                    $this->load->model('ApiModel');
                    if ($row['user_role']!='Coordinator') {
                        $this->ApiModel->add_login_attempt($row['user_name']);
                    }
                    if ($row['user_login_attempts']<=3) {
                        
                    //if($row['user_password']==md5($this->input->post('pass'))){
                    if (password_verify($this->input->post('pass'), $row['user_password'])) { 
					
                        if ($row['user_role']!='Coordinator') {
							$this->db->where('user_email', $this->input->post('user'));
							$this->db->limit(1);
							$loginUser = $this->db->get('login_user');
							$loginUserRow=$loginUser->row_array();
                            if ($loginUserRow === null || count($loginUserRow)==0){
                                if ($this->ApiModel->update_attendance($row['user_name'])) {
                                    $response=$this->ApiModel->update_token_user($row);
                                    if ($response['success']) {
                                        $_SESSION[$this->config->item('exam')['exam_session']]['auth_token'] = $response['user_token'];
                                        $_SESSION[$this->config->item('exam')['exam_session']]['user_email'] = $response['user_email'];
                                    } else {
                                        $data['error']="Cannot update login";
                                    }

                                } else {
                                    $data['error']="Cannot login";
                                }

                            } else {
                                $data['error']="Already Logged In.";
                            }
                        }
                        if (isset($data['error'])) {
                            $this->showform($data);
                        } else {


                            $_SESSION[$this->config->item('exam')['exam_session']]['user']=$row['user_id'];
                            $_SESSION[$this->config->item('exam')['exam_session']]['username']=$row['user_email'];
                            $_SESSION[$this->config->item('exam')['exam_session']]['user_name']=$row['user_name'];
                            $_SESSION[$this->config->item('exam')['exam_session']]['user_role']=$row['user_role'];
                            $_SESSION[$this->config->item('exam')['exam_session']]['user_center']=$row['center_code'];
                            $_SESSION[$this->config->item('exam')['exam_session']]['user_token']='';
                                
                            $this->load->library('curl');
                            if ($this->curl->is_connected()) {
                                $body['username']=$row['user_name'];
                                $body['password']=$this->input->post('pass');
                                //print_r($body);
                                $resp=$this->curl->call('login', 'POST', $body);
                                $obj=json_decode($resp);
                                //print_r($obj);
                                if ($obj->success) {
                                    $_SESSION[$this->config->item('exam')['exam_session']]['user_token']=$obj->user_token;
                                }
                            }
                                    $activity['activity_type']='Login';
                                    $this->ActivitiesModel->add_activity($activity);
                                        
                            if ($this->input->post('remember')=='Yes') {
                                $expire=time()+60*60*24*30;
                                setcookie("user", $row['user_id'], $expire);
                                setcookie("username", $row['user_name'], $expire);
                                setcookie("user_role", $row['user_role'], $expire);
                            }
                        if ($row['user_role'] == 'Evaluator') {
                            $_SESSION[$this->config->item('exam')['exam_session']]['evaluator']=$row['user_id'];
                            //unset($_SESSION[$this->config->item('exam')['exam_session']]['user']);
                            $_SESSION[$this->config->item('exam')['exam_session']]['user_id']=$row['user_id'];
                            //redirect('webotp');
                        }
                            
                        if ($this->input->post('redirect')) {
                            redirect($this->input->post('redirect'));
                        } else {
                            redirect();
                        }

                        }     
                    } else {
                        $data['error']="Invalid email/password";
                        $this->showform($data);
                    }
                }else{
                    $data['error']="Login attempts exceeded";
                        $this->showform($data);
                    }
                } else {
                        $data['error']="Invalid email/password";
                        $this->showform($data);
                }
            }
        }
    }
    public function check_captcha($str)
    {
        $word = $this->session->userdata('captchaWord');
        if (strcmp(strtoupper($str), strtoupper($word)) == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_captcha', 'Please enter correct captcha!');
            return false;
        }
    }

    //Code Written By Vikas
    public function loginForm(){
        $role = $this->input->post('role');
        $data = array('role'=>$role);
        $this->showform($data);
       
    }
    //Code End her

    public function showform($data)
    {

      
                    /****captcha****/
                    $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                        $vals = array(
                        'word'          => $random_number,
                        'img_path'      => './captcha/',
                        'img_url'       => base_url().'captcha',
                        'img_width'     => '300',
                        'img_height'    => 40,
                        'expiration'    => 7200,
                        'word_length'   => 8,
                        'font_size'     => 10,
                        'img_id'        => 'Imageid',
                        'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                        // White background and border, black text and red grid
                        'colors'        => array(
                                'background' => array(41, 170, 225),
                                'border' => array(255, 255, 255),
                                'text' => array(0,0,0),
                                'grid' => array(41, 170, 225)
                            )
                    );

                    $data['captcha'] = create_captcha($vals);
        if (isset($this->session->userdata['captchaImage'])) {
            if (file_exists(BASEPATH."../captcha/".$this->session->userdata['captchaImage'])) {
                unlink(BASEPATH."../captcha/".$this->session->userdata['captchaImage']);
            }
        }

                    $this->session->set_userdata('captchaWord', $data['captcha']['word']);
                    $this->session->set_userdata('captchaImage', $data['captcha']['time'].'.jpg');
                    /****captcha****/
                $this->db->where('ip_address', $_SERVER['REMOTE_ADDR']);
                $this->db->limit(1);
                $query = $this->db->get('login_user');
				$row=$query->num_rows();
				$data['login_session']=$row;

                // //Code Written By Vikas
                if($data['role'] == "Marker"){
                    $this->load->model('SettingsModel');
                    $email_info = $this->SettingsModel->get_setting('email');
                    $sms_info = $this->SettingsModel->get_setting('sms');
                    $data['Email_Setting'] = json_decode($email_info["setting_json"],true);
                    $data['Sms_Setting'] = json_decode($sms_info["setting_json"],true);
                }
                //Code End Here

                $this->load->view('login-form', $data);
    }
    
    public function forgot()
    {
        echo "sf";
                    //$this->load->view('forgot-form');
    }

    //Code Written By vikas
    public function emailSend($email_values){

        $otp =   $this->session->userdata("otp");
        $name = $email_values['name'] ;
         $email = $email_values['email'] ;
        $phone =  $email_values['phone'];
 
         $host = $email_values['host'];
         $username =  $email_values['username'] ;
         $password = $email_values['password'];
         $port = $email_values['port'] ;
         $type = $email_values['type'];
 
       $email_subj = $email_values['email_subj'] ;
 
       $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
       <html xmlns="http://www.w3.org/1999/xhtml">
       
       <head>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Verify your login</title>
         <!--[if mso]><style type="text/css">body, table, td, a { font-family: Arial, Helvetica, sans-serif !important; }</style><![endif]-->
       </head>
       
       <body style="font-family: Helvetica, Arial, sans-serif; margin: 0px; padding: 0px; background-color: #ffffff;">
         <table role="presentation"
           style="width: 100%; border-collapse: collapse; border: 0px none; border-spacing: 0px; font-family: Arial, Helvetica, sans-serif; background-color: rgb(239, 239, 239);">
           <tbody>
             <tr>
               <td style="padding: 1rem 2rem; vertical-align: top; width: 100%;" align="center">
                 <table role="presentation"
                   style="max-width: 600px; border-collapse: collapse; border: 0px none; border-spacing: 0px; text-align: left;">
                   <tbody>
                     <tr>
                       <td style="padding: 40px 0px 0px;">
                         <div style="text-align: center;">
                           <div style="padding-bottom: 20px;"><img src="'.base_url().'logo/logo.png" alt="Company" style="width: 300px;"></div>
                         </div>
                         <div style="padding: 20px; background-color: rgb(255, 255, 255);">
                           <div style="color: rgb(0, 0, 0); text-align: left;">
                             <h1 style="margin: 1rem 0">Verification code</h1>
                             <p style="padding-bottom: 16px">Please use the verification code below to login in.</p>
                             <p style="padding-bottom: 16px;text-align:center;"><strong style="font-size: 130%">'.$otp.'</strong></p>
                            
                             <p style="padding-bottom: 16px">Thanks,<br>The DigiMarker Team</p>
                           </div>
                         </div>
                         <div style="padding-top: 20px; color: rgb(153, 153, 153); text-align: center;">
                           <p style="padding-bottom: 16px">Made with ? DigiMarker</p>
                         </div>
                       </td>
                     </tr>
                   </tbody>
                 </table>
               </td>
             </tr>
           </tbody>
         </table>
       </body>
       
       </html>';
        // echo $message;
 
        $this->load->library('email');
     
      
              $config = array(
                  'protocol'  => 'smtp',
                  'smtp_host' => $host,
                  'smtp_port' => $port,                        
                  'smtp_user' => $username,
                  'smtp_pass' => $password,
                  'mailtype'  => $type,
                  'charset'   => 'utf-8',
				  'smtp_crypto' => 'tls', 
                  'smtp_timeout' => '30',
                  'mailpath' => '/usr/sbin/sendmail',
                  'wordwrap' => TRUE
              );
          $this->email->initialize($config);
          $this->email->set_mailtype("html");
          $this->email->set_newline("\r\n");
          
          $htmlContent = $message;
          $this->email->to($email);
          $this->email->from('pranav.p@techsumsolution.com');
          $this->email->subject('Login Confirmation || DigiMarker');
          $this->email->message($htmlContent);
                      
          $email_response= $this->email->send();
        //  var_dump($email_response);                
          if($email_response){
              return true;
          }else{
              return false;
          }
     }

     public function smsSend($sms_values)
     {   
        $otp = $this->session->userdata("otp");
        $url =  $sms_values["url"];
        $apikey =  $sms_values["apikey"];
        $sender =  $sms_values["sender"];
        $username =  $sms_values["username"];
        $password =  $sms_values["password"];
        $mobile = $sms_values["phone"];
        // $mobile ="8104514650";

        $curl = curl_init();  
                    
                     curl_setopt_array($curl, array(
                      CURLOPT_URL => 'http://api.equence.in/pushsms?username=tecsm_ddrp&password=zyDT-19_&peId=1201162261478998529&tmplId=1207166479748404727&from=MPTWDB&charset=UTF-16&text=Dear%20user,%0AFor%20Registration%20on%20the%20IMS%20Mobile%20app,%0AOTP%20is%20-%20'.$otp.'%0AMP-TWDB&to='.$mobile. '',
                  //  CURLOPT_URL =>$url.'?username='.$username.'&password='.$password.'&peId='.$apikey.'&tmplId='.$sender.'&from=MPTWDB&charset=UTF-16&text=Dear%20user,%0AFor%20Registration%20on%20the%20IMS%20Mobile%20app,%0AOTP%20is%20-%20'.$otp.'%0AMP-TWDB&to='.$mobile. '',
                    CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_ENCODING => '',
                     CURLOPT_MAXREDIRS => 10,
                     CURLOPT_TIMEOUT => 0,
                     CURLOPT_FOLLOWLOCATION => true,
                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                     CURLOPT_CUSTOMREQUEST => 'GET',
                     ));
             $response = curl_exec($curl);
             curl_close($curl);
            //  echo $response;
            // echo $response['response']['status'];
             //if(){}else{}
             return true;
             echo "<br>";
     }
    //Code End Here
}
