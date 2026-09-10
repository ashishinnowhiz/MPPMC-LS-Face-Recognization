<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Language extends CI_Controller
{

    public function __construct()
    {
                parent::__construct();
    }
    public function index()
    {
    }
    
    public function change($language)
    {
        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('site_lang', $language);
        
        redirect($_SERVER['HTTP_REFERER']);
    }
}
