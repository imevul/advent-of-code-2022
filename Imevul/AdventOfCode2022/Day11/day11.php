<?php
/*
 * Monkey in the Middle
 * https://adventofcode.com/2022/day/11
 */

namespace Imevul\AdventOfCode2022\Day11;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    $monkeyDataArr = array_split($input, '');

	Monkey::clearMonkeys();
	$monkeys = [];
	foreach ($monkeyDataArr as $monkeyData) {
		$monkeys[] = new Monkey(
			id: intval(trim(str_replace(['Monkey', ':'], '', $monkeyData[0]))),
			items: array_map(fn(int $item) => $item, array_map('intval', explode(', ', trim(str_replace('Starting items:', '', $monkeyData[1]))))),
			operation: array_slice(explode(' ', str_replace('new = ', '', trim(str_replace('Operation:', '', $monkeyData[2])))), 1, 2),
			test: intval(explode(' ', trim(str_replace('Test:', '', $monkeyData[3])))[2]),
			actions: array_map(function($action) {
				return intval(str_replace('throw to monkey ', '', $action[1]));
			}, [
				explode(': ', trim(str_replace('If', '', $monkeyData[5]))),
				explode(': ', trim(str_replace('If', '', $monkeyData[4])))
			])
		);
	}

	return $monkeys;
}

function doRounds(array &$monkeys, int $numRounds): int {
	for ($i = 1; $i <= $numRounds; $i++) {
		/** @var Monkey $monkey */
		foreach ($monkeys as &$monkey) {
			$monkey->doTurn();
		}
	}

	$activity = array_map(fn(Monkey $m) => $m->inspectCounter, $monkeys);
	rsort($activity);

	return $activity[0] * $activity[1];
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	return doRounds($input, 20);
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	array_walk($input, fn(Monkey $m) => $m->decreaseWorry = FALSE);

	return doRounds($input, 10000);
}

return [test([10605, 2713310158]), run()];
