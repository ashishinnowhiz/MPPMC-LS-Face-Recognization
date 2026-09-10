<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends CI_Controller
{

    public function index()
    {
		$this->load->model('ApiModel');
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator') {
            $this->load->model('EvalModel');
            $log=$this->EvalModel->getLogs('', 1);
            if (sizeof($log)==0) {
                $this->load->model('SheetsModel');
                $this->SheetsModel->unasssign_sheet($_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
            }
			$this->ApiModel->reset_userlogin($_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
      
        $response=$this->ApiModel->reset_token($_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        if ($response['success']) {
            $activity['activity_type']='Logout';
            $this->ActivitiesModel->add_activity($activity);
            unset($_SESSION[$this->config->item('exam')['exam_session']]['user']);
            unset($_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
            $expire=time()-60*60*24*30;
            setcookie("user", '', $expire);
            setcookie("username", '', $expire);
        }
        redirect('');
    }
}
