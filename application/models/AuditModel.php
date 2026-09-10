<?php
class AuditModel extends CI_Model{

    public function getMarks($paperCode,$rollNumbers){
       $this->db->where_in('p.paper_code',$paperCode);
       $this->db->where_in('st.roll_number',$rollNumbers);
       $this->db->select('e.*,st.roll_number');
       $this->db->from('evaluation e');
       $this->db->join('sheets s','e.sheet_file = s.sheet_file');
       $this->db->join('students st','e.sheet_file = st.script_code');
       $this->db->join('papers p','s.paper_code = p.paper_code');
       $result = $this->db->get();
    //    echo $this->db->last_query();
       return $result->result_array();
    }

    public function get_students(){
        $this->db->from("students st");
        $this->db->join("sheets s",'st.script_code = s.sheet_file');
        $this->db->where("(s.sheet_status = 'Rechecked' OR s.sheet_status = 'Checked')");
        $result = $this->db->get();
        if($result->num_rows()){
            return $result->result_array();
        }else{
            return false;
        }
    }

    public function getValidStudents($where){
        $this->db->select('st.*');
        $this->db->from('students st');
        $this->db->join("sheets s",'st.script_code = s.sheet_file');
        $this->db->where("(s.sheet_status = 'Rechecked' OR s.sheet_status = 'Checked')");
        if(!empty($where["students"])){
            $this->db->where_not_in("st.roll_number",$where["students"]);
        }

        if($where["roll_number"] != "null"){
            $this->db->like('st.roll_number', $where["roll_number"]);
        }

        $result = $this->db->get();
        return $result->result_array();
    }

    public function getStSubjects($subjectCodes,$rollNumbers){
       
        $this->db->from("subjects s");
        $this->db->select("s.*,st.roll_number");
        $this->db->join("students st","s.subject_code = st.subject_code");
        $this->db->where_in('s.subject_code',$subjectCodes);
        $this->db->where_in('st.roll_number',$rollNumbers);
        $result = $this->db->get();
        
        return $result->result_array();
    }

    public function getSheetsR($subjectCodes,$rollNumbers){
        $key = $this->config->item('encryption_key');
        $this->db->select('s.sheet_file,s.examiner_username,s.evaluation_date,st.roll_number,p.paper_passing_marks,s.subject_code,s.allocation_id');
        $this->db->select("AES_DECRYPT(s.head_evaluation_marks,UNHEX(SHA2('".$key."',512))) AS head_marks", false);
        $this->db->from('sheets s');
        // if($where != ""){
        //     $this->db->where($where);
        // }

       
        $this->db->where("(s.sheet_status ='Rechecked' OR (s.sheet_status = 'Checked' AND (s.recheck_remarks = '' OR s.recheck_remarks = 'Review')))");
        $this->db->where_in('s.subject_code',$subjectCodes);
        $this->db->where_in('st.roll_number',$rollNumbers);
      
        $this->db->join('students st','s.sheet_file = st.script_code');
        $this->db->join('papers p','s.paper_code = p.paper_code');
        $result = $this->db->get();
   
        return $result->result_array();
    }

    public function getSeedingScriptProgress($subjectCodes,$rollNumbers){
        $key = $this->config->item('encryption_key');
        $this->db->select('s.*,st.roll_number,ex.examiner_name');
        $this->db->select("AES_DECRYPT(s.evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);
        $this->db->from('seeding_sheets s');
      
        $subjects_array = array('CP1A','CP1B','CP2A','CP2B','CS1A','CS1B','CS2A','CS2B','CM1A','CM1B','CM2A','CM2B');
        $this->db->where('s.seed_status','Checked');
        $this->db->where_in('s.subject_code',$subjectCodes);
        $this->db->where_in('st.roll_number',$rollNumbers);
        $this->db->join('students st','s.seed_file = st.script_code');
        $this->db->join('examiners ex','ex.examiner_username = s.evaluator_code');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getAnnotations($paperCode,$rollNumbers){

        $this->db->from("eval e");
        $this->db->select("st.roll_number AS RollNumber,p.paper_code AS PaperCode,e.sheet_file AS ScriptCode,e.user_name AS UserName,e.user_role AS UserRole,e.eval_page AS EvalPage,e.eval_action AS EvalAction,e.eval_time AS EvalTime,e.eval_details as EvalDetails");
        $this->db->join("students st","e.sheet_file = st.script_code");
        $this->db->join("papers p","st.subject_code = p.subject_code");
        $this->db->where_in('st.roll_number',$rollNumbers);
        $this->db->where('p.paper_code',$paperCode);
        $result = $this->db->get();
        return $result->result_array();

    }

    public function getAuditById($id){
        $this->db->where("audit_id",$id);
        $result = $this->db->get('audit_log');
        return $result->row_array();
    }
}
?>