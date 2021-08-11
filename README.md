# How to use
- Have a decent version of PHP installed like 7.3 at least;
- open command line excute the script for example:

$ `php test.php -i=test.txt -f=non-repeating -L -P -S`
- you can use long flags, provide different flags or ommit a few:

$ `php test.php --input=test.txt --format=least-repeating --include-letter -S`

To see errors codes #:
1. don't give input `-i` or `--input` or don't give a file name `-i=` or `--input=`;
2. give invalid file path or name, empty file or make sure file has invalid characters. Only lowercase letters, punctuations and symbols are allowed;
3. don't give format `-f` or `--format`, don't provide a format `-f=` or `--format=` or give invalid format.
Allowed formats are `non-repeating`, `least-repeating` and `most-repeating`;
4. don't provide any flags `-L`, `-P`, `-S`, `--include-letter`, `--include-punctuation` or `--include-symbol`.


To test the script use phpunit in command line:

$ `./vendor/bin/phpunit tests/AssignmentTest.php`

Tests have some predifined various strings in files in the data folder and will give various results.
