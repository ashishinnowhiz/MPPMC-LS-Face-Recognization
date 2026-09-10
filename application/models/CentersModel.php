<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CentersModel extends CI_Model
{

    public function get_centers()
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
            $this->db->where('center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
        }
                    $this->db->where('center_deleted', '0');
                    $query = $this->db->get('centers');
                    return $query->result_array();
    }
    
    public function get_center($id)
    {
                    $this->db->where('center_id', $id);
                    $query = $this->db->get('centers');
                    return $query->row_array();
    }
    public function get_center_bycode($code)
    {
                    $this->db->where('center_code', $code);
                    $query = $this->db->get('centers');
                    return $query->row_array();
    }
    public function add_center($center)
    {
        if ($this->check_center_code($center['center_code'])>0) {
            $data['success']=false;
            $data['message']='center Code '.$center['center_code'].' Exist';
            return $data;
        }
        if ($this->db->insert('centers', $center)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
    public function update_center($center_id, $center)
    {
        if ($this->check_center_code($center['center_code'])>0) {
            $data['success']=false;
            $data['message']='center Code '.$center['center_code'].' Exist';
            return $data;
        }
                    $this->db->where('center_id', $center_id);
        if ($this->db->update('centers', $center)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function delete_center($id)
    {
                    $center['center_deleted']=1;
                    $this->db->where('center_id', $id);
        if ($this->db->update('centers', $center)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function check_center_code($code)
    {
                    $this->db->where('center_code', $code);
                    $query = $this->db->get('centers');
                    return $query->num_rows();
    }
}
