<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SubjectsModel extends CI_Model
{

    public function get_subjects()
    {
                    $this->db->where('subject_deleted', '0');
                    $query = $this->db->get('subjects');
                    return $query->result_array();
    }
    
    public function get_subject($id)
    {
                    $this->db->where('subject_id', $id);
                    $query = $this->db->get('subjects');
                    return $query->row_array();
    }
      //vikas code on 06/04/23
      public function get_subjects_course_wise($course_code){
        $this->db->where_in('course_code',$course_code);
        $this->db->order_by('subject_id', 'DESC');
        $query = $this->db->get('subjects');
        return $query->result_array();
    }
    //code end here

    /*code writen by vikas on 05/04/2023*/
    public function get_courses()
    {
                    //$this->db->where('course_deleted', '0');
                    $this->db->order_by('course_id', 'DESC');
                    $this->db->where("parent !=","");
                    $query = $this->db->get('courses');
                    return $query->result_array();
    }
    public function get_course_name($course_code){
        $query = $this->db->select('course_name')->where('course_code',$course_code)->get('courses');
        return $query->result_array();
    }
/*code end here*/
    public function add_subject($subject)
    {
        if ($this->check_subject_code($subject['subject_code'])>0) {
			
			
			$this->db->where('subject_code', $subject['subject_code']);
			$this->db->update('subjects', $subject);
			
            $data['success']=false;
            $data['message']="Subject Code ".$subject['subject_code']." Already Exist";
            return $data;
        }
        if ($this->db->insert('subjects', $subject)) {
            $data['success']=true;
            $data['message']='Subject Added Successfully';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Subject';
            return $data;
        }
    }
   
    //code written by vikas
    public function get_subject_code_wise($code){
        $this->db->where('subject_code',$code);
        $query = $this->db->get('subjects');
        return $query->result_array();
    }
    //code end here
    
    public function check_subject_code($code)
    {
                    $this->db->where('subject_code', $code);
                    $query = $this->db->get('subjects');
                    return $query->num_rows();
    }
    public function get_evaluators($where_array = '')
    {
                    $select =   array(
                        'evaluators.*',
                        'subjects.subject_name',
                        'mediums.medium_name',
                    );
                    $this->db->select($select);
                    
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('evaluators.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
                    
        if ($where_array!='') {
            $this->db->where($where_array);
        }
                    $this->db->group_by(array("attendance_user"));
                    $this->db->order_by('subjects.subject_code', 'mediums.medium_code');
                    
                    $this->db->from('attendance');
                    $this->db->join('evaluators', 'evaluators.evaluator_username = attendance.attendance_user');
                    $this->db->join('subjects', 'subjects.subject_code = evaluators.subject_code');
                    $this->db->join('mediums', 'mediums.medium_code = evaluators.medium_code');
                    
                    $query = $this->db->get();
                    return $query->result_array();
    }
    // public function get_examiners($where_array = '')
    // {
    //                 $select =   array(
    //                     'subject_examiners.*',
    //                     'subjects.subject_name',
    //                     'mediums.medium_name',
    //                 );
    //                 $this->db->select($select);
                    
    //     if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_examiner') {
    //         $this->db->where('subject_examiners.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
    //     }
                    
    //     if ($where_array!='') {
    //         $this->db->where($where_array);
    //     }
    //                 $this->db->group_by(array("subjects.subject_code","mediums.medium_code"));
    //                 $this->db->order_by('subjects.subject_code', 'mediums.medium_code');
                    
    //                 $this->db->from('attendance');
    //                 $this->db->join('subject_examiners', 'subject_examiners.examiner_username = attendance.attendance_user');
    //                 $this->db->join('subjects', 'subjects.subject_code = subject_examiners.subject_code');
    //                 $this->db->join('mediums', 'mediums.medium_code = subject_examiners.medium_code');
    //                 $query = $this->db->get();
    //                 return $query->result_array();
    // }

    //code written by vikas
 
    public function get_examiners($where_array = '')
    {
        $select = array(
            'examiner_course_subject.*',
            'subjects.subject_name',
            'examiners.center_code',
            'examiners.examiner_name'
        );
        $this->db->select($select);
                        
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] == 'Head_examiner') {
            $this->db->where('examiner_course_subject.examiner_username', $_SESSION['user_name']);
        }
                        
        if ($where_array != '') {
            $this->db->where($where_array);
        }
                        
        $this->db->group_by(array("subjects.subject_code", "examiners.center_code"));
        $this->db->order_by('subjects.subject_code', 'examiners.center_code');
                        
        $this->db->from('attendance');
        $this->db->join('examiner_course_subject', 'examiner_course_subject.examiner_username = attendance.attendance_user');
        $this->db->join('subjects', 'subjects.subject_code = examiner_course_subject.subject_code');
        $this->db->join('examiners', 'examiners.examiner_username = examiner_course_subject.examiner_username');
    
        $query = $this->db->get();
        return $query->result_array();
    }
    

    public function get_subject_by_username($username){
        $this->db->where('evaluator_username',$username);
        $query = $this->db->get('marker_subjects');
        if($query->num_rows()){
            return $query->result_array();
        }else{
            return false;
        }
    }

    public function get_subjects_in($subjects)
    {
                    //$this->db->where('subject_deleted', '0');
					 $this->db->where_in('subject_code',$subjects);
                    $this->db->order_by('subject_id', 'DESC');
                    $query = $this->db->get('subjects');
                    return $query->result_array();
    }
    //code end here
}
