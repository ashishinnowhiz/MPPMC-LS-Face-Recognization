<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Upload extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user']) || ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator')) {
            redirect('login');
        }
		$this->load->library('custlog');
                $this->load->model('SheetsModel');
                $this->load->model('ErrorModel');
                $this->load->model('SettingsModel');
    }
	public function updateStatus(){
			//$this->load->library('curl');
            $allocation=$this->db->query("SELECT sum(`allocation_quantity`) as allocated,sum(`allocation_synced_files`) as syncFile,(allocation_quantity-allocation_synced_files) as pendingDownload FROM `allocation_log` WHERE `allocation_sync_status` ='Pending' ")->result_array();
			//echo $this->db->last_query();
			$pendingSheet=$this->db->get_where("sheets","sheet_status='Pending'")->num_rows();
			//echo $this->db->last_query();
			$checkedSheet=$this->db->get_where("sheets","sheet_status='Checked' or sheet_status='Rechecked'")->num_rows();
		//	echo $this->db->last_query();
			$activeMarker=$this->db->get("login_user")->num_rows();           

		//	echo $this->db->last_query();		
		    $body['pendingDownload']=$allocation[0]['pendingDownload']==''?0:$allocation[0]['pendingDownload'];
			$body['pendingSheet']=$pendingSheet==''?0:$pendingSheet;
			$body['email']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
			$body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
			$body['checkedSheet']=$checkedSheet==''?0:$checkedSheet;
			$body['activeMarker']=$activeMarker==''?0:$activeMarker;
			$MAC = exec('getmac');
			$MAC = strtok($MAC, ' ');
			$body['centerMac']=$MAC?$MAC:$_SERVER['HTTP_HOST'];
			$r=$this->curl->callstatus();
   			 $rr=JSON_decode($r);
			//echo $rr->status;
			// print_r($body);
			$body['backupServerStatus']=$rr->status=='Yes'?'A':'D';
			$token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
             $resp=$this->curl->call('local_server_status', 'POST', $body,$token);
             $obj=json_decode($resp);
			// print_r($obj);
			 if($obj->status=='D')
			 {
				$_SESSION['logouts']='Something went wrong. Please contact the admin';
				redirect('Logout');
			 }
            
	}
    public function index()
    {
        // echo "<pre>";
        // print_r( $_SESSION['settings_s3']);
        $this->load->library('curl');
        if ($this->curl->is_connected() && ($_SESSION[$this->config->item('exam')['exam_session']]['user_token']!='')) {
			$this->updateStatus();
            $data['online']=true;
        } else {
            $data['online']=false;
        }
                $this->syncall();
                $data['paper_counts'] = $this->SheetsModel->get_paper_sheets_count();
                $this->load->model('PapersModel');
                $data['papers'] = $this->PapersModel->get_papers();
                
                $this->load->model('SettingsModel');
                $setting = $this->SettingsModel->get_setting('multieval');
                $data['multieval']=json_decode($setting['setting_json']);
                
                $this->load->model('EvaluatorsModel');
                $data['paper_evaluators'] = $this->EvaluatorsModel->get_by_papers();
            
                $data['total'] = $this->SheetsModel->get_sheets_count();
                $data['allocation'] = $this->SheetsModel->get_allocation_list();
                $data['convertImage'] = $this->ErrorModel->get_unconvert_count();
				//print_r($data['convertImage']);die;
                $this->load->view('header');
                $this->load->view('upload', $data);
                $this->load->view('footer');
    }
    public function master()
    {
        // error_reporting(E_ALL);
        //  ini_set('display_errors', 1);
                    $this->load->library('curl');
                    $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
                    $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
                    $response=$this->curl->call('masterdata', 'POST', $body, $token);
                    // echo "<pre>";
                    // print_r($response);
					// die;
                    $json=json_decode($response);
                //   echo "<pre>";
				// 	print_r($json);
				// 	die;
					$this->custlog->storeLog("Master Data:".json_encode($json));
        if ($json->success) {
            $this->sync_json($json);
            $this->session->set_flashdata('success', 'Basedata Updated Successfully');
        } else {
            $this->session->set_flashdata('error', 'Error updating basedata');
        }
                    
                        $activity['activity_type']='Sync';
                        $this->ActivitiesModel->add_activity($activity);
                        
                    //Model answer/question file update
                    $this->load->model('PapersModel');
                    $papers = $this->PapersModel->get_papers();
					$this->custlog->storeLog("papaers:".json_encode($papers));
        if (sizeof($papers)>0) {
            foreach ($papers as $paper) {
                 $qp_url='answersheets/'.$paper['paper_model_question'];
                if (!file_exists($qp_url)) {
                    //echo $paper['paper_model_question'];
                    // $this->curl->downfile($paper['paper_model_question'],'answersheets/');
                    $body['url']=$paper['paper_model_question'];
                    $qp=$this->curl->call('pdf', 'POST', $body, $token);
                    $file = fopen($qp_url, "w+");
                    chmod($qp_url, 0775);
                    fputs($file, $qp);
                    fclose($file); 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, base_url().'Pdfimage/getUrl/?url='.$qp_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 1); // quick exit
                    $response = curl_exec($ch);
                    curl_close($ch);
                    // shell_exec('curl '.base_url().'Pdfimage/getUrl/?url='.$qp_url.' > /dev/null 2>&1 &');
                }
                $ap_url='answersheets/'.$paper['paper_model_answer'];
               if (!file_exists($ap_url)) {
                    //$this->curl->downfile($paper['paper_model_answer'],'answersheets/');
                    $body['url']=$paper['paper_model_answer'];
                    $ap=$this->curl->call('pdf', 'POST', $body, $token);
                    $file = fopen($ap_url, "w+");
                    chmod($ap_url, 0775);
                    fputs($file, $ap);
                    fclose($file);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, base_url().'Pdfimage/getUrl/?url='.$ap_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 1); // quick exit
                    $response = curl_exec($ch);
                    curl_close($ch);
                   // shell_exec('curl '.base_url().'Pdfextract/getUrl/?url='.$ap_url.' > /dev/null 2>&1 &');
                   // shell_exec('curl '.base_url().'Pdfimage/getUrl/?url='.$ap_url.' > /dev/null 2>&1 &');
                }
                /*Correction in QP @mausmi 28april26*/
                $cp_url='answersheets/'.$paper['correction_qp'];
               if (!file_exists($cp_url)) {
                    //$this->curl->downfile($paper['paper_model_answer'],'answersheets/');
                    $body['url']=$paper['correction_qp'];
                    $cp=$this->curl->call('pdf', 'POST', $body, $token);
                    chmod($cp_url, 0775);
                    $file = fopen($cp_url, "w+");
                    fputs($file, $cp);
                    fclose($file);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, base_url().'Pdfimage/getUrl/?url='.$cp_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 1); // quick exit
                    $response = curl_exec($ch);
                    curl_close($ch);
                    //shell_exec('curl '.base_url().'Pdfextract/getUrl/?url='.$cp_url.' > /dev/null 2>&1 &');
                }
                 /*End Correction in QP @mausmi 28april26*/
            }
        }
                    redirect('upload');
    }
    // public function newt()
    // {
    //     $sheet_url='answersheets/63/RJN2001100122_checked.pdf';
    //         $post_data['file'] = new CurlFile($sheet_url);
    //         $post_data['firstName'] = 'Name';
    //         $post_data['action'] = 'Register';
    //         /*
    //         foreach ( $post_data as $key => $value) {
    //         $post_items[] = $key . '=' . $value;
    //         }
    //         $post_string = implode ('&', $post_items);
    //         */
    //         $curl_connection = curl_init('http://evalguru.jaansi.com/savefile');
    //         curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    //         curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    //         curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    //         curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
    //         curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, 1);
    //         curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_data);
    //         echo $result = curl_exec($curl_connection);
    //         curl_close($curl_connection);
    // }
    
    public function json($allocation_id)
    {
				$this->custlog->storeLog("------------------Allocaion Json:-------------");
                $this->SheetsModel->update_allocation_download($allocation_id);
                $data['sheets'] = $this->SheetsModel->get_allocated_sheets($allocation_id);
                $this->load->model('SubjectsModel');
                $data['subjects'] = $this->SubjectsModel->get_subjects();
                $this->load->model('RegionsModel');
                $data['regions'] = $this->RegionsModel->get_regions();
                $this->load->model('MediumsModel');
                $data['mediums'] = $this->MediumsModel->get_mediums();
                $this->load->model('PapersModel');
                $data['papers'] = $this->PapersModel->get_papers();
                
                $this->load->helper('download');
                $name = $_SESSION[$this->config->item('exam')['exam_session']]['user_center']."-".date('Ymdhis', time()).".json";
				$this->custlog->storeLog("Allocation Json[".$name."]:".json_encode($data));
                force_download($name, json_encode($data));
    }
    public function add_sheets($sheets)
    {
        $sett = $this->SettingsModel->get_setting('general');
        $setting = json_decode($sett['setting_json'],true);
       
            $status=array();
        foreach ($sheets as $sheet) {
                $data['paper_code']=$sheet->paper_code;
                $data['sheet_file']=$sheet->sheet_file;
                $data['examiner_id']=$sheet->examiner_id;
                $data['medium_code']=$sheet->medium_code;
              //  $data['sheet_code']=$sheet->sheet_id;
               /*  if($setting['qc_ls'] == 'on'){
                    $data['sheet_status'] = 'QC';
                   } */
                $data['region_code']=$sheet->region_code;
                $data['subject_code']=$sheet->subject_code;
                $data['exam_code']=$sheet->exam_code;
                $data['center_code']=$_SESSION[$this->config->item('exam')['exam_session']]['user_center'];
                $data['allocation_id']=$sheet->allocation_id;
                $response=$this->SheetsModel->add_sheet($data);
            if ($response['success']) {
                $status['success'][]=$response['message'];
            } else {
                $status['error'][]=$response['message'];
            }
        }
            return $status;
    }
        
    public function add_subjects($subjects)
    {
            $this->load->model('SubjectsModel');
        foreach ($subjects as $subject) {
                $data['subject_code']=$subject->subject_code;
                $data['subject_name']=$subject->subject_name;
                if(empty($subject->course_code)){
                    $data['course_code']='None';
                }else{
                    $data['course_code']=$subject->course_code;
                }
                $response=$this->SubjectsModel->add_subject($data);
            if ($response['success']) {
                $status['success'][]=$response['message'];
            } else {
                $status['error'][]=$response['message'];
            }
        }
            return $status;
    }
//code written by vikas
    public function add_courses($courses)
    {
            $this->load->model('CoursesModel');
        foreach ($courses as $course) {
                $data['course_code']=$course->course_code;
                $data['course_name']=$course->course_name;
                $data['parent']=$course->parent;
                $response=$this->CoursesModel->add_course($data);
            if ($response['success']) {
                $status['success'][]=$response['message'];
            } else {
                $status['error'][]=$response['message'];
            }
        }
            return $status;
    }

    public function add_marker_subjects($marker_subjects)
    {
        $this->db->empty_table('marker_subjects');
            $this->load->model('EvaluatorsModel');
        foreach ($marker_subjects as $marker_subject) {

                $data['evaluator_username']=$marker_subject->evaluator_username;
                $data['subject_code']=$marker_subject->subject_code;
                $data['course_code']=$marker_subject->course_code;

                $response=$this->EvaluatorsModel->add_marker_subject($data);

            if ($response['success']) {
                $status['success'][]=$response['message'];
            } else {
                $status['error'][]=$response['message'];
            }
        }
            return $status;
    }

    public function add_examiner_subjects($examiner_subjects)
    {
        $this->db->empty_table('examiner_course_subject');
            $this->load->model('ExaminersModel');
        foreach ($examiner_subjects as $examiner_subject) {

                $data['examiner_username']=$examiner_subject->examiner_username;
                $data['subject_code']=$examiner_subject->subject_code;
                $data['course_code']=$examiner_subject->course_code;
                $data['subject_status']=$examiner_subject->subject_status;
                $response=$this->ExaminersModel->add_examiner_subject($data);
               

            if ($response['success']) {
                $status['success'][]=$response['message'];
            } else {
                $status['error'][]=$response['message'];
            }
        }
            return $status;
    }
//code end here   
    public function add_mediums($mediums)
    {
            $this->load->model('MediumsModel');
        foreach ($mediums as $medium) {
                $data['medium_code']=$medium->medium_code;
                $data['medium_name']=$medium->medium_name;
                    
                $response=$this->MediumsModel->add_medium($data);
            if ($response['success']) {
                $status['success'][]=$response['message'];
            } else {
                $status['error'][]=$response['message'];
            }
        }
            return $status;
    }
    
    public function add_regions($regions)
    {
            $this->load->model('RegionsModel');
        foreach ($regions as $region) {
                $data['region_code']=$region->region_code;
                $data['region_name']=$region->region_name;
                    
                $response=$this->RegionsModel->add_region($data);
            if ($response['success']) {
                $status['success'][]=$response['message'];
            } else {
                $status['error'][]=$response['message'];
            }
        }
            return $status;
    }
    
    public function add_papers($papers)
    {
            $this->load->model('PapersModel');
            $this->db->empty_table('papers');
        foreach ($papers as $paper) {
                $data['paper_code']=$paper->paper_code;
                $data['course_code']=$paper->course_code;//line of code written by vikas
                $data['paper_set']=$paper->paper_set;
                $data['exam_code']=$paper->exam_code;
                $data['subject_code']=$paper->subject_code;
                $data['medium_code']=$paper->medium_code;
                $data['paper_sheet_pages']=$paper->paper_sheet_pages;
                $data['paper_supp_pages']=$paper->paper_supp_pages;
                $data['paper_model_question']=$paper->paper_model_question;
                $data['paper_model_answer']=$paper->paper_model_answer;
                $data['paper_total_marks']=$paper->paper_total_marks;
                $data['paper_all_marks']=$paper->paper_all_marks;
                $data['paper_passing_marks']=$paper->paper_passing_marks;
                $data['marking_scheme_json']=$paper->marking_scheme_json;
                $data['paper_updated_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                $data['paper_updated_time']=$paper->paper_updated_time;
                $data['min_eval_time']=$paper->min_eval_time;
                $data['correction_qp']=$paper->correction_qp; // /*Correction in QP @mausmi 28april26*/
                //$data['paper_marking_scheme']=$paper->paper_marking_scheme;
                $response=$this->PapersModel->add_paper($data);
               
            if ($response['success']) {
                $status['success'][]=$response['message'];
            } else {
                if ($response['error']==409) {
                    $update=$this->PapersModel->update_paper($data);
                  
                    if ($update['success']) {
                        if (file_exists('answersheets/'.$data['paper_model_question'])) {
                            unlink('answersheets/'.$data['paper_model_question']);
                        }
                        if (file_exists('answersheets/'.$data['paper_model_answer'])) {
                            unlink('answersheets/'.$data['paper_model_answer']);
                        }
                        $status['success'][]=$update['message'];
                    } else {
                        $status['error'][]=$update['message'];
                    }
                } else {
                    $status['error'][]=$response['message'];
                }
            }
        }
            return $status;
    }
        
    public function add_user($user)
    {
            $this->load->model('UsersModel');
                    $response = $this->UsersModel->add_user($user);
        if ($response['success']) {
            $status['success']=true;
            $status['error']=$response['message'];
            $status['message']=$response['message'];
        } else {
            if ($response['error']==409) {
                        $update=$this->UsersModel->update_user($user);
                if ($update['success']) {
                    $status['success']=true;
                    $status['message']=$update['message'];
                } else {
                    $status['success']=false;
                    $status['message']=$update['message'];
                }
            } else {
                            $status['success']=false;
                            $status['message']=$response['message'];
            }
        }
            return $status;
    }
        
    public function add_examiners($examiners)
    {
            //$this->load->model('UsersModel');
            $this->load->model('ExaminersModel');
        foreach ($examiners as $examiner) {
                $user['user_name']=$examiner->examiner_username;
                $user['user_email']=$examiner->examiner_email;
                $user['user_password']=$examiner->examiner_password;
                $user['center_code']=$_SESSION[$this->config->item('exam')['exam_session']]['user_center'];
                // $user['user_role']='Head_Evaluator';
                $user['user_role']=$examiner->examiner_designation;//Line Of Code Written By Vikas
                    
                $user_data = $this->add_user($user);
            if (!$user_data['success']) {
                $status['error'][]=$user_data['message'];
            } else {
                $status['success'][]=$user_data['message'];
                $data['examiner_name']=$examiner->examiner_name;
                $data['subject_code']=$examiner->subject_code;//line of code written by vikas
                $data['course_code']=$examiner->course_code;//line of code written by vikas
                $data['examiner_org']=$examiner->examiner_org;
                $data['examiner_designation']=$examiner->examiner_designation;
                $data['examiner_email']=$examiner->examiner_email;
                $data['examiner_phone']=$examiner->examiner_phone;
                $data['examiner_username']=$examiner->examiner_username;
                //$data['examiner_password']=$examiner->examiner_password;
                        
                $response=$this->ExaminersModel->add_examiner($data);
                if ($response['success']) {
                    $status['success'][]=$response['message'];
                } else {
                    if ($response['error']==409) {
                        $update=$this->ExaminersModel->update_examiner($data);
                        if ($update['success']) {
                            $status['success'][]=$update['message'];
                        } else {
                            $status['error'][]=$update['message'];
                        }
                    } else {
                        $status['error'][]=$response['message'];
                    }
                }
            }
        }
            return $status;
    }
        
    public function add_evaluators($evaluators)
    {
            //$this->load->model('UsersModel');
            $this->load->model('EvaluatorsModel');
        foreach ($evaluators as $evaluator) {
                $user['user_name']=$evaluator->evaluator_username;
                $user['user_email']=$evaluator->evaluator_email;
                $user['user_password']=$evaluator->evaluator_password;
                $user['center_code']=$_SESSION[$this->config->item('exam')['exam_session']]['user_center'];
                $user['user_role']='evaluator';
                    
                $user_data = $this->add_user($user);
            if (!$user_data['success']) {
                $status['error'][]=$user_data['message'];
            } else {
                $status['success'][]=$user_data['message'];
                $data['evaluator_name']=$evaluator->evaluator_name;
                $data['course_code']=$evaluator->course_code;//line of code written by vikas
                $data['evaluator_designation']=$evaluator->evaluator_designation;
                $data['evaluator_email']=$evaluator->evaluator_email;
                $data['evaluator_phone']=$evaluator->evaluator_phone;
                $data['evaluator_username']=$evaluator->evaluator_username;
                //$data['evaluator_password']=$evaluator->evaluator_password;
                $data['examiner_username']=$evaluator->examiner_username;
                $data['center_code']=$evaluator->center_code;
                $data['subject_code']=$evaluator->subject_code;
                $data['medium_code']=$evaluator->medium_code;
                $data['evaluator_daily_limit']=$evaluator->evaluator_daily_limit;
                        
                $response=$this->EvaluatorsModel->add_evaluator($data);
                if ($response['success']) {
                    $status['success'][]=$response['message'];
                } else {
                    if ($response['error']==409) {
						$d['evaluator_name']=$evaluator->evaluator_name;
						$d['course_code']=$evaluator->course_code;//line of code written by vikas
						$d['evaluator_designation']=$evaluator->evaluator_designation;
						$d['evaluator_email']=$evaluator->evaluator_email;
						$d['evaluator_phone']=$evaluator->evaluator_phone;
						$d['evaluator_username']=$evaluator->evaluator_username;
						$d['evaluator_password']=$evaluator->evaluator_password;
						$d['examiner_username']=$evaluator->examiner_username;
						$d['center_code']=$evaluator->center_code;
						//$data['subject_code']=$evaluator->subject_code;
						$d['medium_code']=$evaluator->medium_code;
						$d['evaluator_daily_limit']=$evaluator->evaluator_daily_limit;
                        $update=$this->EvaluatorsModel->update_evaluator($d);
                        if ($update['success']) {
                            $status['success'][]=$update['message'];
                        } else {
                            $status['error'][]=$update['message'];
                        }
                    } else {
                        $status['error'][]=$response['message'];
                    }
                }
            }
        }
            return $status;
    }
    public function add_settings($settings)
    {
            $this->load->model('SettingsModel');
        foreach ($settings as $setting) {
                $response=$this->SettingsModel->add_setting($setting);
            if ($response['success']) {
                    $status['success'][]=$response['message'];
            } else {
                if ($response['error']==409) {
                    $update=$this->SettingsModel->update_setting($setting->setting_for, $setting);
                    if ($update['success']) {
                        $status['success'][]=$update['message'];
                    } else {
                        $status['error'][]=$update['message'];
                    }
                } else {
                    $status['error'][]=$response['message'];
                }
            }
        }
            return $status;
    }
        
    public function files()
    {
            //{"file":{"name":"filedrag3.zip","type":"application\/zip","tmp_name":"C:\\Windows\\Temp\\phpE434.tmp","error":0,"size":4683}}
            $_FILES['file']['tmp_name'];
            $savepath='uploads/'.$_FILES['file']['name'];
            $output['success']=false;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $savepath)) {
            $zip = new ZipArchive;
            if ($zip->open($savepath) === true) {
                if ($zip->locateName('data.json')!==false) {
                        $string=$zip->getFromName('data.json');
                        $json=json_decode($string);
                        $json->allocation->allocation_mode='Offline';
                        $json->allocation->allocation_file=$savepath;
                    if ($this->SheetsModel->allocation_log($json->allocation)) {
                        $output['success']=true;
                        $output['message']="Data Updated";
                    } else {
                        $output['success']=false;
                        $output['message']="Allocation file already uploaded";
                    }
                } else {
                    $output['message']="Data not found";
                }
                $zip->close();
            } else {
                $output['message']='failed';
            }
        } else {
            $output['message']="Error uploading file please check maximum upload size allowed";
        }
            //echo json_encode($output);
        if (!$output['success']) {
            $this->session->set_flashdata('error', $output['message']);
        } else {
            $this->session->set_flashdata('success', $output['message']);
        }
            redirect('upload');
    }
    public function syncall()
    {
        $where['allocation_mode']='Offline';
        $pending= $this->SheetsModel->get_allocation_list('', 'Pending', $where);
        foreach ($pending as $allocation) {
            $this->sync($allocation['allocation_id']);
        }
    }
    public function sync($allocation_id)
    {
                $allocation= $this->SheetsModel->get_allocation_details($allocation_id);
                $savepath=$allocation['allocation_file'];
        if ($savepath!='') {
            $file_parts = pathinfo($savepath);
            if ($file_parts['extension']=='zip') {
                $zip = new ZipArchive;

                if ($zip->open($savepath) === true) {
                    if ($zip->locateName('data.json')!==false) {
                            $string=$zip->getFromName('data.json');
                            $json=json_decode($string);
                            //print_r($json);
                            $log=$this->sync_json($json);
                        if ($this->SheetsModel->update_allocation_log($allocation_id, $log)) {
                                //echo "Synced";
                        } else {
                                    //echo "Error";
                        }
                    } else {
                        echo "Data not found";
                    }
                    $zip->close();
                } else {
                    echo 'failed';
                }
                    
                $zip = new ZipArchive;
                if ($zip->open($savepath) === true) {
                    $zip->extractTo('answersheets/');
                    $zip->close();
                            
                    $files = scandir('answersheets/'.$allocation_id.'/');
                    $file_count=0;
                    foreach ($files as $file) {
                        if (!is_dir($file)) {
                            if ($file!='data.json') {
                                $this->SheetsModel->update_synced_sheet($file, $allocation_id);
                                //$file."<br/>";
                                $file_count=$file_count+1;
                            }
                        }
                    }
                    $this->SheetsModel->update_allocation_file_synced($allocation_id, $file_count);
                } else {
                    echo 'failed';
                }
            } elseif ($file_parts['extension']=='json') {
                            $string=read_file($savepath);
                            $json=json_decode($string);
                            $log=$this->sync_json($json);
                if ($this->SheetsModel->update_allocation_log($allocation_id, $log)) {
                //echo "Synced";
                } else {
                        //echo "Error";
                }
            }
        }
    }
        
    public function sync_json($json)
    {
		
        if (isset($json->subjects) && (sizeof($json->subjects)>0)) {
            $log['subjects']=$this->add_subjects($json->subjects);
        }
        if (isset($json->courses) && (sizeof($json->courses)>0)) {
            $log['courses']=$this->add_courses($json->courses);
        }
        if (isset($json->marker_subjects) && (sizeof($json->marker_subjects)>0)) {
            $log['marker_subjects']=$this->add_marker_subjects($json->marker_subjects);
        }
        if (isset($json->examiner_course_subject) && (sizeof($json->examiner_course_subject)>0)) {
            $log['examiner_course_subject']=$this->add_examiner_subjects($json->examiner_course_subject);
        }
        if (isset($json->mediums) && (sizeof($json->mediums)>0)) {
            $log['mediums']=$this->add_mediums($json->mediums);
        }
        if (isset($json->regions) && (sizeof($json->regions)>0)) {
            $log['regions']=$this->add_regions($json->regions);
        }
        if (isset($json->papers) && (sizeof($json->papers)>0)) {
            $log['papers']=$this->add_papers($json->papers);
        }
        if (isset($json->sheets) && (sizeof($json->sheets)>0)) {
            $log['sheets']=$this->add_sheets($json->sheets);
        }
        if (isset($json->examiners) && (sizeof($json->examiners)>0)) {
            $log['examiners']=$this->add_examiners($json->examiners);
        }
        if (isset($json->evaluators) && (sizeof($json->evaluators)>0)) {
            $log['evaluators']=$this->add_evaluators($json->evaluators);
        }
        if (isset($json->settings) && (sizeof($json->settings)>0)) {
           // $log['settings']=$this->add_settings($json->settings);
        }
        if (isset($log)) {
            return $log;
        } else {
            return false;
        }
    }
    public function online()
    {
                $count=0;
                $allocation_count=0;
                $unallocation_count=0;
                
                $this->load->library('curl');
                
                $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
                $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
                $response=$this->curl->call('check', 'POST', $body, $token);
                $obj=json_decode($response);
        if ($obj->success) {
            if (sizeof($obj->allocation)>0) {
                foreach ($obj->allocation as $allocation) {
                    $allocation->allocation_mode='Online';
                    if ($this->SheetsModel->allocation_log($allocation)) {
                        $count=$count+1;
                        $body['allocation_id']=$allocation->allocation_id;
                        if ($allocation->allocation_type=='Allocation') {
                            $allocation_count=$allocation_count+1;
                            $json_data=$this->curl->call('data', 'POST', $body, $token);
                            $filename=$allocation->allocation_id.'.json';
							$this->custlog->storeLog("Allocaion Data:$filename:".$json_data);
                            if (!write_file('uploads/json/'.$filename, $json_data)) {
                                echo 'Unable to write the file';
                            }
                                    $savepath="uploads/json/".$filename;
                            if ($this->SheetsModel->update_allocation_file($allocation->allocation_id, $savepath)) {
                                        //echo "Synced";
                            } else {
                                //echo "Error";
                            }
                        } else {
                            $unallocation_count=$unallocation_count+1;
                            $qty=$this->SheetsModel->unallocate($allocation->paper_code);
                            $this->SheetsModel->update_allocation_qty($allocation->allocation_id, $qty);
                        }
                    }
                }
            }
            $data['count']=$count;
            $data['allocation_count']=$allocation_count;
            $data['unallocation_count']=$unallocation_count;
            echo json_encode($data);
        } else {
        }
                $activity['activity_data']=json_encode($data);
                $activity['activity_type']='Sync';
                $this->ActivitiesModel->add_activity($activity);
    }
    public function json_sheets($allocation_id=1)
    {
            $this->load->library('curl');
            $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
            $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
            $response=$this->curl->call('settings', 'POST', $body, $token);
            $settings=json_decode($response,true);
        if ($settings['success']==1) {
            $obj=json_decode($settings['data']);
            // print_r($obj); die;
            $config['hostname'] = $obj->host;
            $config['username'] = $obj->username;
            $config['password'] = $obj->password;
            $config['port'] = $obj->port;
            if ($obj->passive) {
                $config['passive']  = true;
            }
            $config['debug']    = false;
            $_SESSION['settings']=$config; 
          }
        if ($settings['s3']['success']==1){
            $obj=json_decode($settings['s3']['data']);
            $config_s3['access_key'] = $obj->access_key;
            $config_s3['secret_key'] = $obj->secret_key;
            $config_s3['region'] = $obj->region;
            $config_s3['bucket'] = $obj->bucket;
            $config_s3['version'] = $obj->version;
            $_SESSION['settings_s3']=$config_s3;
        }

         $allocation=$this->SheetsModel->get_allocation_details($allocation_id);

		 //echo json_encode($allocation);die;
        if ($allocation['allocation_sync_status']=='Pending') {
            $this->sync($allocation_id);
        }
        // echo "<pre>";
        // print_r($_SESSION['settings_s3']);
            $sheets=$this->SheetsModel->get_allocated_sheets($allocation_id, '0');
            echo json_encode($sheets);
    }
    public function update_synced($allocation_id, $sheets_count = '')
    {
        $data['count']=$this->SheetsModel->update_allocation_file_synced($allocation_id, $sheets_count);
        $data['success']=true;
        echo json_encode($data);
    }
        
    public function syncfile($file, $allocation_id)
    {
         error_reporting(E_ALL);
          ini_set('display_errors', 1);
       $this->load->model('SettingsModel');
        $sett = $this->SettingsModel->get_setting('general');
        $setting = json_decode($sett['setting_json'],true);
        if(  $setting['server'] == 'ftp'){
            $this->download_ftp($file, $allocation_id);
       }else if($setting['server'] == 's3'){
            $this->download_s3($file, $allocation_id);
        }

      
    }

    public function download_ftp($file, $allocation_id){
        if (!file_exists('answersheets/'.$allocation_id)) {
            mkdir('answersheets/'.$allocation_id);
        }
       // print_r($_SESSION['settings']); die;
        if (isset($_SESSION['settings'])) {
            $this->load->library('ftp');
              
            if ($this->ftp->connect($_SESSION['settings'])) {
                $this->load->helper('file');
                $tempfile='answersheets/'.$allocation_id.'/'.$file;
                if ($this->ftp->download('/synced/'.$file, $tempfile)) {
                    if (filesize('answersheets/'.$allocation_id.'/'.$file)>10000) {
                        $this->SheetsModel->update_synced_sheet($file, $allocation_id);
						$this->SheetsModel->update_allocation_file_synced($allocation_id);
                         //if(shell_exec('curl '.base_url().'Pdfextract/file/'.$file.'/'.$allocation_id.' > /dev/null 2>&1 &')){
						if($this->extract($file, $allocation_id)!=0){
							$data['success']=true;
						}else{
							$error['allocation_id']=$allocation_id;
							$error['script_name']=$file;
							$error['error_log']="Cannot convert pdf to image";
							$error['error_type']="IC";
							$error['status']="pending";
							$this->ErrorModel->add_error($error);
							$data['success']=true;
							
						} 
						                        
                    } else {
                        unlink('answersheets/'.$allocation_id.'/'.$file, $response);
                        $data['success']=false;
                    }
                } else {
                    $data['success']=false;
                    $data['message']='Cannot download file from File Server. Please contact system administrator.';
                }
                $this->ftp->close();
            } else {
                $data['success']=false;
                $data['message']='Cannot connect to File Server. Please contact system administrator.';
            }
        } else {
            $data['success']=false;
        }
                
            echo json_encode($data);
    }

    public function download_s3($file, $allocation_id){
        if (!file_exists('answersheets/'.$allocation_id)) {
            mkdir('answersheets/'.$allocation_id);
        }
        if (isset($_SESSION['settings_s3'])) {
            $this->load->library('aws');
                $this->load->helper('file');
                $tempfile='answersheets/'.$allocation_id.'/'.$file;
                if ($this->aws->downloadFile('synced/'.$file, $tempfile)) {
                    if (filesize('answersheets/'.$allocation_id.'/'.$file)>10000) {
                        $this->SheetsModel->update_synced_sheet($file, $allocation_id);
						$this->SheetsModel->update_allocation_file_synced($allocation_id);
                         //if(shell_exec('curl '.base_url().'Pdfextract/file/'.$file.'/'.$allocation_id.' > /dev/null 2>&1 &')){
						if($this->extract($file, $allocation_id)!=0){
							$data['success']=true;
						}else{
							$error['allocation_id']=$allocation_id;
							$error['script_name']=$file;
							$error['error_log']="Cannot convert pdf to image";
							$error['error_type']="IC";
							$error['status']="pending";
							$this->ErrorModel->add_error($error);
							$data['success']=true;
							
						} 
						                        
                    } else {
                        unlink('answersheets/'.$allocation_id.'/'.$file, $response);
                        $data['success']=false;
                    }
                } else {
                    $data['success']=false;
                    $data['message']='Cannot download file from File Server. Please contact system administrator.';
                }
               
            
        } else {
            $data['success']=false;
        }
                
            echo json_encode($data);
    }

	public function extract($file, $allocationId)
    {
		$url = "answersheets/".$allocationId."/".$file;
		
        $path=$url.'img';
		
        require_once APPPATH."/third_party/PdfToText/PdfToText.phpclass";
        $pdf        =  new PdfToText($url, PdfToText::PDFOPT_DECODE_IMAGE_DATA) ;
        $imageCount    =  count($pdf -> Images) ;
		if($imageCount>15){
			$countFile=$path.'/count.txt';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			file_put_contents($countFile, $imageCount);

			if ($imageCount!=0) {
				for ($i = 0; $i  <  $imageCount; $i ++) {
					// Get next image and generate a filename for it (there will be a file named "sample.x.jpg"
					// for each image found in file "sample.pdf")
					$img        =  $pdf -> Images [$i] ;            // This is an object of type PdfImage
					//$imgindex   =  sprintf("%02d", $i + 1) ;
					$file=$path.'/'.$i.'.jpg';

					// Save the image (the default is IMG_JPG, but you can specify another IMG_* image type by specifying it
					// as the second parameter)
					$img -> SaveAs($file);
					$this->imgCompress($file,$file,'80');
				}
			 return $imageCount;
			}else{
				$imageCount=0;
				return $imageCount;
			}
		}else{
			$this->SheetsModel->reject_sheet_auto($file, $allocation_id);
			return $imageCount;
		}
    }
	function imgCompress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}
}
