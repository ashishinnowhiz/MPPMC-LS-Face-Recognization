<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Webotp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user_name'])) {
            redirect('login');
        }
        
        //$this->load->model('EvaluatorsModel');
    }
    public function index()
    {
        //$data['evaluators'] = $this->EvaluatorsModel->get_evaluators();
        
        $this->load->model('SettingsModel');
        $setting = $this->SettingsModel->get_setting('config');
        $data['config']=json_decode($setting['setting_json']);
        if (is_object($data['config'])) {
            $this->load->view('webotp', $data);
        } else {
            echo "Data not synced. Please contact coordinator";
        }
    }
    public function verify($otp)
    {
        $generateLink=false;

        $this->load->model('SettingsModel');
        $setting = $this->SettingsModel->get_setting('config');
        $config=json_decode($setting['setting_json']);
        //$data['evaluators'] = $this->EvaluatorsModel->get_evaluators();
        if ($config->otp=='0'|| $_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Coordinator') {
            $generateLink=true;
        } elseif (isset($_SESSION['otp']) && $_SESSION['otp']==$otp) {
            unset($_SESSION['otp']);
            $generateLink=true;
        } else {
            $data['success'] = false;
            $data['message'] = 'OTP Verification Failed';
        }
        if ($generateLink) {
            $this->load->library('curl');
            $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
            $body['url']=base_url();
            $token = $_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
            $resp=$this->curl->call('exelink', 'POST', $body, $token);
            $obj=json_decode($resp);
            if ($obj->success) {
                $data['success'] = true;
                $data['link'] = $obj->link;
            } else {
                $data['success'] = false;
                $data['message'] = 'Link Generation Failed';
            }
        }
        echo json_encode($data);
    }
    public function link()
    {
        $this->load->model('SettingsModel');
        $setting = $this->SettingsModel->get_setting('config');
        $config=json_decode($setting['setting_json']);

        $otp=substr(md5(uniqid(rand(), true)), 4, 4);
        $_SESSION['otp']=$otp;
        $mobile=$this->getMobile();
        $body['username']=$config->smsUser;
        $body['password']=$config->smsPassword;
        $body['from']=$config->smsSender;
        $body['to']=$mobile;
        $body['text']=$otp.' is the one time password to download  software';
        $obj=$this->sendSMS($body);
        
        if (isset($obj->response[0]->status) && $obj->response[0]->status=='success') {
            $data['success'] = true;
            $data['message'] = 'OTP sent to registered Mobile Number';
        } else {
            $data['success'] = false;
            $data['message'] = 'OTP sending failed';
        }
        echo json_encode($data);
    }
    public function password()
    {
        $otp=substr(md5(uniqid(rand(), true)), 4, 8);
        $this->load->model('SettingsModel');
        $setting = $this->SettingsModel->get_setting('config');
        $config=json_decode($setting['setting_json']);
        $mobile=$this->getMobile();
        /*
        $url='https://api.equence.in/xmlcasting';
        $xml='<MESSAGE VER="1.2">
<USER USERNAME="'.$config->smsUser.'" PASSWORD="'.$config->smsPassword.'" />
<SMS TEXT="'.$otp.' is the one time password to login at ApMark software. For all instruction login at '.$_SERVER['REQUEST_URI'].'"  ID="1" NAI= "0" SPLIT="0" CONCAT="1">
<ADDRESS FROM="'.$config->smsSender.'" TO="9623000910" SEQ="1"/>
</SMS>
</MESSAGE>';
*/
 //$getURL='https://api.equence.in/pushsms?username='.$config->smsUser.'&password='.$config->smsPassword.'&from='.$config->smsSender.'&to=9623000910&text='.$otp.' is the one time password to login at ApMark software. For all instruction login at '.$_SERVER['REQUEST_URI'];
        //redirect($url);
     
        $hashOTP=hash_hmac('sha256', $otp, 'aSm0$i_20eNh3os');

        $user['user_name']=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
        $user['user_password']=$hashOTP;
        $user['user_role']=$_SESSION[$this->config->item('exam')['exam_session']]['user_role'];
        //$this->load->model('UsersModel');
        $this->load->model('ApiModel');
        $response=$this->ApiModel->reset_token($user['user_name']);
        if (!$response['success']) {
            $data['success'] = false;
            $data['message'] = 'Last login reset not done';            
        } else {
            if ($user['user_role']=='Evaluator') {
                $evalupdate['evaluator_updated_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user_id'];
                $evalupdate['evaluator_updated_time']=date('Y-m-d H:i:s', time());
                $evalupdate['evaluator_password']=password_hash($user['user_password'], PASSWORD_DEFAULT);
                $this->db->where('evaluator_username', $user['user_name']);
                $this->db->limit(1);
                $this->db->update('evaluators', $evalupdate);
            }
            if ($user['user_role']=='Head_Evaluator') {
                $evalupdate['examiner_updated_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                $evalupdate['examiner_updated_time']=date('Y-m-d H:i:s', time());
                $evalupdate['examiner_password']=password_hash($user['user_password'], PASSWORD_DEFAULT);
                $this->db->where('examiner_username', $user['user_name']);
                $this->db->limit(1);
                $this->db->update('examiners', $evalupdate);
            }
            $body['username']=$config->smsUser;
            $body['password']=$config->smsPassword;
            $body['from']=$config->smsSender;
            $body['to']=$mobile;
            $body['text']=$otp.' is the one time password to login at software. For all instruction login at '.base_url();
            $obj=$this->sendSMS($body);

            if (isset($obj->response[0]->status) && $obj->response[0]->status=='success') {
                $data['success'] = true;
                $data['message'] = 'Password sent successfully to registered Mobile';
            } else {
                $data['success'] = false;
                $data['message'] = 'Password sending failed';
            }
        }
        
        echo json_encode($data);
    }

    private function sendSMS($body)
    {
        $body=json_encode($body);
        $timeout=30;
        $verify_ssl   = false;
        $headers = array(
                        'Content-Type: application/json',
                        'Cache-Control: no-cache',
                        'Content-Length: '.strlen($body)
                        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.equence.in/pushsms');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        //echo $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $obj=json_decode($result);
        return $obj;
    }
    private function getMobile()
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Evaluator') {
            $this->load->model('EvaluatorsModel');
            $eval=$this->EvaluatorsModel->get_evaluator_byname($_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
            $mobile=$eval['evaluator_phone'];
        } elseif ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->load->model('ExaminersModel');
            $eval=$this->ExaminersModel->get_examiner_byname($_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
            $mobile=$eval['examiner_phone'];
        }
            return $mobile;
    }
}
