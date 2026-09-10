<?php


defined('BASEPATH') or exit('No direct script access allowed');



if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");
    $this->api->response($this->api->json($error), 400);
}

if ($this->user['user_role']=='Head_Evaluator' or $this->user['user_role']=='Evaluator') {
    $this->output
        ->set_content_type('application/pdf')
        ->set_output(read_file($this->api->_request['url']));
}
