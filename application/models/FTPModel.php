<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FTPModel extends CI_Model
{

    public function get_settings()
    {
                $FTP['ftp_server']='hostname';
                $FTP['ftp_username']='user';
                $FTP['ftp_userpass']='pass';
                return $FTP;
    }
}
