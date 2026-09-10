<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Limits extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('EvaluatorsModel');
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = 0)
    {
            $this->load->library('pagination');
            $config['base_url'] = base_url().'limits/page/';
            $config['total_rows'] = $this->EvaluatorsModel->get_limits_count();
            $config['per_page'] = 10;
            
            $config['num_links'] = 2;
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="paginate_button active"><a>';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            $data['serial']=$page;
            $limit['start']=$page;
            $limit['per_page'] = $config['per_page'];
            
            $data['limit_updates'] = $this->EvaluatorsModel->get_limit_updates($limit);
            
            $this->load->view('header');
            $this->load->view('limit_updates', $data);
            $this->load->view('footer');
    }
}
