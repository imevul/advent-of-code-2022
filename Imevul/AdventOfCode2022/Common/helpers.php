<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/../../..'));
}

/**
 * Get the puzzle input in a format we can handle.
 * @param bool $useTestData True to use input_test.txt (if it exists)
 * @return array<int>
 */
function getInput(?string $dir = NULL, bool $useTestData = FALSE): array {
    $filename = $useTestData ? 'input_test.txt' : 'input.txt';
    $dir = !empty($dir) ? $dir . DIRECTORY_SEPARATOR  : '';

    $contents = file_get_contents(realpath($dir . $filename));

    return explode(PHP_EOL, $contents);
}

function normalize(int $value): int {
	if ($value === 0) return 0;
	return $value / abs($value);
}

/**
 * Create a 2D array with specified dimensions
 * @param int $fromX Start X-index
 * @param int $sizeX X-size
 * @param int|null $fromY Start Y-index (NULL = same as X-index)
 * @param int|null $sizeY Y-Size (NULL = same as X-size)
 * @param mixed $default Default value of each cell
 * @return array
 */
function createMap(int $fromX, int $sizeX, ?int $fromY = NULL, ?int $sizeY = NULL, mixed $default = 0): array {
    $fromY ??= $fromX;
    $sizeY ??= $sizeX;

    return array_fill($fromY, $sizeY, array_fill($fromX, $sizeX, $default));
}

/**
 * Split an array into chunks based on an item value in the array
 * @param array $arr The array to split
 * @param mixed $delimiter The value to split on
 * @return array
 */
function array_split(array &$arr, mixed $delimiter): array {
	$chunk = [];
	$chunks = [];

	foreach ($arr as $value) {
		if ($delimiter === $value) {
			$chunks[] = $chunk;
			$chunk = [];
			continue;
		}

		$chunk[] = $value;
	}
	$chunks[] = $chunk;

	return $chunks;
}

/**
 * count($arr) pick $count. Only unique permutations. Sorted
 * @param array $arr The array to pick from
 * @param int $count How many to pick in each permutation
 * @return array
 */
function array_pick(array &$arr, int $count): array {
	$iterators = [];
	for ($i = 0; $i < $count; $i++) {
		$iterators[$i] = (new ArrayObject($arr))->getIterator();

		for ($j = 0; $j < $i; $j++) {
			$iterators[$i]->next();
		}
	}

	$result = [];
	$items = [];

	foreach ($iterators as $i => $it) {
		$items[$i] = $it->current();
	}

	$i = count($iterators) - 1;

	while ($i >= 0) {
		/** @var Iterator $it */
		$it = $iterators[$i];

		while ($it->valid()) {
			$items[$i] = $it->current();
			$it->next();

			if ($i < count($iterators) - 1) {
				$i++;
				continue 2;
			}

			if (count(array_unique($items)) === $count) {
				$tmp = $items;
				sort($tmp);
				if (!in_array($tmp, $result)) {
					$result[] = $tmp;
				}
			}
		}
		$it->rewind();
		$i--;
	}

	return $result;
}

/**
 * Compare two values. If the inputs are arrays, compare each element against each other.
 * @param mixed $v1 First value
 * @param mixed $v2 Second value
 * @return int|array 0 if they are equal. 1 if $v1 > $v2. -1 if $v2 > $v1.
 */
function compare(mixed $v1, mixed $v2): int|array {
    if (is_array($v1) && is_array($v2)) {
        return array_map('compare', $v1, $v2);
    }

    if ($v1 === $v2) return 0;
    return $v1 > $v2 ? 1 : -1;
}

/**
 * Output a timestamped line with values.
 * @param mixed ...$args Values to print (must be auto-convertable to string)
 */
function output(...$args): void {
    echo sprintf('[%s] %s', date('H:i:s'), implode(' ', $args)) . PHP_EOL;
}

/**
 * @param mixed ...$args Values to print
 */
function d(...$args): void {
    echo sprintf('[%s]', date('H:i:s')) . PHP_EOL;
    var_dump(...$args);
}

/**
 * @param mixed ...$args Values to print
 */
function dd(...$args): void {
    var_dump(...$args);
    die;
}

/**
 * Asserts that two values are equal.
 * @param mixed $v1 First value
 * @param mixed $v2 Second value
 * @param string|null $name Optional name of assertion
 * @param bool $onlyReturn True to not trigger any errors
 * @return bool
 */
function assertEquals(mixed $v1, mixed $v2, ?string $name = NULL, bool $onlyReturn = FALSE): bool {
    if (is_array($v1) && is_array($v2)) {
        $i = 1;
        return array_reduce(
            array_map(
                function($w1, $w2) use ($onlyReturn, $name, &$i) {
                    return assertEquals($w1, $w2, $name . ($i++), $onlyReturn);
                }, $v1, $v2
            ),
            fn($c, $v) => $c && $v,
            TRUE
        );
    }

    if ($onlyReturn) {
        return $v1 === $v2;
    }

    return assert($v1 === $v2, sprintf('%s: Failed to assert that %s === %s', $name, $v1, $v2));
}

function runParts(bool $useTestData = FALSE): array {
    $key = array_search(__FUNCTION__, array_column(debug_backtrace(), 'function')) + 1;
    $file = realpath(debug_backtrace()[$key]['file']);
    $dir = dirname($file);
    $namespace = str_replace(BASE_PATH, '', $dir);
    $namespace = str_replace(DIRECTORY_SEPARATOR, '\\', $namespace);
    $isMain = realpath(getcwd()) == $dir;
    $getConvertedInput = $namespace . '\\getConvertedInput';
    $part1 = $namespace . '\\part1';
    $part2 = $namespace . '\\part2';
    $input = $getConvertedInput(getInput($dir, $useTestData));
    return [[$part1($input), $part2($input)], $isMain];
}

/**
 * Assert that using test data for part1 and part2 in the caller namespace matches the expected result
 * @param array $expected Expected data
 * @return bool|bool[]
 */
function test(array $expected): array|bool {
    [$parts, $isMain] = runParts(TRUE);

    if ($isMain) {
        return assertEquals($parts, $expected, 'Part');
    } else {
        return array_map(fn($v1, $v2) => assertEquals($v1, $v2, NULL, !$isMain), $parts, $expected);
    }
}

/**
 * Run part1 and part2 in the caller namespace and return the result
 * @param bool $skip
 * @return array
 */
function run(bool $skip = FALSE): array {
    if ($skip) {
        [$parts, $output] = [['-', '-'], FALSE];
    } else {
        [$parts, $output] = runParts();
    }

    if ($output) {
        output('Part1', $parts[0]);
        output('Part2', $parts[1]);
    }

    return $parts;
}
