<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sync extends CI_Controller
{
    
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            $data['success']=false;
            $data['message']='Session has expired. Please login again';
        }
    }
    public function index()
    {
    }
    public function activities()
    {
            $time=date('Y-m-d H:i:s', time());
            $where['sync_status'] = 0;
            $activities=$this->ActivitiesModel->get_activities('', $where);
            
            $body=array();
        if (sizeof($activities)>0) {
            $body['activities']=$activities;
        }
        if (sizeof($body)>0) {
            $this->load->library('curl');
            $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
            $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
            $response=$this->curl->call('activities', 'POST', $body, $token);
            echo $response;
            $obj=json_decode($response);
            if ($obj->success) {
                $this->db->set('sync_status', 1);
                $this->db->where('activity_time <', $time);
                $this->db->update('activities_log');
            }
        } else {
            echo "nothing to update";
        }
    }
    public function limit_updates()
    {
            $time=date('Y-m-d H:i:s', time());
            $where['sync_status'] = 0;
            $this->load->model('EvaluatorsModel');
            $limit_updates=$this->EvaluatorsModel->get_limit_updates('', $where);
        if (sizeof($limit_updates)>0) {
            $this->load->library('curl');
            $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
            $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
            $body['limit_updates']=$limit_updates;
            $response=$this->curl->call('activities', 'POST', $body, $token);
            echo $response;
            $obj=json_decode($response);
            if ($obj->success) {
                $this->db->set('sync_status', 1);
                $this->db->where('limit_update_time <', $time);
                $this->db->update('limit_updates');
            }
        } else {
            echo "nothing to update";
        }
    }
}
