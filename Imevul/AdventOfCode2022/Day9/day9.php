<?php
/*
 * Rope Bridge
 * https://adventofcode.com/2022/day/9
 */

namespace Imevul\AdventOfCode2022\Day9;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return array_map(fn($line) => explode(' ', $line), $input);
}

function moveRope(Rope $rope, array $moves): int {
	$visited = ['0,0'];

	foreach ($moves as $move) {
		[$dx, $dy] = match ($move[0]) {
			'R' => [$move[1], 0],
			'L' => [-$move[1], 0],
			'U' => [0, $move[1]],
			'D' => [0, -$move[1]]
		};

		$log = $rope->move($dx, $dy);
		array_push($visited, ...$log);
	}

	return count(array_unique($visited));
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	return moveRope(new Rope(), $input);
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	return moveRope(new Rope(10), $input);

}

return [test([13, 1]), run()];
