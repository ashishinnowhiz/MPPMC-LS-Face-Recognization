<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
        $this->load->model('SettingsModel');
		if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!="Coordinator"){
		$this->sessionunset();
		}
    }
	public function sessionunset() 
    {
		$this->db->where("user_name",$_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
		$s=$this->db->get("login_user");
		$sessiondata=$s->result_array();
		//echo $s->num_rows();
		if($s->num_rows()==0){
			redirect("logout");
		}else{
		foreach($sessiondata as $result){
			 $user=$result['user_name'];
			 
			 if($user==$_SESSION[$this->config->item('exam')['exam_session']]['user_name']){
				 
			
				$usertime=$result['user_time'];
			//echo "<br>";
			 $currenttime=date('Y-m-d H:i:s', time());
			$usertime = strtotime($usertime); 
			$currenttime = strtotime($currenttime); 
			$diff_minutes = ($currenttime - $usertime)/60;
			//echo $diff_minutes;
			//die;
			if($diff_minutes>60){
				
				$this->db->delete("login_user",array("user_name"=>$user));
				 if($user==$_SESSION[$this->config->item('exam')['exam_session']]['user_name']){
					redirect("logout");
				}
				
			}else{
					 $this->load->model('ApiModel');
					
					$this->ApiModel->update_login_time($user);
					//print_r($user);
				} 
				
			 }else{
				 redirect("logout");
			 }	
				
		}
		}
    }
    public function index()
    {         
         //ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        
        $this->load->model("GraphModel");
		//print_r($_SESSION);
  //		echo "MAC address of client is: $MAC";
		//echo "IF address of client is:". $_SERVER['REMOTE_ADDR'];
        if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator'){
			
			
			 $this->load->library('curl'); 
          /*  $allocation=$this->db->query("SELECT sum(`allocation_quantity`) as allocated,sum(`allocation_synced_files`) as syncFile,(allocation_quantity-allocation_synced_files) as pendingDownload FROM `allocation_log` WHERE `allocation_sync_status` ='Pending'")->result_array();
			$pendingSheet=$this->db->get_where("sheets","sheet_status='Pending' or sheet_status='Assigned'")->num_rows();
			$checkedSheet=$this->db->get_where("sheets","sheet_status='Checked' or sheet_status='Rechecked'")->num_rows();
			$activeMarker=$this->db->get("login_user")->num_rows();           
			$body['pendingDownload']=$allocation[0]['pendingDownload'];
			$body['pendingSheet']=$pendingSheet;
			$body['email']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
			$body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
			$body['checkedSheet']=$checkedSheet;
			$body['activeMarker']=$activeMarker;
			$MAC = exec('getmac');
			$MAC = strtok($MAC, ' ');
		    $body['centerMac']=$MAC?$MAC:$_SERVER['HTTP_HOST'];
			 $r=$this->curl->callstatus();
   			 $rr=JSON_decode($r);
			//echo $rr->status;
			$body['backupServerStatus']=$rr->status=='Yes'?'A':'D';
			
			//echo "Welcome";
			 $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
             $resp=$this->curl->call('local_server_status', 'POST', $body,$token);
             $obj=json_decode($resp);
             
			 if($obj->status=='D')
			 {
				$_SESSION['logouts']='Something went wrong. Please contact the admin';
				// redirect('Logout');
			 }
			//echo $obj->status;
			//echo $obj['status'];
			//print_r($body);
			//print_r($resp);die;
             if ($obj->success) {
                // $_SESSION[$this->config->item('exam')['exam_session']]['user_token']=$obj->user_token;
             } */
                 
				 
            $this->load->model('SheetsModel');
            //Code Written By Vikas
            // $where['sheet_status'] = "ReMarking";
            $data['sheets'] = $this->SheetsModel->get_sheets_count($where);
            //Code End Here
            // $data['sheets'] = $this->SheetsModel->get_sheets_count();
            
            $data['sheetData'] = $this->SheetsModel->get_active_script();
            $data['checked'] = $this->SheetsModel->get_evaluation_count();
            // echo "<pre>";
            // print_r($data['checked']);
            // die;
            $this->load->model('PapersModel');
            $data['papers'] = $this->PapersModel->get_papers_count();
            $this->load->model('UsersModel');
            $data['logged_in'] = $this->UsersModel->get_loggged_in_users();

            //code written by vikas
            $settings = $this->SettingsModel->get_settings_for('evaluation');
            $status = json_decode($settings[0]['setting_json'],true);
            $data['status'] = $status['status'];
            //code end here

        
            $setting = $this->SettingsModel->get_setting('config');
            $data['config']=json_decode($setting['setting_json']);
			
            $result["results"] = $this->GraphModel->getGraphValues();

            $result["json_report"] = json_encode($result["results"]);
			/* echo "<pre>";
			print_r($data);
			print_r($result);
			die; */
            // echo "<pre>";
            // print_r($result);
           
            $this->load->view('header',$data); // Code With Ubes
             $this->load->view('dashboard');
            //$this->load->view('dashboard-chart', $result);
            $this->load->view('footer');
        }else if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator'){
            $this->load->model('ReportsModel');
            if (isset($_GET['date']) && $_GET['date']!='') {
					$date=$_GET['date'];
				}else{
					$date=date('Y-m-d', time());
				}
            $reportsheets= $this->ReportsModel->head_reports($date, $_SESSION[$this->config->item('exam')['exam_session']]['user_name'], true);
            $csheets=array();
            $serial=0;
            foreach ($reportsheets as $row) {
                $serial=$serial+1;
                $row['srno']=$serial;
                $row['evaluation_marks']=$row['head_evaluation_marks'];
                $csheets[]=$row;
            }
            $data['reports']=$csheets;
            $data['stats'] = $this->ReportsModel->head_stats($date, $_SESSION[$this->config->item('exam')['exam_session']]['user_name'], true);

            $data['date']=$date;
            $headData['eval']=true;
            $this->load->view('header',$headData);
            $this->load->view('he-evaluator', $data);
            $this->load->view('footer');
            //$this->load->view('eval');
        }else if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){
                redirect('marker');
        }
		else{
                $data['title']=$this->config->item('exam')['exam_name'].' Marking';
                $evaluator=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
            //    if (isset($_GET['date']) && $_GET['date']!='') {
			// 		$date=$_GET['date'];
			// 	}else{
			// 		$date=date('Y-m-d', time());
			// 	}
                $this->load->model('EvaluatorsModel');
                $this->load->model('ReportsModel');
               //code written by vikas
               $this->load->model('SubjectsModel');
               $this->load->model('coursesModel');
               $this->load->model('SheetsModel');
               //code end here
                $data['evaluator'] = $this->EvaluatorsModel->get_evaluator_byname($evaluator);
                // $data['reports'] = $this->ReportsModel->evaluator_reports($date, $evaluator);
                // $data['stats'] = $this->ReportsModel->evaluator_stats($date, $evaluator);
                // $data['date']=$date;

            //code written by vikas
            $settings = $this->SettingsModel->get_settings_for('evaluation');
            $status = json_decode($settings[0]['setting_json'],true);
            $data['status'] = $status['status'];
            //code end here
           
            $headData['eval']=true;
            $this->load->view('header',$headData);
            $this->load->view('dashboard-subject-evaluator', $data);
            $this->load->view('footer');
            //$this->load->view('eval');
        }
    }
    
    public function Summary(){

        //print_r($_SESSION);
        //echo "MAC address of client is: $MAC";
		//echo "IF address of client is:". $_SERVER['REMOTE_ADDR'];
        if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator'){
			
			
            $this->load->library('curl'); 
           $allocation=$this->db->query("SELECT sum(`allocation_quantity`) as allocated,sum(`allocation_synced_files`) as syncFile,(allocation_quantity-allocation_synced_files) as pendingDownload FROM `allocation_log` WHERE `allocation_sync_status` ='Pending'")->result_array();
           $pendingSheet=$this->db->get_where("sheets","sheet_status='Pending' or sheet_status='Assigned'")->num_rows();
           $checkedSheet=$this->db->get_where("sheets","sheet_status='Checked' or sheet_status='Rechecked'")->num_rows();
           $activeMarker=$this->db->get("login_user")->num_rows();           
           $body['pendingDownload']=$allocation[0]['pendingDownload'];
           $body['pendingSheet']=$pendingSheet;
           $body['email']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
           $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
           $body['checkedSheet']=$checkedSheet;
           $body['activeMarker']=$activeMarker;
           $MAC = exec('getmac');
           $MAC = strtok($MAC, ' ');
           $body['centerMac']=$MAC?$MAC:$_SERVER['HTTP_HOST'];
            $r=$this->curl->callstatus();
               $rr=JSON_decode($r);
           //echo $rr->status;
           $body['backupServerStatus']=$rr->status=='Yes'?'A':'D';
           
           //echo "Welcome";
            $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
            print_r(json_encode($body));
            print_r($token); die;
            $resp=$this->curl->call('local_server_status', 'POST', $body,$token);
            $obj=json_decode($resp);
            
            if($obj->status=='D')
            {
               $_SESSION['logouts']='Something went wrong. Please contact the admin';
               redirect('Logout');
            }
           //echo $obj->status;
           //echo $obj['status'];
           //print_r($body);
           //print_r($resp);die;
            if ($obj->success) {
               // $_SESSION[$this->config->item('exam')['exam_session']]['user_token']=$obj->user_token;
            } 
                
                
           $this->load->model('SheetsModel');
           $data['sheets'] = $this->SheetsModel->get_sheets_count();
           $data['sheetData'] = $this->SheetsModel->get_active_script();
           $data['checked'] = $this->SheetsModel->get_evaluation_count();
           $this->load->model('PapersModel');
           $data['papers'] = $this->PapersModel->get_papers_count();
           $this->load->model('UsersModel');
           $data['logged_in'] = $this->UsersModel->get_loggged_in_users();

           $this->load->model('SettingsModel');
           $setting = $this->SettingsModel->get_setting('config');
           $data['config']=json_decode($setting['setting_json']);

           $this->load->view('header',$data);
           $this->load->view('dashboard');
           $this->load->view('footer');
       }else if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator'){
           $this->load->model('ReportsModel');
           if (isset($_GET['date']) && $_GET['date']!='') {
                   $date=$_GET['date'];
               }else{
                   $date=date('Y-m-d', time());
               }
           $reportsheets= $this->ReportsModel->head_reports($date, $_SESSION[$this->config->item('exam')['exam_session']]['user_name'], true);
           $csheets=array();
           $serial=0;
           foreach ($reportsheets as $row) {
               $serial=$serial+1;
               $row['srno']=$serial;
               $row['evaluation_marks']=$row['head_evaluation_marks'];
               $csheets[]=$row;
           }
           $data['reports']=$csheets;
           $data['stats'] = $this->ReportsModel->head_stats($date, $_SESSION[$this->config->item('exam')['exam_session']]['user_name'], true);

           $data['date']=$date;
           $headData['eval']=true;
           $this->load->view('header',$headData);
           $this->load->view('he-evaluator', $data);
           $this->load->view('footer');
           //$this->load->view('eval');
       }
       else{
               $data['title']=$this->config->item('exam')['exam_name'].' Marking';
               $evaluator=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
            //   if (isset($_GET['date']) && $_GET['date']!='') {
            //        $date=$_GET['date'];
            //    }else{
            //        $date=date('Y-m-d', time());
            //    }

            if (isset($_GET['date_from']) && isset($_GET['date_to']) ) {
                if( $_GET['date_from']!='' && $_GET['date_to']!=''){
                    $date_from = $_GET['date_from'];
                    $date_to = $_GET['date_to'];
                }else{
                    $date_from=date('Y-m-d', time());
                    $date_to=date('Y-m-d', time());
                }
               
            }else{
                $date_from=date('Y-m-d', time());
                $date_to=date('Y-m-d', time());
            }
               $this->load->model('EvaluatorsModel');
               $this->load->model('ReportsModel');
              //code written by vikas
              $this->load->model('SubjectsModel');
              $this->load->model('coursesModel');
              $this->load->model('SheetsModel');
              //code end here
              $where['evaluation.evaluation_date >='] = $date_from;
              $where['evaluation.evaluation_date <='] = $date_to;
              $where_state['evaluation_date >='] = $date_from;
              $where_state['evaluation_date <='] = $date_to;
               $data['evaluator'] = $this->EvaluatorsModel->get_evaluator_byname($evaluator);
               $data['reports'] = $this->ReportsModel->evaluator_reports($date_from, $evaluator, $where);
               $data['stats'] = $this->ReportsModel->evaluator_stats($date_from, $evaluator, $where_state);
               $data['date_from'] = $date_from;
               $data['date_to'] = $date_to;

            //code written by vikas
            $settings = $this->SettingsModel->get_settings_for('evaluation');
            $status = json_decode($settings[0]['setting_json'],true);
            $data['status'] = $status['status'];
            //code end here
            
           
           $headData['eval']=true;
           $this->load->view('header',$headData);
           $this->load->view('dashboard-evaluator', $data);
           $this->load->view('footer');
           //$this->load->view('eval');
       }

    }
     public function marking()
    {
		 $data['title']='WebPilot Marking';
                $evaluator=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
                $date=date('Y-m-d', time());
                $this->load->model('EvaluatorsModel');
                $this->load->model('ReportsModel');
                $data['evaluator'] = $this->EvaluatorsModel->get_evaluator_byname($evaluator);
                $data['reports'] = $this->ReportsModel->evaluator_reports($date, $evaluator);
                $data['stats'] = $this->ReportsModel->evaluator_stats($date, $evaluator );
                $data['date']=$date;
            $headData['eval']=true;
           /*  $this->load->view('header',$headData);
            $this->load->view('dashboard-evaluator', $data);
            $this->load->view('footer'); */
            $this->load->view('jslinks');
            $this->load->view('eval',$headData,$data);
	}
    public function reset_script(){
		
		if($this->input->get('username')!=""){
			$this->load->model('SheetsModel');
			 if ($this->SheetsModel->unasssign_sheet($this->input->get('username'))) {
				$this->session->set_flashdata('success', 'Scripts unassigned successfully');
			} else {
				$this->session->set_flashdata('error', 'error unasssigning scripts');
			}
		$activity['activity_type']='Update';
		$activity['activity_detail']='Unassigned scripts for '.$this->input->get('username');
		$this->ActivitiesModel->add_activity($activity);
		redirect(base_url()); }else{
			redirect(base_url());
		}
	}
	 public function ftp_setting(){
		
		if($this->input->get('username')!=""){
			$this->load->model('SheetsModel');
			 if ($this->SheetsModel->unasssign_sheet($this->input->get('username'))) {
				$this->session->set_flashdata('success', 'Scripts unassigned successfully');
			} else {
				$this->session->set_flashdata('error', 'error unasssigning scripts');
			}
		$activity['activity_type']='Update';
		$activity['activity_detail']='Unassigned scripts for '.$this->input->get('username');
		$this->ActivitiesModel->add_activity($activity);
		redirect(base_url()); }else{
			redirect(base_url());
		}
	}
	 public function reset_password(){
		
            $user['user_password']=hash_hmac('sha256', $this->input->post('password'), 'aSm0$i_20eNh3os');
            $user['user_email']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
           
            $this->load->model('UsersModel');
            $user_data = $this->UsersModel->change_password_center($user);
            if (!$user_data['success']) {
				
                $this->session->set_flashdata('error', $user_data['message']);
            } else {
                $activity['activity_type']='Update';
                $activity['activity_detail']='Change Center Password';
                $update['user_name']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
                $activity['activity_data']=json_encode($update);
              $this->ActivitiesModel->add_activity($activity);
                $this->session->set_flashdata('success', $user_data['message']);
				session_destroy();
			
            }
			
		redirect('welcome');
        
	}
    public function reset_login()
    {
        if ($this->input->post('username')!='') {
            $this->load->model('ApiModel');
            if ($this->input->post('username')=='All') {
				$this->load->model('UsersModel');
				$data['logged_in'] = $this->UsersModel->get_loggged_in_users();
                $username='';
                $userlogin=array();
				foreach($data['logged_in'] as $login){
					array_push($userlogin,$login['user_name']);
				}
            } else {
				$userlogin='';
                $username=$this->input->post('username');
            }
            $this->load->model('SheetsModel');
            if ($this->input->post('unassign')=='true') {
				if($this->input->post('username')!='All'){
					if ($this->SheetsModel->unasssign_sheet($this->input->post('username'))) {
						$this->session->set_flashdata('success', 'Scripts unassigned successfully');
					} else {
						$this->session->set_flashdata('error', 'error unasssigning scripts');
					}
					$activity['activity_type']='Update';
					$activity['activity_detail']='Unassigned scripts for '.$this->input->post('username');
					$this->ActivitiesModel->add_activity($activity);
				}
            }
                    
            $this->ApiModel->reset_userlogin($username,$userlogin);
            $response=$this->ApiModel->reset_token($username);
            if (!$response['success']) {
                    $this->session->set_flashdata('error', $response['message']);
            } else {
                        $this->session->set_flashdata('success', $response['message']);
            }
                            $activity['activity_type']='Update';
                            $activity['activity_detail']='Reset login for '.$this->input->post('username');
                            $this->ActivitiesModel->add_activity($activity);
        }
                redirect('');
    }
	public function marker_print(){
		if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Evaluator'){
		$data['title']='WebPilot Marking';
                $evaluator=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
                // if (isset($_GET['date']) && $_GET['date']!='') {
				// 	$date=$_GET['date'];
				// }else{
				// 	$date=date('Y-m-d', time());
				// }
                //Code Written By Vikas
                if (isset($_GET['date_from']) && isset($_GET['date_to']) ) {
                    if( $_GET['date_from']!='' && $_GET['date_to']!=''){
                        $date_from = $_GET['date_from'];
                        $date_to = $_GET['date_to'];
                    }else{
                        $date_from=date('Y-m-d', time());
                        $date_to=date('Y-m-d', time());
                    }
                   
                }else{
                    $date_from=date('Y-m-d', time());
                    $date_to=date('Y-m-d', time());
                }
                //Code End here
                $this->load->model('EvaluatorsModel');
                $this->load->model('ReportsModel');
                // $data['evaluator'] = $this->EvaluatorsModel->get_evaluator_byname($evaluator);
                // $data['reports'] = $this->ReportsModel->evaluator_reports($date, $evaluator);
                // $data['stats'] = $this->ReportsModel->evaluator_stats($date, $evaluator);
                // $data['date']=$date;
               
                //code written by vikas
                $where['evaluation.evaluation_date >='] = $date_from;
                $where['evaluation.evaluation_date <='] = $date_to;
                $where_state['evaluation_date >='] = $date_from;
                $where_state['evaluation_date <='] = $date_to;
                 $data['evaluator'] = $this->EvaluatorsModel->get_evaluator_byname($evaluator);
                 $data['reports'] = $this->ReportsModel->evaluator_reports($date_from, $evaluator, $where);
                 $data['stats'] = $this->ReportsModel->evaluator_stats($date_from, $evaluator, $where_state);
                 $data['date_from'] = $date_from;
                 $data['date_to'] = $date_to;
            
                //code end here
            
            $this->load->view('reports/marker_report', $data);
         }else{
			redirect(base_url());
		}   
	}
	public function hm_print(){
		if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator'){
			
                $this->load->model('ReportsModel');
            if (isset($_GET['date']) && $_GET['date']!='') {
					$date=$_GET['date'];
				}else{
					$date=date('Y-m-d', time());
				}
			$head_marker=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
            $this->load->model('ExaminersModel');
            $data['examiner'] = $this->ExaminersModel->get_examiner_byname($head_marker);
            $data['reports'] = $this->ReportsModel->head_reports($date, $head_marker);
            $data['stats'] = $this->ReportsModel->head_stats($date, $head_marker);
            $data['date']=$date;
            
            
		$this->load->view('reports/hm_report', $data);
		}else{
			redirect(base_url());
		}
            
	}

    //Code Written By Vikas
    public function summary_export(){
        $this->load->model('ReportsModel');
        $eval_username=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
        $eval_array = array();
           
            $evaluator = $this->ReportsModel->evaluator_reports_export($eval_username);
            // print_r($this->db->last_query());
            // die;
            $data['marks'] = "EvaluationMarks";
           // $data['sheet'] = "SheetFile";
            $data['assign_time'] = "SheetAssignTime";
            $data['evaluation_date'] = "EvaluationDate";
            $data['type'] = "EvaluationType";
            $data['duration'] = "CheckDuration";
            array_push($eval_array,$data);

            foreach($evaluator as $eval){

                $data['marks'] = $eval['evaluation_marks'];
               // $data['sheet'] = $eval['sheet_file'];
                $data['assign_time'] = $eval['sheet_assign_time'];
                $data['evaluation_date'] = $eval['evaluation_date'];
                $data['type'] = $eval['evaluation_type'];
                $data['duration'] =  $eval['check_duration'];
                array_push($eval_array,$data);

            }
            
            
            $name='Summary_Report'.date('d-m-Y', time());
            $this->load->library('csvexport');
            $this->csvexport->array_to_csv($eval_array, $name);
    }

    public function get_datewise(){
        $this->load->model('ReportsModel');

        $where=array();
        $like=array();
    if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
        $date_from=$_GET['date_from'];
    }
    // if (isset($_GET['course']) && ($_GET['course']!='')) {
    //     $course = $_GET['course'];
    // } else {
    //     $course='';
    // }
   
       
    if($date_from !=""){
        $where['evaluation.evaluation_date']=$date_from;
      }

    if($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] == "Evaluator"){
        $evaluator = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];

    }
        // $where['evaluation.evaluation_date <=']=$date_to;
        // if($course != ""){
        //     $where['evaluators.course_code']=$course;//line written by vikas
        // }
        // $this->load->model('CoursesModel');
        // $data['courses'] = $this->CoursesModel->get_courses();
        $data['reports'] = $this->ReportsModel->get_marker_reports_datewise($where,$evaluator );
        $data['date_from']=$date_from;
        // $data['date_to']=$date_to;
        // $data['courseCode']=$course;//line written by vikas
        // echo "<pre>";
        // print_r($data["reports"]);
         //print_r($this->db->last_query());die;
        // echo "</pre>";
        $this->load->view('header');
        $this->load->view('reports/datewise_marker', $data);
        $this->load->view('footer');
    }

    public function marker_export()
    {
        $this->load->model('ReportsModel');
       
           $eval_array = array();
           $eval_username ="";
           if($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] == "Evaluator"){
            $eval_username = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
           }
            $evaluator = $this->ReportsModel->export_marker_reports($eval_username);
           
            $data['name'] = "Name";
            $data['username'] = "UserName";
            $data['phone'] = "Phone No.";
            $data['subject_name'] = "SubjectName";
            $data['subject_code'] = "SubjectCode";
            $data['branch_name'] = "BranchName";
            $data['branch_code'] = "BranchCode";
            $data['course_name'] = "CourseName";
            $data['course_code'] = "CourseCode";
            $data['count'] = "SheetCount";
            array_push($eval_array,$data);

            foreach($evaluator as $eval){

                $data['name'] = $eval["Name"];
                $data['username'] = $eval["UserName"];
                $data['phone'] = $eval["Mobile"];
                $data['subject_name'] = $eval["SubjectName"];
                $data['subject_code'] = $eval["SubjectCode"];
                $data['branch_name'] = $eval["BranchName"];
                $data['branch_code'] = $eval["BranchCode"];
                $data['course_name'] = $eval["CourseName"];
                $data['course_code'] = $eval["CourseName"];
                $data['date'] = $eval["EvaluationDate"];
                $data['count'] = $eval["SheetCount"];
                array_push($eval_array,$data);

            }
            
            
            $name='Marker_Report'.date('d-m-Y', time());
            $this->load->library('csvexport');
            $this->csvexport->array_to_csv($eval_array, $name);
    }
    //Code End Here
	
}
