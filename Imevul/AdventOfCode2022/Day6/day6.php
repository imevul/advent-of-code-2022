<?php
/*
 * Tuning Trouble
 * https://adventofcode.com/2022/day/6
 */

namespace Imevul\AdventOfCode2022\Day6;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return mb_str_split($input[0]);
}

function findMarker(array $bufferStream, int $markerLength): array {
	for ($i = 0; $i < count($bufferStream); $i++) {
		$markerCandidate = array_unique(array_slice($bufferStream, $i, $markerLength));
		if (count($markerCandidate) === $markerLength) {
			return [$markerCandidate, $i + $markerLength];
		}
	}

	return [NULL, 0];
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	[, $position] = findMarker($input, 4);
	return $position;
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	[, $position] = findMarker($input, 14);
	return $position;
}

return [test([7, 19]), run()];
