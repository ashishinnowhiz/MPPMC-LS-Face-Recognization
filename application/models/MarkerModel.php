	<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MarkerModel extends CI_Model
{
	 public function add_user($user)
    {
        if ($this->check_user_email($user['user_email'])>0) {
            $data['success']=false;
            $data['message']='user Code '.$user['user_email'].' Exist';
            return $data;
        }
                    $user['user_token']=md5(uniqid(rand(), true));
                    //$user['user_password']=md5($user['user_password']);
                    $user['user_password']=password_hash($user['user_password'], PASSWORD_DEFAULT);
        if ($this->db->insert('users', $user)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding User '.$user['user_email'];
            return $data;
        }
    }
	public function check_user_email($code, $except_id = '')
    {
        if ($except_id!='') {
            $this->db->where('user_id !=', $except_id);
        }
                    $this->db->where('user_email', $code);
                    $query = $this->db->get('users');
                    return $query->num_rows();
    }
	 public function add_evaluator($evaluator)
    {
        if ($this->check_evaluator_username($evaluator['evaluator_username'])>0) {
            $data['success']=false;
            $data['message']='evaluator username '.$evaluator['evaluator_username'].' Exist';
            return $data;
        }
                    $evaluator['evaluator_password']=password_hash($evaluator['evaluator_password'], PASSWORD_DEFAULT);
                    $evaluator['evaluator_updated_time']=date('Y-m-d H:i:s', time());
                    $evaluator['evaluator_updated_by']=2;
                    $evaluator['evaluator_created_by']=2;
        if ($this->db->insert('evaluators', $evaluator)) {
            $data['success']=true;
            $data['message']='Evaluator Added Successfully';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Evaluator';
            return $data;
        }
    }
	 public function check_evaluator_username($code, $except_id = '')
    {
        if ($except_id!='') {
            $this->db->where('evaluator_id !=', $except_id);
        }
                    $this->db->where('evaluator_username', $code);
                    $query = $this->db->get('evaluators');
                    return $query->num_rows();
    }
   public function get_sheets_count($where_array = '', $like_array = '')
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);

        }
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
        return $this->db->count_all_results('sheets');
    }
    
     public function get_active_script($user)
	 
    {				//$query=$this->db->query("SELECT * FROM sheets as S,subject_head as SH WHERE S.sheet_status='Assigned' and S.paper_code=SH.subject_code and SH.examiner_code='".$user."'");
                    $query=$this->db->query("SELECT * FROM sheets as S,examiner_course_subject as SH WHERE S.sheet_status='Assigned' and S.paper_code=SH.subject_code and SH.examiner_username='".$user."'");
					/* $this->db->where('sheet_status','Assigned');
                    $this->db->order_by('sheet_id','DESC');
                    $query = $this->db->get('sheets'); */
                    return $query->result_array();
    }    
    public function get_evaluation_count($user='')
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
                    $where = "(sheet_status='Checked' OR sheet_status='Rechecked')";
                    $this->db->where($where);
                    return $this->db->count_all_results('sheets');
    }
	 public function get_papers_count($user='')
    {
                    //$this->db->where('paper_deleted', '0');
                    return $this->db->count_all_results('papers');
    }
    // public function get_loggged_in_users($user='')

	public function get_loggged_in_users($user='',$Subjects_head_marker)
    {                    
                   // $where = "(user_token!='' OR user_login_attempts>=3)";
                   // $this->db->where($where);
                //    $query = $this->db->get('login_user');
                //    return $query->result_array();
                //code written By Vikas
                   $this->db->from('login_user u');
                   $this->db->join('evaluators e','u.user_email = e.evaluator_email');
                //    $this->db->join('examiners ex','u.user_email = ex.examiner_email');
                   $this->db->where_in('e.subject_code',$Subjects_head_marker);
                   //$this->db->where("e.course_code='".$course_code."' or ex.course_code='". $course_code."'");
                   $query = $this->db->get();
                   return $query->result_array();
                //Code End Here
    }
    //Code Written By Vikas
    public function get_deputy($where_array = '')
    {  
     	$this->db->where("e.examiner_designation","Head_Evaluator");
        $this->db->from('examiners e');
        $this->db->join('courses c','c.course_code = e.course_code');
        $this->db->join('examiner_course_subject ec','ec.course_code = e.course_code');
	   $this->db->where_in('ec.subject_code',$where_array);
	   $this->db->group_by('examiner_email');
	    $query =  $this->db->get();
       return $query->result_array();
    }
	 public function get_deputys($where_array = '')
    {  
     	$this->db->where("e.examiner_designation","Head_Evaluator");
        $this->db->from('examiners e,courses c');
        //$this->db->join('courses c','c.course_code = e.course_code');
        $this->db->join('examiner_course_subject ec','ec.course_code = c.course_code');
        $this->db->where('ec.examiner_username = e.examiner_username');
	   $this->db->where_in('ec.subject_code',$where_array);
	   $this->db->group_by('examiner_email');
	    $query =  $this->db->get();
       return $query->result_array();
    }
	 public function get_subject($where_array = '')
    {
        // $this->db->where($where_array);
        $this->db->where_in('subject_code',$where_array);
       $query =  $this->db->get('subjects');
       return $query->result_array();
    }
    public function get_evaluator($where_array = '')
    {
        // $this->db->where($where_array);
        $this->db->where_in('subject_code',$where_array);
       $query =  $this->db->get('evaluators');
       return $query->result_array();
    }
    //Code End Here
	public function get_reports($where_array = '')
    {
                    $select =   array(
                        'evaluators.*',
                        'subjects.subject_name',
                        'mediums.medium_name',
                        'SEC_TO_TIME(AVG(TIME_TO_SEC(attendance_login))) AS attendance_login',
                        'SEC_TO_TIME(AVG(TIME_TO_SEC(attendance_logout))) AS attendance_logout',
                        'SEC_TO_TIME(SUM(TIME_TO_SEC(attendance_hours))) AS hour_worked',
                        'attendance_user AS evaluator_code',
                        'sum(attendance_checked) as sheet_count',
                        'count(attendance_id) as day_count'
                    );
                    $this->db->select($select);
                    
      /*  if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('evaluators.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }*/
        //$this->db->where('subject_head.examiner_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
       // $this->db->where('evaluators.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        if ($where_array!='') {
            $this->db->where($where_array);
        }
                    
                    $this->db->group_by(array("evaluator_code"));
                    $this->db->from('attendance');
                    $this->db->join('evaluators', 'evaluators.evaluator_username = attendance.attendance_user');
                    //$this->db->join('attendance', 'attendance.attendance_user = sheets.evaluator_code','right');
                    $this->db->group_by(array("attendance_user"));
                    $this->db->join('subjects', 'subjects.subject_code = evaluators.subject_code');
                  // $this->db->join('subject_head', 'subject_head.subject_code = evaluators.subject_code');
                   $this->db->join('examiner_course_subject', 'examiner_course_subject.subject_code = evaluators.subject_code');
                    $this->db->join('mediums', 'mediums.medium_code = evaluators.medium_code');
                    $query = $this->db->get();
                    return $query->result_array();
    }
	 public function get_reports_heads($where_array = '')
    {
			//$this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
            $this->db->where('examiner_deleted', '0');
             $q = $this->db->get('examiners');
                    $select =   array(
                        'examiners.*',
                        'subjects.subject_name',
                        'SEC_TO_TIME(AVG(TIME_TO_SEC(attendance_login))) AS attendance_login',
                        'SEC_TO_TIME(AVG(TIME_TO_SEC(attendance_logout))) AS attendance_logout',
                        'SEC_TO_TIME(SUM(TIME_TO_SEC(attendance_hours))) AS hour_worked',
                        'sum(attendance_checked) as sheet_count',
                        'sum(under_checked) as under_checked',
                        'count(attendance_id) as day_count'
                    );
                    $this->db->select($select);
			
			
              foreach($q->result_array() as $result){
      
				$this->db->where('examiners.examiner_org', $result['examiner_org']);
			  }            
             
        if ($where_array!='') {
            $this->db->where($where_array);
        }
                    
                    $this->db->group_by(array("attendance_user"));
                    $this->db->from('attendance');
                    $this->db->join('examiners', 'examiners.examiner_username = attendance.attendance_user');
                    $this->db->join('subjects', 'subjects.subject_code = examiners.subject_code');
                    $query = $this->db->get();
                    return $query->result_array();
    }

    //Code Written By Vikas
    public function get_reports_datewise($where_array = '',$subjects_array = '')
    {
		
        $select =   array(
            'evaluators.evaluator_name',
            'evaluators.evaluator_username',
            'evaluators.subject_code',
            'evaluation.evaluation_date',
            'count(evaluators.evaluator_name) as sheet_count'
        );
    
        $this->db->select($select);

        if($where_array !=''){
            $this->db->where($where_array);
        }
        $this->db->from('evaluation');
        $this->db->where_in('evaluators.subject_code',$subjects_array);
        $this->db->where('sheets.sheet_status','Checked');
        $this->db->group_by(array("evaluation.evaluator_username","evaluation.evaluation_date"));
        $this->db->join('sheets', 'sheets.sheet_file = evaluation.sheet_file');
        $this->db->join('evaluators','evaluators.evaluator_username = evaluation.evaluator_username');
        $query = $this->db->get();
        
        return $query->result_array();
    }
    //Code End Here


	public function get_subjects()
    {
        
         $this->db->from('subjects');
        // $this->db->join('subject_head', 'subject_head.subject_code = subjects.subject_code');
		 $this->db->join('examiner_course_subject', 'examiner_course_subject.subject_code = subjects.subject_code');
		// $this->db->where('subject_head.examiner_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        $this->db->where('examiner_course_subject.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        $query = $this->db->get();
         return $query->result_array();
    }
	public function get_subject_sheets_count($course_code)
    {   //code edit by vikas
                    // $select =   array(
                    //     'subject_code',
                    //     'sheet_status',
                    //     'count(sheet_id) as Total'
                    // );

                    // $this->db->select($select);
                    // $this->db->group_by(array("subject_code", "sheet_status"));
                    // // $query = $this->db->get('sheets');
                    // $query = $this->db->get();
                    // return $query->result_array();

                    $select = array(
                        's.subject_code',
                        's.sheet_status',
                        'COUNT(s.sheet_id) AS Total'
                    );
                    
                    $this->db->from('sheets s');
                  //  $this->db->where('sub.course_code', $course_code);
                    $this->db->select($select);
                    $this->db->group_by(array("s.subject_code", "s.sheet_status"));
                    $this->db->join('subjects sub', 'sub.subject_code = s.subject_code');
                    $query = $this->db->get();
                    return $query->result_array();
    }
     public function get_checked_total_subject($date)
    {
                    $select =   array(
                        'subject_code',
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
                    $where = "(sheet_status='Checked' OR sheet_status='Rechecked' OR sheet_status='ReMarking') AND evaluation_date='".$date."'";
                    $this->db->where($where);
                    $this->db->group_by(array("subject_code"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
 
    //Code Written by Vikas Verma
    public function get_subj_head_eval($user_name){
        $this->db->select('subject_code');
        $this->db->where(array('examiner_username'=>$user_name));
       $query =  $this->db->get('examiner_course_subject');
       return $query->result_array();
    }
	public function get_subj_course_eval($user_name,$course){
        
        $this->db->select('s.subject_code,s.subject_name');
        $this->db->from('examiner_course_subject as ecs');
        $this->db->join('subjects as s','s.subject_code=ecs.subject_code');
        $this->db->where(array('ecs.examiner_username'=>$user_name,'ecs.course_code'=>$course));
		$query =  $this->db->get();
       return $query->result_array();
    }
	 public function get_course_head_eval($user_name){
       
        $this->db->select('c.course_code,c.course_name');
        $this->db->from('examiner_course_subject as ecs');
        $this->db->join('courses as c','c.course_code=ecs.course_code');
        $this->db->where(array('ecs.examiner_username'=>$user_name));
		$this->db->group_by('ecs.course_code');
       $query =  $this->db->get();
       return $query->result_array();
    }
    //Code End Here
}
