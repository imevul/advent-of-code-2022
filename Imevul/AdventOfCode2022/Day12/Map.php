<?php

namespace Imevul\AdventOfCode2022\Day12;

class Map {
	public array $startPosition;
	public array $endPosition;

	public function __construct(public array $data) {
		$this->setStartEnd();
	}

	public function setStartEnd(): void {
		foreach ($this->data as $y => $row) {
			foreach ($row as $x => $node) {
				if ($node->value === 'S') {
					$this->startPosition = [$x, $y];
				} elseif ($node->value === 'E') {
					$this->endPosition = [$x, $y];
				}
			}
		}
	}

	public function getSize(): array {
		return [count($this->data[0]), count($this->data)];
	}

	public function getNode(int $x, int $y): ?Node {
		return $this->data[$y][$x] ?? NULL;
	}

	public function getStartNode(): Node {
		return $this->getNode($this->startPosition[0], $this->startPosition[1]);
	}

	public function getEndNode(): Node {
		return $this->getNode($this->endPosition[0], $this->endPosition[1]);
	}

	public function __toString(): string {
		return implode(PHP_EOL, array_map(fn($line) => implode('', $line), $this->data));
	}
}