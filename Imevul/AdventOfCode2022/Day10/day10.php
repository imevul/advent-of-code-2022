<?php
/*
 * Cathode-Ray Tube
 * https://adventofcode.com/2022/day/10
 */

namespace Imevul\AdventOfCode2022\Day10;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return array_map(fn($line) => explode(' ', $line), $input);
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	$result = 0;
	$cpu = new CPU($input);
	$cpu->registerIRQHandler(function() use (&$result) {
		if (($this->cycle - 20) % 40 === 0) {
			$result += $this->getSignalStrength();
		}
	});
	$cpu->run();

    return $result;
}

/**
 * @param array $input The list of input
 * @return string The result
 */
function part2(array $input): string {
	$output = '';
	$cpu = new CPU($input);
	$cpu->registerIRQHandler(function() use (&$result, &$output) {
		$x = ($this->cycle - 1) % 40;
		$px = $this->x;
		if (abs($x - $px) > 1) {
			$output .= '.';
		} else {
			$output .= '#';
		}

		if ($this->cycle % 40 === 0) {
			$output .= PHP_EOL;
		}
	});
	$cpu->run();

	return $output;
}

return [test([13140, "##..##..##..##..##..##..##..##..##..##..\n###...###...###...###...###...###...###.\n####....####....####....####....####....\n#####.....#####.....#####.....#####.....\n######......######......######......####\n#######.......#######.......#######.....\n"]), run()];
