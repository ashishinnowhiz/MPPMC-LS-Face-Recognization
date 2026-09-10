<?php
defined('BASEPATH') or exit('No direct script access allowed');

if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");

    $this->api->response($this->api->json($error), 400);
}

include('Api/include/getsheet.php');
