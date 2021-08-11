<?php declare(strict_types = 1);

define('NONE', 0x0);
define('LETTER', 0x1);
define('PUNCTUATION', 0x2);
define('SYMBOL', 0x4);
define('ERROR_STR', 'Program exited with error code: %1$s'."\n");

$filename = '';
$format = '';
$flags = NONE;

foreach ($argv as $i => $arg) {
	if ($i == 0) {
		continue;
	}

	foreach(['-i=', '-input='] as $flag) {
		$pos = strpos($arg, $flag);

		if ($pos !== false) {
			$filename = substr($arg, $pos + strlen($flag));
			continue 2;
		}
	}

	foreach(['-f=', '-format='] as $flag) {
		$pos = strpos($arg, $flag);

		if ($pos !== false) {
			$format = substr($arg, $pos + strlen($flag));
			continue 2;
		}
	}

	if ($arg === '-L' || $arg === '--include-letter') {
		$flags |= LETTER;
	}
	elseif ($arg === '-P' || $arg === '--include-punctuation') {
		$flags |= PUNCTUATION;
	}
	elseif ($arg ===  '-S' || $arg === '--include-symbol') {
		$flags |= SYMBOL;
	}
}

if ($filename === '') {
	echo sprintf(ERROR_STR, 1);
	exit(1);
}

if (file_exists($filename)) {
	$contents = file_get_contents($filename);
}
else {
	echo sprintf(ERROR_STR, 2);
	exit(1);
}

if ($contents === '') {
	echo sprintf(ERROR_STR, 2);
	exit(2);
}

$i = 0;
$characters = [];
$punctuations = [];
$symbols = [];

while (isset($contents[$i])) {
	$chr = $contents[$i];

	if (($chr >= '!' && $chr <= '/') || ($chr >= ':' && $chr <= '@') || ($chr >= '[' && $chr <= '~')) {
		if ($chr >= 'a' && $chr <= 'z') {
			if (!array_key_exists($chr, $characters)) {
				$characters[$chr] = 0;
			}
			$characters[$chr]++;
		}
		elseif ($chr === '$' || $chr === '+' || ($chr >= '<' && $chr <= '>') || $chr === '^'
				|| $chr === '`' || $chr === '|' || $chr === '~') {
			if (!array_key_exists($chr, $symbols)) {
				$symbols[$chr] = 0;
			}
			$symbols[$chr]++;
		}
		else {
			if (!array_key_exists($chr, $punctuations)) {
				$punctuations[$chr] = 0;
			}
			$punctuations[$chr]++;
		}
	}
	else {
		echo sprintf(ERROR_STR, 2);
		exit(2);
	}

	$i++;
}

// Check the contents. There must be at least of each - a letter, a symbol and a punctuation.
if (!$characters || !$punctuations || !$symbols) {
	echo sprintf(ERROR_STR, 2);
	exit(2);
}

if (!in_array($format, ['non-repeating', 'least-repeating', 'most-repeating'])) {
	echo sprintf(ERROR_STR, 3);
	exit(3);
}

if ($flags == NONE) {
	echo sprintf(ERROR_STR, 4);
	exit(4);
}

/**
 * Finds the non-repeating, least repeating or most repeating letter, punctuation or symbol. And then returns a
 * formatted resulting string.
 *
 * @param string $format  The input format. Possible values: "non-repeating", "least-repeating", "most-repeating".
 * @param array $array    Array of letters, punctuations and symbols. Key is the character and value is the number of
 *                        occurences.
 *
 * @return string
 */
function getResult(string $format, array $array): string {
	switch ($format) {
		case 'non-repeating':
			$found = array_keys($array, 1);
			if ($found) {
				$val = $found[0];

				if ($val === '%') {
					$val = '%%%';
				}
			}
			else {
				$val = 'None';
			}

			return 'First non-repeating %1$s: '.$val;

		case 'least-repeating':
			$diff = array_diff($array, [1]);
			if ($diff) {
				$min = min(array_values($diff));
				$val = array_keys($array, $min)[0];

				if ($val === '%') {
					$val = '%%%';
				}
			}
			else {
				$val = 'None';
			}

			return 'First least repeating %1$s: '.$val;

		case 'most-repeating':
			$diff = array_diff($array, [1]);
			if ($diff) {
				$max = max(array_values($diff));
				$val = array_keys($array, $max)[0];

				if ($val === '%') {
					$val = '%%%';
				}
			}
			else {
				$val = 'None';
			}

			return 'First most repeating %1$s: '.$val;
	}
}

echo 'File: '.$filename."\n";
if (LETTER == ($flags & LETTER)) {
	echo sprintf(getResult($format, $characters), 'letter')."\n";
}

if (PUNCTUATION == ($flags & PUNCTUATION)) {
	//echo getResult($format, $punctuations);
	echo sprintf(getResult($format, $punctuations), 'punctuation')."\n";
}

if (SYMBOL == ($flags & SYMBOL)) {
	echo sprintf(getResult($format, $symbols), 'symbol')."\n";
}
