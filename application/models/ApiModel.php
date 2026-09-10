<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiModel extends CI_Model
{
    public function update_login_time($username)
    {
            
            //$where['user_name']=$userdata;
			$this->db->where('user_name', $username);
            //$user_login['user_email']=$userdata['user_email'];
			$user_login['user_time']=date('Y-m-d H:i:s', time());
			//print_r($user_login);
			$this->db->update('login_user', $user_login);
			//echo $this->db->last_query();
    }
    public function GetsheetData($filename){
        $this->db->select('sheet_json_marks');
        $this->db->where('sheet_file',$sheet_file);
        $query =  $this->db->get('sheets');
        if ($query->num_rows()>0) {
            $user=$query->row_array();
            return $user;
        } else {
            return false;
        }
    }
    public function get_user_token($username)
    {
                    $this->db->where('user_name', $username);
                    $this->db->limit(1);
                    $query = $this->db->get('users');
        if ($query->num_rows()>0) {
            $user=$query->row_array();
            return $user;
        } else {
            return false;
        }
    }
    
    public function add_login_attempt($username)
    {
                    $this->db->where('user_name', $username);
                    $this->db->set('user_login_attempts', 'user_login_attempts+1', false);
        if ($this->db->update('users')) {
            return true;
        } else {
            return false;
        }
    }
     public function update_token_user($userdata)
    {
            $user_login['user_id']=$userdata['user_id'];
            $user_login['user_name']=$userdata['user_name'];
            $user_login['user_email']=$userdata['user_email'];
			$user_login['user_time']=date('Y-m-d H:i:s', time());
			$user_login['user_login_time']=date('Y-m-d H:i:s', time());
            $user_login['ip_address']=$_SERVER['REMOTE_ADDR'];
			$this->db->insert('login_user', $user_login);

				  
                    $user['user_login_ip']=$_SERVER['REMOTE_ADDR'];
                    $user['user_login_time']=date('Y-m-d H:i:s', time());
                    $user['user_token']=md5(uniqid(rand(), true));
                    $user['user_login_attempts']=0;
                    
                    $this->db->where('user_name', $userdata['user_name']);
        if ($this->db->update('users', $user)) {
            $data['success']=true;
            $data['user_token']=$user['user_token'];
            $data['user_email']=$userdata['user_email'];
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function update_token($username)
    {
                    
                    $user['user_login_ip']=$_SERVER['REMOTE_ADDR'];
                    $user['user_login_time']=date('Y-m-d h:i:s', time());
                    $user['user_token']=md5(uniqid(rand(), true));
                    $user['user_login_attempts']=0;
                    
                    $this->db->where('user_name', $username);
        if ($this->db->update('users', $user)) {
            $data['success']=true;
            $data['user_token']=$user['user_token'];
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    
    function update_attendance($username)
    {
                    $time=date('H:i:s', time());
                    $today=date('Y-m-d', time());
                    $this->db->where('attendance_user', $username);
                    $this->db->where('attendance_date', $today);
                    $this->db->limit(1);
                    $query = $this->db->get('attendance');
        if ($query->num_rows()>0) {
            $atd=$query->row_array();
            if ($atd['attendance_login']=='00:00:00') {
                $this->db->set('attendance_login', $time);
            }
            $this->db->set('attendance_logout', $time);
            $this->db->set('attendance_hours', 'TIMEDIFF("'.$time.'",attendance_login)', false);
            $this->db->where('attendance_user', $username);
            $this->db->where('attendance_date', $today);
            $this->db->limit(1);
            if ($this->db->update('attendance')) {
                //echo $this->db->last_query();
                return true;
            }
        } else {
            $attendance['attendance_user']=$username;
            $attendance['attendance_login']=$time;
            $attendance['attendance_logout']=$time;
            $attendance['attendance_date']=date('Y-m-d', time());
            if ($this->db->insert('attendance', $attendance)) {
                return true;
            }
        }
                    return false;
    }
     public function reset_userlogin($username = '',$userlogin='')
    {
        if ($username!='') {
            $this->db->where('user_name', $username);
        }
		if ($userlogin!='') {
            $this->db->where_in('user_name', $userlogin);
        }
                    
        if ($this->db->delete('login_user')) {
            $data['success']=true;
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error reseting login';
            return $data;
        }
    }
    public function reset_token($username = '')
    {
        if ($username!='') {
            $this->db->where('user_name', $username);
        }
                    $user['user_token']='';
                    $user['user_login_attempts']=0;
        if ($this->db->update('users', $user)) {
            $data['success']=true;
            $data['user_token']=$user['user_token'];
            $data['message']=$this->db->affected_rows(). " user login reset done successfully";
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error reseting login';
            return $data;
        }
    }
    
    public function check_user_token($token)
    {
                    $this->db->select('user_id,user_name,user_email,user_role,center_code,user_login_ip');
                    $this->db->where('user_token', $token);
                    $query = $this->db->get('users');
        if ($query->num_rows()>0) {
            $user=$query->row_array();
            return $user;
        } else {
            return false;
        }
    }
}
