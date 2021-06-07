For a long time in PHP the types have been independent & solitary, it's now time for the uprising, and the uniting of types.

Create a program which adds up and prints the result of all the arguments passed to the program (not including the program name).

In the process you should create a function named `adder` which accepts these numbers as a variadic parameter.

The type of the parameter should be a union of all the types of numbers we might pass to your program.

We will pass to your program any amount of random numbers which could be integers, floats or strings. Your `adder` function
should only accept these types. Regardless of the type, every argument will be a number.

You should output the sum of the numbers followed by a new line.

How you print and add the numbers is up to you.

### The advantages of union types

* Allows us to represent more complex types in a simpler manner, such as the pseudo `Number` type we are inventing here in this exercise.
* The types are enforced by PHP so `TypeError`'s will be thrown when attempting to pass non-valid types.
* Allows us to move information from phpdoc into function signatures.
* It prevents incorrect function information. phpdocs can often go stale when they are not updated with the function itself.    


----------------------------------------------------------------------
## HINTS

Remember the first argument will be the programs file path and not an argument passed to the program.

The function you implement must be called `adder`.

It is up to you to pass the numbers to your function.

Documentation on union types can be found by pointing your browser here:
[https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.union]()

----------------------------------------------------------------------
## EXTRA

You should access `$argv` directly to fetch the numbers (we have casted the arguments from strings to their respective types)

Think about the return type of your `adder` function - you could declare it as a float.
