<?php

namespace Imevul\AdventOfCode2022\Day12;

class Node {
	public static int $maxScore = 10000;
	public function __construct(public int $x, public int $y, public string $value) {
	}

	public function getId(): string {
		return "$this->x,$this->y";
	}

	public function getIntVal(): int {
		if ($this->value === 'S') return ord('a');
		if ($this->value === 'E') return ord('z');
		return ord($this->value);
	}

	public function getDiff(Node $destination): int {
		return $destination->getIntVal() - $this->getIntVal();
	}

	public function __toString(): string {
		return $this->value;
	}
}