<?php 

class courses extends CI_Controller {

    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('CoursesModel');
                $this->load->library('form_validation');
    }

    public function index(){

        $this->page();

    }

    public function page($page = '')
    {
            $this->load->library('pagination');
            $config['base_url'] = base_url().'courses/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $data['courses'] = $this->CoursesModel->get_parent_course();
            $this->load->view('header');
            $this->load->view('courses', $data);
            $this->load->view('footer');
    }

     public function branches(){
        $this->load->model("SubjectsModel");
        $data['parents'] = $this->CoursesModel->get_parent_course();
        $data['courses'] = $this->SubjectsModel->get_courses();
        // echo "<pre>";
        // print_r($data['courses']);
        $this->load->view('header');
        $this->load->view('branches', $data);
        $this->load->view('footer');

    }
   
}

?>