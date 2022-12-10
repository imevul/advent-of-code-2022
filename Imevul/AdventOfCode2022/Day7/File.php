<?php

namespace Imevul\AdventOfCode2022\Day7;

class File extends Entry {
	public function __construct(public string $name, public ?int $size = NULL, public ?Directory $parent = NULL) {
		parent::__construct($name, $size, $this->parent);
	}
}