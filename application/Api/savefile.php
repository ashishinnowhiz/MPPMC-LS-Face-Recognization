<?php


defined('BASEPATH') or exit('No direct script access allowed');

if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");
    $this->api->response($this->api->json($error), 400);
}

if ($this->user['user_role']=='Head_Evaluator' or $this->user['user_role']=='Evaluator') {
    $path='answersheets/uploads/'.date('Y-m-d', time());
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
        //chmod($path, 0777);
    }
    $filename='answersheets/uploads/'.date('Y-m-d', time()).'/'.$this->user['user_name']."_".$_FILES["file"]["name"];
    if (!file_exists($filename)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename)) {
            chmod($filename, 0666);
            $data = array('success' => true, "message" => "Uploaded Successfully");
        } else {
            $data = array('success' => false, "message" => "Error uploading");
        }
    } else {
        $data = array('success' => false, "message" => "Already Uploaded");
    }
}

                   $this->api->response($this->api->json($data), 200);
