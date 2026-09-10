<?php
error_reporting(E_ERROR | E_PARSE);
/**
* Callbacks class
*/
class Callbacks extends Callbacks_Core
{
    function install($params = array())
    {
        $dbconf = array(
            'db_host' => $_SESSION['params']['db_hostname'],
            'db_user' => $_SESSION['params']['db_username'],
            'db_pass' => $_SESSION['params']['db_password'],
            'db_name' => $_SESSION['params']['db_name'],
            'db_encoding' => 'utf8',
        );
        if (!$this->db_init($dbconf)) {
            return false;
        }
        
        $replace = array(
            '{:db_prefix}' => 'my_',
            '{:db_engine}' => in_array('innodb', $this->db_engines) ? 'InnoDB' : 'MyISAM',
            '{:db_charset}' => $this->db_version >= '4.1' ? 'DEFAULT CHARSET=utf8' : '',
            '{:website}' => $_SESSION['params']['virtual_path']
        );
        
        if (!$this->db_import_file(BASE_PATH.'sql/webpilot.sql', $replace)) {
            return false;
        }
        // you can also manually run a query
        //$this->db_query("INSERT INTO `my_table`(`name`,`val`) VALUES ('manual', 'Another value')", true);
        $this->db_close();

        $db_file="<?php defined('BASEPATH') OR exit('No direct script access allowed');
		\$active_group = 'default';
		\$query_builder = TRUE;
		\$db['default'] = array(
			'dsn'	=> '',
			'hostname' => '".addslashes($_SESSION['params']['db_hostname'])."',
			'username' => '".addslashes($_SESSION['params']['db_username'])."',
			'password' => '".addslashes($_SESSION['params']['db_password'])."',
			'database' => '".addslashes($_SESSION['params']['db_name'])."',
			'dbdriver' => 'mysqli',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => (ENVIRONMENT !== 'production'),
			'cache_on' => FALSE,
			'cachedir' => '',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'encrypt' => FALSE,
			'compress' => FALSE,
			'stricton' => FALSE,
			'failover' => array(),
			'save_queries' => TRUE
		);";
        
        //$_SESSION['params']['virtual_path']=rtrim(preg_replace('#/install/$#', '', VIRTUAL_PATH), '/').'/';
        //@file_put_contents('../application/config/config.php', str_replace('$config[\'base_url\'] = \'/\'', '$config[\'base_url\'] = \''.$_SESSION['params']['virtual_path'].'\'', @file_get_contents('../application/config/config.php')));
         $_SESSION['params']['virtual_path']=rtrim(preg_replace('#/install/$#', '', URL_PATH), '/').'/';
		 @file_put_contents('../application/config/config.php', str_replace('$config[\'base_url\'] = \'/\'', '$config[\'base_url\'] = isset($_SERVER[\'HTTPS\']) ? \'https\' : \'http\'.\'://\'.$_SERVER[\'HTTP_HOST\'].\''.$_SESSION['params']['virtual_path'].'\'', @file_get_contents('../application/config/config.php')));
        
        @file_put_contents('../application/config/database.php', $db_file);
        return true;
    }
    
    function setup_config($params = array())
    {
        //@file_put_contents('../application/config/config.php', str_replace('$config[\'base_url\'] = \'/\'', '$config[\'base_url\'] = \''.$_SESSION['params']['virtual_path'].'\'', @file_get_contents('../application/config/config.php')));
        @file_put_contents('../application/config/config.php', str_replace('$config[\'base_url\'] = \'/\'', '$config[\'base_url\'] = isset($_SERVER[\'HTTPS\']) ? \'https\' : \'http\'.\'://\'.$_SERVER[\'HTTP_HOST\'].\''.$_SESSION['params']['virtual_path'].'\'', @file_get_contents('../application/config/config.php')));
        return true;
    }
    function setup_exam($params = array())
    {
        $exam_file="<?php
			defined('BASEPATH') OR exit('No direct cript access allowed');
			/*Examinations Configuration File*/
			\$config['exam'] = array(
				'exam_code'	=> '".addslashes($_SESSION['params']['exam_code'])."',
				'exam_name'	=> '".addslashes($_SESSION['params']['exam_name'])."',
				'center_name'	=> '".addslashes($_SESSION['params']['center_name'])."',
                'exam_session' => '".addslashes(md5(rand()))."'
			);";
        
        @file_put_contents('../application/config/exam.php', $exam_file);
        @file_put_contents('../application/libraries/Curl.php', str_replace('replace_with_server_url', $_SESSION['params']['server_url'], @file_get_contents('../application/libraries/Curl.php')));
        @file_put_contents('../application/libraries/Curl.php', str_replace('replace_with_backup_url', $_SESSION['params']['backup_url'], @file_get_contents('../application/libraries/Curl.php')));
        return true;
    }
    function setup_admin($params = array())
    {
        $ho_url=$_SESSION['params']['server_url'];
        if (substr_count($ho_url, "https://")>0) {
            $url=str_replace("https://", "", $ho_url);
            $url_array=explode('/', $url);
            $connected = @fsockopen($url_array[0], 443);
        } else {
            $url=str_replace("http://", "", $ho_url);
            $url_array=explode('/', $url);
            $connected = @fsockopen($url_array[0], 80);
        }
        //website, port  (try 80 or 443)
        if ($connected) {
              $is_conn = true; //action when connected
              fclose($connected);
        } else {
            $this->error = "Cannot establish connected with HO please check url/internet";
            return false; //action in connection failure
        }
        $body['username']=$_SESSION['params']['user_email'];
        $body['password']=$_SESSION['params']['user_password'];
		$MAC = exec('getmac');
		$MAC = strtok($MAC, ' ');
		$body['centerMAC']=$MAC?$MAC:$_SERVER['HTTP_HOST'];
        $url=$ho_url."/api/login";
        $body=json_encode($body);
     
        $timeout=30;
        $verify_ssl   = false;
        $headers = array(
                        'Content-Type: application/json',
                        'Cache-Control: no-cache',
                        'Content-Length: '.strlen($body)
                        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        //echo $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $obj=json_decode($result);
        $check=false;
        if ($obj->success) {
            if ($obj->user_center==$_SESSION['params']['user_center']) {
                $check = true;
            }
        }
        if ($check) {
            $dbconf = array(
                'db_host' => $_SESSION['params']['db_hostname'],
                'db_user' => $_SESSION['params']['db_username'],
                'db_pass' => $_SESSION['params']['db_password'],
                'db_name' => $_SESSION['params']['db_name'],
                'db_encoding' => 'utf8',
            );
            if (!($db = $this->db_init($dbconf))) {
                return false;
            }
            $this->db_query("DELETE FROM users");
            $time_now=date('Y-m-d h:i:s', time());
            $hashpass=password_hash($_SESSION['params']['user_password'], PASSWORD_DEFAULT);
            if ($this->db_query("INSERT INTO users (user_name,user_email,user_password,user_status,user_role,ip_address,user_login_ip,center_code,user_token,user_login_time,user_updated_time) VALUES('".$this->db_escape($_SESSION['params']['user_email'])."', '".$this->db_escape($_SESSION['params']['user_email'])."', '".$this->db_escape($hashpass)."','Active','Coordinator','','','".$this->db_escape($_SESSION['params']['user_center'])."','','".$time_now."','".$time_now."')")) {
                $this->db_close();
                @file_put_contents('../index.php', @file_get_contents('../index_main.php'));
                return true;
            } else {
                $this->db_close();
                $this->error = "Error Adding details ";
                return false;
            }
        }
        $this->error = "Error verifying details at HO server";
        return false;
    }
}
