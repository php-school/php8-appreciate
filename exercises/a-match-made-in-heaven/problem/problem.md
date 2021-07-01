You have been given a piece of code (look for `a-match-made-in-heaven.php` in your working directory) which is full of bugs.
The code has been implemented badly, using a `switch` statement. Your job is to fix the code, by using the newly introduced `match` expression in PHP 8. 

The piece of code is supposed to take a string representing a keyboard keypress and convert it to its equivalent ANSI decimal code.

There are only four key presses supported at the minute (enter, up, down & escape). It should stay like that for now.

Focus on converting the switch statement to a match expression.

The key presses will be provided as strings via command line arguments. Only one keypress will be passed on each program invocation but it will be randomly picked from the four supported key presses.

### The advantages of match

* Match uses strict equality, unlike switch which uses weak comparison and can lead to subtle bugs.
* Each match arm does not fall through without a break statement, unlike switch.
* Match expressions must be exhaustive, if there is no default arm specified, and no arm matches the given value, an `UnhandledMatchError` is thrown.
* Match is an expression and thus returns a value, reducing unnecessary variables and reducing the risk of accessing undefined variables.


----------------------------------------------------------------------
## HINTS

Documentation on the `match` expression can be found by pointing your browser here:
[https://www.php.net/manual/en/control-structures.match.php]()

Remember the first argument will be a randomly picked supported keypress.

You will be expected to make use of the match expression.

You should return 0 for any unsupported key presses.

The core logic of the program should not change. eg the key presses and decimal codes.


----------------------------------------------------------------------
## EXTRA

Try to spot the bugs in the code given to you using the switch statement.
