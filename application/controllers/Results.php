<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Results extends CI_Controller
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
    public function page($page = 0)
    {
            
            $where=array();
            $like=array();
        if (isset($_GET['paper']) && ($_GET['paper']!='')) {
            $where['s.paper_code']=$_GET['paper'];
        }
        if (isset($_GET['sheet']) && ($_GET['sheet']!='')) {
            $like['e.sheet_file']=$_GET['sheet'];
        }
        if (isset($_GET['evaluator']) && ($_GET['evaluator']!='')) {
            $like['e.evaluator_code']=$_GET['evaluator'];
        }
        if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
            $where['e.evaluation_time >']=$_GET['date_from'];
        }
        if (isset($_GET['date_to']) && ($_GET['date_to']!='')) {
            $where['e.evaluation_time <']=$_GET['date_to']." 23:59:59";
        }
            // $this->load->library('pagination');
            // $config=$this->config->item('pagination_config');
            // $config['base_url'] = base_url().'results/page/';
            // $config['total_rows'] = $this->SheetsModel->get_result_count($where, $like);
            // $config['per_page'] = 10;
            // $config['num_links'] = 2;
            // $this->pagination->initialize($config);
            // $data['pagination']=$this->pagination->create_links();
            // $data['serial']=$page;
            // $limit['start']=$page;
            // $limit['per_page'] = $config['per_page'];
            // $data['total']=$config['total_rows'];
            // $data['sheets'] = $this->SheetsModel->get_results_data($limit, $where, $like);

            $data['sheets'] = $this->SheetsModel->get_results_data($where, $like);

            
            $this->load->model('PapersModel');
            $data['papers'] = $this->PapersModel->get_papers();

            
            $this->load->view('header');
            $this->load->view('results', $data);
            $this->load->view('footer');
    }
        
        
    function csv()
    {
            
        $sheets = $this->SheetsModel->get_results_data();
        $serial=0;
        $dataArray=array();
            
        //titles
        $data['S No']='Sr No';
        $data['Paper Code']='Paper Code';
        $data['Medium Code']='Medium Code';
        $data['Head Marker']='Head Marker';
        $data['Marker']='Marker';
        $data['Marks']='Marks';
        $data['Final Marks']='Final Marks';
        $data['Duration']='Duration in Minutes';
        $data['Marking Date']='Marking Date';
        $data['Recheck Duration']='Recheck Duration in Minutes';
        $data['Recheck Time']='Recheck Time';
        $data['Status']='Status';
        array_push($dataArray, $data);
        //end of titles
        foreach ($sheets as $sheet) {
            $serial=$serial+1;
            /* $diff=strtotime($sheet['evaluation_time'])-strtotime($sheet['sheet_assign_time']);
            $min=intval($diff/60);
            $sec=$diff%60; */
            if (($sheet['recheck_time']==null) or ($sheet['recheck_time']=='0000-00-00 00:00:00')) {
                                                $recheck_duration='NA';
            } else {
                $recheck_duration=$sheet['recheck_duration'];
            }
                                            $data['S No']=$serial;
                                            $data['Paper Code']=$sheet['paper_code'];
                                            $data['Medium Code']=$sheet['medium_code'];
                                            $data['Head Marker']=$sheet['examiner_username'];
                                            $data['Marker']=$sheet['evaluator_code'];
                                            $data['Marks']=$sheet['evaluation_marks'];
                                            $data['Final Marks']=$sheet['final_marks'];
                                            $data['Duration']=$sheet['check_duration'];
                                            $data['Marking Date']=$sheet['evaluation_time'];
                                            $data['Recheck Duration']=$recheck_duration;
                                            $data['Recheck Time']=$sheet['recheck_time'];
                                            $data['Status']=$sheet['sheet_status'];
                                            array_push($dataArray, $data);
        }
                                            
            //print_r($dataArray);
            $name = 'Result'.$this->config->item('exam')['exam_code'].date('d-m-Y', time()).'.csv';
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"".$name."\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            $handle = fopen('php://output', 'w');

        foreach ($dataArray as $row) {
            fputcsv($handle, $row);
        }
            fclose($handle);
            exit;
    }
}
