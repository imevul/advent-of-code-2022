<?php

namespace Imevul\AdventOfCode2022\Day2;

enum RPSMove : string {
	case Rock = 'Rock';
	case Paper = 'Paper';
	case Scissors = 'Scissors';

	public function compare(self $move): int {
		if ($move === $this) {
			return 0;
		}

		return match ($move) {
			self::Rock => $this === self::Paper,
			self::Paper => $this === self::Scissors,
			self::Scissors => $this === self::Rock
		} ? 1 : -1;
	}

	public function winsAgainst(): self {
		return match ($this) {
			self::Rock => self::Scissors,
			self::Paper => self::Rock,
			self::Scissors => self::Paper
		};
	}

	public function drawsAgainst(): self {
		return $this;
	}

	public function losesAgainst(): self {
		return match ($this) {
			self::Rock => self::Paper,
			self::Paper => self::Scissors,
			self::Scissors => self::Rock
		};
	}
}
