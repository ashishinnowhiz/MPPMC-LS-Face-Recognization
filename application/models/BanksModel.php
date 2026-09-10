<?php 

class BanksModel extends CI_Model {

    public function getBankDetails($user_email) {
        $this->db->where('user_email', $user_email);
        $result = $this->db->get('bank_details');
        return $result->result_array();
    }

    public function get_bank_details(){
        $this->db->select('user_email,beneficiary_name,account_number,beneficiary_bank_name,branch_name,ifsc_code,account_type,city,pan');
        $this->db->order_by('id','DESC');
        $result = $this->db->get('bank_details');
        return $result->result_array();
    }


    public function get_examiner_by_email($email,$subject_code=""){
        if($subject_code != ""){
            $this->db->where('cs.subject_code',$subject_code);
        }
        $this->db->where('ex.examiner_email',$email);
        $this->db->from('examiners ex');
        $this->db->join('examiner_course_subject cs','ex.examiner_username = cs.examiner_username');
        $result = $this->db->get();
        return $result->row_array();
    }

    public function get_evaluator_by_email($email,$subject_code=""){
        if($subject_code != ""){
            $this->db->where('ms.subject_code',$subject_code);
        }
        $this->db->where('e.evaluator_email',$email);
        $this->db->from('evaluators e');
        $this->db->join('marker_subjects ms','e.evaluator_username = ms.evaluator_username');
        $result = $this->db->get();
        return $result->row_array();
    }



    public function addBankDetails($bank_info , $user_email){
        if($this->checkBenificiar($user_email) == false){
            $query = $this->db->insert('bank_details',$bank_info);
                return true;
        }else{
            return false;
        }

    }

    public function checkBenificiar($user_email){
        $this->db->where('user_email',$user_email);
        $result = $this->db->get('bank_details');

        if( $result->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}

?>
