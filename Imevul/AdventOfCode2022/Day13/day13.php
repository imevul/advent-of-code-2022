<?php
/*
 * Distress Signal
 * https://adventofcode.com/2022/day/13
 */

namespace Imevul\AdventOfCode2022\Day13;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return array_map(fn(array $signal) => array_map('json_decode', $signal), array_split($input, ''));
}

function compareSignals(array|int $left, array|int $right): int {
	if (is_int($left) && is_int($right)) {
		return compare($left, $right);
	}

	if (is_array($left) && is_array($right)) {
		for ($i = 0; $i < max(count($left), count($right)); $i++) {
			if (isset($left[$i]) && !isset($right[$i])) return 1;
			if (!isset($left[$i]) && isset($right[$i])) return -1;

			$cmp = compareSignals($left[$i], $right[$i]);
			if ($cmp === 0) continue;
			return $cmp;
		}
		return 0;
	}

	if (is_array($left) && is_int($right)) return compareSignals($left, [$right]);
	if (is_int($left) && is_array($right)) return compareSignals([$left], $right);

	return 1;
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	return array_sum(array_map(
		fn(int $v) => $v + 1,
		array_keys(array_filter(
			array_map(fn($signal) => compareSignals($signal[0], $signal[1]), $input),
			fn(int $v) => $v < 0
		))
	));
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	$packets = array_merge(...$input);
	$delimiters = [[[2]], [[6]]];
	array_push($packets, ...$delimiters);

	usort($packets, 'Imevul\AdventOfCode2022\Day13\compareSignals');

	$key = array_map(
		fn(int $v) => $v + 1,
		array_keys(array_filter($packets, fn($packet) => in_array($packet, $delimiters)))
	);

	return array_reduce($key, fn(int $carry, $val) => $carry * $val, 1);
}

return [test([13, 140]), run()];
