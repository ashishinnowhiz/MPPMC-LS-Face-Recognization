<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EvaluatorsModel extends CI_Model
{

    public function get_evaluators()
    {
		 if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
			$s=$this->db->get_where('examiner_course_subject',array('examiner_username'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_name']))->result_array();
			$subject=array();
		 }
		 $this->db->where('evaluator_deleted', '0');
		 $this->db->from('evaluators');
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            //$this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
			foreach($s as $sresult){
				$subject[]=$sresult['subject_code'];
			}
		  $this->db->join('marker_subjects','marker_subjects.evaluator_username=evaluators.evaluator_username');
		  
		  $this->db->where_in('marker_subjects.subject_code', $subject);
		  $this->db->group_by('marker_subjects.evaluator_username');
		}
                    $query = $this->db->get();
                    return $query->result_array();
    }
    
    public function get_evaluator($id)
    {
                    $this->db->where('evaluator_id', $id);
                    $query = $this->db->get('evaluators');
                    return $query->row_array();
    }
    public function get_evaluator_byname($user_name)
    {
                    $this->db->select('evaluator_name,evaluator_username,evaluator_password,evaluator_designation,evaluator_address,evaluator_email,evaluator_phone,evaluator_username,center_code,course_code,subject_code,medium_code,examiner_username,evaluator_daily_limit,evaluator_remarks');
                    $this->db->where('evaluator_username', $user_name);
                    $query = $this->db->get('evaluators');
                    return $query->row_array();
    }
	
    public function add_evaluator($evaluator)
    {
        if ($this->check_evaluator_username($evaluator['evaluator_username'])>0) {
            $data['success']=false;
            $data['error']=409;
            $data['message']='evaluator username '.$evaluator['evaluator_username'].' Exist';
            return $data;
        }
        if ($this->db->insert('evaluators', $evaluator)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
    //code written by vikas
    public function add_marker_subject($marker_subject)
    {
    
        if ($this->check_marker_subject($marker_subject['evaluator_username'],$marker_subject['subject_code'])>0) {
            $data['success']=false;
            $data['error']=409;
            $data['message']='Already assigned subject '.$marker_subject['subject_code'].' to evaluator username '.$evaluator['evaluator_username'];
            return $data;
        }
        if ($this->db->insert('marker_subjects', $marker_subject)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }

    public function check_marker_subject($username,$code){
              $this->db->where(['evaluator_username'=>$username,'subject_code'=>$code]);
              $query = $this->db->get('marker_subjects');
              return $query->num_rows();
    }

    public function get_marker_subject($username){
        $this->db->where('evaluator_username',$username);
        $query = $this->db->get('marker_subjects');
        return $query->result();
    }

    public function get_marker_subject_by_id($id){
        $this->db->from('marker_subjects m');
        $this->db->select('m.subject_code');
        $this->db->join('evaluators e','e.evaluator_username = m.evaluator_username');
        $this->db->where('e.evaluator_id',$id);
        $query = $this->db->get();
        return $query->result();
    }

    public function update_default_subject($username,$code)
	{
        $this->db->where('evaluator_username',$username);
        $query = $this->db->set('subject_code',$code)->update('evaluators');
       
        if($query)
		{
            $sheet['sheet_status']='Pending';
            $sheet['examiner_username']='';
            $sheet['evaluator_code']='';
			$this->db->where('evaluator_code',$username);
            $this->db->where('sheet_status', 'Assigned');
            $this->db->update('sheets', $sheet);
           // echo $this->db->last_query(); die;
			
			return true;
			                
        }
		else
		{
            return false;
        }
		
		/*
        $this->db->where('evaluator_username',$username);
        $query = $this->db->set('subject_code',$code)->update('evaluators');
        if($query){
            return true;
        }else{
            return false;
        }
		*/
    }

    // public function remove_marker_subjects($evaluator_username,$subject_code){
	
    //     $this->db->where("evaluator_username",$evaluator_username);
    //     $this->db->where_in("subject_code",$subject_code);
    //     $query = $this->db->update("marker_subjects",["subject_status" => "Inactive"]);
    //     if($query){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }
	
    //code end here
    public function get_by_papers()
    {
        $evaluator_count = array();
                    
                    $this->db->select("paper_code");
                    $query = $this->db->get('papers');
                    $papers = $query->result_array();
        foreach ($papers as $paper) {
            $evaluator_count[$paper['paper_code']]=0;
        }
                    
                    $this->db->select("paper_code,count(evaluator_id) AS evaluator_count");
                    $this->db->from('papers');
                    $this->db->join('evaluators', 'papers.subject_code = evaluators.subject_code AND papers.medium_code=evaluators.medium_code');
                    $this->db->group_by("paper_code");
                    $query = $this->db->get();
                    $counts = $query->result_array();
                    
        foreach ($counts as $count) {
            $evaluator_count[$count['paper_code']]=$count['evaluator_count'];
        }
                    return $evaluator_count;
    }
    
    public function add_limit($limit)
    {
                    $limit['limit_update_time']=date('Y-m-d H:i:s', time());
                    $limit['limit_updated_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
        if ($this->db->insert('limit_updates', $limit)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
    public function get_limits_count($where_array = '', $like_array = '')
    {
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
                    return $this->db->count_all_results('limit_updates');
    }
    public function get_limit_updates($limit = '', $where_array = '', $like_array = '')
    {
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
        if ($limit!='') {
            $this->db->limit($limit['per_page'], $limit['start']);
        }
                    $query = $this->db->get('limit_updates');
                    return $query->result_array();
    }
    public function get_evaluator_limit($user_name)
    {
                    $this->db->where('limit_update_time >', date('Y-m-d 00:00:00', time()));
                    $this->db->where('evaluator_username', $user_name);
                    $this->db->order_by('limit_update_id', 'DESC');
                    $this->db->limit(1);
                    $query = $this->db->get('limit_updates');
                  // echo $this->db->last_query(); die;
                    return $query->row_array();
    }
    public function update_evaluator($evaluator)
    {
                    $evaluator['evaluator_updated_time']=date('Y-m-d h:i:s', time());
                    $evaluator['evaluator_updated_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                    $this->db->where('evaluator_username', $evaluator['evaluator_username']);
        if ($this->db->update('evaluators', $evaluator)) {
            $data['success']=true;
            $data['message']='Successfully Updated '.$evaluator['evaluator_username'];
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function delete_evaluator($id)
    {
                    $evaluator['evaluator_deleted']=1;
                    $this->db->where('evaluator_id', $id);
        if ($this->db->update('evaluators', $evaluator)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function check_evaluator_username($code)
    {
                    $this->db->where('evaluator_username', $code);
                    $query = $this->db->get('evaluators');
                    return $query->num_rows();
    }
     public function add_book_activity($activity){
            if ($this->db->insert('user_attemp_book', $activity)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }

   
}
