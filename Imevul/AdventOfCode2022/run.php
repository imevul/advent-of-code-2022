<?php

namespace Imevul\AdventOfCode2022;

ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
define('IS_CLI', php_sapi_name() == 'cli');

use Console_Color2;
use Console_Table;
use DateTime;
use SebastianBergmann\Timer\Timer;

require_once __DIR__ . '/../../bootstrap.php';

function success(mixed $v, bool $color = TRUE): mixed {
	return $color ? (new Console_Color2)->convert("%G$v%n") : $v;
}

function failure(mixed $v, bool $color = TRUE): mixed {
	return $color ? (new Console_Color2)->convert("%R$v%n") : $v;
}

function runDay(int $day): ?array {
	$filename = "Day$day/day$day.php";

	if (file_exists($filename)) {
		$timer = new Timer;
		$timer->start();
		$result = include_once($filename);
		$duration = $timer->stop();
		return [$day, ...$result, $duration->asString()];
	}

	return NULL;
}

function runDays(mixed $day): void {
	$days = [];
	$directories = glob('Day*');
	natsort($directories);
	$maxDay = (int)str_replace('Day', '', $directories[array_key_last($directories)]);

	$day ??= $maxDay;

	if ($day == 'all') {
		for ($i = 1; $i <= $maxDay; $i++) {
			$days[$i] = runDay($i);
		}
	} else {
		$result = runDay($day);
		if ($result !== NULL) {
			$days[$day] = $result;
		}
	}

	$data = [];
	foreach ($days as $day) {
		$data[] = [
			$day[1][0] && $day[1][1] ? success($day[0], IS_CLI) : failure($day[0], IS_CLI),
			$day[1][0] ? success($day[2][0], IS_CLI) : failure($day[2][0], IS_CLI),
			$day[1][1] ? success($day[2][1], IS_CLI) : failure($day[2][1], IS_CLI),
			$day[3],
		];
	}

	$table = new Console_Table(color: IS_CLI);
	$table->setHeaders(['Day', 'Part1', 'Part2', 'Time']);
	$table->addData($data);
	$tableText = $table->getTable();
	$tableText = IS_CLI ? $tableText : str_replace([' ', PHP_EOL], ['&nbsp;', '<br>'], $tableText);

	echo (new DateTime('now'))->format('c') . PHP_EOL;
	echo $tableText;
}

runDays($argv[1] ?? NULL);
