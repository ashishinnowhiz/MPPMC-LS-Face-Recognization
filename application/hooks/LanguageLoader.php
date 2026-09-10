<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LanguageLoader
{
    function initialize()
    {
        $ci =& get_instance();
        $ci->load->helper('language');
        $siteLang = $ci->session->userdata('site_lang');
        if ($siteLang) {
            $ci->lang->load('general', $siteLang);
        } else {
            $ci->lang->load('general', $ci->config->item('language'));
        }
    }
}
