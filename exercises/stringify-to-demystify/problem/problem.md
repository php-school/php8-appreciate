`__toString()` a magic class method long-standing in PHP but never truly something you could rely on unless you rolled your own interface. All that has changed with the simple introduction of the `Stringable` interface in PHP 8.

`Stringable` is a simple interface that requires the implementation of `__toString(): string`

----------------------------------------------------------------------

Create a program that reads the JSON body of the request input stream (mimicking an API response). The body will need to be JSON decoded and will be a variation of the example below:

```json
{
  "success": false,
  "status": "401",
  "error": "Unauthorized: Not authorised for this resource"
}
```

You will need to define a new class that implements `Stringable` and it's method requirements. This class should take enough information from the request so that the `__toString` method can return a string like below:

```
Status: 401
Error: Unauthorized: Not authorised for this resource
```

Once constructed pass the object to a `log_failure` function. This function is provided for you and has a signature of `log_failure(\Stringable $error): void`.

Your program may also receive successful payloads, which you should ignore for the purposes of this exercise. These requests will be identifiable by the `success` key in the decoded JSON payload.

### The advantages of the new Stringable interface

* Allows typing of string like objects
* Can be used in conjunction with union types alongside `string`

----------------------------------------------------------------------
## HINTS

To easily read the request body you can use `file_get_contents('php://input')`

For more details look at the docs for...

**Stringable** - [https://www.php.net/manual/en/class.stringable.php]()

Note that while the `Stringable` interface isn't required to pass the type hint check, however, it should be used if not only to show intent.  

Oh, and don't forget about the basics for classes and interfaces :)

**class** - [https://www.php.net/manual/en/language.oop5.basic.php]()
**interfaces** - [https://www.php.net/manual/en/language.oop5.interfaces.php]()
