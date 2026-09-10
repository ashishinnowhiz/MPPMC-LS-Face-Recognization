<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Curl
{
        
    private $ho_url = "https://vmsbevenjunecs25.digimarker.online/";
    private $st_url = "https://vmsbevenjunecs25.digimarker.online/";
    public function __construct()
    {
        //$this->inputs();
    }
     public function callstatus()
    {
        $url=$this->st_url;
    
        $timeout=30;
        $verify_ssl   = false;
        $headers = array(
                        'Content-Type: application/json',
                        'Cache-Control: no-cache'

                        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        //echo $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;
    }    
    public function call($url, $method = 'GET', $body = '', $token = '')
    {
        $url=$this->ho_url."/api/".$url;
        $body=json_encode($body);
        $timeout=30;
        $verify_ssl   = false;
        $headers = array(
                        'Content-Type: application/json',
                        'Cache-Control: no-cache',
                        'Authorisation: bearer '.$token,
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
        return $result;
    }
    function upfile($post_data, $url)
    {
        $curl_connection = curl_init($this->ho_url.$url);
        curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($curl_connection);
        curl_close($curl_connection);
        return $result;
    }
    function downfile($url, $destination = '')
    {
        $ch = curl_init();
        $source = $this->ho_url.$url;
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $file = fopen($destination.$url, "w+");
        fputs($file, $data);
        fclose($file);
    }
    function is_connected()
    {
        if (substr_count($this->ho_url, "https://")>0) {
            $url=str_replace("https://", "", $this->ho_url);
            $url_array=explode('/', $url);
            $connected = @fsockopen($url_array[0], 443);
        } else {
            $url=str_replace("http://", "", $this->ho_url);
            $url_array=explode('/', $url);
            $connected = @fsockopen($url_array[0], 80);
        }
        //website, port  (try 80 or 443)
        if ($connected) {
              $is_conn = true; //action when connected
              fclose($connected);
        } else {
            $is_conn = false; //action in connection failure
        }
            return $is_conn;
    }
}
