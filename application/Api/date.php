<?php


defined('BASEPATH') or exit('No direct script access allowed');




                   $data['date']=date('d-m-Y', time());


                   $data['success']=true;


                   $this->api->response($this->api->json($data), 200);
