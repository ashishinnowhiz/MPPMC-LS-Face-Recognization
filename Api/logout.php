<?php


defined('BASEPATH') or exit('No direct script access allowed');



if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");

    $this->api->response($this->api->json($error), 400);
}


$this->load->model('SheetsModel');
$this->SheetsModel->unasssign_sheet($this->user['user_name']);

                       $response=$this->ApiModel->reset_token($this->user['user_name']);

if ($response['success']) {
            $activity['user_id']=$this->user['user_id'];
            $activity['activity_type']='Login';
            $this->ActivitiesModel->add_activity($activity);
                                    
    $data['success']=true;
    $data['message']='Logged out successfully';
} else {
    $data['success']=false;

    $data['message']='Cannot update token please contact Coodinator';
}


                   $data['success']=true;


                   $this->api->response($this->api->json($data), 200);
