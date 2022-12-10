<?php
/*
 * Rucksack Reorganization
 * https://adventofcode.com/2022/day/3
 */

namespace Imevul\AdventOfCode2022\Day3;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
	return $input;
}

function getPriority(string $type): int {
	$types = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	return mb_strpos($types, $type) + 1 ?? 0;
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	$input = array_map(fn($line) => [
		mb_str_split(mb_substr($line, 0, ceil(mb_strlen($line) / 2))),
		mb_str_split(mb_substr($line, ceil(mb_strlen($line) / 2)))
	], $input);

	return array_sum(
		array_map(
			'Imevul\AdventOfCode2022\Day3\getPriority',
			array_map(
				fn($rucksack) => array_values($rucksack)[0] ?? '',
				array_map(fn($compartments) => array_unique(array_intersect(...$compartments)), $input)
			)
		)
	);
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	return array_sum(
		array_map(
			'Imevul\AdventOfCode2022\Day3\getPriority',
			array_map(
				fn($items) => array_values($items)[0],
				array_filter(
					array_map(
						fn($items) => array_unique(array_intersect(...$items)),
						array_map(
							fn($items) => array_map(
								'mb_str_split',
								$items
							),
							array_chunk($input, 3)
						),
					),
					fn($items) => count($items) === 1
				)
			)
		)
	);
}

return [test([157, 70]), run(empty($skipRun))];
