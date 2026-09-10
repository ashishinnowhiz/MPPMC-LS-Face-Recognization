<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingsModel extends CI_Model
{

    public function get_settings()
    {               
                    $query = $this->db->get('settings');
                    return $query->result_array();
    }

    //Code Written By Vikas
    public function get_settings_for($for)
    {               $this->db->where('setting_for',$for);
                    $query = $this->db->get('settings');
                    return $query->result_array();
    }
    //Code End Here
    
    public function get_setting($setting_for)
    {
                    $this->db->where('setting_for', $setting_for);
                    $query = $this->db->get('settings');
                   // echo $this->db->last_query(); die;
                    return $query->row_array();
    }
    
    public function add_setting($setting)
    {
        if ($this->check_setting_for($setting->setting_for)>0) {
            $data['success']=false;
            $data['error']=409;
            $data['message']="setting Code ".$setting->setting_for." Already Exist";
            return $data;
        }
        if ($this->db->insert('settings', $setting)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
    public function update_setting($setting_for, $setting)
    {
                    $setting->setting_updated=date('Y-m-d H:i:s', time());
                    $setting->setting_updated_by=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                    $this->db->where('setting_for', $setting_for);
        if ($this->db->update('settings', $setting)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    
    public function check_setting_for($setting_for)
    {
                    $this->db->where('setting_for', $setting_for);
                    $query = $this->db->get('settings');
                    return $query->num_rows();
    }
}
