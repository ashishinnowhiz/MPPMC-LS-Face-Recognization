<?php 

class GraphModel extends CI_Model {

    public function getGraphValues() {
        $this->db->from("sheets");
        $this->db->select("subject_code,
       COUNT(CASE WHEN sheet_status = 'Pending' THEN 1 END) as Pending,
       COUNT(CASE WHEN sheet_status = 'Checked' THEN 1 END) as Checked,
       COUNT(CASE WHEN sheet_status = 'Rechecked' THEN 1 END) as Rechecked,
       COUNT(CASE WHEN sheet_status = 'Rejected' THEN 1 END) as Rejected,
       COUNT(*) as Total");
       $this->db->group_by("subject_code");
    $result = $this->db->get();
    return $result->result_array();
    }
    
}

?>