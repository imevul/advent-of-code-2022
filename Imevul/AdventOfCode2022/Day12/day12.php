<?php
/*
 * Hill Climbing Algorithm
 * https://adventofcode.com/2022/day/12
 */

namespace Imevul\AdventOfCode2022\Day12;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    $data = array_map(fn($line) => mb_str_split($line), $input);
	$mapData = [];
	foreach ($data as $y => $row) {
		$rowData = [];
		foreach ($row as $x => $value) {
			$rowData[] = new Node($x, $y, $value);
		}
		$mapData[] = $rowData;
	}

	return $mapData;
}

function reconstructPath(array $cameFrom, Node $current): array {
	$totalPath = [$current];
	while (in_array($current->getId(), array_keys($cameFrom))) {
		$current = $cameFrom[$current->getId()];
		array_unshift($totalPath, $current);
	}

	return $totalPath;
}

function aStar(Map $map, Node $start, Node $goal, callable $h, callable $d, array $neighbourOffsets): array {
	$openSet = [$start];
	$cameFrom = [];
	$gScore = [];
	$gScore[$start->getId()] = 0;
	$fScore = [];
	$fScore[$start->getId()] = 0;

	while (!empty($openSet)) {
		usort(
			$openSet,
			fn(Node $a, Node $b) => ($fScore[$a->getId()] ?? Node::$maxScore) === ($fScore[$b->getId()] ?? Node::$maxScore) ?
				0 :
				normalize(($fScore[$a->getId()] ?? Node::$maxScore) - ($fScore[$b->getId()] ?? Node::$maxScore))
		);
		$current = array_shift($openSet);

		if ($current === $goal) {
			return reconstructPath($cameFrom, $current);
		}

		foreach ($neighbourOffsets as [$ox, $oy]) {
			$neighbour = $map->getNode($current->x + $ox, $current->y + $oy);
			if ($neighbour === NULL) continue;

			$tmpGScore = ($gScore[$current->getId()] ?? Node::$maxScore) + $d($current, $neighbour);
			if ($tmpGScore < ($gScore[$neighbour->getId()] ?? Node::$maxScore)) {
				$cameFrom[$neighbour->getId()] = $current;
				$gScore[$neighbour->getId()] = $tmpGScore;
				$fScore[$neighbour->getId()] = $tmpGScore + $h($neighbour);

				if (!in_array($neighbour, $openSet)) {
					$openSet[] = $neighbour;
				}
			}
		}
	}

	return [];
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
	$map = new Map($input);

	$path = aStar($map, $map->getStartNode(), $map->getEndNode(), fn() => 1,
		function(Node $current, Node $neighbour) {
			$diff = $current->getDiff($neighbour);
			return $diff <= 1 ? 1 : Node::$maxScore;
		}, [[-1, 0], [1, 0], [0, -1], [0, 1]]
	);

	return count($path) - 1;
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	$map = new Map($input);

	$potentialStartNodes = array_merge(...array_map(fn(array $row) => array_filter($row, fn(Node $n) => $n->value === 'a' || $n->value === 'S'), $map->data));

	$fewest = PHP_INT_MAX;
	foreach ($potentialStartNodes as $start) {
		$path = aStar($map, $start, $map->getEndNode(), fn() => 1,
			function(Node $current, Node $neighbour) {
				$diff = $current->getDiff($neighbour);
				return $diff <= 1 ? 1 : Node::$maxScore;
			}, [[-1, 0], [1, 0], [0, -1], [0, 1]]
		);

		if (empty($path)) continue;
		$fewest = min($fewest, count($path) - 1);
	}

	return $fewest;
}

return [test([31, 29]), run()];
