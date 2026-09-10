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
    //update by ashish duplicate booklet issue
    public function CheckSheetEval($sheet_file,$user_role){
       $this->db->select('eval.user_name,eval.user_role');
       $this->db->where('eval.sheet_file',$sheet_file);
       $this->db->where('eval.user_role',$user_role);
       $this->db->from('eval');
       $this->db->join('evaluators','evaluators.evaluator_username=eval.user_name');
       $this->db->limit('1');
       $query = $this->db->get();
       if($query->num_rows()>0){
        return $query->result_array();
       } else{ return false; }
    }
	 public function addLogSheet($log,$delete){
        $username = $delete['username'];
        $user_role = $delete['user_role'];
        $sheet_file = $delete['sheet_file'];
        //check sheet in eval table
        $evaldata = $this->CheckSheetEval($sheet_file,$user_role);
        $eval_user = $evaldata[0]['user_name'];
         if($eval_user){
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
          } 
        $this->db->delete('eval',$delete);
        $this->db->insert_batch('eval', $log);
        if ($this->db->affected_rows()>0) {       
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
    //end process ashish
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
       // echo $this->db->last_query(); die;
        return $query->result_array();
    }
}
