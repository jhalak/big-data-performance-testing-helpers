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

$numberOrRows = 10000;

echo "Creating $numberOrRows records in students table ... " . PHP_EOL;


try {
	$timeFrom = new DateTime('now');
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
	$timeTo = new DateTime('now');
	$diff = $timeFrom->diff($timeTo);
	echo PHP_EOL . "DONE in " . $diff->s . ' seconds (' . $diff->i . ' minutes)';

} catch (Exception $e) {
	echo $e->getMessage();
}