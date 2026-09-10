<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evaluators extends CI_Controller
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
    public function page($page = '')
    {       $this->load->model('SubjectsModel');
            $this->load->library('pagination');
            $config['base_url'] = base_url().'evaluators/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $this->load->model('CentersModel');
            $data['centers'] = $this->CentersModel->get_centers();
            $data['evaluators'] = $this->EvaluatorsModel->get_evaluators();
            
            $this->load->view('header');
            $this->load->view('evaluators', $data);
            $this->load->view('footer');
    }

    public function edit($evaluator_id)
    {
            $this->load->model('SubjectsModel');
            $data['subjects'] = $this->SubjectsModel->get_subjects();
            
            $this->load->model('MediumsModel');
            $data['mediums'] = $this->MediumsModel->get_mediums();
            
            $this->load->model('ExaminersModel');
            $data['examiners'] = $this->ExaminersModel->get_examiners();
            
            
            $data['evaluator']=$this->EvaluatorsModel->get_evaluator($evaluator_id);
            $this->load->view('forms/edit_evaluator', $data);
    }
    public function editlimit($evaluator_id)
    {
            $data['evaluator']=$this->EvaluatorsModel->get_evaluator($evaluator_id);
            $limit=$this->EvaluatorsModel->get_evaluator_limit($data['evaluator']['evaluator_username']);

        if (is_array($limit) && sizeof($limit)>0) {
            $data['limit']=$limit['limit_updated_value'];
        } else {
            $this->load->model('SettingsModel');
            $setting = $this->SettingsModel->get_setting('config');
            $config=json_decode($setting['setting_json']);
            $data['limit']=$config->dailylimit;
        }
            $this->load->view('forms/limit_evaluator', $data);
    }
    
    //Code Written By vikas
    public function viewSubjectList($evaluator_id){
        $this->load->model('SubjectsModel');
        $this->load->model('EvaluatorsModel');
        //$data['evaluator']=$this->EvaluatorsModel->get_evaluator($evaluator_id);
      //  $evaluator_username = $data['evaluator']['evaluator_username'];
        $data['marker_subjects'] = $this->EvaluatorsModel->get_marker_subject_by_id($evaluator_id);
        // echo $this->db->last_query();
       // print_r( $data['marker_subjects']);
        $this->load->view('forms/evaluator_subject_list',$data);
    }
    //Code End Here

    public function limit()
    {
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('limit', 'Limit', 'numeric');
                    $this->form_validation->set_rules('remark', 'Remark', 'alpha_numeric_spaces');
        if ($this->form_validation->run() === true) {
            $eval=$this->EvaluatorsModel->get_evaluator_byname($this->input->post('user'));
            $limit=$this->input->post('limit');
            $limit_update['evaluator_username']=$this->input->post('user');
            $limit_update['limit_updated_value']=$limit;
            $limit_update['limit_update_remark']=$this->input->post('remark');
            $response=$this->EvaluatorsModel->add_limit($limit_update);
            if (!$response['success']) {
                $this->session->set_flashdata('error', $response['message']);
            } else {
                $this->session->set_flashdata('success', 'Data Updated Succesfully');
            }
            $activity['activity_type']='Update';
            $activity['activity_detail']=json_encode($evaluator);
            $this->ActivitiesModel->add_activity($activity);
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
                    redirect('evaluators');
    }
    public function update()
    {
                    $evaluator['evaluator_name']=$this->input->post('name');
                    //$evaluator['evaluator_username']=$this->input->post['username'];
                    $evaluator['evaluator_email']=$this->input->post('email');
                    $evaluator['evaluator_password']=$this->input->post('password');
                    $evaluator['evaluator_phone']=$this->input->post('phone');
                    $evaluator['examiner_username']=$this->input->post('head');
                    $evaluator['subject_code']=$this->input->post('subject');
                    $evaluator['medium_code']=$this->input->post('medium');
                    $evaluator_id=$this->input->post('id');
                    
                    $response=$this->EvaluatorsModel->update_evaluator($evaluator_id, $evaluator);
        if (!$response['success']) {
            $this->session->set_flashdata('error', $response['message']);
        } else {
            $this->session->set_flashdata('success', 'Data Updated Succesfully');
        }
                    redirect('evaluators');
    }

    //code written by vikas 
    public function update_default(){
        error_reporting(E_ALL);
 ini_set('display_errors', 1);

        $this->load->model('EvaluatorsModel');
        $username = $this->input->post('username');
        $code = $this->input->post('code');
       // echo $username." ".$code;
        $result = $this->EvaluatorsModel->update_default_subject($username,$code);
        if($result==true){
             $this->session->set_flashdata('success','Default Subject Updated Successfully.');
        }else{
              $this->session->set_flashdata('error','Error Updating Default Subject.');
        }
        redirect('welcome');
    }
    //code end here
}
