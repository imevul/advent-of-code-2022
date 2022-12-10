<?php
/*
 * Treetop Tree House
 * https://adventofcode.com/2022/day/8
 */

namespace Imevul\AdventOfCode2022\Day8;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return array_map(fn($line) => array_map('intval', mb_str_split($line)), $input);
}

function look(array &$map, int $x, int $y, int $dx, int $dy, int $maxHeight = PHP_INT_MAX): array {
	$ySize = count($map);
	$xSize = count($map[0]);
	$tx = $x;
	$ty = $y;

	$ret = [];
	while ($tx > 0 && $tx < $xSize - 1 && $ty > 0 && $ty < $ySize - 1) {
		$tx += $dx;
		$ty += $dy;
		$height = $map[$ty][$tx];
		$ret[] = $height;
		if ($height >= $maxHeight) break;
	}

	return $ret;
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	$ySize = count($input);
	$xSize = count($input[0]);
	$visibilityMap = createMap(0, $xSize, 0, $ySize, FALSE);

	for ($y = 0; $y < $ySize; $y++) {
		for ($x = 0; $x < $xSize; $x++) {
			$height = $input[$y][$x];
			$visibilityMap[$y][$x] = array_sum(array_map('intval', [
					max([-1, ...look($input, $x, $y, 0, -1)]) < $height,
					max([-1, ...look($input, $x, $y, 0, 1)]) < $height,
					max([-1, ...look($input, $x, $y, -1, 0)]) < $height,
					max([-1, ...look($input, $x, $y, 1, 0)]) < $height,
				])) > 0;
		}
	}

	return array_sum(array_map(fn($row) => array_sum(array_map('intval', $row)), $visibilityMap));
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	$ySize = count($input);
	$xSize = count($input[0]);

	$maxScore = 0;
	for ($y = 0; $y < $ySize; $y++) {
		for ($x = 0; $x < $xSize; $x++) {
			$height = $input[$y][$x];
			$distances = [
				count(look($input, $x, $y, 0, -1, $height)),
				count(look($input, $x, $y, 0, 1, $height)),
				count(look($input, $x, $y, -1, 0, $height)),
				count(look($input, $x, $y, 1, 0, $height)),
			];

			$score = array_reduce($distances,
				fn(int $carry, $count) => $carry * $count,
				1
			);

			$maxScore = max($maxScore, $score);
		}
	}

	return $maxScore;
}

return [test([21, 8]), run(empty($skipRun))];
