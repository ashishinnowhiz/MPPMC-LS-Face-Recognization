<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Recheck_m extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('SheetsModel');
    }
    public function index()
    {
            $this->page();
    }
	 public function viewscript()
    {
		if(isset($_GET['date']) && $_GET['date']!=""){
			 $ext = pathinfo($_GET['script_file'], PATHINFO_EXTENSION);
			 $path_parts = pathinfo($_GET['script_file']);
			 
		
$data['url']=base_url()."answersheets/uploads/".$_GET['date']."/".$path_parts['filename']."_checked.".$ext;
		}
		redirect("answersheets/uploads/".$_GET['date']."/".$path_parts['filename']."_checked.".$ext);
            //$this->load->view('pdfscript',$data);
    }
    public function page($page = 0)
    {
        
            $where=array();
            $like=array();
        if (isset($_GET['evaluator']) && ($_GET['evaluator']!='')) {
            $where['examiner_username']=$_GET['evaluator'];
        }
            $data['date']=date('Y-m-d', time());
            $where['sheet_status']='Rechecked';
            $where['marker_evaluation_marks']=0;
            /*
            if(isset($_GET['day']) && ($_GET['day']!='')){
                if($_GET['day']=='yesterday'){
                    $where['evaluation_date']=date('Y-m-d',strtotime('-1 days'));
                }
            }
            */
        if (isset($_GET['date']) && ($_GET['date']!='')) {
            $where['evaluation_date']=$_GET['date'];
            $data['date']=$where['evaluation_date'];
        } else {
            $where['evaluation_date']=$data['date'];
        }
            
        if (isset($_GET['sheet']) && ($_GET['sheet']!='')) {
            $like['sheet_file']=$_GET['sheet'];
        }
            
            $this->load->library('pagination');
            $config['base_url'] = base_url().'recheck_m/page/';
            $config['total_rows'] = $this->SheetsModel->get_sheets_count_m($where, $like);
			//echo $this->SheetsModel->get_sheets_count_m($where, $like);
		
            $config['per_page'] = 1000;
            
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
            $config['reuse_query_string'] = true;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            $data['serial']=$page;
            $limit['start']=$page;
            $limit['per_page'] = $config['per_page'];
            
            $data['sheets'] = $this->SheetsModel->get_sheets_m($limit, $where, $like);
            
            $this->load->model('ExaminersModel');
            $data['evaluators'] = $this->ExaminersModel->get_examiners_m();
            
            $this->load->view('header');
            $this->load->view('recheck_m', $data);
            $this->load->view('footer');
    }
    public function updateSheet()
    {
        $id = $this->input->get('id');
        $val = $this->input->get('val');
        $remarks = $this->input->get('remarks');

        $data = ['remark' => $val,'remark_remarks' => $remarks,'marker_username'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_name']];
        
                        $activity['activity_type']='Update';
                        $activity['activity_data']=json_encode($data);
                        $this->ActivitiesModel->add_activity($activity);
        //print_r($data);exit;
        $res = $this->SheetsModel->update_sheet($id, $data);
        echo json_encode($res);
    }
}
