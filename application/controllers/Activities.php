<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activities extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = 0)
    {
        
            $where=array();
            $like=array();
        if (isset($_GET['user']) && ($_GET['user']!='')) {
            $where['user_id']=$_GET['user'];
        }
        if (isset($_GET['activity']) && ($_GET['activity']!='')) {
            $where['activity_type']=$_GET['activity'];
        }
        if (isset($_GET['url']) && ($_GET['url']!='')) {
            $like['activity_url']=$_GET['url'];
        }
            $this->load->library('pagination');
            $config=$this->config->item('pagination_config');
            $config['base_url'] = base_url().'activities/page/';
            $config['total_rows'] = $this->ActivitiesModel->get_activities_count($where, $like);
            $config['per_page'] = 10;
            $config['num_links'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            $data['serial']=$page;
            $limit['start']=$page;
            $limit['per_page'] = $config['per_page'];
            $data['count']=$config['total_rows'];
            $data['activities'] = $this->ActivitiesModel->get_activities($limit, $where, $like);
            
            $this->load->view('header');
            $this->load->view('activities', $data);
            $this->load->view('footer');
    }
}
