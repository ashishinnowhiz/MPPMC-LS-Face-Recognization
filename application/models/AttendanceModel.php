<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AttendanceModel extends CI_Model
{

    public function get_attendance($date, $where_array = '')
    {
                    $this->db->where('attendance_date', $date);
                    $query = $this->db->get('attendance');
                    return $query->result_array();
    }
}
