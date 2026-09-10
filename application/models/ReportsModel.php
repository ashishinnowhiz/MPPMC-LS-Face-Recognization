<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportsModel extends CI_Model
{

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
                    
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('evaluators.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
                    
        if ($where_array!='') {
            $this->db->where($where_array);
        }
                    
                    $this->db->group_by(array("evaluator_code"));
                    $this->db->from('attendance');
                    $this->db->join('evaluators', 'evaluators.evaluator_username = attendance.attendance_user');
                    //$this->db->join('attendance', 'attendance.attendance_user = sheets.evaluator_code','right');
                    $this->db->group_by(array("attendance_user"));
                    $this->db->join('subjects', 'subjects.subject_code = evaluators.subject_code');
                    $this->db->join('mediums', 'mediums.medium_code = evaluators.medium_code');
                    $query = $this->db->get();
                    return $query->result_array();
    }
    public function evaluator_reports($date, $evaluator , $condition = "")
    {
                    $select =   array(
                        // 'sheets.sheet_id',
'sheets.subject_code',
                        'evaluation.sheet_file',
                        'evaluation.sheet_assign_time',
                        'evaluation.evaluation_date',
                        'evaluation.evaluation_type',
                        'TIMEDIFF(evaluation.evaluation_time,evaluation.sheet_assign_time) AS check_duration'
                    );
                    $key = $this->config->item('encryption_key');
                    $this->db->select("AES_DECRYPT(evaluation.evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);
                    $this->db->distinct();
                    $this->db->select($select);
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('evaluation.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
                    $this->db->where('evaluator_username', $evaluator);
                  //code written by vikas
                  if($condition != ""){
                    $this->db->where($condition);
                }else{
                //$this->db->where('sheets.sheet_status !=', 'Unallocated');
                 $where = "(evaluation.evaluation_time>='".$date." 00:00:00' AND evaluation.evaluation_time<='".$date." 23:59:59')";
                //$where = "(evaluation.evaluation_date>'".$date." 00:00:00' AND evaluation.evaluation_date<'".$date." 23:59:59')";
                $this->db->where($where);
                
                }
                  //code end here
                    $this->db->from('evaluation');
                    $this->db->join('sheets', 'sheets.sheet_file = evaluation.sheet_file');
                    $this->db->where("sheets.sheet_file !='Rejected'");
                    $query = $this->db->get();
  //                   print_r($this->db->last_query());
//            die;

                    return $query->result_array();
    }

    //Code Written By Vikas Verma 
    public function evaluator_reports_export($evaluator )
    {
                    $select =   array(
'sheets.subject_code',
                        'evaluation.sheet_file',
                        'evaluation.sheet_assign_time',
                        'evaluation.evaluation_date',
                        'evaluation.evaluation_type',
                        'TIMEDIFF(evaluation.evaluation_time,evaluation.sheet_assign_time) AS check_duration'
                    );
                    $key = $this->config->item('encryption_key');
                    $this->db->select("AES_DECRYPT(evaluation.evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);
                    $this->db->distinct();
                    $this->db->select($select);
                    $this->db->where('evaluator_username', $evaluator);
                    $this->db->from('evaluation');
$this->db->join('sheets', 'sheets.sheet_file = evaluation.sheet_file');
                    $query = $this->db->get();
                    return $query->result_array();
    }
    //Code End Here
    
    public function evaluator_stats($date, $evaluator, $where_state='')
    {
                    $key = $this->config->item('encryption_key');
                    $this->db->select("(AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512)))) AS evaluation_marks", false);
                    /*
                    $this->db->select("ROUND(Avg(AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512)))),2) AS sheet_avg",FALSE);
                    $this->db->select("Min(AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512)))) AS sheet_min",FALSE);
                    $this->db->select("Max(AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512)))) AS sheet_max",FALSE);
                    $select =   array(
                        'count(evaluation_id) as sheet_count'
                    );
                    $this->db->select($select);
                    */
                    $this->db->where('evaluator_username', $evaluator);
                    //$this->db->where('sheet_status','Checked');
                    //code written b vikas
                    if($where_state != ""){
                        $this->db->where($where_state);
                    }else{
                        $this->db->where('evaluation_date', $date);
                    }
                    //code end here
                    //$where = "(evaluation.evaluation_time>'".$date." 00:00:00' AND evaluation.evaluation_time<'".$date." 23:59:59')";
                    //$this->db->where($where);
                    //$this->db->order_by('evaluation_time DESC');
                    $query = $this->db->get('evaluation');
                    $result=$query->result_array();
                    $marks=array();
        foreach ($result as $row) {
            $marks[]=$row['evaluation_marks'];
        }
                    /*
                    $stats=$query->row_array();
                    if($stats['sheet_min']>$stats['sheet_max']){
                        $tmp=$stats['sheet_min'];
                        $stats['sheet_min']=$stats['sheet_max'];
                        $stats['sheet_max']=$tmp;
                    }
                    */
        if (sizeof($marks)>0) {
            $stats['sheet_min']=min($marks);
            $stats['sheet_max']=max($marks);
            $stats['sheet_count']=sizeof($marks);
            $avg=(array_sum($marks))/sizeof($marks);
            $stats['sheet_avg']=(floor($avg*100))/100;
        } else {
            $stats['sheet_min']=0;
            $stats['sheet_max']=0;
            $stats['sheet_count']=0;
            $stats['sheet_avg']=0;
        }
                    return $stats;
    }
    
    public function get_reports_heads($where_array = '')
    {
                    $select =   array(
                        'examiners.*',
                        'SEC_TO_TIME(AVG(TIME_TO_SEC(attendance_login))) AS attendance_login',
                        'SEC_TO_TIME(AVG(TIME_TO_SEC(attendance_logout))) AS attendance_logout',
                        'SEC_TO_TIME(SUM(TIME_TO_SEC(attendance_hours))) AS hour_worked',
                        'sum(attendance_checked) as sheet_count',
                        'sum(under_checked) as under_checked',
                        'count(attendance_id) as day_count'
                    );
                    $this->db->select($select);
                    
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('examiners.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
                    
        if ($where_array!='') {
            $this->db->where($where_array);
        }
                    
                    $this->db->group_by(array("attendance_user"));
                    $this->db->from('attendance');
                    $this->db->join('examiners', 'examiners.examiner_username = attendance.attendance_user');
                    $query = $this->db->get();
                    return $query->result_array();
    }
    public function get_reports_atcs($where_array = '')
    {
                    $select =   array(
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
        if ($where_array!='') {
            $this->db->where($where_array);
        }
                    
                    $where = "(sheet_status='Checked' OR sheet_status='Rechecked')";
                    $this->db->where($where);
                    
                    $query = $this->db->get('sheets');
                    return $query->row_array();
    }
    public function head_reports($date, $examiner, $rechecked = false)
    {
                    $select =   array(
                        'sheet_file','paper_code',
                        'sheet_status',
                        'TIMEDIFF(recheck_time,recheck_assign_time) AS recheck_duration'
                    );
                    
                    $key = $this->config->item('encryption_key');
                    $this->db->select("AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(head_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS head_evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(re_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS re_evaluation_marks", false);
                    
        if ($rechecked) {
            $this->db->where('head_json_marks!=', '');
        }
                    $this->db->select($select);
                    $this->db->where('examiner_username', $examiner);
                    $this->db->where('evaluation_date', $date);
                    $this->db->where('sheet_status', 'Rechecked');
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }

    //Code Written By Vikas
    public function get_marker_reports_datewise($where_array = '',$evaluator="")
    {
                    $select =   array(
                        'evaluators.*',
                        'subjects.subject_name',
                        'evaluation.evaluation_date',
                        'count(evaluators.evaluator_username) as data_count'
                    );
                    $this->db->select($select);
                    
      
                    if($evaluator !=""){
                        $this->db->where('evaluators.evaluator_username ' ,$evaluator );
                    }        
                    if ($where_array!='') {
                            $this->db->where($where_array);
                        }
						
                     $this->db->where("(sheets.sheet_status='Checked' or sheets.sheet_status='Rechecked' or sheets.sheet_status='ReMarking')" );                
                    $this->db->group_by(array("sheets.evaluator_code","sheets.subject_code","evaluation.evaluation_date"));
                    $this->db->from('evaluation');
                    $this->db->join('sheets', 'sheets.sheet_file = evaluation.sheet_file');
                    $this->db->join('evaluators', 'evaluators.evaluator_username = evaluation.evaluator_username');
                    $this->db->join('subjects', 'subjects.subject_code = sheets.subject_code');
                    $query = $this->db->get();
					//echo $this->db->last_query();
                    return $query->result_array();
    }

    public function export_marker_reports($evaluator="")
    {
                    $this->db->select('evaluators.evaluator_name AS Name,
                                        evaluators.evaluator_username AS UserName,
                                        evaluators.evaluator_phone as Mobile,
                                        subjects.subject_name as SubjectName,
                                        subjects.subject_code as SubjectCode,
                                        b.course_name as BranchName,
                                        b.course_code as BranchCode,
                                        c.course_name as CourseName,
                                        c.course_code as CourseCode,
                                        count(evaluators.evaluator_username) as SheetCount,
                                        evaluation.evaluation_date as EvaluationDate');
                  
                    if($evaluator !=""){
                        $this->db->where('evaluators.evaluator_username ' ,$evaluator );
                    }
                    $this->db->where("(sheets.sheet_status='Checked' or sheets.sheet_status='Rechecked' or sheets.sheet_status='ReMarking')" );                
                    $this->db->group_by(array("sheets.evaluator_code","sheets.subject_code","evaluation.evaluation_date"));
                    $this->db->from('evaluation');
                    $this->db->join('sheets', 'sheets.sheet_file = evaluation.sheet_file');
                    $this->db->join('evaluators', 'evaluators.evaluator_username = evaluation.evaluator_username');
                    $this->db->join('subjects', 'subjects.subject_code = sheets.subject_code');
                    $this->db->join('courses b', 'b.course_code = subjects.course_code');
                    $this->db->join('courses c', 'b.parent = c.course_code');
                    $query = $this->db->get();
                  // echo $this->db->last_query();
                    return $query->result_array();
    }
    //Code and Here

    public function head_stats($date, $examiner)
    {
                    $key = $this->config->item('encryption_key');
                    $this->db->select("(AES_DECRYPT(head_evaluation_marks,UNHEX(SHA2('".$key."',512)))) AS evaluation_marks", false);
                    /*
                    $this->db->select("ROUND(Avg(AES_DECRYPT(head_evaluation_marks,UNHEX(SHA2('".$key."',512)))),2) AS sheet_avg",FALSE);
                    $this->db->select("Min(AES_DECRYPT(head_evaluation_marks,UNHEX(SHA2('".$key."',512)))) AS sheet_min",FALSE);
                    $this->db->select("Max(AES_DECRYPT(head_evaluation_marks,UNHEX(SHA2('".$key."',512)))) AS sheet_max",FALSE);

                    $select =   array(
                        'count(sheet_id) as sheet_count'
                    );
                    */
                    $this->db->where('head_json_marks!=', '');
                    //$this->db->select($select);
                    $this->db->where('examiner_username', $examiner);
                    $this->db->where('evaluation_date', $date);
                    $query = $this->db->get('sheets');
                    //$stats=$query->row_array();
                    $result=$query->result_array();
                    $marks=array();
        foreach ($result as $row) {
            $marks[]=$row['evaluation_marks'];
        }
        if (sizeof($marks)>0) {
            $stats['sheet_min']=min($marks);
            $stats['sheet_max']=max($marks);
            $stats['sheet_count']=sizeof($marks);
            $avg=(array_sum($marks))/sizeof($marks);
            $stats['sheet_avg']=(floor($avg*100))/100;
        } else {
            $stats['sheet_min']=0;
            $stats['sheet_max']=0;
            $stats['sheet_count']=0;
            $stats['sheet_avg']=0;
        }
                    return $stats;
    }
    
    public function center_reports($date)
    {
                    $select =   array(
                        'sheet_file',
                        'evaluator_code',
                        'examiner_username',
                        'sheet_status',
                        'sheet_remarks'
                    );
                    
                    $key = $this->config->item('encryption_key');
                    $this->db->select("AES_DECRYPT(final_marks,UNHEX(SHA2('".$key."',512))) AS final_marks", false);
                    $this->db->select("AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(head_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS head_evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(re_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS re_evaluation_marks", false);
                    
                    
                    $this->db->select($select);
                    $where = "(sheet_status='Checked' OR sheet_status='Rejected' OR  sheet_status='Rechecked')";
                    $this->db->where($where);
                    $this->db->where('evaluation_date', $date);
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function center_stats($date)
    {
                    $key = $this->config->item('encryption_key');
                    $this->db->select("Avg(AES_DECRYPT(final_marks,UNHEX(SHA2('".$key."',512)))) AS sheet_avg", false);
                    $this->db->select("Min(AES_DECRYPT(final_marks,UNHEX(SHA2('".$key."',512)))) AS sheet_min", false);
                    $this->db->select("Max(AES_DECRYPT(final_marks,UNHEX(SHA2('".$key."',512)))) AS sheet_max", false);
                    
                    
                    $select =   array(
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
                    $where = "(sheet_status='Checked' OR sheet_status='Rejected' OR  sheet_status='Rechecked')";
                    $this->db->where($where);
                    $this->db->where('evaluation_date', $date);
                    $query = $this->db->get('sheets');
                    return $query->row_array();
    }
    
    public function get_summary_reports($date)
    {
                    $select =   array(
                        'evaluator_code',
                        'sheet_status',
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
                    $where = "(sheet_status='Checked' OR sheet_status='Rechecked')";
                    $this->db->where($where);
                    
                    $this->db->group_by(array("evaluator_code"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function get_checked_total_subject($date)
    {
                    $select =   array(
                        'subject_code',
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
                    //Added sheet_status = 'ReMarking' by Vikas
                    $where = "(sheet_status='Checked' OR sheet_status='Rechecked' OR sheet_status='ReMarking') AND evaluation_date='".$date."'";
                    $this->db->where($where);
                    $this->db->group_by(array("subject_code"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    
    public function get_reject_reports_evalauator($date)
    {
                    $select =   array(
                        'evaluator_code',
                        'sheet_status',
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
                    $this->db->where('sheet_status', 'Rejected');
                    $this->db->group_by(array("evaluator_code"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    
    public function get_reject_reports($date)
    {
                    $select =   array(
                        'subject_code',
                        'sheet_status',
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
                    $this->db->where('sheet_status', 'Rejected');
                    $this->db->group_by(array("subject_code"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function get_reject_sheets($by, $status)
    {
        if ($status != "" && $by != "") {
            if ($by == "subject") {
                $this->db->where('subject_code', $status);
            } if ($by == "remarks") {
                $this->db->where('sheet_remarks', $status);
            }
        }

                    $this->db->where('sheet_status', 'Rejected');
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function get_reject_reports_by_reason($date)
    {
                    $select =   array(
                        'sheet_remarks',
                        'sheet_status',
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
                    $this->db->where('sheet_status', 'Rejected');
                    $this->db->group_by(array("sheet_remarks"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    
    public function get_subject_sheets_count()
    {
                    $select =   array(
                        'subject_code',
                        'sheet_status',
                        'count(sheet_id) as Total'
                    );
                    $this->db->select($select);
                    $this->db->group_by(array("subject_code", "sheet_status"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }

    //code written by vikas
    public function get_user_info($id){
       
        $this->db->where('user_id',$id);

        $query = $this->db->get('users');

        return $query->result_array();

    }
    //code end here
}
