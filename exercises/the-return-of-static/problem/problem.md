You have been given a piece of code (look for `the-return-of-static.php` in your working directory) which is using static return types.

You will find two classes. `File`, a base class, and `Image` a class extending and adding behavior to `File`. We instantiate `Image`, set some properties using a fluent interface and then dump the object using `var_dump`.

If you run the code using `{appname} run the-return-of-static.php` you will see it is broken.

Locate and fix the issue!

### The advantages of the static return type

* Enforces that an instance of the class the method is called from, is returned. 
* Most useful for fluent interfaces and static constructors to ensure an instance of a parent class is not returned.

----------------------------------------------------------------------
## HINTS

(Brief) Documentation on the static return type feature can be found by pointing your browser here:
[https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.static]()

The static return type enforces methods to return an instance of the class that the method was called from, rather than the one it was defined in.

