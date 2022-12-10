<?php

namespace Imevul\AdventOfCode2022\Day7;

class Directory extends Entry {
	public function __construct(public string $name, public ?int $size = NULL, public ?Directory $parent = NULL, /** @var Entry[] */public array $entries = []) {
		parent::__construct($name, $size, $this->parent);
	}

	public function entryExists(string $name): bool {
		foreach ($this->entries as $entry) {
			if ($entry->name === $name) return TRUE;
		}

		return FALSE;
	}

	public function addEntry(Entry $entry): void {
		if ($this->entryExists($entry->name)) return;

		$this->entries[] = $entry;
	}

	public function addDirectory(string $name): void {
		$this->addEntry(new Directory(name: $name, parent: $this));
	}

	public function addFile(string $name, int $size): void {
		$this->addEntry(new File(name: $name, size: $size, parent: $this));
	}

	public function cd(string $params): Directory {
		switch ($params) {
			case '/':
				if ($this->parent === NULL) return $this;
				return $this->parent->cd('/');
			case '..':
				if ($this->parent === NULL) return $this;
				return $this->parent;
			default:
				$directories = array_filter($this->entries, fn(Entry $entry) => $entry instanceof Directory);
				foreach ($directories as $directory) {
					if ($directory->name === $params) {
						return $directory;
					}
				}
		}

		return $this;
	}

	public function calculateSize(): void {
		if ($this->size !== NULL) {
			return;
		}

		$totalSize = 0;

		foreach ($this->entries as $entry) {
			if ($entry instanceof Directory) {
				$entry->calculateSize();
			}
			$totalSize += $entry->size;
		}

		$this->size = $totalSize;
	}

	public function getDirectories(): array {
		$items = [];

		foreach ($this->entries as $entry) {
			if ($entry instanceof Directory) {
				$items[] = $entry;
				array_push($items, ...$entry->getDirectories());
			}
		}

		return $items;
	}

	public function prettyPrint(int $indent = 0): string {
		$sub = '';
		foreach ($this->entries as $entry) {
			$sub .= $entry->prettyPrint($indent + 1);
		}

		return str_repeat('  ', $indent) . $this . PHP_EOL . $sub;
	}
}