<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marker extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
		$this->load->library('curl');
		        $this->load->model('MarkerModel');
                $this->load->model('ReportsModel');
                $this->load->model('SettingsModel');
				   $this->load->library('form_validation');
    }
    public function index()
    {
		$user=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];

       //Code Written By Vikas
       $this->load->model('ExaminersModel');
       $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
       $data['Examiners'] = $this->ExaminersModel->get_examiner_byname($user_name);
       $course_code =  $data['Examiners']['course_code'];
       $where['examiners.course_code'] =  $course_code ;
       $where['examiners.examiner_designation'] = "Head_Evaluator";

       $Subjects_head_marker = $this->MarkerModel->get_subj_head_eval( $user_name , $course_code); 
        $subjects_array = array();
        for($i = 0 ; $i < count( $Subjects_head_marker); $i++){

            array_push( $subjects_array , $Subjects_head_marker[$i]['subject_code']);

        }
        
    //    print_r($subjects_array);
    //    die();
      
   //Code End Here
			
            // $data['sheets'] = $this->MarkerModel->get_sheets_count();
            $data['sheets'] = $this->MarkerModel->get_sheets_count();
            $data['sheetData'] = $this->MarkerModel->get_active_script($user);
            $data['checked'] = $this->MarkerModel->get_evaluation_count($user);
            //$this->load->model('PapersModel');
            $data['papers'] = $this->MarkerModel->get_papers_count($user);
           // $this->load->model('UsersModel');
          // $data['logged_in'] = $this->MarkerModel->get_loggged_in_users($user); alter by vikas
            $data['logged_in'] = $this->MarkerModel->get_loggged_in_users($user,$subjects_array);
            $data['evaluators'] = $this->MarkerModel->get_evaluator($subjects_array);//This Line Of Code is Added By Vikas
            //code written by vikas
            $settings = $this->SettingsModel->get_settings_for('evaluation');
            $status = json_decode($settings[0]['setting_json'],true);
            $data['status'] = $status['status'];
            //code end here
            
            $this->load->view('header');
            $this->load->view('dashboard_m', $data);
            $this->load->view('footer');
    }
    //Code Written By Vikas
    public function deputy_head()
    {
            $where=array();
            $this->load->model('SubjectsModel');
            $this->load->model('ExaminersModel');
             $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
             $data['Examiners'] = $this->ExaminersModel->get_examiner_byname($user_name);
             $course_code =  $data['Examiners']['course_code'];
            $where['course_code'] =   $course_code ;
            // $where['examiner_designation'] = "Deputy Head Marker";
            $Subjects_head_marker = $this->MarkerModel->get_subj_head_eval($user_name); 
			 // print_r($this->db->last_query());
            $subjects_array = array();
            for($i = 0 ; $i < count( $Subjects_head_marker); $i++){
    
                array_push( $subjects_array , $Subjects_head_marker[$i]['subject_code']);
    
            }
          
            $data['deputies'] = $this->MarkerModel->get_deputys($subjects_array);
          
             //echo "<pre>";
           //  print_r( $data['deputies']);
            //print_r($this->db->last_query());
           // die();  
            $this->load->view('header');
            $this->load->view('deputy_head', $data);
            $this->load->view('footer');
    }

    public function evaluators_info()
    {
            $where=array();
            $this->load->model('SubjectsModel');
            $this->load->model('ExaminersModel');
             $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
             $data['Examiners'] = $this->ExaminersModel->get_examiner_byname($user_name);
             $course_code =  $data['Examiners']['course_code'];
            $where['course_code'] = $course_code;

            $data['courses'] = $this->MarkerModel->get_course_head_eval( $user_name , $course_code); 
            $Subjects_head_marker = $this->MarkerModel->get_subj_head_eval( $user_name , $course_code); 
            $subjects_array = array();
            for($i = 0 ; $i < count( $Subjects_head_marker); $i++){
    
                array_push( $subjects_array , $Subjects_head_marker[$i]['subject_code']);
    
            }
          
            //$data['subjectlist'] = $this->MarkerModel->get_subject($subjects_array);
            $data['evaluators'] = $this->MarkerModel->get_evaluator($subjects_array);
          
           
            $this->load->view('header');
            $this->load->view('evaluators_view', $data);
            $this->load->view('footer');
    }
	public function get_subject($course_code){
		 $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
		// $course_code = $this->input->post('selectedValues');
		 $data = $this->MarkerModel->get_subj_course_eval( $user_name , $course_code); 
		 $json_data=json_encode($data);
		 echo $json_data;
	}
	public function add_subject(){
		
        for($i = 0 ; $i < count($this->input->post('subject')) ; $i++){
		 $marker_subject['subject_code'] = $this->input->post('subject['.$i.']');
		 $marker_subject['course_code'] = $this->input->post('course');
         $marker_subject['evaluator_username'] = $this->input->post('username');
		 
		 
		
        $resp=$this->curl->call('addsubject', 'GET', $marker_subject);
        $obj=json_decode($resp);
		//print_r($obj);die;
		
			  if($obj->success==1){ 
//echo $obj->success;			   
					$this->load->model('EvaluatorsModel');
						$insert = $this->EvaluatorsModel->add_marker_subject($marker_subject);

					if(!$insert) {
							$this->session->set_flashdata('error', 'Subjects Not Added !');
					} else {
						$this->session->set_flashdata('success', 'Subjects Added Successfuly');
					}
			  }else{
				 // echo $obj->success;
				  $this->session->set_flashdata('error', 'Subjects Not Added !');
			  }
            }
            redirect('marker/evaluators_info');
    }
	 public function markeradd()
    {
		$this->load->model('EvaluatorsModel');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|max_length[80]');
            $this->form_validation->set_rules('name', 'Center Name', 'required|alpha_numeric_spaces');
           $this->form_validation->set_rules('username', 'Marker Name', 'required|alpha_numeric');
            // $this->form_validation->set_rules('head', 'Examiner Name', 'required|alpha_numeric');
           // $this->form_validation->set_rules('center', 'center code', 'required|alpha_numeric');
            $this->form_validation->set_rules('phone', 'phone', 'numeric');
           // $this->form_validation->set_rules('medium', 'Medium', 'required|alpha_numeric');
            //$this->form_validation->set_rules('course', 'course', 'required|alpha_numeric');
            // $this->form_validation->set_rules('subject', 'subject', 'required|alpha_numeric');
            //$this->form_validation->set_rules('region', 'region', 'required|alpha_numeric');
               /*  print_r($_POST);
				
				echo $this->form_validation->run();
				print_r(validation_errors()); */
        if ($this->form_validation->run() === true) {
			//echo "sss";
				$e['evaluator_name']=$this->input->post('name');
                $e['evaluator_username']=$this->input->post('username');
                $e['evaluator_email']=$this->input->post('email');
                $e['evaluator_password']=$this->input->post('password');
                $e['evaluator_phone']=$this->input->post('phone');
                // $evaluator['examiner_username']=$this->input->post('head');
                $e['medium_code']='E';
                $e['course_code']='';
                $e['subject_code']=$this->input->post('subject');//code alter by vikas 10/04/23
                $e['center_code']='vmsbwit';
                $e['region_code']='';
			//print_r($e);
			  $resp=$this->curl->call('addmarker', 'POST', $e);
              $obj=json_decode($resp);
			/* print_r($obj);
			die; */
			if($obj->success==1){
			
            $user_password=hash_hmac('sha256', $this->input->post('password'), 'aSm0$i_20eNh3os');
            $user['user_name']=$this->input->post('username');
            $user['user_email']=$this->input->post('email');
            $user['user_password']=$user_password;
            $user['center_code']='vmsbwit';
            $user['user_role']='Evaluator';
            $this->load->model('UsersModel');
            $user_data = $this->MarkerModel->add_user($user);
            if (!$user_data['success']) {
                $this->session->set_flashdata('error', $user_data['message']);
            } else {
                $user_password=hash_hmac('sha256', $this->input->post('password'), 'aSm0$i_20eNh3os');
                $evaluator['evaluator_name']=$this->input->post('name');
                $evaluator['evaluator_username']=$this->input->post('username');
                $evaluator['evaluator_email']=$this->input->post('email');
                $evaluator['evaluator_password']=$user_password;
                $evaluator['evaluator_phone']=$this->input->post('phone');
                // $evaluator['examiner_username']=$this->input->post('head');
                $evaluator['medium_code']='E';
                $evaluator['course_code']='';
                $evaluator['subject_code']=$this->input->post('subject[0]');//code alter by vikas 10/04/23
                $evaluator['center_code']='vmsbwit';
              //  $evaluator['region_code']='Dehradun';

                //vikas code 10/04/23
               
                for($i = 0 ; $i < count($this->input->post('subject')) ; $i++){

                    $marker_subject['subject_code'] = $this->input->post('subject['.$i.']');
                    $marker_subject['evaluator_username'] = $this->input->post('username');

                    $insert = $this->EvaluatorsModel->add_marker_subject($marker_subject);

                if (!$insert) {
                        $this->session->set_flashdata('error', 'Subjects Not Inserted !');
                } else {
                    $this->session->set_flashdata('success', 'Subjects Inserted Successfuly');
                }
            }

               /* $Email = $this->SettingsModel->get_setting("email");
                $Email_array = json_decode($Email['setting_json'],true);
                $status = $Email_array['email_status'];
print_r($Email_array);
                if($status == 1){
                        $email_values['name'] = $this->input->post('name');
                        $email_values['email'] =$this->input->post('email');
                        $email_values['phone'] = $this->input->post('phone');
                        $email_values['pass'] = $this->input->post('password');

                        $email_values['host'] = $Email_array['host'];
                        $email_values['username'] = $Email_array['username'];
                        $email_values['password'] = $Email_array['password'];
                        $email_values['port'] = $Email_array['port_number'];
                        $email_values['type'] = $Email_array['multitype'];

                      $email_values['email_subj'] = implode(", ",$this->input->post('subject'));
                     
                        $this->emailSend($email_values);
                 
                 
                 
                    // $this->Curl->send_email($host,$username,$password,$port,$type,$message,$email);

                }*/
                //code end here
                    
                $evaluator = $this->security->xss_clean($evaluator);
                $data = $this->MarkerModel->add_evaluator($evaluator);
				//echo $this->db->last_query();  
                if (!$data['success']) {
                        $this->session->set_flashdata('error', $data['message']);
                } else {
                    $this->session->set_flashdata('success', $data['message']);
                }
          
            }
            redirect('marker/evaluators_info');
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
		}
        
		   redirect('marker/evaluators_info');
    }
	 public function emailSend($email_values){
        $name = $email_values['name'] ;
        $email = $email_values['email'] ;
       $phone =  $email_values['phone'];
        $pass = $email_values['pass'] ;

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
          <title>Login Details</title>
          <!--[if mso]><style type="text/css">body, table, td, a { font-family: Arial, Helvetica, sans-serif !important; }</style><![endif]-->
        </head>
        
        <body style="font-family: Helvetica, Arial, sans-serif; margin: 0px; padding: 0px; background-color: #ffffff;">
          <table role="presentation"
            style="width: 100%; border-collapse: collapse; border: 0px none; border-spacing: 0px; font-family: Arial, Helvetica, sans-serif; background-color: rgb(239, 239, 239);">
            <tbody>
              <tr>
                <td style="padding: 1rem 2rem; vertical-align: top; width: 100%;" align="center">
                  <table role="presentation"
                    style="width: 80%; border-collapse: collapse; border: 0px none; border-spacing: 0px; text-align: left;">
                    <tbody>
                      <tr>
                        <td style="padding: 40px 0px 0px;">
                          <div style="text-align: center;">
                            <div style="padding-bottom: 20px;"><img src="'.base_url().'logo/logo.png" alt="Company" style="width: 300px;"></div>
                          </div>
                          <div style="padding: 20px; background-color: rgb(255, 255, 255);">
                            <div style="color: rgb(0, 0, 0); text-align: left;">
                              
                              <p style="padding-bottom: 16px">Dear '.$name.',</p>
                               <p style="padding-bottom: 16px">Please use the detail below to login in for digital booklet evaluation.</p>
                              <p style="text-align:left;"><strong style="font-size: 90%"><a href="https://vmsbevenjunels25.digimarker.online/login">https://vmsbevenjunels25.digimarker.online/login</a></strong></p>
                  <table border=1
                    style="width: 80%; border-collapse: collapse;  border-spacing: 0px; text-align: left;">
                    <tbody>
                    <tr>
                        <th style="padding: 5px;">Name</th>
                        <th style="padding: 5px;">Mobile</th>
                        <th style="padding: 5px;">Subject</th>
                        <th style="padding: 5px;">Email</th>
                        <th style="padding: 5px;">Password</th>
                        
                        </tr>
                        <tr>
                        <td style="padding: 5px;">'.$name.'</td>
                        <td style="padding: 5px;">'.$phone.'</td>
                        <td style="padding: 5px;">'.$email_subj.'</td>
                        <td style="padding: 5px;">'.$email.'</td>
                        <td style="padding: 5px;">'.$pass.'</td>
                        
                        </tr>
                        </tbody></table>
                        <p style="padding-bottom: 16px">Kindly join this group for all updates :.</p>
                              
                             
                              <p style="padding-top: 50px">Thanks,<br>The DigiMarker Team</p><p style="text-align:left;">8817119597/9770338535</p>
                            </div>
                          </div>
                          <div style="padding-top: 20px; color: rgb(153, 153, 153); text-align: center;">
                            <p style="padding-bottom: 16px">Made with â™¥ DigiMarker</p>
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
         var_dump($email_response);                
         if($email_response){
             return true;
         }else{
             return false;
         }
    }
    //Code End Here
	  public function evaluators()
    {
            $where=array();
            $like=array();
        if (isset($_GET['date']) && $_GET['date']!='') {
            $date=$_GET['date'];
        }else{
			$date=date('Y-m-d', time());
		}
            //$where['evaluation_time >']=$date;
            //$where['evaluation_time <']=$date." 23:59:59";
            //Code Written By Vikas
            $this->load->model('ExaminersModel');
             $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
             $data['Examines'] = $this->ExaminersModel->get_examiner_byname($user_name);
             $where['evaluators.course_code'] = $data['Examines']['course_code'];
            //Code End HEre
            $where['attendance_date']=$date;
            $data['reports'] = $this->MarkerModel->get_reports($where);
            $data['date']=$date;
            // echo "<pre>";
            // print_r($data['reports']);
           
            $this->load->view('header');
            $this->load->view('reports', $data);
            $this->load->view('footer');
    }
	 public function dated_evaluators()
    {
            $where=array();
            $like=array();
        if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
            $date_from=$_GET['date_from'];
        } else {
            $date_from=date('Y-m-d', strtotime("-30 days"));
        }
        if (isset($_GET['date_to']) && ($_GET['date_to']!='')) {
            $date_to=$_GET['date_to'];
        } else {
            $date_to=date('Y-m-d', time());
        }
            //$where['evaluation_time >']=$date_from;
            //$where['evaluation_time <']=$date_to." 23:59:59";
            $where['attendance_date >=']=$date_from;
            $where['attendance_date <=']=$date_to;
             //Code Written By Vikas
             $this->load->model('ExaminersModel');
             $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
             $data['Examines'] = $this->ExaminersModel->get_examiner_byname($user_name);
             $where['evaluators.course_code'] = $data['Examines']['course_code'];
            
            //Code End HEre
            $data['reports'] = $this->MarkerModel->get_reports($where);
            $data['date_from']=$date_from;
            $data['date_to']=$date_to;
            $this->load->view('header');
            $this->load->view('reports/consolidated_evaluators', $data);
            $this->load->view('footer');
    }
     public function heads()
    {
        
         $where=array();
         if (isset($_GET['date']) && $_GET['date']!='') {
            $date=$_GET['date'];
        }else{
			$date=date('Y-m-d', time());
		}
            $where['attendance_date']=$date;
        //Code Written By Vikas
            $this->load->model('ExaminersModel');
            $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
            $data['Examiners'] = $this->ExaminersModel->get_examiner_byname($user_name);
        
            $where['examiners.course_code'] = $data['Examiners']['course_code'];
            $where['examiners.examiner_designation'] = "Head_Evaluator";
        
        //Code End Here
            $data['reports'] = $this->MarkerModel->get_reports_heads($where);
            $data['date']=$date;
            $this->load->view('header');
            $this->load->view('heads_reports', $data);
            $this->load->view('footer');
    }
    //Code Written By Vikas
    public function datewise(){
        
        $where=array();
        $like=array();
        if (isset($_GET['date']) && ($_GET['date']!='')) {
            $date=$_GET['date'];
        } 

        $this->load->model('ExaminersModel');
        $this->load->model('SubjectsModel');
        $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
        $data['Examiners'] = $this->ExaminersModel->get_examiner_byname($user_name);

        $course_code = $data['Examiners']['course_code'];
        $Subjects_head_marker = $this->MarkerModel->get_subj_head_eval( $user_name , $course_code); 

            $subjects_array = array();
            for($i = 0 ; $i < count( $Subjects_head_marker); $i++){
    
                array_push( $subjects_array , $Subjects_head_marker[$i]['subject_code']);
    
            }
     
        if($date != ''){
            $where["evaluation.evaluation_date"] = $date ;
        }
       
        $data['reports'] = $this->MarkerModel->get_reports_datewise($where,$subjects_array);
        // print_r($this->db->last_query());
        $data['date']=$date;
        $this->load->view('header');
        $this->load->view('reports/datewise_reports', $data);
        $this->load->view('footer');
    }
    //Code End Here
    public function dated_heads()
    {
        $where=array();
        $like=array();
        if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
            $date_from=$_GET['date_from'];
        } else {
            $date_from=date('Y-m-d', strtotime("-30 days"));
        }
        if (isset($_GET['date_to']) && ($_GET['date_to']!='')) {
            $date_to=$_GET['date_to'];
        } else {
            $date_to=date('Y-m-d', time());
        }
        $where['attendance_date >=']=$date_from;
        $where['attendance_date <=']=$date_to;
        //Code Written By Vikas
        $this->load->model('ExaminersModel');
        $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
        $data['Examiners'] = $this->ExaminersModel->get_examiner_byname($user_name);
       
       $where['examiners.course_code'] = $data['Examiners']['course_code'];
       $where['examiners.examiner_designation'] = "Head_Evaluator";
     
        //Code End Here
        $data['reports'] = $this->MarkerModel->get_reports_heads($where);
        $data['date_from']=$date_from;
        $data['date_to']=$date_to;
        $this->load->view('header');
        // $this->load->view('reports/consolidated_examiners', $data);
        $this->load->view('reports/consolidated_deputy_head', $data);//Line Added By Vikas
        $this->load->view('footer');
    }
	public function subjectwise($date = '')
    {
       
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
          //Code Written By Vikas
          $this->load->model('ExaminersModel');
          $user_name = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
          $data['Examines'] = $this->ExaminersModel->get_examiner_byname($user_name);
          $course_code = $data['Examines']['course_code'];
         //Code End HEre
                $data['subject_counts'] = $this->MarkerModel->get_subject_sheets_count($course_code);
                $this->load->model('SubjectsModel');
                $data['subjects'] = $this->MarkerModel->get_subjects();
                $data['subject_date'] = $this->MarkerModel->get_checked_total_subject(date('Y-m-d', time()));
				
        if (isset($_GET['export'])) {
            if ($_GET['export']=='html') {
                $this->load->view('reports/subject_wise_print', $data);
            } else {
                    //load our new PHPExcel library
                    $this->load->library('excel');
                    //activate worksheet number 1
                    $this->excel->setActiveSheetIndex(0);
                    //name the worksheet
                            
                    $this->excel->getActiveSheet()->setTitle('Subjectwise');
                    $this->excel->getActiveSheet()->mergeCells('B1:F2');
                    $this->excel->getActiveSheet()->setCellValue('B1', 'Subject Wise Marking Report');

                    $styleArray = array( 'font' => array( 'bold' => false, 'color' => array('rgb' => '3b3b3b'), 'size' => 18, 'name' => 'Verdana' ));
                    $this->excel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
                            
                            
                    //database
                    //$this->load->model('CentersModel');
                    //$center = $this->CentersModel->get_center_bycode($_SESSION[$this->config->item('exam')['exam_session']]['user_center']);

                    $this->excel->getActiveSheet()->setCellValue('A4', 'Marking Center');
                    $this->excel->getActiveSheet()->setCellValue('A5', 'Date');
                            
                    $this->excel->getActiveSheet()->setCellValue('B4', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
                    $this->excel->getActiveSheet()->setCellValue('B5', $date);

                    $this->excel->getActiveSheet()->setCellValue('E4', 'Exam');
                    $this->excel->getActiveSheet()->setCellValue('E5', 'Class');
                            
                    $this->excel->getActiveSheet()->setCellValue('F4', $this->config->item('exam')['exam_code']);
                    $this->excel->getActiveSheet()->setCellValue('F5', '');

                foreach ($data['subject_counts'] as $subcount) {
                    $subject_count[$subcount['subject_code']][$subcount['sheet_status']]=$subcount['Total'];
                }
                foreach ($data['subject_date'] as $row) {
                    $today[$row['subject_code']]=$row['sheet_count'];
                }
                                    $export_array=array();
                foreach ($data['subjects'] as $subject) {
                    if (isset($subject_count[$subject['subject_code']])) {
                                    $pcc=$subject_count[$subject['subject_code']];
                        if (isset($pcc['Pending'])) {
                            $pc['Pending']=$pcc['Pending'];
                        } else {
                                $pc['Pending']=0;
                        }
                        if (isset($pcc['Assigned'])) {
                            $pc['Assigned']=$pcc['Assigned'];
                        } else {
                            $pc['Assigned']=0;
                        }
                        if (isset($pcc['Checked'])) {
                            $pc['Checked']=$pcc['Checked'];
                        } else {
                            $pc['Checked']=0;
                        }
                        if (isset($pcc['Rejected'])) {
                            $pc['Rejected']=$pcc['Rejected'];
                        } else {
                            $pc['Rejected']=0;
                        }
                        if (isset($pcc['Rechecked'])) {
                            $pc['Rechecked']=$pcc['Rechecked'];
                        } else {
                            $pc['Rechecked']=0;
                        }
                                    unset($pcc);
                    } else {
                                            $pc['Pending']=0;
                                            $pc['Assigned']=0;
                                                $pc['Checked']=0;
                                                $pc['Rejected']=0;
                                                $pc['Rechecked']=0;
                    }
                                                            $total=$pc['Pending']+$pc['Assigned']+$pc['Checked']+$pc['Rejected']+$pc['Rechecked'];
                                                            $subject_count[$subject['subject_code']]['Pending']=$pc['Pending'];
                    if (isset($today[$subject['subject_code']])) {
                        $today_count=$today[$subject['subject_code']];
                    } else {
                        $today_count='0';
                    }
                                                            $checked=intval($pc['Checked'])+intval($pc['Rechecked']);
                                                            $pending=intval($pc['Pending'])+intval($pc['Assigned']);
                                                
                                                            $row_array=array();
                                                            $row_array['Sub code']=$subject['subject_code'];
                                                            $row_array['Subject']=$subject['subject_name'];
                                                            $row_array['Tot ABs on Server']=$total;
                                                            $row_array['Checked Today']=$today_count;
                                                            $row_array['Checked till date']=$checked;
                                                            $row_array['Remaining AB']=$pending;
                                                            $row_array['Rejected']=$pc['Rejected'];
                                                            $export_array[]=$row_array;
                                                            unset($pc);
                }
                                        //$this->load->library('excel');
                                        //$this->excel->array_to_xls($export_array,'evaluation_reports');
                                        $this->excel->getActiveSheet()->fromArray(array_keys(current($export_array)), null, 'A7');
                                        $this->excel->getActiveSheet()->fromArray($export_array, null, 'A8');

                                    $filename='download.xls'; //save our workbook as this file name
                                    header('Content-Type: application/vnd.ms-excel'); //mime type
                                    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                                    header('Cache-Control: max-age=0'); //no cache
                                    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                                    //if you want to save it as .XLSX Excel 2007 format
                                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                                    //force user to download the Excel file without writing it to server's HD
                                    $objWriter->save('php://output');
            }
        } else {
            $this->load->view('header');
            $this->load->view('reports/subject_wise_h', $data);
            $this->load->view('footer');
        }
    }
}
