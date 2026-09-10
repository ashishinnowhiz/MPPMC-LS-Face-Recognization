<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SheetsModel extends CI_Model
{
	 public function sheet_search($limit = '',$search)
    {				
		$this->db->like('sheet_file',$search);
		$this->db->or_like('sheet_status',$search);
		$this->db->or_like('region_code',$search);
		if ($limit!='') {
            $this->db->limit($limit['per_page'], $limit['start']);
        }
		$this->db->order_by('sheet_id','DESC');
        $query = $this->db->get('sheets');
        return $query->result_array();
    }
	 public function sheet_search_count($search)
    {				
		$this->db->like('sheet_file',$search);
		$this->db->or_like('sheet_status',$search);
		$this->db->or_like('region_code',$search);
        return $this->db->count_all_results('sheets');
    }
	 public function get_active_script()
    {				
					$this->db->where('sheet_status','Assigned');
                    $this->db->order_by('sheet_id','DESC');
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function get_all_script($where=[])
    {				
					if(!empty($where)){
                        $this->db->where($where);
                    }
                    $this->db->order_by('sheet_id','DESC');
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function get_sheets($limit = '', $where_array = '', $like_array = '')
    {
		  $s=$this->db->get_where('examiner_course_subject',array('examiner_username'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_name']))->result_array();
			$subject=array();

			  $key = $this->config->item('encryption_key');
                    
                    $this->db->select("sheets.*");
                    $this->db->select("AES_DECRYPT(final_marks,UNHEX(SHA2('".$key."',512))) AS final_marks", false);
                    
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
           // $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
			
			foreach($s as $sresult){
				$subject[]=$sresult['subject_code'];
			}
		   $this->db->where_in('subject_code', $subject); 
        }
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
        if ($limit!='') {
            $this->db->limit($limit['per_page'], $limit['start']);
        }
                    $this->db->order_by('sheet_id','DESC');
                    $query = $this->db->get('sheets');
					//print_r($this->db->last_query());    die;
                    return $query->result_array();
    }
	public function get_sheets_m($limit = '', $where_array = '', $like_array = '')
    {
		//echo $this->db->last_query();
		
		 $s=$this->db->get_where('examiner_course_subject',array('examiner_username'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_name']))->result_array();
			$subject=array();
      
			foreach($s as $sresult){
				$subject[]=$sresult['subject_code'];
			}
			
                    $key = $this->config->item('encryption_key');
                    
                    $this->db->select("sheets.*");
                    $this->db->select("AES_DECRYPT(final_marks,UNHEX(SHA2('".$key."',512))) AS final_marks", false);
                    
      
		
		   $this->db->where_in('subject_code', $subject);
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
		
        if ($limit!='') {
            $this->db->limit($limit['per_page'], $limit['start']);
        }
                    $this->db->order_by('sheet_id','DESC');
                    $query = $this->db->get('sheets');
					//echo $this->db->last_query();
                    return $query->result_array();
    }
	 public function get_sheets_count_m($where_array = '', $like_array = '')
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            //$this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
		$s=$this->db->get_where('examiner_course_subject',array('examiner_username'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_name']))->result_array();
			$subject=array();
      
			foreach($s as $sresult){
				$subject[]=$sresult['subject_code'];
			}
		   $this->db->where_in('subject_code', $subject);
		
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
		
                    return $this->db->count_all_results('sheets');
    }
    public function get_rejected()
    {
                    $select =   array(
                        'evaluation_date',
                        'subjects.subject_code',
                        'mediums.medium_code',
                        'subjects.subject_name',
                        'mediums.medium_name',
                        'sheet_file',
                        'sheet_remarks'
                    );
                    $this->db->select($select);
                    if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
                        $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
                    }
                    $this->db->where('sheet_status', 'Rejected');
                    $this->db->from('sheets');
                    $this->db->join('subjects', 'subjects.subject_code = sheets.subject_code');
                    $this->db->join('mediums', 'mediums.medium_code = sheets.medium_code');
                    $query = $this->db->get();
                    return $query->result_array();
    }
	
    public function get_sheets_count($where_array = '', $like_array = '')
    {
		$s=$this->db->get_where('examiner_course_subject',array('examiner_username'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_name']))->result_array();
			$subject=array();
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            //$this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
			
			foreach($s as $sresult){
				$subject[]=$sresult['subject_code'];
			}
		   $this->db->where_in('subject_code', $subject);
	   }
        if ($where_array!='') {
            $this->db->where($where_array);
        }
        if ($like_array!='') {
            $this->db->like($like_array);
        }
		//$this->db->count_all_results('sheets');
		//print_r($this->db->last_query());    die;
                    return $this->db->count_all_results('sheets');
    }
    public function get_datewise($where_array = '')
    {
        if (isset($where_array['sheet_status']) && ($where_array['sheet_status']=='pending')) {
            $where_array="(sheet_status='Pending')";//Code Added By Vikas
            $column='sheet_date';
            $column_select='DATE_FORMAT(sheet_time,"%Y-%m-%d") AS sheet_date';
        } else {
            $column='evaluation_date';
            $column_select='evaluation_date';
        }
                    
                    $select =   array(
                        $column_select,
                        'subjects.subject_code',
                        'mediums.medium_code',
                        'subjects.subject_name',
                        'mediums.medium_name',
                        'count(sheet_id) as sheet_count'
                    );
                    $this->db->select($select);
                    if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
                        $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
                    }
                    if ($where_array!='') {
                        $this->db->where($where_array);
                    }
                    
                    $this->db->from('sheets');
                    $this->db->join('subjects', 'subjects.subject_code = sheets.subject_code');
                    $this->db->join('mediums', 'mediums.medium_code = sheets.medium_code');
                    $this->db->group_by(array($column,"subject_code","medium_code"));
                    $this->db->order_by($column, "subject_code", "medium_code");
                    $query = $this->db->get();
                    
                    return $query->result_array();
    }
    public function get_result_count($where_array = '', $like_array = '')
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
                    $where = "(sheet_status='Checked' OR  sheet_status='Rechecked')";
                    $this->db->where($where);
                    return $this->db->count_all_results('sheets');
    }
    public function get_evaluation_count()
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
                    $where = "(sheet_status='Checked' OR sheet_status='Rechecked'  OR sheet_status='ReMarking')";
                    $this->db->where($where);
                    return $this->db->count_all_results('sheets');
    }
    public function get_center_sheets_count($center_code = '')
    {
                    $select =   array(
                        'center_code',
                        'sheet_status',
                        'count(sheet_id) as Total'
                    );
                    $this->db->select($select);
                    if ($center_code!='') {
                        $this->db->where('center_code', $center_code);
                    }
                    $this->db->group_by(array("center_code", "sheet_status"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function get_examiner_sheets_count($center_code = '')
    {
                    $select =   array(
                        'examiner_username',
                        'sheet_status',
                        'count(sheet_id) as Total'
                    );
                    $this->db->select($select);
                    if ($center_code!='') {
                        $this->db->where('center_code', $center_code);
                    }
                    $this->db->group_by(array("examiner_username", "sheet_status"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    
    public function get_center_status($center_code = '')
    {
                    $select =   array(
                        'paper_code',
                        'center_code',
                        'sheet_status',
                        'count(sheet_id) as Total'
                    );
                    $this->db->select($select);
                    if ($center_code!='') {
                        $this->db->where('center_code', $center_code);
                    }
                    $this->db->group_by(array("paper_code", "sheet_status"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    
    public function get_paper_sheets_count()
    {
                    $select =   array(
                        'paper_code',
                        'sheet_status',
                        'count(sheet_id) as Total'
                    );
                    $this->db->select($select);
                    if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
                        $this->db->where('center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
                    }
                    $this->db->group_by(array("paper_code", "sheet_status"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    
    public function get_sheet($id)
    {
                    $this->db->where('sheet_id', $id);
                    $query = $this->db->get('sheets');
                    return $query->row_array();
    }
    public function get_sheet_by_file($file)
    {
                    $this->db->where('sheet_file', $file);
                    $query = $this->db->get('sheets');
                    return $query->row_array();
    }
     public function get_checked_sheet($file)
    {
                    $this->db->where('sheet_file', $file);
                    $query = $this->db->get('sheets');
                    return $query->row_array();
    }
    public function get_evaluation_sheet($evaluator, $where_array)
    {
                    $sheet=$this->get_file($evaluator['evaluator_username']);
        if ($sheet==null) {
            $this->db->select('sheets.sheet_file');
            $this->db->where('sheet_status', 'Pending');
            $this->db->where('evaluation.evaluator_username', $evaluator['evaluator_username']);
            $this->db->from('sheets');
            $this->db->join('evaluation', 'sheets.sheet_file = evaluation.sheet_file');
            $query = $this->db->get();
            $result = $query->result_array();
            $done=array();
            foreach ($result as $row) {
                $done[]=$row['sheet_file'];
            }
            $sheet['sheet_status']='Assigned';
           // $sheet['examiner_username']=$evaluator['examiner_username'];
            $sheet['evaluator_code']=$evaluator['evaluator_username'];
            $sheet['sheet_assign_time']=date('Y-m-d H:i:s', time());
            $this->db->where($where_array);
            $this->db->where('sheet_status', 'Pending');
            if (sizeof($done)>0) {
                $this->db->where_not_in('sheet_file', $done);
            }
            $this->db->where('sheet_synced', 1);
            $this->db->limit(1);
            if ($this->db->update('sheets', $sheet)) {
                $sheet=$this->get_file($evaluator['evaluator_username']);
                return $sheet;
            } else {
                return null;
            }
        } else {
            return $sheet;
        }
    }
    
    public function unasssign_sheet($evaluator_code, $sheet = array())
    {
                       
						
					$this->db->where('evaluator_code', $evaluator_code);
					$this->db->where('sheet_status', 'Assigned');
					if($sheetResult=$this->db->get('sheets')){
						
						 foreach($sheetResult->result_array() as $sheetData){
							 $this->db->where('sheet_file', $sheetData['sheet_file']);
							 $this->db->delete('eval');
						 }
						 
						 
							if ($evaluator_code!='All') {	
								$this->db->where('evaluator_code', $evaluator_code);
							}
							 $sheet['sheet_status']='Pending';
							 $sheet['examiner_username']='';
							$sheet['evaluator_code']='';
							$this->db->where('sheet_status', 'Assigned');
							if ($this->db->update('sheets', $sheet)) {
								return true;
							} else {
								return false;
							}
					}else{
						return false;
					}
						
       
    }
    public function get_file($evaluator)
    {
                    $this->db->select('sheet_file,allocation_id,sheets.medium_code,sheets.paper_code,sheets.subject_code,sheet_status,sheet_assign_time,examiner_username,paper_model_question, paper_model_answer,paper_total_marks, sheet_eval, sheet_json_marks');
                    $this->db->where('evaluator_code', $evaluator);
                    $this->db->where('sheet_status', 'Assigned');
                    $this->db->limit(1);
                    $this->db->from('sheets');
                    $this->db->join('papers', 'papers.paper_code = sheets.paper_code');
                    $query = $this->db->get();
                    return $query->row_array();
    }
    
    public function get_checked_file($examiner)
    {
                        $this->db->select('sheet_file,allocation_id,medium_code,paper_code,subject_code,sheet_status,evaluation_date,recheck_assign_time AS sheet_assign_time,evaluator_code');
                        $this->db->where('examiner_username', $examiner);
                        $this->db->where('sheet_status', 'Checked');
                        $this->db->where('head_evaluation_marks', 0);
                        $this->db->where('recheck', 1);
                        $this->db->where('recheck_remarks!=','');
                        //$this->db->order_by('rand()');
                        $this->db->limit(1);
                        $query = $this->db->get('sheets');
                        $sheet=$query->row_array();
						
			if($sheet==null){			
                        $this->db->select('sheet_file,allocation_id,medium_code,paper_code,subject_code,sheet_status,evaluation_date,recheck_assign_time AS sheet_assign_time,evaluator_code');
                        $this->db->where('examiner_username', $examiner);
                        $this->db->where('sheet_status', 'Checked');
                        $this->db->where('head_evaluation_marks', 0);
                        $this->db->where('recheck', 1);
                        $this->db->order_by('rand()');
                        $this->db->limit(1);
                        $query = $this->db->get('sheets');
                        $sheet=$query->row_array();
			}
			return $sheet;
    }
    public function get_re_evaluation_sheet($examiner)
    {
                    $sheet=$this->get_checked_file($examiner);
					
        if ($sheet==null) {
			$subject=array();
            $sheet['recheck']=1;
            $sheet['recheck_assign_time']=date('Y-m-d H:i:s', time());
            $sheet['examiner_username']=$examiner;
			$s=$this->db->get_where('examiner_course_subject',array('examiner_username'=>$examiner))->result_array();
			
			foreach($s as $sresult){
				$subject[]=$sresult['subject_code'];
			}
			
            //$this->db->select('sheet_file,allocation_id,medium_code,paper_code,subject_code,sheet_status,evaluation_date');
           // $this->db->where('examiner_username', $examiner);
		   $this->db->where_in('subject_code', $subject);
            $this->db->where('sheet_status', 'Checked');
            $this->db->where('head_evaluation_marks', 0);
            $this->db->where('recheck', 0);
            //$this->db->where('evaluation_date',date('Y-m-d',time()));
            $this->db->order_by('rand()');
            $this->db->limit(1);
                        
            if ($this->db->update('sheets', $sheet)) {
                $sheet=$this->get_checked_file($examiner);
                return $sheet;
            } else {
				
               return null;
            }
        } else {
            if ($sheet['sheet_assign_time']==null) {
                $data['recheck_assign_time']=date('Y-m-d H:i:s', time());
                $this->db->where('sheet_file', $sheet['sheet_file']);
                $this->db->limit(1);
                if ($this->db->update('sheets', $data)) {
                    $sheet['sheet_assign_time']=$data['recheck_assign_time'];
                } else {
                   return null;
                }
            }
            return $sheet;
        }
    }
	public function get_re_marking_sheet($examiner)
    {
                    $sheet=$this->get_re_checked_file($examiner);
        if ($sheet==null) {
			//echo $examiner;
			$subject=array();
			//$this->db->select('subject_code');
			$s=$this->db->get_where('examiner_course_subject',array('examiner_username'=>$examiner))->result_array();
			foreach($s as $sresult){
				$subject[]=$sresult['subject_code'];
			}
			//print_r($subject);die;
            $sheet['remark']=1;
            $sheet['marker_username']=$examiner;
            $sheet['remark_assign_time']=date('Y-m-d H:i:s', time());
            //$this->db->select('sheet_file,allocation_id,medium_code,paper_code,subject_code,sheet_status,evaluation_date');
            //$this->db->where('marker_username', $examiner);
            $this->db->where_in('subject_code', $subject);
            $this->db->where('sheet_status', 'Rechecked');
            $this->db->where('marker_evaluation_marks', 0);
            $this->db->where('remark', 0);
            //$this->db->where('evaluation_date',date('Y-m-d',time()));
            $this->db->order_by('rand()');
            $this->db->limit(1);
                        
            if ($this->db->update('sheets', $sheet)) {
                $sheet=$this->get_re_checked_file($examiner);
                return $sheet;
            } else {
                return null;
            }
        } else {
            if ($sheet['sheet_assign_time']==null) {
                $data['remark_assign_time']=date('Y-m-d H:i:s', time());
                $this->db->where('sheet_file', $sheet['sheet_file']);
                $this->db->limit(1);
                if ($this->db->update('sheets', $data)) {
                    $sheet['sheet_assign_time']=$data['remark_assign_time'];
                } else {
                    return null;
                }
            }
			//print_r($sheet);
            return $sheet;
        }
    }
	public function get_re_checked_file($examiner)
    {
                        $this->db->select('sheet_file,allocation_id,medium_code,paper_code,subject_code,sheet_status,evaluation_date,remark_assign_time AS sheet_assign_time');
                        $this->db->where('marker_username', $examiner);
                        $this->db->where('sheet_status', 'Rechecked');
                        $this->db->where('marker_evaluation_marks', 0);
                        $this->db->where('remark', 1);
                        $this->db->where('remark_remarks!=','');
                        //$this->db->order_by('rand()');
                        $this->db->limit(1);
                        $query = $this->db->get('sheets');
                        $sheet=$query->row_array();
						
			if($sheet==null){			
                        $this->db->select('sheet_file,allocation_id,medium_code,paper_code,subject_code,sheet_status,evaluation_date,remark_assign_time AS sheet_assign_time');
                        $this->db->where('marker_username', $examiner);
                        $this->db->where('sheet_status', 'Rechecked');
                        $this->db->where('marker_evaluation_marks', 0);
                        $this->db->where('remark', 1);
                        $this->db->order_by('rand()');
                        $this->db->limit(1);
                        $query = $this->db->get('sheets');
                        $sheet=$query->row_array();
			}
			return $sheet;
    }
    public function add_sheet($sheet)
    {
        if ($this->check_sheet($sheet['sheet_file'], $sheet['allocation_id'])>0) {
            $data['success']=false;
            $data['message']=$sheet['sheet_file'].' Sheet File Duplicate';
            return $data;
        }
        if ($this->db->insert('sheets', $sheet)) {
            $data['success']=true;
            $data['message']=$sheet['sheet_file'].' Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']=$sheet['sheet_file'].' Error Adding Data';
            return $data;
        }
    }
    public function update_evaluation_sheet($user_id, $data)
    {
                    
                    $marks=$data['evaluation_marks'];
                    unset($data['evaluation_marks']);
                    $sheet=$data;
                    $sheet['sheet_status']='Checked';
                    $sheet['sheet_updated_time']=date('Y-m-d H:i:s', time());
                    $sheet['sheet_updated_by']=$user_id;
                    
                    $key = $this->config->item('encryption_key');
                    $this->db->set('evaluation_marks', "AES_ENCRYPT('{$marks}',UNHEX(SHA2('".$key."',512)))", false);
                    $this->db->set('final_marks', "AES_ENCRYPT('{$marks}',UNHEX(SHA2('".$key."',512)))", false);
                    //unset($sheet['roll_no']);
                    
                    $this->db->where('sheet_file', $data['sheet_file']);
                    $this->db->where('sheet_status', 'Assigned');
                    
                    $this->db->limit(1);
        if ($this->db->update('sheets', $sheet)) {
            $rows_affected=$this->db->affected_rows();
            //$this->add_attendance_checked($evaluator);
            //$this->add_under_checked($evaluator);
            //$this->add_attendance_checked($data['examiner_username'],'under_checked');
            return $rows_affected;
        } else {
            return false;
        }
    }
        
    public function reject_sheet2($data)
    {
                    $sheet['sheet_status']='Rejected';
                    $sheet['sheet_remarks']=$data['sheet_remarks'];
        if (isset($data['reject_reason'])) {
            $sheet['reject_reason']=$data['reject_reason'];
        }

                    $sheet['evaluation_time']=date('Y-m-d H:i:s', time());
                    $sheet['evaluation_date']=date('Y-m-d', time());
                    
                    $this->db->where('sheet_file', $data['sheet_file']);
                   // $this->db->where('evaluator_code', $evaluator);
                    $this->db->where('sheet_status', 'Pending');
                    
                    $this->db->limit(1);
        if ($this->db->update('sheets', $sheet)) {
            //$this->add_attendance_checked($evaluator);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    public function reject_sheet($evaluator, $data)
    {
                    $sheet['sheet_status']='Rejected';
                    $sheet['sheet_remarks']=$data['sheet_remarks'];
        if (isset($data['reject_reason'])) {
            $sheet['reject_reason']=$data['reject_reason'];
        }

                    $sheet['evaluation_time']=date('Y-m-d H:i:s', time());
                    $sheet['evaluation_date']=date('Y-m-d', time());
                    
                    $this->db->where('sheet_file', $data['sheet_file']);
                    $this->db->where('evaluator_code', $evaluator);
                    $this->db->where('sheet_status', 'Assigned');
                    
                    $this->db->limit(1);
        if ($this->db->update('sheets', $sheet)) {
            //$this->add_attendance_checked($evaluator);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
	public function reject_sheet_auto($file, $allocation_id)
    {
            $sheet['sheet_status']='Rejected';
            $sheet['sheet_remarks']='Rejected By System';
            $sheet['reject_reason']="Page Count issue.";
       
            $this->db->where('sheet_file', $file);
            $this->db->where('allocation_id', $allocation_id);
                    
            $this->db->limit(1);
        if ($this->db->update('sheets', $sheet)) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    public function update_re_evaluation_sheet($examiner, $data, $user_id)
    {
                    $sheet['sheet_status']='Rechecked';
                    $sheet['recheck_time']=date('Y-m-d H:i:s', time());
                    $sheet['evaluation_date']=date('Y-m-d', time());
                    //$sheet['head_evaluation_marks']=$data['evaluation_marks'];
                    //$sheet['final_marks']=$data['evaluation_marks'];
                    $sheet['head_json_marks']=$data['sheet_json_marks'];
                    $sheet['sheet_updated_time']=date('Y-m-d H:i:s', time());
                    $sheet['sheet_updated_by']=$user_id;
                    
                    $key = $this->config->item('encryption_key');
                    $this->db->set('head_evaluation_marks', "AES_ENCRYPT('{$data['evaluation_marks']}',UNHEX(SHA2('".$key."',512)))", false);
                    $this->db->set('final_marks', "AES_ENCRYPT('{$data['evaluation_marks']}',UNHEX(SHA2('".$key."',512)))", false);
                    //unset($sheet['roll_no']);
                    
                    $this->db->where('sheet_file', $data['sheet_file']);
                    $this->db->where('examiner_username', $examiner);
                    $this->db->where('sheet_status', 'Checked');
                    $this->db->limit(1);
        if ($this->db->update('sheets', $sheet)) {
            //$this->add_attendance_checked($examiner);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
	public function update_re_marking_sheet($examiner, $data, $user_id)
    {
                    $sheet['sheet_status']='ReMarking';
                    $sheet['remark_time']=date('Y-m-d H:i:s', time());
                    $sheet['evaluation_date']=date('Y-m-d', time());
                    //$sheet['head_evaluation_marks']=$data['evaluation_marks'];
                    //$sheet['final_marks']=$data['evaluation_marks'];
                    $sheet['marker_json_marks']=$data['sheet_json_marks'];
                    $sheet['sheet_updated_time']=date('Y-m-d H:i:s', time());
                    $sheet['sheet_updated_by']=$user_id;
                    
                    $key = $this->config->item('encryption_key');
                    $this->db->set('marker_evaluation_marks', "AES_ENCRYPT('{$data['evaluation_marks']}',UNHEX(SHA2('".$key."',512)))", false);
                    $this->db->set('final_marks', "AES_ENCRYPT('{$data['evaluation_marks']}',UNHEX(SHA2('".$key."',512)))", false);
                    //unset($sheet['roll_no']);
                    
                    $this->db->where('sheet_file', $data['sheet_file']);
                    $this->db->where('marker_username', $examiner);
                    $this->db->where('sheet_status', 'Rechecked');
                    $this->db->limit(1);
        if ($this->db->update('sheets', $sheet)) {
            //$this->add_attendance_checked($examiner);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    public function update_synced_sheet($sheet, $allocation_id)
    {
                    $sheet_data['sheet_synced']=1;
                    $this->db->where('allocation_id', $allocation_id);
                    $this->db->where('sheet_file', $sheet);
        if ($this->db->update('sheets', $sheet_data)) {
            //$this->update_allocation_file_synced($allocation_id);
            return true;
        } else {
            return false;
        }
    }
    public function update_sheet($sheet_id, $sheet)
    {
                    $this->db->where('sheet_id', $sheet_id);
        if ($this->db->update('sheets', $sheet)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function check_sheet($code, $allocation_id)
    {
                    $this->db->where('allocation_id', $allocation_id);
                    $this->db->where('sheet_file', $code);
                    $query = $this->db->get('sheets');
                    return $query->num_rows();
    }
    
    public function check_log($log_id)
    {
                    $this->db->where('allocation_id', $log_id);
                    $query = $this->db->get('allocation_log');
                    return $query->num_rows();
    }
    
    
    public function region_stat()
    {
                    $this->db->select('region_code, COUNT(sheet_id) as scanned');
                    $this->db->group_by('region_code');
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    
    public function add_sync_log($log)
    {
        if ($this->db->insert('scan_log', $log)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_allocation($limit = '')
    {
                    $this->db->select('a.*, COUNT(s.sheet_id) AS total');
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
            $this->db->where('a.center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
        }
        if ($limit!='') {
            $this->db->limit($limit['per_page'], $limit['start']);
        }
                    $this->db->where('a.allocation_type', 'Allocation');
                    $this->db->from('allocation_log AS a');
                    $this->db->join('sheets AS s', 's.allocation_id = a.allocation_id');
                    $this->db->group_by("s.allocation_id");
                    $this->db->order_by('s.allocation_id', 'DESC');
                    $query = $this->db->get();
                    return $query->result_array();
    }
    public function get_allocation_list($limit = '', $status = '', $where_array = '')
    {
        if ($status!='') {
            $this->db->where('allocation_sync_status', $status);
        }
        if ($limit!='') {
            $this->db->limit($limit['per_page'], $limit['start']);
        }
        if ($where_array!='') {
            $this->db->where($where_array);
        }			
					$this->db->order_by('allocation_id','DESC');
                    $query = $this->db->get('allocation_log');
                    return $query->result_array();
    }
    public function get_allocation_details($allocation_id)
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
            $this->db->where('center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
        }
                    $this->db->where('allocation_id', $allocation_id);
                    $query = $this->db->get('allocation_log');
                    return $query->row_array();
    }
    public function get_allocated_sheets($allocation_id, $synced = '')
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
            $this->db->where('center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
        }
        if ($synced!='') {
            $this->db->where('sheet_synced', $synced);
        }
                    $this->db->where('allocation_id', $allocation_id);
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function update_allocation_log($allocation_id, $log)
    {
                    $allocation['allocation_log_details']=json_encode($log);
                    //$allocation['allocation_sync_status']='Synced';
                    
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
            $this->db->where('center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
        }
                    $this->db->where('allocation_id', $allocation_id);
        if ($this->db->update('allocation_log', $allocation)) {
            $this->update_allocation_file_synced($allocation_id);
            return true;
        } else {
            return false;
        }
    }
    public function update_allocation_qty($allocation_id, $qty)
    {
                    $allocation['allocation_quantity']=$qty;
                    $allocation['allocation_sync_status']='Synced';
                    $this->db->where('allocation_id', $allocation_id);
        if ($this->db->update('allocation_log', $allocation)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_allocation_file($allocation_id, $file)
    {
                    $allocation['allocation_file']=$file;
                    $this->db->where('allocation_id', $allocation_id);
        if ($this->db->update('allocation_log', $allocation)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_allocation_file_synced($allocation_id, $count = '')
    {
                    $this->db->where('sheet_synced', 1);
                    $this->db->where('allocation_id', $allocation_id);
                    $allocation['allocation_synced_files']=$this->db->count_all_results('sheets');
                    
                    $row=$this->get_allocation_details($allocation_id);
        if ($row['allocation_quantity']==$allocation['allocation_synced_files']) {
            $allocation['allocation_sync_status']='Synced';
        }
                    
                    $this->db->where('allocation_id', $allocation_id);
        if ($this->db->update('allocation_log', $allocation)) {
            return $allocation['allocation_synced_files'];
        } else {
            return false;
        }
    }
    public function update_allocation_download($allocation_id, $coulmn)
    {
                    $allocation[$coulmn]=time();
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
            $this->db->where('center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
        }
                    $this->db->where('allocation_id', $allocation_id);
        if ($this->db->update('allocation_log', $allocation)) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    public function allocate($center_code, $paper_code, $quantity = 0)
    {
                    $log['allocation_type']='Allocation';
                    $log['paper_code']=$paper_code;
                    $log['center_code']=$center_code;
                    $log['allocation_quantity']=$quantity;
                    $log['allocation_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                    
                    $sheet['allocation_id']=$this->allocation_log($log);
                    $sheet['sheet_status']='Allocated';
                    $sheet['center_code']=$center_code;
                    $this->db->where('sheet_status', 'Scanned');
                    $this->db->where('paper_code', $paper_code);
                    $this->db->limit($quantity);
        if ($this->db->update('sheets', $sheet)) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    public function unallocate($paper_code)
    {
                    $log['allocation_sync_status']='Unallocated';
                    $this->db->where('allocation_sync_status', 'Pending');
                    $this->db->where('paper_code', $paper_code);
                    $this->db->update('allocation_log', $log);
                    
                    $sheet['sheet_status']='Unallocated';
                    $this->db->where('sheet_status', 'Pending');
                    $this->db->where('paper_code', $paper_code);
        if ($this->db->update('sheets', $sheet)) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    public function allocation_log($log)
    {
        if ($this->check_log($log->allocation_id)>0) {
            if ($log->allocation_type=='Allocation') {
                $update['allocation_sync_status']='Pending';
            } else {
                $update['allocation_sync_status']='Synced';
            }
            $update['allocation_mode']=$log->allocation_mode;
            $update['allocation_file']=$log->allocation_file;
            $this->db->where('allocation_id', $log->allocation_id);
            if ($this->db->update('allocation_log', $update)) {
                return true;
            } else {
                return false;
            }
        }
        if ($this->db->insert('allocation_log', $log)) {
            return true;
        } else {
            return false;
        }
    }
    public function assign($paper_code, $examiner_id, $quantity)
    {
                    //$sheet['allocation_id']=$this->allocation_log($center_code,$paper_code,$quantity,'Allocation');
                    $sheet['sheet_status']='Allocated';
                    $sheet['examiner_username ']=$examiner_id;
                    $this->db->where('sheet_status', 'Scanned');
                    $this->db->where('paper_code', $paper_code);
                    $this->db->limit($quantity);
        if ($this->db->update('sheets', $sheet)) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }

    public function get_results()
    {
                    $select =   array(
                        'sheet_status',
                        'count(sheet_id) as Total',
                        'evaluation_date'
                    );
                    $this->db->select($select);
                    
                    $where = "(sheet_status='Checked' OR sheet_status='Rejected' OR  sheet_status='Rechecked' OR sheet_status='ReMarking')";
                    $this->db->where($where);
                    
                    $this->db->group_by(array("evaluation_date"));
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    // public function get_results_data($limit = '', $where_array = '', $like_array = '')
    // {
    //                 $select =   array(
    //                     'sheet_file',
    //                     'paper_code',
    //                     'medium_code',
    //                     'region_code',
    //                     'center_code',
    //                     'examiner_username',
    //                     'marker_username',
    //                     'evaluator_code',
    //                     'evaluation_date',
    //                     'sheet_status',
    //                     'evaluation_time',
    //                     'sheet_assign_time',
    //                     'recheck_assign_time',
    //                     'remark_assign_time',
    //                     'recheck_time',
    //                     'remark_time',
    //                     'TIMEDIFF(evaluation_time,sheet_assign_time) AS check_duration',
    //                     'TIMEDIFF(recheck_time,recheck_assign_time) AS recheck_duration',
    //                     'TIMEDIFF(remark_time,remark_assign_time) AS remark_duration'
    //                 );
    //                 if ($where_array!='') {
    //                     $this->db->where($where_array);
    //                 }
    //                 if ($like_array!='') {
    //                     $this->db->like($like_array);
    //                 }
    //                 $key = $this->config->item('encryption_key');
                    
    //                 if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
    //                     $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
    //                 }
    //                 $this->db->select("AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);
    //                 $this->db->select("AES_DECRYPT(head_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS head_evaluation_marks", false);
    //                 $this->db->select("AES_DECRYPT(marker_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS marker_evaluation_marks", false);
    //                 $this->db->select("AES_DECRYPT(final_marks,UNHEX(SHA2('".$key."',512))) AS final_marks", false);
                    
    //                 $this->db->select($select);
                    
                    
    //                 if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
    //                     $this->db->where('center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
    //                 }
    //                 if ($limit!='') {
    //                     $this->db->limit($limit['per_page'], $limit['start']);
    //                 }
    //                 $where = "(sheet_status='Checked' OR sheet_status='Rechecked' OR sheet_status='ReMarking')";
    //                 $this->db->where($where);
                    
    //                 $query = $this->db->get('sheets');
	// 				//echo  $this->db->last_query();
    //                 return $query->result_array();
    // }

    public function get_results_data($limit = '', $where_array = '', $like_array = '')
    {
                    $select =   array(
                        'e.sheet_file',
                        's.paper_code',
                        's.medium_code',
                        's.region_code',
                        's.center_code',
                        'e.examiner_username',
                        'e.evaluator_username as marker_username',
                        's.evaluator_code',
                        'e.evaluation_date',
                        's.sheet_status',
                        'e.evaluation_time',
                        'e.sheet_assign_time',
                        's.recheck_assign_time',
                        's.remark_assign_time',
                        's.recheck_time',
                        's.remark_time',
                        'TIMEDIFF(e.evaluation_time,e.sheet_assign_time) AS check_duration',
                        'TIMEDIFF(s.recheck_time,s.recheck_assign_time) AS recheck_duration',
                        'TIMEDIFF(s.remark_time,s.remark_assign_time) AS remark_duration'
                    );
                    $this->db->from("evaluation e");
                    if ($where_array!='') {
                        $this->db->where($where_array);
                    }
                    if ($like_array!='') {
                        $this->db->like($like_array);
                    }
                    $key = $this->config->item('encryption_key');
                    
                    if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
                        $this->db->where('e.examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
                    }
                    $this->db->select("AES_DECRYPT(s.evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(s.head_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS head_evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(s.marker_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS marker_evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(s.final_marks,UNHEX(SHA2('".$key."',512))) AS final_marks", false);
                    
                    $this->db->select($select);
                    
                    
                    if ($_SESSION[$this->config->item('exam')['exam_session']]['user_center']!='') {
                        $this->db->where('s.center_code', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
                    }
                    if ($limit!='') {
                        $this->db->limit($limit['per_page'], $limit['start']);
                    }
                    $where = "(s.sheet_status='Checked' OR s.sheet_status='Rechecked' OR s.sheet_status='ReMarking')";
                    $this->db->where($where);
                    $this->db->join("sheets s","e.sheet_file = s.sheet_file");
                    $query = $this->db->get();
					//echo  $this->db->last_query();
                    return $query->result_array();
    }
    public function update_result_download($date, $count)
    {
                    $log['result_date']=$date;
                    $log['result_count']=$count;
                    $log['result_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                    
        if ($this->db->insert('results_log', $log)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    
    public function get_result_sheets($date)
    {
                    $key = $this->config->item('encryption_key');
                    
                    $this->db->select("sheets.*");
                    $this->db->select("AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(head_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS head_evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(marker_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS marker_evaluation_marks", false);
                    $this->db->select("AES_DECRYPT(final_marks,UNHEX(SHA2('".$key."',512))) AS final_marks", false);
                    
                    $where = "(sheet_status='Checked' OR sheet_status='Rejected' OR  sheet_status='Rechecked' OR sheet_status='ReMarking')";
                    $this->db->where($where);
                    
                    $where_time = "evaluation_date='".$date."' OR (sheet_updated_time>'".$date." 00:00:00' AND sheet_updated_time<'".$date." 23:59:59')";
                    $this->db->where($where_time);
                    
                    //$this->db->where('evaluation_date',$date);
                    
                    $query = $this->db->get('sheets');
                    return $query->result_array();
    }
    public function get_result_log()
    {
                    $query = $this->db->get('results_log');
                    return $query->result_array();
    }

      //Code written by vikas;
      public function get_sheets_subject_wise($code){
        $this->db->where(['subject_code'=>$code,'sheet_status'=>'Pending']);
        $query = $this->db->get('sheets');
        return $query->num_rows();
    }
    //Code End Here
}
