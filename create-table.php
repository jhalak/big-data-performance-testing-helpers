<?php
/**
 * create-table.php
 *
 * @author: Onirudda Odikare Jhalak <jhalak1983@gmail.com>
 * @created on: 6/10/18
 */
require_once "vendor/autoload.php";
require_once "database.php";

use Illuminate\Database\Capsule\Manager as Database;

Database::schema()->create('students', function ($table) {
	$table->increments('id');
	$table->date('joinDate');
	$table->string('name', 255);
	$table->bigInteger('regNo');
	$table->string('address', 255);
});