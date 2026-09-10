<?php 

class CoursesModel extends CI_Model {

    // public function get_courses()
    // {
    //                 //$this->db->where('course_deleted', '0');
    //                 $this->db->order_by('course_id', 'DESC');
    //                 $query = $this->db->get('courses');
    //                 return $query->result_array();
    // }
    public function get_courses()
    {
                    //$this->db->where('course_deleted', '0');
                    // $this->db->order_by('c.course_id', 'DESC');
                    // $this->db->where('c.parent <> 0');
                    $select = array(
                        'c.course_code as course_code',
                        'c.course_name as branch_name',
                        'c.course_code as branch_code',
                        'p.course_name as course_name',
                        'p.course_code as course_code',
                       
                          );
        $this->db->select($select);

        $this->db->from('courses c');
        $this->db->join('courses p','c.parent = p.course_code');
		
        $query = $this->db->get();
        return $query->result_array();
                    
    }
    public function get_course($id)
    {
                    $this->db->where('course_id', $id);
                    $query = $this->db->get('courses');
                    return $query->row_array();
    }

    public function add_course($course)
    {
        if ($this->check_course_code($course['course_code'])>0) {
            $data['success']=false;
            $data['message']="Course Code ".$course['course_code']." Already Exist";
            return $data;
        }
        if ($this->db->insert('courses', $course)) {
            $data['success']=true;
            $data['message']='Course Added Successfully';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding course';
            return $data;
        }
    }

    public function check_course_code($code, $except_id = '')
    {
        if ($except_id!='') {
            $this->db->where('course_id !=', $except_id);
        }
                    $this->db->where('course_code', $code);
                    $query = $this->db->get('courses');
                    return $query->num_rows();
    }

    public function get_courses_by_code($course_code){
        $this->db->order_by('course_id', 'DESC');
        $this->db->where('course_code', $course_code);
        $query = $this->db->get('courses');
        return $query->result();
    }

    public function get_parent_course(){
        $this->db->where('parent','');
		$this->db->order_by('course_name', 'ASC');
        $query = $this->db->get('courses');
        return $query->result_array();
    }

    

}

?>