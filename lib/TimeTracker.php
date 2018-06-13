<?php
/**
 * TimeTracker.php
 *
 * @author: Onirudda Odikare Jhalak <jhalak1983@gmail.com>
 * @created on: 6/13/18
 */
class TimeTracker
{
	public static $startTime;
	public static $endTime;

	public static function start()
	{
		self::$startTime = new DateTime('now');
	}

	public static function end()
	{
		self::$endTime = new DateTime('now');
	}

	public static function getTimeDiff()
	{
		return self::$startTime->diff(self::$endTime);
	}

	public static function showTimeDiff()
	{
		$diff = self::getTimeDiff();
		printf("DONE in %d minutes %d seconds", $diff->i, $diff->s);
	}
}
