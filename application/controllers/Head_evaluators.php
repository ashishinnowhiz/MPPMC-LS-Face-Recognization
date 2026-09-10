<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Head_evaluators extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('ExaminersModel');
                $this->load->library('form_validation');
           //code added by vikas
            $this->load->model("SettingsModel");
           // $this->load->library('Curl');
            //code end Here
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = '')
    {
		 $this->load->model('MediumsModel');
            $this->load->library('pagination');
            $config['base_url'] = base_url().'examiners/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $this->load->model('CentersModel');
            $data['centers'] = $this->CentersModel->get_centers();
            
            
            $this->load->model('CoursesModel');
            $data['courses'] = $this->CoursesModel->get_courses();

            $this->load->model('SubjectsModel');
            $data['subjects'] = $this->SubjectsModel->get_subjects();

            $this->load->model('RegionsModel');
            $data['regions'] = $this->RegionsModel->get_regions();
            
            $data['centers'] = $this->CentersModel->get_centers();
			$data['mediums'] = $this->MediumsModel->get_mediums();
            $data['examiners'] = $this->ExaminersModel->get_examiners();

            $this->load->view('header');
            $this->load->view('examiners', $data);
            $this->load->view('footer');
    }
    public function autoGenerate(){
	
		 $this->load->model('MediumsModel');
		 $this->load->model('CentersModel');
		 $this->load->model('SubjectsModel');
		 $this->load->model('EvaluatorsModel');
		 if(!isset($_POST['center']))
		 {
			 
			 $centers=$this->CentersModel->get_centers();
			 $c=true;
		 }else{
			 $c=false;
			 $centers=$_POST['center'];
		 }
		 if(!isset($_POST['medium']))
		 {
			 $m=true;
			 $mediums=$this->MediumsModel->get_mediums();
		 }else{
			 $m=false;
			 $mediums=$_POST['medium'];
		 }
		
		$hmpassword=$_POST['password'];
		
		
		$phone='8888888888';
		$d['subjects'] = $this->SubjectsModel->get_subjects();
		foreach($centers as $center){
			$cent=$c?$center['center_code']:$center;
			
			foreach($mediums as $medium){
				$medium_code=$m?$medium['medium_code']:$medium;
				foreach($d['subjects'] as $sub){
					$subject_code=$sub['subject_code'];
					//echo "<br>";
					$username=strtolower($_POST['exam']).strtolower($_POST['std']).strtolower(substr($cent,0,3)).strtolower($m?$medium['medium_code']:$medium).strtolower(substr($sub['subject_name'],0,3));
					//echo "<br>";
					$hmemail=$username."@dm.com";
					//echo "<br>===========================<br>";
				
		
						$hmuser['user_name']=$username;
						$hmuser['user_email']=$hmemail;
						$hmuser['user_password']=$password;
						$hmuser['center_code']=$cent;
						$hmuser['user_role']='Head_Evaluator';
						$this->load->model('UsersModel');
						$hmuser = $this->security->xss_clean($hmuser);
						$user_data = $this->UsersModel->add_user($hmuser);
						if (!$user_data['success']) {
						   $data['error'][]=$user_data['message'];
						} else {
							$examiner['examiner_name']=$username;
							$examiner['examiner_designation']='Head_Evaluator';
							$examiner['examiner_username']=$username;
							$examiner['examiner_email']=$hmemail;
							$examiner['examiner_password']=$password;
							$examiner['examiner_phone']=$phone;
							$examiner['center_code']=$cent;
							$examiner['region_code']=$cent;
							$examiner = $this->security->xss_clean($examiner);
							$data = $this->ExaminersModel->add_examiner($examiner);
							if (!$data['success']) {
									$data['error'][]= $data['message'];
							}
								$activity['activity_type']='Create';
								$activity['activity_data']=json_encode($examiner);
								$this->ActivitiesModel->add_activity($activity);
						}
						//================================================================
						
						
						//================================================================
				}
			}
		}
         if (!empty($data['error'])) {
                $this->session->set_flashdata('error', implode('<br/>', $data['error']));
            } else {
                $this->session->set_flashdata('success', 'Data inserted Succesfully');
            }
                redirect('head_evaluators');
            //redirect('head_evaluators');
	}
   
   public function add()
     {      
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|max_length[80]');
            $this->form_validation->set_rules('name', 'Head Marker Name', 'required|alpha_numeric_spaces');
            $this->form_validation->set_rules('center', 'center code', 'required|alpha_numeric');
            $this->form_validation->set_rules('username', 'user name', 'required|alpha_numeric');
            $this->form_validation->set_rules('phone', 'phone', 'numeric');
            //$this->form_validation->set_rules('medium', 'Medium', 'required|alpha_numeric');
            //$this->form_validation->set_rules('subject', 'subject', 'required|alpha_numeric');
            $this->form_validation->set_rules('region', 'region', 'required|alpha_numeric');
            $this->form_validation->set_rules('designation', 'designation', 'required');
            $this->form_validation->set_rules('course', 'course', 'required|alpha_numeric');

        if ($this->form_validation->run() === true) {
            $user_password=hash_hmac('sha256', $this->input->post('password'), 'aSm0$i_20eNh3os');

            $user['user_name']=$this->input->post('username');
            $user['user_email']=$this->input->post('email');
            $user['user_password']= $user_password;
            $user['center_code']=$this->input->post('center');
            $user['user_role']='Head_Evaluator';
            $this->load->model('UsersModel');
            $user = $this->security->xss_clean($user);
            $user_data = $this->UsersModel->add_user($user);
            if (!$user_data['success']) {
                $this->session->set_flashdata('error', $user_data['message']);
            } else {
                $user_password=hash_hmac('sha256', $this->input->post('password'), 'aSm0$i_20eNh3os');

                $examiner['examiner_name']=$this->input->post('name');
                $examiner['examiner_designation']=$this->input->post('designation');
                $examiner['examiner_username']=$this->input->post('username');
                $examiner['examiner_email']=$this->input->post('email');
                $examiner['examiner_password']=$user_password;
                $examiner['examiner_phone']=$this->input->post('phone');
                $examiner['center_code']=$this->input->post('center');
                $examiner['region_code']=$this->input->post('region');
                $examiner['course_code']=$this->input->post('course');
                $examiner['subject_code']=$this->input->post('subject[0]');
                $examiner = $this->security->xss_clean($examiner);
                $data = $this->ExaminersModel->add_examiner($examiner);
                if (!$data['success']) {
                        $this->session->set_flashdata('error', $data['message']);
                } else {
                    //whole else part written by vikas
                    for( $i=0 ; $i < count($this->input->post('subject')) ; $i++){

                        $subject['subject_code'] = $this->input->post('subject['.$i.']');
                        $subject['examiner_username'] = $this->input->post('username');
                        $subject['course_code'] =  $this->input->post('course');
                        $status = $this->ExaminersModel->insert_subjects($subject);

                        if($status == false){

                            $this->ExaminersModel->remove_examiner($subjects['examiner_username']);
                            $this->ExaminersModel->remove_subjects($subjects['examiner_username']);
                            $this->session->set_flashdata('error', 'Something Wrong While inserting Subjects !');
                        }

                    }
                    $this->session->set_flashdata('success', $data['message']);
                    //code Written By Vikas on 22/05/2023
                    $Email = $this->SettingsModel->get_setting("email");
                    $Email_array = json_decode($Email['setting_json'],true);
                    $status = $Email_array['email_status'];

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

                    }
                    
                }
                    $activity['activity_type']='Create';
                    $activity['activity_data']=json_encode($examiner);
                    $this->ActivitiesModel->add_activity($activity);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
           redirect('head_evaluators');
    }
    //Code Written By Vikas
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
                              <p style="text-align:left;"><strong style="font-size: 90%"><a href="https://chat.whatsapp.com/JvOtojYDDC3FoUzrJlF2Bo">https://chat.whatsapp.com/JvOtojYDDC3FoUzrJlF2Bo</a></strong></p>
                              
                             
                              <p style="padding-top: 50px">Thanks,<br>The DigiMarker Team</p><p style="text-align:left;">8770791699 / 8817119597</p>
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
       //  var_dump($email_response);                
         if($email_response){
             return true;
         }else{
             return false;
         }
    }
    //code End Here
    public function edit($examiner_id)
    {
            $this->load->model('CentersModel');
            $data['centers'] = $this->CentersModel->get_centers();
            
            $this->load->model('RegionsModel');
            $data['regions'] = $this->RegionsModel->get_regions();
            
            $data['examiner']=$this->ExaminersModel->get_examiner($examiner_id);
            $this->load->view('forms/edit_examiner', $data);
    }
    //Vikas Code Writen on 15/04/23
    public function add_subject($examiner_id){

        $this->load->model('ExaminersModel');
        $data['examiner']=$this->ExaminersModel->get_examiner($examiner_id);
        
         $data['examiner_subject'] = $this->ExaminersModel->get_examiner_subjects($data['examiner']['examiner_username']);
        
         $this->load->model('SubjectsModel');
         $data['subjects'] = $this->SubjectsModel->get_subjects_course_wise($data['examiner']['course_code']);

         $this->load->model('CoursesModel');
         $data['courses'] = $this->CoursesModel->get_courses_by_code($data['examiner']['course_code']);

        $this->load->view('forms/addExaminar_subjects', $data);
        
    }

    public function insert_subject(){
        $this->load->model('ExaminersModel');
        $data['examiner_username'] = $this->input->post('username');
        $data['course_code'] = $this->input->post('course_code');

        for( $i=0 ; $i < count($this->input->post('subject')) ; $i++ ){

            $data['subject_code'] = $this->input->post('subject['.$i.']');

            $check = $this->ExaminersModel->insert_subjects($data);

        }
        if( $check == false){
            $this->session->set_flashdata('error', 'Subjects Not Added');
        }else{
            $this->session->set_flashdata('success', 'Subjects Added Successfully');

        }
        redirect('head_evaluators');
    }
    //Code End Here
    public function update()
    {
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                //$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|max_length[80]');
                $this->form_validation->set_rules('name', 'Head Marker Name', 'required|alpha_numeric_spaces');
                $this->form_validation->set_rules('center', 'center code', 'required|alpha_numeric');
                $this->form_validation->set_rules('phone', 'phone', 'numeric');
                //$this->form_validation->set_rules('medium', 'Medium', 'required|alpha_numeric');
                //$this->form_validation->set_rules('subject', 'subject', 'required|alpha_numeric');
                $this->form_validation->set_rules('region', 'region', 'required|alpha_numeric');
                $this->form_validation->set_rules('designation', 'designation', 'required|alpha_numeric_spaces');
            
        if ($this->form_validation->run() === true) {
            $examiner['examiner_name']=$this->input->post('name');
            $examiner['examiner_designation']=$this->input->post('designation');
            //$examiner['examiner_username']=$this->input->post('username');
            $examiner['examiner_email']=$this->input->post('email');
            //$examiner['examiner_password']=$this->input->post('password');
            $examiner['examiner_phone']=$this->input->post('phone');
            $examiner['center_code']=$this->input->post('center');
            $examiner['region_code']=$this->input->post('region');
            $examiner_id=$this->input->post('id');
                    
            $examiner = $this->security->xss_clean($examiner);
            $response=$this->ExaminersModel->update_examiner($examiner_id, $examiner);
            if (!$response['success']) {
                $this->session->set_flashdata('error', $response['message']);
            } else {
                $this->session->set_flashdata('success', 'Data Updated Succesfully');
            }
            $activity['activity_type']='Update';
            $activity['activity_data']=json_encode($examiner);
            $this->ActivitiesModel->add_activity($activity);
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
                    redirect('head_evaluators');
    }
    public function remove($examiner_id)
    {
        if ($this->ExaminersModel->delete_examiner($examiner_id)) {
            $status='success';
            $message='HE deleted successfully';
        } else {
            $status='error';
            $message='Error deleting HE';
        }
                $activity['activity_type']='Delete';
                $this->ActivitiesModel->add_activity($activity);
            $this->session->set_flashdata($status, $message);
            redirect('head_evaluators');
    }
    public function getExaminers($centerCode)
    {
            $examiners = $this->ExaminersModel->get_examiner_bycenter($centerCode);
            //var_dump($centers);exit;
        if (empty($examiners)) {
            echo "<option value=''>No data found</option>";
        } else {
            $options='';
            foreach ($examiners as $examiner) {
                $options .= "<option value='".$examiner['examiner_username']."'>".$examiner['center_code']." - ".$examiner['examiner_username']."</option>";
            }
            echo $options;
        }
    }
    public function export()
    {
            /* $this->load->dbutil();
            $query = $this->db->query("SELECT * FROM examiners");
            $delimiter = ",";
            $newline = "\r\n";
            $enclosure = '"';
            $data=$this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
            $this->load->helper('file');
            $this->load->helper('download');
            force_download('examiners.csv', $data); */
            
            $examiners = $this->ExaminersModel->export_examiner();
            $this->load->library('excel');
            $this->excel->array_to_xls($examiners, 'Examiners');
    }
        
    public function importcsv()
    {
        $data['error'] = array();    //initialize image upload error array to empty
 
        echo $config['upload_path'] = 'uploads/csv/examiners/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000';
 
        $this->load->library('csvimport');
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
 
 
        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            $this->session->set_flashdata('error', $data['error']);
            redirect('head_evaluators');
        } else {
            $file_data = $this->upload->data();
            $file_path =  $config['upload_path'].$file_data['file_name'];
            
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                $i=2;
                $flag=0;
                foreach ($csv_array as $row) {
                    if (preg_match('/^[a-z0-9 .\-]+$/i', $row['Name']) == false) {
                        $data['error'][]='Head Evaluator name should contain alphanumeric character or spaces in row'.$i;
                        $flag=1;
                    }
                    /* if (preg_match('/^[a-z0-9 .\-]+$/i', $row['OrgName']) == false) {
                        $data['error'][]='Org Name should contain alphanumeric character or spaces in row'.$i;
                        $flag=1;
                    } */
                    if (preg_match('/^_?[a-z0-9]+$/i', $row['Designation']) == false) {
                        $data['error'][]='Designation should contain alphanumeric character or spaces in row'.$i;
                        $flag=1;
                    }
                    if (preg_match('/^[a-zA-Z0-9]+$/i', $row['UserName']) == false) {
                        $data['error'][]='Head Evaluator Username should contain alphanumeric character in row'.$i;
                        $flag=1;
                    }
                    if (filter_var($row['Email'], FILTER_VALIDATE_EMAIL) == false) {
                        $data['error'][]='Evaluator email is invalid in row'.$i;
                        $flag=1;
                    }
                    if (preg_match('/^[a-zA-Z0-9]+$/i', $row['CenterCode']) == false) {
                        $data['error'][]='CenterCode should contain alphanumeric character in row'.$i;
                        $flag=1;
                    }
                    if (preg_match('/^[a-zA-Z0-9]+$/i', $row['CourseCode']) == false) {
                        $data['error'][]='CourseCode should contain alphanumeric character in row'.$i;
                        $flag=1;
                    }
                    if (preg_match('/^[a-zA-Z0-9]+$/i', $row['SubjectCode']) == false) {
                        $data['error'][]='SubjectCode should contain alphanumeric character in row'.$i;
                        $flag=1;
                    }
                    if ($flag==0) {
                        $insert_data = array(
                            'examiner_name'=>$row['Name'],
                            'examiner_org'=>$row['OrgName'],
                            'examiner_designation'=>$row['Designation'],
                            'examiner_email'=>$row['Email'],
                            'examiner_phone'=>$row['Phone'],
                            'examiner_username'=>$row['Username'],
                            'examiner_password'=>hash('sha256', $row['Password']),
                            'center_code'=>$row['CenterCode'],
                            'course_code' =>$row['CourseCode'],
                            'subject_code' =>$row['SubjectCode']
                        );
                        $insert_data = $this->security->xss_clean($insert_data);
                        $response=$this->ExaminersModel->add_examiner($insert_data);
                        if (!$response['success']) {
                            $data['error'][]=$response['message'];
                        }
                    }
                    $flag=0;
                    $i++;
                }
                if (!empty($data['error'])) {
                    $this->session->set_flashdata('error', implode('<br/>', $data['error']));
                } else {
                    $this->session->set_flashdata('success', 'Data Imported Succesfully');
                }
                redirect('head_evaluators');
                //echo "<pre>"; print_r($insert_data);
            } else {
                $data['error'] = "Error occured";
            }
                $this->load->view('csvindex', $data);
        }
    }
    public function import()
    {
        $config['upload_path'] = 'uploads/xls/head/';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = '1000';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            $this->session->set_flashdata('error', $data['error']);
            redirect(base_url().'head_evaluators');
        } else {
            $this->load->model('UsersModel');
            $this->load->model('CentersModel');
            $centers = $this->CentersModel->get_centers();
            foreach ($centers AS $center) {
                $region[$center['center_code']]=$center['region_code'];
            } 
            
            $data['error'] = array();    //initialize image upload error array to empty
            $file_data = $this->upload->data();
            $file_path =  $config['upload_path'].$file_data['file_name'];
            $this->load->library('excel');
                $data_array = $this->excel->xls_to_array($file_path);
                $i=2;
                $flag=0;
            foreach ($data_array as $row) {
                //code Written By Vikas
                $subjects_codes = explode(',',$row['SubjectCode']);
                //code End Here
                if (preg_match('/^[a-z0-9 .\-]+$/i', $row['Name']) == false) {
                    $data['error'][]='Head Evaluator name should contain alphanumeric character or spaces in row'.$i;
                    $flag=1;
                }
                if (preg_match('/^[a-z0-9 .\-]+$/i', $row['OrgName']) == false) {
                    $data['error'][]='Org Name should contain alphanumeric character or spaces in row'.$i;
                    $flag=1;
                }
                if (preg_match('/^[a-z0-9_ ]+$/i', $row['Designation']) == false) {
                    $data['error'][]='Designation should contain alphanumeric character or spaces in row'.$i;
                    $flag=1;
                }
                if (preg_match('/^[a-zA-Z0-9]+$/i', $row['Username']) == false) {
                    $data['error'][]='Head Evaluator Username should contain alphanumeric character in row'.$i;
                    $flag=1;
                }
                foreach($subjects_codes as $SubjectCode){
                    if (preg_match('/^[a-zA-Z0-9]+$/i', $SubjectCode) == false) {
                        $data['error'][]='SubjectCode should contain alphanumeric character in row'.$i;
                        $flag=1;
                    }
                }
                if (filter_var($row['Email'], FILTER_VALIDATE_EMAIL) == false) {
                    $data['error'][]='Evaluator email is invalid in row'.$i;
                    $flag=1;
                }
                if (preg_match('/^[a-zA-Z0-9]+$/i', $row['CenterCode']) == false) {
                    $data['error'][]='CenterCode should contain alphanumeric character in row'.$i;
                    $flag=1;
                }
                if ($flag==0) {
                    $user_password=hash_hmac('sha256', $row['Password'], 'aSm0$i_20eNh3os');

                    $user['user_name']=$row['Username'];
                    $user['user_email']=$row['Email'];
                    $user['user_password']=$user_password;
                    $user['center_code']=$row['CenterCode'];
                    $user['user_role']='Head_Evaluator';
                    $user = $this->security->xss_clean($user);
                    $user_data = $this->UsersModel->add_user($user);
                    if (!$user_data['success']) {
                        $data['error'][]=$user_data['message'];
                    } else {
                        $insert_data = array(
                            'examiner_name'=>$row['Name'],
                            'examiner_org'=>$row['OrgName'],
                            'examiner_designation'=>$row['Designation'],
                            'examiner_email'=>$row['Email'],
                            'examiner_phone'=>$row['Phone'],
                            'examiner_username'=>$row['Username'],
                            'examiner_password'=>$user_password,
                            'region_code'=> $region[$row['CenterCode']],
                            'center_code'=>$row['CenterCode'],
                            'subject_code'=> $subjects_codes[0] ,
                            'course_code' =>$row['CourseCode'],
                        );
                         //code written By vikas
                         for($i = 0 ; $i < count($subjects_codes) ; $i++){

                            $subject['subject_code'] = $subjects_codes[$i];
                            $subject['examiner_username'] = $row['Username'];
                            $subject['course_code'] = $row['CourseCode'];
                            $status = $this->ExaminersModel->insert_subjects($subject);
    
                            if($status == false){
    
                                $this->ExaminersModel->remove_examiner($subjects['examiner_username']);
                                $this->ExaminersModel->remove_subjects($subjects['examiner_username']);
                            }
    
        
                       
                    }
                    
                    //Code end Here
                        $insert_data = $this->security->xss_clean($insert_data);
                        $response=$this->ExaminersModel->add_examiner($insert_data);
                        if (!$response['success']) {
                            $data['error'][]=$response['message'];
                        }
                    }
                }
                $flag=0;
                $i++;
            }
            if (!empty($data['error'])) {
                $this->session->set_flashdata('error', implode('<br/>', $data['error']));
            } else {
                $this->session->set_flashdata('success', 'Data Imported Succesfully');
            }
                redirect('head_evaluators');
        }
    }
    public function validate($userid = "")
    {
        $response = [];
        if ($this->input->get('code')) {
            $user = $this->ExaminersModel->check_examiner_username($this->input->get('code'), $userid);
            if ($user) {
                // User name is registered on another account
                $response = array('valid' => false, 'message' => 'This username already exist.');
            } else {
                // User name is available
                $response = array('valid' => true);
            }
        }
        echo json_encode($response);
    }


}
