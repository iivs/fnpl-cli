<?php
use PHPUnit\Framework\TestCase;

/**
 * Test all the possible inputs and outputs of the homework assignment script.
 */
class AssignmentTest extends TestCase
{
    /**
     * Path and filename to script which is tested.
     *
     * @var string
     */
    private $cmd = 'php '.__DIR__.'/../test.php ';

    /**
     * Directory for test files with contents to check.
     *
     * @var string
     */
    private $dir = __DIR__.'/data';

    /**
     * An array if filenames found in data directory. File names should have prefix "valid" or "invalid".
     *
     * @var array
     */
    private $files = [];

    /**
     * Expected results for each file, format and flag. And new file names here and follow the format:
     *  [<filename>] => [
     *      <cmd_format> => [
     *          <cmd_flags> = [
     *              <flag> => <expected result>
     *          ]
     *      ]
     *  ]
     *
     * Where:
     * - <filename>         any text file of any extention;
     * - <cmd_format>       format in the command line "non-repeating", "least-repeating" or "most-repeating" or all
     *                      three. Also will be converted to word according to formatToWord();
     * - <cmd_flags>        command line flags "-L", "-P", "-S" at least one, or all or in any order;
     * - <flag>             will be converted to word according to flagToWord(). If <cmd_flags> is given all three,
     *                      results will follow in order letter, punctuation, symbol;
     * - <expected result>  resulting character or "None".
     *
     * @var array
     */
    private $results = [
        'valid_all_short.txt' => [
            'non-repeating' => [
                // Variations of output: all, some or one.
                '-L -P -S' => [
                    '-L' => 'd', '-P' => '_', '-S' => '<'
                ],
                '-L -P' => [
                    '-L' => 'd', '-P' => '_'
                ],
                '-L -S' => [
                    '-L' => 'd', '-S' => '<'
                ],
                '-P -S' => [
                    '-P' => '_', '-S' => '<'
                ],
                '-L' => [
                    '-L' => 'd'
                ],
                '-P' => [
                    '-P' => '_'
                ],
                '-S' => [
                    '-S' => '<'
                ]
            ],
            'least-repeating' => [
                '-L -P -S' => [
                    '-L' => 'a', '-P' => '&', '-S' => '+'
                ],
                '-L -P' => [
                    '-L' => 'a', '-P' => '&'
                ],
                '-L -S' => [
                    '-L' => 'a', '-S' => '+'
                ],
                '-P -S' => [
                    '-P' => '&', '-S' => '+'
                ],
                '-L' => [
                    '-L' => 'a'
                ],
                '-P' => [
                    '-P' => '&'
                ],
                '-S' => [
                    '-S' => '+'
                ]
            ],
            'most-repeating' => [
                '-L -P -S' => [
                    '-L' => 'b', '-P' => '!', '-S' => '>'
                ],
                '-L -P' => [
                    '-L' => 'b', '-P' => '!'
                ],
                '-L -S' => [
                    '-L' => 'b', '-S' => '>'
                ],
                '-P -S' => [
                    '-P' => '!', '-S' => '>'
                ],
                '-L' => [
                    '-L' => 'b'
                ],
                '-P' => [
                    '-P' => '!'
                ],
                '-S' => [
                    '-S' => '>'
                ]
            ]
        ],
        'valid_has_no_letters.txt' => [
            'non-repeating' => [
                '-L -P -S' => [
                    '-L' => 'b', '-P' => '.', '-S' => '|'
                ],
                '-L -P' => [
                    '-L' => 'b', '-P' => '.'
                ],
                '-L -S' => [
                    '-L' => 'b', '-S' => '|'
                ],
                '-P -S' => [
                    '-P' => '.', '-S' => '|'
                ],
                '-L' => [
                    '-L' => 'b'
                ],
                '-P' => [
                    '-P' => '.'
                ],
                '-S' => [
                    '-S' => '|'
                ]
            ],
            'least-repeating' => [
                '-L -P -S' => [
                    '-L' => 'None', '-P' => '&', '-S' => '+'
                ],
                '-L -P' => [
                    '-L' => 'None', '-P' => '&'
                ],
                '-L -S' => [
                    '-L' => 'None', '-S' => '+'
                ],
                '-P -S' => [
                    '-P' => '&', '-S' => '+'
                ],
                '-L' => [
                    '-L' => 'None'
                ],
                '-P' => [
                    '-P' => '&'
                ],
                '-S' => [
                    '-S' => '+'
                ]
            ],
            'most-repeating' => [
                '-L -P -S' => [
                    '-L' => 'None', '-P' => '@', '-S' => '^'
                ],
                '-L -P' => [
                    '-L' => 'None', '-P' => '@'
                ],
                '-L -S' => [
                    '-L' => 'None', '-S' => '^'
                ],
                '-P -S' => [
                    '-P' => '@', '-S' => '^'
                ],
                '-L' => [
                    '-L' => 'None'
                ],
                '-P' => [
                    '-P' => '@'
                ],
                '-S' => [
                    '-S' => '^'
                ]
            ]
        ],
        'valid_has_no_punctuations.txt' => [
            'non-repeating' => [
                '-L -P -S' => [
                    '-L' => 'x', '-P' => ',', '-S' => '<'
                ],
                '-L -P' => [
                    '-L' => 'x', '-P' => ','
                ],
                '-L -S' => [
                    '-L' => 'x', '-S' => '<'
                ],
                '-P -S' => [
                    '-P' => ',', '-S' => '<'
                ],
                '-L' => [
                    '-L' => 'x'
                ],
                '-P' => [
                    '-P' => ','
                ],
                '-S' => [
                    '-S' => '<'
                ]
            ],
            'least-repeating' => [
                '-L -P -S' => [
                    '-L' => 'a', '-P' => 'None', '-S' => '+'
                ],
                '-L -P' => [
                    '-L' => 'a', '-P' => 'None'
                ],
                '-L -S' => [
                    '-L' => 'a', '-S' => '+'
                ],
                '-P -S' => [
                    '-P' => 'None', '-S' => '+'
                ],
                '-L' => [
                    '-L' => 'a'
                ],
                '-P' => [
                    '-P' => 'None'
                ],
                '-S' => [
                    '-S' => '+'
                ]
            ],
            'most-repeating' => [
                '-L -P -S' => [
                    '-L' => 'e', '-P' => 'None', '-S' => '>'
                ],
                '-L -P' => [
                    '-L' => 'e', '-P' => 'None'
                ],
                '-L -S' => [
                    '-L' => 'e', '-S' => '>'
                ],
                '-P -S' => [
                    '-P' => 'None', '-S' => '>'
                ],
                '-L' => [
                    '-L' => 'e'
                ],
                '-P' => [
                    '-P' => 'None'
                ],
                '-S' => [
                    '-S' => '>'
                ]
            ]
        ],
        'valid_has_no_symbols.txt' => [
            'non-repeating' => [
                '-L -P -S' => [
                    '-L' => 'a', '-P' => '_', '-S' => '<'
                ],
                '-L -P' => [
                    '-L' => 'a', '-P' => '_'
                ],
                '-L -S' => [
                    '-L' => 'a', '-S' => '<'
                ],
                '-P -S' => [
                    '-P' => '_', '-S' => '<'
                ],
                '-L' => [
                    '-L' => 'a'
                ],
                '-P' => [
                    '-P' => '_'
                ],
                '-S' => [
                    '-S' => '<'
                ]
            ],
            'least-repeating' => [
                '-L -P -S' => [
                    '-L' => 'r', '-P' => '&', '-S' => 'None'
                ],
                '-L -P' => [
                    '-L' => 'r', '-P' => '&'
                ],
                '-L -S' => [
                    '-L' => 'r', '-S' => 'None'
                ],
                '-P -S' => [
                    '-P' => '&', '-S' => 'None'
                ],
                '-L' => [
                    '-L' => 'r'
                ],
                '-P' => [
                    '-P' => '&'
                ],
                '-S' => [
                    '-S' => 'None'
                ]
            ],
            'most-repeating' => [
                '-L -P -S' => [
                    '-L' => 'r', '-P' => '&', '-S' => 'None'
                ],
                '-L -P' => [
                    '-L' => 'r', '-P' => '&'
                ],
                '-L -S' => [
                    '-L' => 'r', '-S' => 'None'
                ],
                '-P -S' => [
                    '-P' => '&', '-S' => 'None'
                ],
                '-L' => [
                    '-L' => 'r'
                ],
                '-P' => [
                    '-P' => '&'
                ],
                '-S' => [
                    '-S' => 'None'
                ]
            ]

        ],
        'valid_has_none_all.txt' => [
            'non-repeating' => [
                '-L -P -S' => [
                    '-L' => 'a', '-P' => '_', '-S' => '+'
                ],
                '-L -P' => [
                    '-L' => 'a', '-P' => '_'
                ],
                '-L -S' => [
                    '-L' => 'a', '-S' => '+'
                ],
                '-P -S' => [
                    '-P' => '_', '-S' => '+'
                ],
                '-L' => [
                    '-L' => 'a'
                ],
                '-P' => [
                    '-P' => '_'
                ],
                '-S' => [
                    '-S' => '+'
                ]
            ],
            'least-repeating' => [
                '-L -P -S' => [
                    '-L' => 'None', '-P' => 'None', '-S' => 'None'
                ],
                '-L -P' => [
                    '-L' => 'None', '-P' => 'None'
                ],
                '-L -S' => [
                    '-L' => 'None', '-S' => 'None'
                ],
                '-P -S' => [
                    '-P' => 'None', '-S' => 'None'
                ],
                '-L' => [
                    '-L' => 'None'
                ],
                '-P' => [
                    '-P' => 'None'
                ],
                '-S' => [
                    '-S' => 'None'
                ]
            ],
            'most-repeating' => [
                '-L -P -S' => [
                    '-L' => 'None', '-P' => 'None', '-S' => 'None'
                ],
                '-L -P' => [
                    '-L' => 'None', '-P' => 'None'
                ],
                '-L -S' => [
                    '-L' => 'None', '-S' => 'None'
                ],
                '-P -S' => [
                    '-P' => 'None', '-S' => 'None'
                ],
                '-L' => [
                    '-L' => 'None'
                ],
                '-P' => [
                    '-P' => 'None'
                ],
                '-S' => [
                    '-S' => 'None'
                ]
            ]
        ],
        'valid_has_none_all_2txt' => [
            'non-repeating' => [
                '-L -P -S' => [
                    '-L' => 'None', '-P' => 'None', '-S' => 'None'
                ],
                '-L -P' => [
                    '-L' => 'None', '-P' => '_None'
                ],
                '-L -S' => [
                    '-L' => 'None', '-S' => 'None'
                ],
                '-P -S' => [
                    '-P' => 'None', '-S' => 'None'
                ],
                '-L' => [
                    '-L' => 'None'
                ],
                '-P' => [
                    '-P' => 'None'
                ],
                '-S' => [
                    '-S' => 'None'
                ]
            ],
            'least-repeating' => [
                '-L -P -S' => [
                    '-L' => 'a', '-P' => '&', '-S' => '$'
                ],
                '-L -P' => [
                    '-L' => 'a', '-P' => '&'
                ],
                '-L -S' => [
                    '-L' => 'a', '-S' => '$'
                ],
                '-P -S' => [
                    '-P' => '&', '-S' => '$'
                ],
                '-L' => [
                    '-L' => 'a'
                ],
                '-P' => [
                    '-P' => '&'
                ],
                '-S' => [
                    '-S' => '$'
                ]
            ],
            'most-repeating' => [
                '-L -P -S' => [
                    '-L' => 'a', '-P' => '&', '-S' => '$'
                ],
                '-L -P' => [
                    '-L' => 'a', '-P' => '&'
                ],
                '-L -S' => [
                    '-L' => 'a', '-S' => '$'
                ],
                '-P -S' => [
                    '-P' => '&', '-S' => '$'
                ],
                '-L' => [
                    '-L' => 'a'
                ],
                '-P' => [
                    '-P' => '&'
                ],
                '-S' => [
                    '-S' => '$'
                ]
            ]
        ],
        'valid_long.txt' => [
            'non-repeating' => [
                '-L -P -S' => [
                    '-L' => 't', '-P' => '}', '-S' => '~'
                ],
                '-L -P' => [
                    '-L' => 't', '-P' => '}'
                ],
                '-L -S' => [
                    '-L' => 't', '-S' => '~'
                ],
                '-P -S' => [
                    '-P' => '}', '-S' => '~'
                ],
                '-L' => [
                    '-L' => 't'
                ],
                '-P' => [
                    '-P' => '}'
                ],
                '-S' => [
                    '-S' => '~'
                ]
            ],
            'least-repeating' => [
                '-L -P -S' => [
                    '-L' => 'y', '-P' => '#', '-S' => '>'
                ],
                '-L -P' => [
                    '-L' => 'y', '-P' => '#'
                ],
                '-L -S' => [
                    '-L' => 'y', '-S' => '>'
                ],
                '-P -S' => [
                    '-P' => '#', '-S' => '>'
                ],
                '-L' => [
                    '-L' => 'y'
                ],
                '-P' => [
                    '-P' => '#'
                ],
                '-S' => [
                    '-S' => '>'
                ]
            ],
            'most-repeating' => [
                '-L -P -S' => [
                    '-L' => 'd', '-P' => '&', '-S' => '$'
                ],
                '-L -P' => [
                    '-L' => 'd', '-P' => '&'
                ],
                '-L -S' => [
                    '-L' => 'd', '-S' => '$'
                ],
                '-P -S' => [
                    '-P' => '&', '-S' => '$'
                ],
                '-L' => [
                    '-L' => 'd'
                ],
                '-P' => [
                    '-P' => '&'
                ],
                '-S' => [
                    '-S' => '$'
                ]
            ]
        ],
        'valid_very_long.txt' => [
            'non-repeating' => [
                '-L -P -S' => [
                    '-L' => 'None', '-P' => 'None', '-S' => 'None'
                ],
                '-L -P' => [
                    '-L' => 'None', '-P' => 'None'
                ],
                '-L -S' => [
                    '-L' => 'None', '-S' => 'None'
                ],
                '-P -S' => [
                    '-P' => 'None', '-S' => 'None'
                ],
                '-L' => [
                    '-L' => 'None'
                ],
                '-P' => [
                    '-P' => 'None'
                ],
                '-S' => [
                    '-S' => 'None'
                ]
            ],
            'least-repeating' => [
                '-L -P -S' => [
                    '-L' => 's', '-P' => "'", '-S' => '<'
                ],
                '-L -P' => [
                    '-L' => 's', '-P' => "'"
                ],
                '-L -S' => [
                    '-L' => 's', '-S' => '<'
                ],
                '-P -S' => [
                    '-P' => "'", '-S' => '<'
                ],
                '-L' => [
                    '-L' => 's'
                ],
                '-P' => [
                    '-P' => "'"
                ],
                '-S' => [
                    '-S' => '<'
                ]
            ],
            'most-repeating' => [
                '-L -P -S' => [
                    '-L' => 'w', '-P' => '%', '-S' => '^'
                ],
                '-L -P' => [
                    '-L' => 'w', '-P' => '%'
                ],
                '-L -S' => [
                    '-L' => 'w', '-S' => '^'
                ],
                '-P -S' => [
                    '-P' => '%', '-S' => '^'
                ],
                '-L' => [
                    '-L' => 'w'
                ],
                '-P' => [
                    '-P' => '%'
                ],
                '-S' => [
                    '-S' => '^'
                ]
            ]
        ]
    ];

    /**
     * Data provider for invalid input file arguments. Should return exit code 1.
     *
     * @return array
     */ 
    public function invalidInputProvider(): array
    {
        return [
            'empty' => [
                '',
                1
            ],
            'space' => [
                '  ',
                1
            ],
            'dash' => [
                '-',
                1
            ],
            'no input argument provided short' => [
                'i',
                1
            ],
            'no input argument provided long' => [
                'input',
                1
            ],
            'invalid input argument provided' => [
                '-input',
                1
            ],
            'invalid input argument short' => [
                '-i',
                1
            ],
            'invalid input argument long' => [
                '--input',
                1
            ],
            'no file name short, missing format, missing flags' => [
                '-i=',
                1
            ],
            'no file name long, missing format, missing flags' => [
                '--input=',
                1
            ],
            'invalid file name short' => [
                '-i=nonexistentfile',
                1
            ],
            'invalid file name long' => [
                '--input=nonexistentfile',
                1
            ],
            'no input argument, invalid format argument short' => [
                '-f',
                1
            ],
            'no input argument, invalid format argument long' => [
                '--format',
                1
            ],
            'no input argument, no format short' => [
                '-f=',
                1
            ],
            'no input argument, no format long' => [
                '--format=',
                1
            ],
            'no input argument, invalid format short' => [
                '-f=repeating',
                1
            ],
            'no input argument, invalid format long' => [
                '--format=repeating',
                1
            ],
            'no input argument, no format argument, no flag argument short' => [
                '-l -s -p',
                1
            ],
            'no input argument, no format argument, no flag argument long' => [
                '--letter --symbol --punctuation',
                1
            ],
            'no input argument, no format argument, flag argument letter short' => [
                '-L',
                1
            ],
            'no input argument, no format argument, flag argument letter long' => [
                '--include-letter',
                1
            ],
            'no input argument, no format argument, flag argument punctuation short' => [
                '-P',
                1
            ],
            'no input argument, no format argument, flag argument punctuation long' => [
                '--include-punctuation',
                1
            ],
            'no input argument, no format argument, flag argument symbol short' => [
                '-S',
                1
            ],
            'no input argument, no format argument, flag argument symbol long' => [
                '--include-symbol',
                1
            ],
            'no input argument, no format argument, flag arguments all short' => [
                '-L -S -P',
                1
            ],
            'no input argument, no format argument, flag arguments all long' => [
                '--include-letter --include-symbol --include-punctuation',
                1
            ],
            'no input argument, no format argument, flag arguments mixed' => [
                '-S --include-punctuation -L',
                1
            ],
            'no input argument, format argument non-repeating short, flag arguments all short' => [
                '-f=non-repeating -L -S -P',
                1
            ],
            'no input argument, format argument non-repeating long, flag arguments all short' => [
                '--format=non-repeating -L -S -P',
                1
            ],
            'no input argument, format argument long at end, flag arguments all long mixed places' => [
                '--include-letter --include-punctuation --format=non-repeating --include-symbol',
                1
            ],
            'input argument, format argument long at end, flag arguments all long mixed places' => [
                '--include-letter --include-punctuation --format=non-repeating --include-symbol',
                1
            ],
            'no input argument provided, format argument short, flag arguments all short' => [
                'i -f=non-repeating -S -P -L',
                1
            ],
            'invalid input argument short, format argument short, flag arguments all short' => [
                '-i -f=non-repeating -S -P -L',
                1
            ],
            'no file name short, format argument short, flag arguments all short' => [
                '-i= -f=non-repeating -S -P -L',
                1
            ],
            'invalid file name short, format argument short, flag arguments all short' => [
                '-i=nonexistentfile -f=non-repeating -S -P -L',
                1
            ],
            'invalid file name long, format argument short, flag arguments all short' => [
                '--input=nonexistentfile -f=non-repeating -S -P -L',
                1
            ]
        ];
    }

    /**
     * Data provider for invalid file contents. Should return exit code 2.
     *
     * @return array
     */ 
    public function invalidFileContentsProvider(): array
    {
        $this->getFileList();
        $tests = [];

        foreach ($this->files as $file) {
            // Find only invalid data files.
            $pos = strrpos($file, 'invalid_');
            if ($pos !== false) {
                $tests['test '.$file.' with valid format and flags'] = [
                    '-i='.$this->dir.'/'.$file.' -f=non-repeating -S -P -L',
                    2
                ];
            }
        }

        return $tests;
    }

    /**
     * Data provider for invalid format arguments. Should return exit code 3.
     *
     * @return array
     */
    public function invalidFormatProvider(): array
    {
        $this->getFileList();

        foreach ($this->files as $file) {
            // Find only one valid data file. Since names are similar only other option is a valid file. 
            $pos = strrpos($file, 'invalid_', );
            if ($pos === false) {
                $valid_file = $file;
                break;
            }
        }

        return [
            'file short, no format argument provided' => [
                '-i='.$this->dir.'/'.$valid_file,
                3
            ],
            'file short, no format argument provided short' => [
                '-i='.$this->dir.'/'.$valid_file.' f',
                3
            ],
            'file short, no format argument provided long' => [
                '-i='.$this->dir.'/'.$valid_file.' format',
                3
            ],
            'file short, invalid format argument short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f',
                3
            ],
            'file short, invalid format argument long' => [
                '-i='.$this->dir.'/'.$valid_file.' --format',
                3
            ],
            'file short, no format short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=',
                3
            ],
            'file short, no format long' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=',
                3
            ],
            'file short, invalid format short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=repeating',
                3
            ],
            'file short, invalid format long' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=repeating',
                3
            ],
            'file short, no format argument, no flag argument short' => [
                '-i='.$this->dir.'/'.$valid_file.' -l -s -p',
                3
            ],
            'file short, no format argument, no flag argument long' => [
                '-i='.$this->dir.'/'.$valid_file.' --letter --symbol --punctuation',
                3
            ],
            'file short, no format argument, flag argument letter short' => [
                '-i='.$this->dir.'/'.$valid_file.' -L',
                3
            ],
            'file short, no format argument, flag argument letter long' => [
                '-i='.$this->dir.'/'.$valid_file.' --include-letter',
                3
            ],
            'file short, no format argument, flag argument punctuation short' => [
                '-i='.$this->dir.'/'.$valid_file.' -P',
                3
            ],
            'file short, no format argument, flag argument punctuation long' => [
                '-i='.$this->dir.'/'.$valid_file.' --include-punctuation',
                3
            ],
            'file short, no format argument, flag argument symbol short' => [
                '-i='.$this->dir.'/'.$valid_file.' -S',
                3
            ],
            'file short, no format argument, flag argument symbol long' => [
                '-i='.$this->dir.'/'.$valid_file.' --include-symbol',
                3
            ],
            'file short, no format argument, flag arguments all short' => [
                '-i='.$this->dir.'/'.$valid_file.' -L -S -P',
                3
            ],
            'file short, no format argument, flag arguments all long' => [
                '-i='.$this->dir.'/'.$valid_file.' --include-letter --include-symbol --include-punctuation',
                3
            ],
            'file short, no format argument, flag arguments mixed' => [
                '-i='.$this->dir.'/'.$valid_file.' -S --include-punctuation -L',
                3
            ],
            'file short, invalid format argument short, flag arguments all short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f -L -S -P',
                3
            ],
            'file short, invalid format argument long, flag arguments all short' => [
                '-i='.$this->dir.'/'.$valid_file.' --format -L -S -P',
                3
            ],
            'file short, no format short, flag arguments all short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f= -L -S -P',
                3
            ],
            'file short, no format long, flag arguments all short' => [
                '-i='.$this->dir.'/'.$valid_file.' --format= -L -S -P',
                3
            ],
            'file short, invalid format short, flag arguments all short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=repeating -L -S -P',
                3
            ],
            'file short, invalid format long, flag arguments all short' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=repeating -L -S -P',
                3
            ],
            'file short, invalid format argument short, flag arguments all long' => [
                '-i='.$this->dir.'/'.$valid_file.' -f --include-letter --include-punctuation --include-symbol',
                3
            ],
            'file short, invalid format argument long, flag arguments all mixed' => [
                '-i='.$this->dir.'/'.$valid_file.' --format --include-letter -P --include-symbol',
                3
            ],
            'file short, no format short, flag arguments all long' => [
                '-i='.$this->dir.'/'.$valid_file.' -f= --include-letter --include-punctuation --include-symbol',
                3
            ],
            'file short, no format long, flag arguments all long' => [
                '-i='.$this->dir.'/'.$valid_file.' --format= --include-letter --include-punctuation --include-symbol',
                3
            ],
            'file short, invalid format short, flag arguments all long' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=repeating --include-letter --include-punctuation --include-symbol',
                3
            ],
            'file short, invalid format long, flag arguments all long' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=repeating --include-letter --include-punctuation --include-symbol',
                3
            ],
            'file short, no format short, flag arguments all mixed' => [
                '-i='.$this->dir.'/'.$valid_file.' -f= --include-letter -P --include-symbol',
                3
            ],
            'file short, no format long, flag arguments all mixed' => [
                '-i='.$this->dir.'/'.$valid_file.' --format= --include-letter -P --include-symbol',
                3
            ],
            'file long, no format long, flag arguments all mixed' => [
                '--input='.$this->dir.'/'.$valid_file.' --format= --include-letter -P --include-symbol',
                3
            ]
        ];
    }

    /**
     * Data provider for invalid flag arguments. Should return exit code 4.
     *
     * @return array
     */
    public function invalidFlagProvider(): array
    {
        $this->getFileList();

        foreach ($this->files as $file) {
            // Find only one valid data file. Since names are similar only other option is a valid file. 
            $pos = strrpos($file, 'invalid_', );
            if ($pos === false) {
                $valid_file = $file;
                break;
            }
        }

        return [
            'file short, format short, no flags provided' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating',
                4
            ],
            'file short, format short, no flags provided short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating l p s',
                4
            ],
            'file short, format short, no flags provided long' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating include-letter include-punctuation include-symbol',
                4
            ],
            'file short, format short, invalid flags provided short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -l -p -s',
                4
            ],
            'file short, format short, invalid flags provided upper' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -L= -P= -S=',
                4
            ],
            'file short, format short, invalid flags provided long' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -include-letter -include-punctuation -include-symbol',
                4
            ],
            'file short, format short, invalid flags provided long' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating --include-letterz --include-punctuationz --include-symbolz',
                4
            ],
            'file short, format short, invalid flags provided one upper' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -LPS',
                4
            ]
        ];
    }

    /**
     * Data provider for valid flag arguments. Should return exit code 0.
     *
     * @return array
     */
    public function validArgumentProvider(): array
    {
        $this->getFileList();

        foreach ($this->files as $file) {
            // Find only one valid data file. Since names are similar only other option is a valid file. 
            $pos = strrpos($file, 'invalid_', );
            if ($pos === false) {
                $valid_file = $file;
                break;
            }
        }

        return [
            'file short, format short, flags short all' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -L -S -P',
                0
            ],
            'file short, format short, flags short punctuation' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -P',
                0
            ],
            'file short, format short, flags short symbols' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -S',
                0
            ],
            'file short, format short, flags short letters' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -L',
                0
            ],
            'file short, format short, flags short letters and symbols' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -L -S',
                0
            ],
            'file short, format short, flags short letters and symbols short punctuation long' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -L --include-punctuation -S',
                0
            ],
            'file short, format short non-repeating, flags short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=non-repeating -P -L -S',
                0
            ],
            'file short, format short least-repeating, flags short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=least-repeating -P -L -S',
                0
            ],
            'file short, format short most-repeating, flags short' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=most-repeating -P -L -S',
                0
            ],
            'file short, format long non-repeating, flags short' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=non-repeating -P -L -S',
                0
            ],
            'file short, format long least-repeating, flags short' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=least-repeating -P -L -S',
                0
            ],
            'file short, format long most-repeating, flags short' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=most-repeating -P -L -S',
                0
            ],
            'file short, format long most-repeating, flags mixed' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=most-repeating -L --include-symbol',
                0
            ],
            'file long, format short, flags short all' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=non-repeating -L -S -P',
                0
            ],
            'file long, format short, flags short punctuation' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=non-repeating -P',
                0
            ],
            'file long, format short, flags short symbols' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=non-repeating -S',
                0
            ],
            'file long, format short, flags short letters' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=non-repeating -L',
                0
            ],
            'file long, format short, flags short letters and symbols' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=non-repeating -L -S',
                0
            ],
            'file long, format short, flags short letters and symbols short punctuation long' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=non-repeating -L --include-punctuation -S',
                0
            ],
            'file long, format short non-repeating, flags short' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=non-repeating -P -L -S',
                0
            ],
            'file long, format short least-repeating, flags short' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=least-repeating -P -L -S',
                0
            ],
            'file long, format short most-repeating, flags short' => [
                '--input='.$this->dir.'/'.$valid_file.' -f=most-repeating -P -L -S',
                0
            ],
            'file long, format long non-repeating, flags short' => [
                '--input='.$this->dir.'/'.$valid_file.' --format=non-repeating -P -L -S',
                0
            ],
            'file long, format long least-repeating, flags short' => [
                '--input='.$this->dir.'/'.$valid_file.' --format=least-repeating -P -L -S',
                0
            ],
            'file long, format long most-repeating, flags short' => [
                '--input='.$this->dir.'/'.$valid_file.' --format=most-repeating -P -L -S',
                0
            ],
            'file long, format long most-repeating, flags mixed' => [
                '--input='.$this->dir.'/'.$valid_file.' --format=most-repeating -L --include-symbol',
                0
            ],

            // Probably not a good idea to allow repating arguments and allow non-existing ones. Out of scope for now.
            'file long and short, format short, flags short all' => [
                '--input='.$this->dir.'/'.$valid_file.' -i='.$this->dir.'/'.$valid_file.' -f=most-repeating -L -P -S',
                0
            ],
            'file short and long, format short, flags short all' => [
                '-i='.$this->dir.'/'.$valid_file.' --input='.$this->dir.'/'.$valid_file. ' -f=most-repeating -L -P -S',
                0
            ],
            'file short, format short and long different, flags short all' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=most-repeating --format=least-repeating -L -P -S',
                0
            ],
            'file short, format long and short different, flags short all' => [
                '-i='.$this->dir.'/'.$valid_file.' --format=least-repeating -f=most-repeating -L -P -S',
                0
            ],
            'file short, format short two different, flags short all' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=least-repeating -f=most-repeating -L -P -S',
                0
            ],
            'file short, format short, flags short all kinds repeating' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=least-repeating -L -L -L -P -P -S -S --include-symbol',
                0
            ],
            'file short, format short, flags short and non-existent' => [
                '-i='.$this->dir.'/'.$valid_file.' -f=least-repeating -L -P -S this should not be here but sadly is',
                0
            ]
        ];
    }

    /**
     * Populates the file list.
     */
    private function getFileList(): void
    {
        // Read the directory and store file names for later use.
        if (!$this->files) {
            if (is_dir($this->dir)) {
                if ($handle = opendir($this->dir)) {
                    while (($file = readdir($handle)) !== false) {
                        if ($file !== '.' && $file !== '..') {
                            $this->files[] = $file;
                        }
                    }
                    closedir($handle);
                }
            }
        }
    }

    /**
     * Check the arguments passed to script and compare if the exit code is correct.
     *
     * @dataProvider invalidInputProvider
     * @dataProvider invalidFileContentsProvider
     * @dataProvider invalidFormatProvider
     * @dataProvider invalidFlagProvider
     * @dataProvider validArgumentProvider
     * 
     * @param string $input     Input arguments that are passed to script.
     * @param int    $expected  Expected exit code.
     */
    public function testParams(string $input, int $expected): void
    {
        exec($this->cmd.$input, $output, $exit_code);

        self::assertSame($expected, $exit_code);
    }

    /**
     * Data provider for valid contents. Should return exit code 0 and some text what script is doing. This function
     * assembles the $this->results and formats the output according to data provider array.
     *
     * @return array
     */
    public function validContentProvider(): array
    {
        $this->getFileList();

        $tests = [];

        foreach ($this->files as $file) {
            // Find only one valid data files.
            $pos = strrpos($file, 'invalid_', );
            if ($pos === false) {
                if (array_key_exists($file, $this->results)) {
                    foreach ($this->results[$file] as $format => $variations) {
                        foreach ($variations as $variation => $flags) {
                            // exec output each line is returned individualy with no "\n", so make it an array;
                            $lines = ['File: '.$this->dir.'/'.$file];
                            // Prepare the commanline arguments and test name.
                            $cmd = '-i='.$this->dir.'/'.$file.' -f='.$format.' '.$variation;
                            $key = 'test '.$file.' with valid contents '.$format.' '.$variation;

                            foreach ($flags as $flag => $result) {
                                $lines[] = $this->formatToWord($format).' '.$this->flagToWord($flag).': '.$result;
                            }

                            $tests[$key] = [$cmd, 0, $lines];
                        }
                    }
                }
            }
        }

        return $tests;
    }

    /**
     * Check the valid files and if contents match the expected results.
     *
     * @dataProvider validContentProvider
     *
     * @param string $input     Input arguments that are passed to script.
     * @param int    $expected  Expected exit code.
     */
    public function testResults(string $input, int $expected_code, array $expected_output): void
    {
        exec($this->cmd.$input, $output, $exit_code);

        self::assertSame($expected_code, $exit_code);
        self::assertSame($expected_output, $output);
    }

    /**
     * Returns a part of resulting sentence depenging on then given format.
     *
     * @param string $format    Fortam given in comman line.
     *                          Possible values: non-repeating, least-repeating, most-repeating
     */
    private function formatToWord(string $format): string
    {
        switch ($format) {
            case 'non-repeating':
                return 'First non-repeating';
            case 'least-repeating':
                return 'First least repeating';
            case 'most-repeating':
                return 'First most repeating';
        }
    }

    /**
     * Returns a part of resulting sentence depenging on then given flag.
     *
     * @param string $flag  Flag given in comman line.
     *                      Possible values: -L, -S, -P
     */
    private function flagToWord(string $flag): string
    {
        switch ($flag) {
            case '-L':
                return 'letter';
            case '-P':
                return 'punctuation';
            case '-S':
                return 'symbol';
        }
    }
}
