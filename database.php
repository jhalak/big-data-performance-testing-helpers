<?php
/**
 * database.php
 *
 * @author: Onirudda Odikare Jhalak <jhalak1983@gmail.com>
 * @created on: 6/10/18
 */

use Illuminate\Database\Capsule\Manager as Database;

$database = new Database();
$database->addConnection([
	'driver'    => 'mysql',
	'host'      => '127.0.0.1',
	'port'      => '3308',
	'database'  => 'mysql_ginipig',
	'username'  => 'root',
	'password'  => 'root',
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => '',
	'strict'    => false,
	'options'  => array(PDO::MYSQL_ATTR_LOCAL_INFILE => true),
]);

$database->setAsGlobal();