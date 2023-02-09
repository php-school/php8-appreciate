You have been given a piece of code (look for `php-gets-a-promotion.php` in {{context cli 'your working directory'}} {{context cloud 'the editor'}}) which provides a class written for PHP 7.

The code itself works well, assigning constructor arguments to the class properties.

Your job is to write the code in a more terse format, by using the newly introduced constructor property promotion feature in PHP 8.

The code is a class that would hypothetically be used to call a provided `\Closure` when reading a row of a CSV, passing in the appropriate row values, but the implementation logic is not important and thus left out.

Focus on converting the constructor to a terse format using constructor property promotion.

### The advantages of constructor property promotion

* Less boilerplate code is required when writing a class.
* It still provides the required information for static analysis / runtime type parsing.
* Is compatible in conjunction with properties that cannot be promoted.


----------------------------------------------------------------------
## HINTS

{{ doc 'Constructor Property Promotion' en language.oop5.decon.php#language.oop5.decon.constructor.promotion }}

Remember to keep the same visibility for the properties.

You will be expected to make use of the constructor property promotion feature.

You should have less code than the provided initial code.

