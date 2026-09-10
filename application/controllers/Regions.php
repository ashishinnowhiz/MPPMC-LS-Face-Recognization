<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Regions extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('RegionsModel');
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = '')
    {
            $this->load->library('pagination');
            $config['base_url'] = base_url().'regions/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $data['regions'] = $this->RegionsModel->get_regions();
            $this->load->view('header');
            $this->load->view('regions', $data);
            $this->load->view('footer');
    }
}
