<?php
/**
 * write-data-in-file.php
 *
 * @author: onirudda.odikare@widespace.com
 * @created on: 6/10/18
 */
require_once "vendor/autoload.php";

if (count($argv) < 4) {
	print "Argument missing: " . PHP_EOL;
	print "Example: php write-data-in-file.php [startId] [endId]" . PHP_EOL;
	exit();
}

$filename = $argv[1];
$startId  = $argv[2];
$endId    = $argv[3];
printf("Writing %d records in file %s" . PHP_EOL, ($endId - $startId), $filename);

$faker = Faker\Factory::create();
try {
	$timeFrom = new DateTime('now');
	$fileHandler = fopen($filename, "w");
	for ($i = $startId; $i <= $endId; $i++) {
		// Prepare the data raw
		$date = $faker->date('Y-m-d');
		$data = [
			$i,
			(string) $date,
			$faker->name,
			(string) $faker->numberBetween(10000000, 99999999999999),
			str_replace("\n", "", $faker->address)
		];

		// write data in as CSV in file
		fputcsv($fileHandler, $data, "\t");

		// Show a signal that you are not dead yet :)
		if ($i % 10000 == 0) {
			echo ".";
		}
	}
	fclose($fileHandler);

	// Track how much time it took to write that data in file
	$timeTo = new DateTime('now');
	$diff = $timeFrom->diff($timeTo);
	printf("DONE in %d minutes %d seconds", $diff->i, $diff->s);

} catch (Exception $e) {
	echo $e->getMessage();
}