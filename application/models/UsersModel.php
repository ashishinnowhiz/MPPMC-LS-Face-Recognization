<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsersModel extends CI_Model
{
    public function add_user($user)
    {
        if ($this->check_user_email($user['user_email'])>0) {
            $data['success']=false;
            $data['error']=409;
            $data['message']='user Code '.$user['user_email'].' Exist';
            return $data;
        }
                   $user['user_token']='';
                   //$user['user_password']=md5($user['user_password']);
                    //$user['user_password']=password_hash($user['user_password'], PASSWORD_DEFAULT);
        if ($this->db->insert('users', $user)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
                  $data['success']=false;
                  $data['message']='Error Adding Data';
                  return $data;
        }
    }
	public function reset_login_session()
    {		$ip=$_SERVER['REMOTE_ADDR'];
            $this->db->where('ip_address', $ip);
            return $this->db->delete('login_user');
    }
    public function get_login_count()
    {
                   $this->db->where('user_token !=', '');
                   return $this->db->count_all_results('users');
    }
    public function get_loggged_in_users()
    {                    
                   // $where = "(user_token!='' OR user_login_attempts>=3)";
                   // $this->db->where($where);
                   $query = $this->db->get('login_user');
                   return $query->result_array();
    }
	public function change_password_center($user)
    {
        $update['user_updated_time']=date('Y-m-d H:i:s', time());
        $update['user_password']=password_hash($user['user_password'], PASSWORD_DEFAULT);
      // echo  $update['user_password']=hash_hmac('sha256', $user['user_password'], 'aSm0$i_20eNh3os');
        $this->db->where('user_email', $user['user_email']);
        $this->db->limit(1);
        if ($this->db->update('users', $update)) {
            $data['success']=true;
            $data['message']='Password changed successfully';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Password';
            return $data;
        }
    }

    public function update_user($user)
    {

                   $user['user_updated_time']=date('Y-m-d h:i:s', time());
                    $this->db->where('user_email', $user['user_email']);
        if ($this->db->update('users', $user)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }


    public function delete_user($id)
    {
                   $user['user_deleted']=1;
                   $this->db->where('user_id', $id);
        if ($this->db->update('users', $user)) {
            return true;
        } else {
            return false;
        }
    }


    public function check_user_email($code, $except_id = '')
    {

        if ($except_id!='') {
            $this->db->where('user_id !=', $except_id);
        }
                   $this->db->where('user_email', $code);
                   $query = $this->db->get('users');
                   return $query->num_rows();
    }

    public function change_password($user)
    {
                    $update['user_updated_time']=date('Y-m-d H:i:s', time());
                    $update['user_password']=password_hash($user['user_password'], PASSWORD_DEFAULT);
                    $this->db->where('user_name', $user['user_name']);
                    $this->db->limit(1);
        if ($this->db->update('users', $update)) {
            if ($user['user_type']=='evaluator') {
                $evalupdate['evaluator_updated_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                $evalupdate['evaluator_updated_time']=date('Y-m-d H:i:s', time());
                $evalupdate['evaluator_password']=password_hash($user['user_password'], PASSWORD_DEFAULT);
                $this->db->where('evaluator_username', $user['user_name']);
                $this->db->limit(1);
                $this->db->update('evaluators', $evalupdate);
            }
            if ($user['user_type']=='examiner') {
                $evalupdate['examiner_updated_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                $evalupdate['examiner_updated_time']=date('Y-m-d H:i:s', time());
                $evalupdate['examiner_password']=password_hash($user['user_password'], PASSWORD_DEFAULT);
                $this->db->where('examiner_username', $user['user_name']);
                $this->db->limit(1);
                $this->db->update('examiners', $evalupdate);
            }
            $data['success']=true;
            $data['message']='Successfully Password Changed';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Password';
            return $data;
        }
    }

    public function update_users_password($user){
        $update['user_updated_time']=date('Y-m-d H:i:s', time());
        $update['user_password']=password_hash($user['password'], PASSWORD_DEFAULT);
        $this->db->where('user_email', $user['email']);
        if ($this->db->update('users', $update)) {
            $data['success'] = 'Password Updated Successfully';
        }else{
            $data['error'] = 'Unable to Update Password';
        }

        return $data;
    }
    public function update_profile_pic($user,$data)
    {
		 $update['profile_img']=$data['filePath'];
		$this->db->where('user_id', $user);
        if ($this->db->update('users',$update)) {
            return true;
        } else {
            return false;
        }
	}
}
