<?php

namespace Imevul\AdventOfCode2022\Day10;

use Closure;

class CPU {
	public int $cycle = 0;
	public int $x = 1;
	public ?Closure $handler;
	protected array $availableInstructions = ['addx', 'noop'];

	public function __construct(public array $instructions) {}

	public function registerIRQHandler(Closure $handler): void {
		$this->handler = $handler;
	}

	public function execute(string $instruction, ...$params): void {
		if (!in_array($instruction, $this->availableInstructions)) return;

		$this->{$instruction}($params[0] ?? NULL);
	}

	public function run(): void {
		foreach ($this->instructions as $instruction) {
			$this->execute(...$instruction);
		}
	}

	public function getSignalStrength(): int {
		return $this->cycle * $this->x;
	}

	public function tick(): void {
		$this->cycle++;
		$this->irq();
	}

	protected function irq(): void {
		$this->handler?->call($this);
	}

	public function addx(int $v): void {	// 2 cycles
		$this->tick();
		$this->tick();
		$this->x += $v;
	}

	public function noop(): void {	// 1 cycle
		$this->tick();
	}
}