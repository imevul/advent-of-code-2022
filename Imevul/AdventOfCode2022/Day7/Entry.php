<?php

namespace Imevul\AdventOfCode2022\Day7;

class Entry {
	public function __construct(public string $name, public ?int $size = NULL, public ?Directory $parent = NULL) {}

	public function __toString(): string {
		return "$this->name ($this->size)";
	}

	public function prettyPrint(int $indent = 0): string {
		return str_repeat('  ', $indent) . $this . PHP_EOL;
	}
}