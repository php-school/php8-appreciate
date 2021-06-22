You have been given a piece of code (look for `throw-an-expression.php` in your working directory) which is checking the requested URL and throwing an exception when a secret area of the website is accessed.

If the request is allowed, `Welcome!` is printed out.


Traditionally, pre PHP 8, an exception throw has been a statement. There are certain places where statements cannot be used, and only expressions can be used. For example, in ternaries and short closures, only expressions can be used.

Now with PHP 8, throw statements are expressions making them available to use in pretty much all locations.

----------------------------------------------------------------------
Your task is to convert the `if` statement to one line of code, using the ternary operator.

### The advantages of throwing being an exception

* It is possible to throw an exception in a short closure
* It is possible to throw an exception in a ternary or coalesce operation

----------------------------------------------------------------------
## HINTS

Documentation on throwing exception can be found by pointing your browser here:
[https://www.php.net/manual/en/language.exceptions.php]()

