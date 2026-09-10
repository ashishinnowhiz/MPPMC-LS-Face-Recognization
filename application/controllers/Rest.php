<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rest extends CI_Controller
{
    public $user;
    public $token;
    public function __construct()
    {
                parent::__construct();
                $this->load->library('api');
                $this->load->model('ApiModel');
    }
    
    public function index($method, $folder = '')
    {
        
            $method = strtolower(trim($method));
        if ($method!='login') {
            $this->authenticate();
        }
        if ($folder=='') {
            $file="Api/".$method.".php";
        } else {
            $file="Api/".$folder."/".$method.".php";
        }
            
        if (file_exists($file)) {
            include($file);
        } else {
            $error = array('success' => false, "msg" => "Invalid Method");
            $this->api->response($this->api->json($error), 404);
        }
    }
    
    public function authenticate()
    {
                $headers=getallheaders();
        if (isset($headers['Authorisation'])) {
            $auth=explode(' ', $headers['Authorisation']);
            $this->token=$auth[1];
            $this->user=$this->ApiModel->check_user_token($this->token);
            if (is_array($this->api->_request)) {
                $username=$this->api->_request['username'];
            } else {
                $username=$this->api->_request->username;
            }
                    
            if ($this->user==false || ($this->user['user_name']!=$username) || ($this->user['user_login_ip']!=$_SERVER['REMOTE_ADDR'])) {
                $data['success']=false;
                $data['message']=$this->user['user_name'].' Invalid Auth Token';
                $this->api->response($this->api->json($data), 401);
            } else {
                if ($this->ApiModel->update_attendance($username)) {
                } else {
                    $error = array('success' => false, "msg" => "Error Updating Attendance");
                    $this->api->response($this->api->json($error), 403);
                }
            }
        } else {
            $error = array('success' => false, "msg" => "Authentication Error");
            $this->api->response($this->api->json($error), 403);
        }
    }
    public function test()
    {
        print_r($_GET);
    }
}
