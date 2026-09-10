<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mediums extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('MediumsModel');
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = '')
    {
            $this->load->library('pagination');
            $config['base_url'] = base_url().'mediums/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $data['mediums'] = $this->MediumsModel->get_mediums();
            $this->load->view('header');
            $this->load->view('mediums', $data);
            $this->load->view('footer');
    }
}
