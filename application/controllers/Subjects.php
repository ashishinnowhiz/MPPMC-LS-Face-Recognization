<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subjects extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('SubjectsModel');
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = '')
    {       $this->load->model('CoursesModel');
            $this->load->library('pagination');
            $config['base_url'] = base_url().'subjects/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $data['subjects'] = $this->SubjectsModel->get_subjects();
            $this->load->view('header');
            $this->load->view('subjects', $data);
            $this->load->view('footer');
    }
        
    public function evaluators()
    {
            $where=array();
        if (isset($_GET['date']) && $_GET['date']!='') {
            $date=$_GET['date'];
        }else{
			$date=date('Y-m-d', time());
		}
          //code written by Vikas
          if (isset($_GET['course']) && ($_GET['course']!='')) {
            $course = $_GET['course'];
        } else {
            $course='';
        }
       //code end here
            //$where['evaluation_time >']=$date;
            //$where['evaluation_time <']=$date." 23:59:59";
            $where['attendance_date']=$date;
          
            if($course != ""){
                $where['evaluators.course_code']=$course;//code written by vikas
            }

            $this->load->model('CoursesModel');
            $data['courses'] = $this->CoursesModel->get_courses();

            $data['reports'] = $this->SubjectsModel->get_evaluators($where);
            $data['date']=$date;
            $data['courseCode']=$course;//line written by vikas
            $this->load->view('header');
            $this->load->view('reports/subject_evals', $data);
            $this->load->view('footer');
    }
    public function dated_evaluators()
    {
            $where=array();
        if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
            $date_from=$_GET['date_from'];
        } else {
            $date_from=date('Y-m-d', strtotime("-30 days"));
        }
        if (isset($_GET['date_to']) && ($_GET['date_to']!='')) {
            $date_to=$_GET['date_to'];
        } else {
            $date_to=date('Y-m-d', time());
        }
         //code written by Vikas
         if (isset($_GET['course']) && ($_GET['course']!='')) {
            $course = $_GET['course'];
        } else {
            $course='';
        }
       //code end here
            $where['attendance_date >=']=$date_from;
            $where['attendance_date <=']=$date_to;
          
            if($course != ""){
                $where['evaluators.course_code']=$course;//code written by vikas
            }

            $this->load->model('CoursesModel');
            $data['courses'] = $this->CoursesModel->get_courses();

            $data['reports'] = $this->SubjectsModel->get_evaluators($where);
            $data['date_from']=$date_from;
            $data['date_to']=$date_to;
            $data['courseCode']=$course;//line written by vikas
            $this->load->view('header');
            $this->load->view('reports/consolidated_subject_evals', $data);
            $this->load->view('footer');
    }
    public function examiners()
    {
            $where=array();
        if (isset($_GET['date']) && $_GET['date']!='') {
            $date=$_GET['date'];
        }else{
			$date=date('Y-m-d', time());
		}
        //code written by Vikas
          if (isset($_GET['course']) && ($_GET['course']!='')) {
            $course = $_GET['course'];
        } else {
            $course='';
        }
       //code end here
            $where['attendance_date']=$date;
           if($course != ""){
            $where['examiners.course_code']=$course;//code written by vikas
           }
            $this->load->model('CoursesModel');
            $data['courses'] = $this->CoursesModel->get_courses();
            $data['reports'] = $this->SubjectsModel->get_examiners($where);
            $data['date']=$date;
            $data['courseCode']=$course;//line written by vikas
            $this->load->view('header');
            $this->load->view('reports/subject_examiners', $data);
            $this->load->view('footer');
    }
    public function dated_examiners()
    {
            $where=array();
        if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
            $date_from=$_GET['date_from'];
        } else {
            $date_from=date('Y-m-d', strtotime("-30 days"));
        }
        if (isset($_GET['date_to']) && ($_GET['date_to']!='')) {
            $date_to=$_GET['date_to'];
        } else {
            $date_to=date('Y-m-d', time());
        }
         //code written by Vikas
         if (isset($_GET['course']) && ($_GET['course']!='')) {
            $course = $_GET['course'];
        } else {
            $course='';
        }
           //code end here
            $where['attendance_date >=']=$date_from;
            $where['attendance_date <=']=$date_to;
             
            if($course !=""){
              $where['examiners.course_code']=$course;//code written by vikas
            }
            $this->load->model('CoursesModel');
            $data['courses'] = $this->CoursesModel->get_courses();

            $data['reports'] = $this->SubjectsModel->get_examiners($where);
            $data['date_from']=$date_from;
            $data['date_to']=$date_to;
            $data['courseCode']=$course;//line written by vikas
            $this->load->view('header');
            $this->load->view('reports/consolidated_subject_examiners', $data);
            $this->load->view('footer');
    }
}
