<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ActivitiesModel extends CI_Model
{

    public function get_activities($limit = '', $where_array = '', $like_array = '')
    {
                    $this->db->select('activities_log.*,u.user_name');
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
        if ($limit!='') {
            $this->db->limit($limit['per_page'], $limit['start']);
        }
                    $this->db->join('users u', 'u.user_id=activities_log.user_id', 'left');
                    $this->db->order_by('activity_time', 'DESC');
                    $query = $this->db->get('activities_log');
                    return $query->result_array();
    }
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
    
    public function add_activity($activity)
    {
                    $activity['activity_time']=date('Y-m-d H:i:s', time());
                    $activity['activity_ip']=$_SERVER['REMOTE_ADDR'];
        if (!isset($activity['activity_url'])) {
            $activity['activity_url']=$_SERVER['REQUEST_URI'];
        }
        if (!isset($activity['user_id'])) {
            $activity['user_id']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
        }
        if ($this->db->insert('activities_log', $activity)) {
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
