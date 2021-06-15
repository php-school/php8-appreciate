Write a program that accepts two floating point numbers as arguments. The first number is the dividend, and the second is the divisor. The divisor may be zero.

First, divide the numbers using the traditional binary operator (`/`). Make sure to wrap it in a try/catch statement because the operator can throw a `DivisionByZeroError` error.

In the case of an exception, you should print the exception message followed by a new line.

Second, use the `fdiv` function with the same numbers. There are a few different return values you can expect from `fdiv` based on the values you pass to it.

Based on those values you should print a specific message followed by a new line:

* INF -> print "Infinite"
* -INF -> print "Minus infinite"
* A valid float -> print the number, rounding it to three decimal places.

`fdiv` will return INF (which is a PHP constant) if you attempt to divide a positive number by zero.
`fdiv` will return -INF if you attempt to divide a negative number by zero.
`fdiv` will return a valid float if your divisor is greater than zero.


### The advantages of the fdiv function

* No exceptions are thrown if you divide by zero

----------------------------------------------------------------------
## HINTS

Documentation on the fdiv function can be found by pointing your browser here:
[https://www.php.net/manual/en/function.fdiv.php]()

