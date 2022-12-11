<?php

namespace Imevul\AdventOfCode2022\Day11;

class Monkey {
	public static array $monkeys = [];
	public static int $gcd = 0;
	public int $inspectCounter = 0;
	public bool $decreaseWorry = TRUE;

	public function __construct(public int $id, public array $items, public array $operation, public int $test, public array $actions) {
		$this->operation = [
			$operation[0],
			$operation[1] === 'old' ? NULL : intval($operation[1])
		];
		self::addMonkey($this);
	}

	public function inspect(int $item): void {
		$this->inspectCounter++;

		$item = match($this->operation[0]) {
				'+',  => $item + ($this->operation[1] ?? $item),
				'*',  => $item * ($this->operation[1] ?? $item)
			} % self::$gcd;

		if ($this->decreaseWorry) {
			$item = floor($item / 3);
		}

		$monkey = self::getMonkey($this->actions[$item % $this->test === 0]);
		$monkey->items[] = $item;
	}

	public function doTurn(): void {
		while (!empty($this->items)) {
			$this->inspect(array_shift($this->items));
		}
	}

	public static function clearMonkeys(): void {
		self::$monkeys = [];
	}
	public static function addMonkey(Monkey $monkey): void {
		self::$monkeys[] = $monkey;
		self::$gcd = array_reduce(
			array_map(fn(Monkey $m) => $m->test, self::$monkeys),
			fn(int $carry, int $div) => $carry * $div,
			1
		);
	}

	public static function getMonkey(int $id): ?Monkey {
		foreach (self::$monkeys as $monkey) {
			if ($id === $monkey->id) {
				return $monkey;
			}
		}

		return NULL;
	}
}
