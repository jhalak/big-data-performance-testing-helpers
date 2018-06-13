<?php
/**
 * insert-into-database.php
 *
 * @author: onirudda.odikare@widespace.com
 * @created on: 6/10/18
 */
require_once "vendor/autoload.php";
require_once "database.php";

use Illuminate\Database\Capsule\Manager as Database;

$faker = Faker\Factory::create();

if (count($argv) < 2) {
	print "Argument missing: " . PHP_EOL;
	print "Example: php insert-into-database.php [numberOfRows]" . PHP_EOL;
	exit();
}

$numberOrRows = $argv[1];
printf("Creating %d records in students table ... " . PHP_EOL, $numberOrRows);

try {
	TimeTracker::start();
	for ($i = 0; $i < $numberOrRows; $i++) {
		Database::table('students')->insert([
			'joinDate' 	=> $faker->date('Y-m-d'),
			'name'     	=> $faker->name,
			'regNo'	   	=> $faker->numberBetween(10000000, 999999999999999999),
			'address'   => $faker->address
		]);

		if ($i % 1000 == 0) {
			echo ".";
		}
	}
	TimeTracker::end();
	TimeTracker::showTimeDiff();

} catch (Exception $e) {
	echo $e->getMessage();
}