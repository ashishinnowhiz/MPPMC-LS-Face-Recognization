<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alter extends CI_Controller
{

    
    public function index()
    {
        if ($this->db->query("ALTER TABLE sheets MODIFY COLUMN sheet_file VARCHAR(40) NOT NULL")) {
            echo "<br/>Field length altered";
        }
        if ($this->db->query("ALTER TABLE `sheets` ADD UNIQUE( `sheet_file`, `allocation_id`)")) {
            echo "<br/>Unique key added";
        }
        //unlink(__FILE__);
    }
}
