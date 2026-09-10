<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ErrorModel extends CI_Model
{

    
    public function get_attendance($where_array = '')
    {
        if ($where_array!='') {
            $this->db->where($where_array);
        }
                    $query = $this->db->get('attendance');
                    return $query->result_array();
    }
    public function get_activities_count($where_array = '', $like_array = '')
    {
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
                    return $this->db->count_all_results('activities_log');
    }
    public function get_activity($id)
    {
                    $this->db->where('activity_id', $id);
                    $query = $this->db->get('activities_log');
                    return $query->row_array();
    }
    
    public function add_error($error)
    {
          $error['error_time']=date('Y-m-d H:i:s', time());
          $error['error_ip']=$_SERVER['REMOTE_ADDR'];
          $error['error_url']=$_SERVER['REQUEST_URI'];
        
        
         $error['user_id']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
       
        if ($this->db->insert('file_error_log', $error)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
	public function get_unconvert_count()
    {
          $select =   array(
                        'allocation_id',
                        'count(allocation_id) as unConvertedTotal'
                    );
                     $this->db->select($select);
                    $this->db->where('status', 'pending');
                    $this->db->group_by("allocation_id");
                    $query = $this->db->get('file_error_log');
                    return $query->result_array();
    }
	public function get_unconvert_list($allocation_id)
    {
                    $this->db->where('status', 'pending');
                    $this->db->where('allocation_id', $allocation_id);
                    $query = $this->db->get('file_error_log');
                    return $query->result_array();
    }
	public function update_error($error,$id)
    {
          $error['error_time']=date('Y-m-d H:i:s', time());
          $error['error_ip']=$_SERVER['REMOTE_ADDR'];
          $error['error_url']=$_SERVER['REQUEST_URI'];
        
        
       
       
        if ($this->db->update('file_error_log',$error,$id)) {
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
