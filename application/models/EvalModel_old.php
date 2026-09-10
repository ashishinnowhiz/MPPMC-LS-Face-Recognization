<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EvalModel extends CI_Model
{

    public function getLogs($where_array='',$limit=0)
    {
        $where['sheet_file'] = $_SESSION['sheet_file'];
        $where['user_name'] = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
        $this->db->where($where);
        if ($limit>0) {
            $this->db->limit($limit);
        }
        $query = $this->db->get('eval');
        return $query->result_array();
    }
    public function getLog($action)
    {
                    $this->db->where('eval_id', $action);
                    $query = $this->db->get('eval');
                    return $query->row_array();
    }
    
    public function addLog($log)
    {
        $log['sheet_file'] = $_SESSION['sheet_file'];
        $log['user_name'] = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
        $log['user_role'] = $_SESSION[$this->config->item('exam')['exam_session']]['user_role'];
        if ($this->db->insert('eval', $log)) {
            $data['success']=true;
            $data['id'] = $this->db->insert_id();
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
	 public function addLogSheet($log)
    {
        
        if ($this->db->insert_batch('eval', $log)) {
            $data['success']=true;
           // $data['id'] = $this->db->insert_id();
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
    public function undo($page, $action)
    {
        $update['eval_status']='Undone';
        $this->db->where('eval_id', $action);
        $this->db->limit(1);
        if ($this->db->update('eval', $update)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            $data['action'] = $this->getLog($action);
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function redo($page, $action)
    {
        $update['eval_status']='Done';
        $this->db->where('eval_id', $action);
        $this->db->limit(1);
        if ($this->db->update('eval', $update)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            $data['action'] = $this->getLog($action);
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function pageLog($page, $where=array()){
        $where['sheet_file'] = $_SESSION['sheet_file'];
		if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=="Evaluator"){
			$where['user_name'] = $_SESSION[$this->config->item('exam')['exam_session']]['user_name']; 
		}
        $this->db->select('eval_page, eval_action, eval_details, eval_que_index, eval_time,user_role,eval_role');
        $this->db->where($where);
        $this->db->where('eval_page', $page);
        $this->db->where('eval_status', 'Done');
        $query = $this->db->get('eval');
        return $query->result_array();
    }
}
