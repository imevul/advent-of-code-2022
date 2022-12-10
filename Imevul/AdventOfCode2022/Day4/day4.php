<?php
/*
 * Camp Cleanup
 * https://adventofcode.com/2022/day/4
 */

namespace Imevul\AdventOfCode2022\Day4;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return array_map(
		fn($line) => array_map(
			fn($range) => explode('-', $range),
			explode(',', $line)
		),
		$input
	);
}

function contains(array $range1, array $range2): bool {
	return $range1[0] <= $range2[0] && $range1[1] >= $range2[1];
}

function overlap(array $range1, array $range2): bool {
	return $range1[0] <= $range2[1] && $range1[1] >= $range2[0];
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	return count(
		array_filter(
			$input,
			fn($pair) => contains(...$pair) || contains(... array_reverse($pair))
		)
	);
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	return count(
		array_filter(
			$input,
			fn($pair) => overlap(...$pair)
		)
	);
}

return [test([2, 4]), run()];
