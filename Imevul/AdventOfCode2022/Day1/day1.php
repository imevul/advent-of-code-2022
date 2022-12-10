<?php
/*
 * Calorie Counting
 * https://adventofcode.com/2022/day/1
 */

namespace Imevul\AdventOfCode2022\Day1;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return array_map('intval', $input);
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	$result = array_map('array_sum', array_split($input, 0));
	rsort($result);
    return $result[0];
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	$result = array_map('array_sum', array_split($input, 0));
	rsort($result);
	return $result[0] + $result[1] + $result[2];
}

return [test([24000, 45000]), run(empty($skipRun))];
