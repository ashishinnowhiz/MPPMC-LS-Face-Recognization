<?php


defined('BASEPATH') or exit('No direct script access allowed');



if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");

    $this->api->response($this->api->json($error), 404);
}


                

if ($this->user['user_role']=='Coordinator') {
    $this->load->model('SheetsModel');

    $data['success']=true;


                    

    $data['allocation']=$this->SheetsModel->sync_allocation($this->user['center_code']);

    $this->api->response($this->api->json($data), 200);
} elseif ($this->user['user_role']=='Evaluator') {
    $error = array('success' => false, "msg" => "Access Forbidden");

    $this->api->response($this->api->json($error), 404);
}
