<?php defined('BASEPATH') OR exit('No direct script access allowed');
		$active_group = 'default';
		$query_builder = TRUE;
		$db['default'] = array(
			'dsn'	=> '',
			'hostname' => 'localhost',
			'username' => 'digimarker',
			'password' => 'Ashish@98065',
			'database' => 'vls',
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
		);
		$db['vmsbrevalls'] = array(
			'dsn'	=> '',
			'hostname' => 'localhost',
			'username' => 'digimarker',
			'password' => 'Ashish@98065',
			'database' => 'vls-reval',
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
		);