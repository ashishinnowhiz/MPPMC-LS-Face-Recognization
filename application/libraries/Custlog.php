<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once dirname(__FILE__).'/php/Logger.php';
Logger::configure(dirname(__FILE__).'/config.xml');


class Custlog
{
    public function __construct()
    {
        

    }
    public function storeLog($m)
    {
       	$logger = Logger::getRootLogger();
		$msg="=================================================================================".PHP_EOL.$m.PHP_EOL."=================================================================================";
		$logger->info($msg);
		return true;
    }


}
