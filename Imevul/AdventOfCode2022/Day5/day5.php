<?php
/*
 * Supply Stacks
 * https://adventofcode.com/2022/day/5
 */

namespace Imevul\AdventOfCode2022\Day5;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
	[$stacks, $moves] = array_split($input, '');
	array_pop($stacks);

	$stacks = array_map(
		fn($line) => explode(',', str_replace(['    ', ' ', '[', ']', '*'], [',', ',', '', '', ''], $line)),
		$stacks
	);
	$stacks = array_reverse($stacks);
	$tmp = [];
	foreach ($stacks as $level => $items) {
		foreach ($items as $index => $item) {
			$tmp[$index][$level] = $item;
		}
	}
	$stacks = array_map(fn($stack) => array_filter($stack, fn($item) => $item !== ''), $tmp);

	$moves = array_map(
		fn($line) => array_map('intval', explode(',', str_replace(['move ', ' from ', ' to '], ['', ',', ','], $line))),
		$moves
	);

    return [$stacks, $moves];
}

function move(array &$stacks, int $amount, int $fromIndex, int $toIndex, int $perOperation = 1): void {
	$fromIndex--;
	$toIndex--;

	for ($i = 0; $i < $amount; $i += $perOperation) {
		$items = array_slice($stacks[$fromIndex], -min($amount, count($stacks[$fromIndex]), $perOperation));
		$stacks[$fromIndex] = array_slice($stacks[$fromIndex], 0, -min($amount, count($stacks[$fromIndex]), $perOperation));
		if (empty($items)) continue;
		array_push($stacks[$toIndex], ...$items);
	}
}

/**
 * @param array $input The list of input
 * @return string The result
 */
function part1(array $input): string {
	[$stacks, $moves] = $input;

	foreach ($moves as [$amount, $from, $to]) {
		move($stacks, $amount, $from, $to);
	}

	return array_reduce(
		$stacks,
		fn($carry, $stack) => $carry . array_pop($stack)
	);
}

/**
 * @param array $input The list of input
 * @return string The result
 */
function part2(array $input): string {
	[$stacks, $moves] = $input;

	foreach ($moves as [$amount, $from, $to]) {
		move($stacks, $amount, $from, $to, PHP_INT_MAX);
	}

	return array_reduce(
		$stacks,
		fn($carry, $stack) => $carry . array_pop($stack)
	);
}

return [test(['CMZ', 'MCD']), run(empty($skipRun))];
