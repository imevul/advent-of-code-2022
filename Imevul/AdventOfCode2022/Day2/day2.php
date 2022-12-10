<?php

/*
 * Rock Paper Scissors
 * https://adventofcode.com/2022/day/2
 */

namespace Imevul\AdventOfCode2022\Day2;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return array_map(fn($line) => explode(' ', $line), $input);
}

function getRules() : array {
	return [
		[	// Opponent moves
			'A' => RPSMove::Rock,
			'B' => RPSMove::Paper,
			'C' => RPSMove::Scissors
		],
		[	// Move score
			RPSMove::Rock->value => 1,
			RPSMove::Paper->value => 2,
			RPSMove::Scissors->value => 3
		],
		[	// Round score
			-1 => 0,
			0 => 3,
			1 => 6
		]
	];
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input) : int {
	[$opponentMoves, $moveScores, $roundScores] = getRules();

	$myMoves = [
		'X' => RPSMove::Rock,
		'Y' => RPSMove::Paper,
		'Z' => RPSMove::Scissors
	];

	return array_sum(
		array_map(
			fn($moves) => $roundScores[$moves[1]->compare($moves[0])] + $moveScores[$moves[1]->value],
			array_map(fn($moves) => [$opponentMoves[$moves[0]], $myMoves[$moves[1]]], $input)
		)
	);
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	[$opponentMoves, $moveScores, $roundScores] = getRules();

	$myMoves = [
		'X' => fn(RPSMove $move) => $move->winsAgainst(),
		'Y' => fn(RPSMove $move) => $move->drawsAgainst(),
		'Z' => fn(RPSMove $move) => $move->losesAgainst()
	];

	return array_sum(
		array_map(
			fn($moves) => $roundScores[$moves[1]->compare($moves[0])] + $moveScores[$moves[1]->value],
			array_map(fn($moves) => [$opponentMoves[$moves[0]], $myMoves[$moves[1]]($opponentMoves[$moves[0]])], $input)
		)
	);
}

return [test([15, 12]), run()];
