<?php
/*
 *  Space Left On Device
 * https://adventofcode.com/2022/day/7
 */

namespace Imevul\AdventOfCode2022\Day7;

use Exception;

require_once __DIR__ . '/../../../bootstrap.php';

function getConvertedInput(array $input): array {
    return $input;
}

function mapStorage(array $terminalOutput): Directory {
	$storage = new Directory('/');

	foreach ($terminalOutput as $line) {
		processLine($storage, $line);
	}

	return $storage->cd('/');
}

/**
 * @throws Exception
 */
function processLine(Directory &$storage, string $line): void {
	if (str_starts_with($line, '$ ')) {
		$commandInput = mb_substr($line, 2);
		$parts = explode(' ', $commandInput);
		$command = $parts[0];
		$params = $parts[1] ?? NULL;

		$storage = match ($command) {
			'cd' => $storage->cd($params),
			'ls' => $storage,
			default => throw new Exception('Unknown command: ' . $command),
		};
		return;
	}

	if (str_starts_with($line, 'dir')) {
		$dirName = mb_substr($line, 4);
		$storage->addDirectory($dirName);
		return;
	}

	[$size, $filename] = explode(' ', $line);
	$storage->addFile($filename, intval($size));
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part1(array $input): int {
    $storage = mapStorage($input);
	$storage->calculateSize();

	/** @var Directory[] $directories */
	$directories = $storage->getDirectories();
	return array_sum(
		array_map(
			fn(Directory $directory) => $directory->size,
			array_filter($directories, fn(Directory $directory) => $directory->size <= 100000)
		)
	);
}

/**
 * @param array $input The list of input
 * @return int The result
 */
function part2(array $input): int {
	$maxSpace = 70000000;
	$updateSize = 30000000;
	$storage = mapStorage($input);
	$storage->calculateSize();

	$unusedSpace = $maxSpace - $storage->size;
	$spaceNeeded = $updateSize - $unusedSpace;

	/** @var Directory[] $directories */
	$directories = $storage->getDirectories();
	$directories = array_filter($directories, fn(Directory $directory) => $directory->size > $spaceNeeded);
	usort($directories, fn(Directory $a, Directory $b) => compare($a->size, $b->size));

	return $directories[0]->size;
}

return [test([95437, 24933642]), run(empty($skipRun))];
