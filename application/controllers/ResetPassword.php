<?php 

class ResetPassword extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('captcha');
        $this->load->model('UsersModel');
    }

    public function index(){

        $this->load->view('reset-password',$data);
    }

    public function send_otp(){
        $email = $this->input->post('email');
        $otp = $this->input->post('otp');
        if($otp != ''){
            return $this->validate_otp($otp, $email);
        }
        $data['email'] =  $email;
        $check = $this->UsersModel->check_user_email( $email);
        // echo $this->db->last_query();
        // echo $check;
        if($check > 0){
            $this->session->set_flashdata('success','OTP Send Successfully');
            $this->session->set_userdata('otp',5414);
            $data['otp'] = true;
        }else{
            $this->session->set_flashdata('error','Invalid Email Id');
        }
       

        $this->load->view('reset-password',$data);
    }

    public function validate_otp($otp, $email){
        $data['email'] =  $email;
        $s_otp = $this->session->userdata('otp');
        if($s_otp == $otp){
            $this->load->view('new-password',$data);
        }else{
            $this->session->set_flashdata('error','Invalid OTP');
            $data['otp'] = true;
            $this->load->view('reset-password',$data);
        }
    }

    public function update_password(){
        $user['password'] = $user_password=hash_hmac('sha256', $this->input->post('password'), 'aSm0$i_20eNh3os');
        $user['email'] = $this->input->post('email');
        $result = $this->UsersModel->update_users_password($user);
        // echo $this->db->last_query();
        // echo "<pre>";
        // print_r($result);
        // die;

        if(isset($result['success'])){
            $this->session->set_flashdata('success',$result['success']);
        }else{
            $this->session->set_flashdata('error',$result['error']);
        }

        redirect('login');
    }
}

?>