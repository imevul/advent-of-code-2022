<?php
/*
 * Regolith Reservoir
 * https://adventofcode.com/2022/day/14
 */

namespace Imevul\AdventOfCode2022\Day14;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    $traces = array_map(fn($line) => array_map(fn($point) => array_map('intval', explode(',', $point)), explode(' -> ', $line)), $input);
	$minX = array_reduce(array_map(fn($point) => $point[0], array_merge(...$traces)), fn(int $carry, $v) => min($carry, $v), PHP_INT_MAX);
	$minY = array_reduce(array_map(fn($point) => $point[1], array_merge(...$traces)), fn(int $carry, $v) => min($carry, $v), PHP_INT_MAX);
	$maxX = array_reduce(array_map(fn($point) => $point[0], array_merge(...$traces)), fn(int $carry, $v) => max($carry, $v), 0);
	$maxY = array_reduce(array_map(fn($point) => $point[1], array_merge(...$traces)), fn(int $carry, $v) => max($carry, $v), 0);

	return [
		'size' => ['x' => [$minX, $maxX], 'y' => [$minY, $maxY]],
		'data' => $traces
	];
}

function drawLine(array &$map, int $fromX, int $fromY, int $toX, int $toY, mixed $value): void {
	$dx = normalize($toX - $fromX);
	$dy = normalize($toY - $fromY);

	$x = $fromX;
	$y = $fromY;
	while ($x != $toX || $y != $toY) {
		$map[$y][$x] = $value;

		if ($x != $toX) $x += $dx;
		if ($y != $toY) $y += $dy;
	}

	$map[$toY][$toX] = $value;
}

function drawTraces(array &$map, array $traces): void {
	foreach ($traces as $trace) {
		$sx = NULL;
		$sy = NULL;
		foreach ($trace as $i => [$x, $y]) {
			if ($i !== 0) {
				drawLine($map, $sx, $sy, $x, $y, '#');
			}

			$sx = $x;
			$sy = $y;
		}
	}
	$map[0][500] = '+';
}

function simulate(array &$map, array &$falling): bool {
	$moves = [[0, 1], [-1, 1], [1, 1]];

	while (!empty($falling)) {
		[$x, $y] = array_shift($falling);

		foreach ($moves as [$ox, $oy]) {
			if (!isset($map[$y + $oy][$x + $ox])) {
				$map[$y][$x] = '.';
				return TRUE;
			}

			if ($map[$y + $oy][$x + $ox] === '.') {
				$falling[] = [$x + $ox, $y + $oy];
				continue 2;
			}
		}

		$map[$y][$x] = 'o';
	}

	return FALSE;
}

function printMap(array &$map): void {
	echo implode(PHP_EOL, array_map(fn(array $row) => implode('', $row), $map)) . PHP_EOL;
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	['x' => [$minX, $maxX], 'y' => [, $maxY]] = $input['size'];
	$minY = 0;
	$maxY++;
	$maxX++;
	$map = createMap($minX, $maxX - $minX, $minY, $maxY - $minY, '.');

	drawTraces($map, $input['data']);

	$falling = [];
	$step = 0;
	while (!simulate($map, $falling)) {
		$step++;

		if ($step % 2 === 0) {
			$falling[] = [500, 0];
		}
	}

	return count(array_filter(array_merge(...$map), fn($value) => $value === 'o'));
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	['x' => [$minX, $maxX], 'y' => [, $maxY]] = $input['size'];

	$traces = $input['data'];
	$traces[] = [[$minX - $maxY - 3, $maxY + 2], [$maxX + $maxY + 3, $maxY + 2]];
	$minX = array_reduce(array_map(fn($point) => $point[0], array_merge(...$traces)), fn(int $carry, $v) => min($carry, $v), PHP_INT_MAX);
	$maxX = array_reduce(array_map(fn($point) => $point[0], array_merge(...$traces)), fn(int $carry, $v) => max($carry, $v), 0);
	$maxY = array_reduce(array_map(fn($point) => $point[1], array_merge(...$traces)), fn(int $carry, $v) => max($carry, $v), 0);

	$minY = 0;
	$maxY++;
	$maxX++;
	$map = createMap($minX, ($maxX - $minX), $minY, $maxY - $minY, '.');

	drawTraces($map, $traces);

	$falling = [];
	$step = 0;
	while ($map[0][500] !== 'o') {
		$step++;

		if ($step % 2 === 0) {
			$falling[] = [500, 0];
		}

		simulate($map, $falling);
	}

	return count(array_filter(array_merge(...$map), fn($value) => $value === 'o'));
}

return [test([24, 93]), run()];
