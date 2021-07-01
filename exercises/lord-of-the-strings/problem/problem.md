PHP has a huge standard library with the most extravagant and highly specific string and array functions available. `levenshtein` & `array_change_key_case` anyone??

On the flip side, historically it has missed some very common string operations. There's always been a way to get the same results, but they are cumbersome and error prone.

For example, if you want to check if a string contains a string you might use `strpos`. Eg:

```php
if (strpos('colourful', 'colour')) {
}
```
Well, this check will fail because `strpos` will return `0` which will be interpreted as false. You will need to adapt that check to account for 0 with `strpos('colourful', 'colour') !== false` where the strict comparison is important.

Thankfully, PHP 8 has done away with all that nonsense. Please welcome the following functions in to the world of PHP:

* `str_contains`
* `str_starts_with`
* `str_ends_with`

----------------------------------------------------------------------

Create a program that accepts two arguments, the first is a word, the second is a sentence (random words).

You must print a table which contains the results of each of the above functions with the provided word and sentence.

* First, does the sentence contain the word?
* Second, does the sentence start with the word?
* Lastly, does the sentence end with the word?

You will use the composer package `symfony/console` to print a table using the `Table` helper.

The table headers should be `Function` & `Result`

There should be three rows, one for each function. The function column should contain the function name, eg `str_contains`.
The result column should be `true` or `false` based on the result of the corresponding function call.

### The advantages of the new string functions

* Simpler and more concise.
* Saner return types.
* It is harder to get their usage wrong, for example checking for 0 vs false with `strpos`.
* The functions are faster, being implemented in C.
* The operations require less function calls, for example no usages of `strlen` are required.

----------------------------------------------------------------------
## HINTS

Point your browser to [https://getcomposer.org/doc/00-intro.md](https://getcomposer.org/doc/00-intro.md) which will walk you through **Installing Composer** if you haven't already!

Use `composer init` to create your `composer.json` file with interactive search.

For more details look at the docs for...

**Composer** - [https://getcomposer.org/doc/01-basic-usage.md](https://getcomposer.org/doc/01-basic-usage.md)
**Symfony Console** - [https://symfony.com/doc/current/components/console.html](https://symfony.com/doc/current/components/console.html)
**str_contains** - [https://www.php.net/manual/en/function.str-contains.php](https://www.php.net/manual/en/function.str-contains.php)
**str_starts_with** - [https://www.php.net/manual/en/function.str-starts-with.php](https://www.php.net/manual/en/function.str-starts-with.php)
**str_ends_with** - [https://www.php.net/manual/en/function.str-ends-with.php](https://www.php.net/manual/en/function.str-ends-with.php)

For Symfony Console you will want to look specifically for the Table Helper.

Oh, and don't forget to use the Composer autoloader with:

```php
require_once __DIR__ . '/vendor/autoload.php';
```