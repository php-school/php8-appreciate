PHP's type system has been constantly and consistently growing and evolving for a long time.

PHP 8 comes with a new type `mixed` which is essentially a union of `array|bool|callable|int|float|object|resource|string|null`. 

This means that any value which can be produced in PHP will pass the check. Which is exactly the same as no type check.

I guess you're wondering why you would use it, right?

If anything - it signals intent. Your function really does accept anything. It's not just missing type information.

----------------------------------------------------------------------
Create a program which contains one function named `logParameter`. It should have one parameter, and it's type must be `mixed`.

Within your function you should log the type of the parameter that was passed. For this, you can use a new PHP 8 function called `get_debug_type`.

This function will give you a string identifier representing the type of any PHP variable.

You should log the parameter to a file named `param.log` next to your PHP script.

We will call this function multiple times when we verify it, so make sure you append to the file instead of overwriting it with each log.

Each log entry should be on a new line and should follow the format: `10:04:06: Got: string` where `10:04:06` is the time: hours, minutes & seconds and where `string` is the type, the result of `get_debug_type`.

The format of the line is very important, including the time.

You do not need to call the function, just define it. We will call it for you, with every PHP value we can think of!

### The advantages of the mixed type

* Allows us to indicate that the type has not been forgotten, it just cannot be specified more precisely.
* Allows us to move information from phpdoc into function signatures.
----------------------------------------------------------------------
## HINTS

The function you implement must be called `logParameter`.

`file_put_contents` has a handy flag to append to a file instead of creating it anew: `FILE_APPEND`.

We will call your function automatically with a bunch of different types to make sure that it can accept anything.

We will specifically check that you used the `mixed` type. Omitting the type will not pass.

{{ doc 'mixed' en language.types.declarations.php#language.types.declarations.mixed }}

{{ doc 'get_debug_type' en function.get-debug-type.php }}

----------------------------------------------------------------------
## EXTRA

You might want to delete or empty your log file each time your program starts, otherwise it will grow and grow and the comparison will fail.

Think about the return type of your `logParameter` function - you could declare it as `void`.

If you are curious how we compare the output of your program when it includes time, we simply check that it matches the format, rather than comparing exactly. More specifically, we use the regex `/\d{2}:\d{2}:\d{2}/`.
