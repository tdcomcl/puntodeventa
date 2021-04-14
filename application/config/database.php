<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => !empty(getenv('MYSQL_HOST_NAME')) ? getenv('MYSQL_HOST_NAME') : 'localhost',
	'username' => !empty(getenv('MYSQL_USERNAME')) ? getenv('MYSQL_USERNAME') : 'user_basededato',
	'password' => !empty(getenv('MYSQL_PASSWORD')) ? getenv('MYSQL_PASSWORD') : 'passwr_basededatos',
	'database' => !empty(getenv('MYSQL_DB_NAME')) ? getenv('MYSQL_DB_NAME') : 'basedatos',
	'dbdriver' => 'mysqli',
	'dbprefix' => 'ospos_',
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
