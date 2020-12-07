You have been given a piece of code (look for `php-gets-a-promotionn.php` in your working directory) which provides a class written for PHP 7.
The code itself works well assigning constructor arguments to the class properties. Your job is to write the code in a more terse format, by using the newly introduced
constructor property promotion in PHP 8.

The code is a class is one that would hypothetically be used to call a provided `\Closure` when reading a row of a CSV, passing in the appropriate cell value, but the implementation logic is not important and thus left out.

Focus on converting the constructor to a terse format using constructor propery promotion.

### The advantages of constructor property promotion

* Less boilerplate code is required when writing a class
* Still provides the required information for static analysis / runtime type parsing
* Is compatible in conjuction with properties that cannot be promoted


----------------------------------------------------------------------
## HINTS

Documentation on the constructor property promotion feature can be found by pointing your browser here:
[https://www.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor.promotion]()

Remember to keep the same visibility for the properties

You will be expected to make use of the constructor propery promotion feature

You should have less code than the provided initial code

----------------------------------------------------------------------

----------------------------------------------------------------------
## EXTRA

?? ALways fun to have something, but what...