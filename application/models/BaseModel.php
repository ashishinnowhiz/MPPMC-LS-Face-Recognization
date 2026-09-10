<?php defined('BASEPATH') or exit('No direct script access allowed');
class BaseModel extends CI_Model
{
    private $db;
    public function __construct(){
        parent::__construct();
        $this->db = $this->database->connect('VMSBREVAL');
        
    }
}