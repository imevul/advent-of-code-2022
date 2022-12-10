<?php

namespace Imevul\AdventOfCode2022\Day9;

class Rope {
	public array $knots = [];
	public function __construct(int $length = 2) {
		$this->knots = array_fill(0, $length, [0, 0]);
	}

	public function move(int $dx, int $dy): array {
		$sx = normalize($dx);
		$sy = normalize($dy);
		$targetX = $this->knots[0][0] + $dx;
		$targetY = $this->knots[0][1] + $dy;
		$tailLog = [];

		while ($this->knots[0][0] != $targetX || $this->knots[0][1] != $targetY) {
			$this->knots[0][0] += $sx;
			$this->knots[0][1] += $sy;

			foreach ($this->knots as $index => &$knot) {
				if ($index === 0) continue;
				$prevKnot = $this->knots[$index - 1];

				$diffX = $prevKnot[0] - $knot[0];
				$diffY = $prevKnot[1] - $knot[1];

				if (abs($diffX) >= 2 || abs($diffY) >= 2) {
					$knot[0] += normalize($diffX);
					$knot[1] += normalize($diffY);
				}
			}
			$tail = $this->knots[count($this->knots) - 1];
			$tailLog[] = "$tail[0],$tail[1]";
		}

		return $tailLog;
	}
}