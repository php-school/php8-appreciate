You're writing a login validation service for a password provided as a command line argument. In this service you have access to the function `verify_password`. 

The signature of this function is as follows: 

```php
/**
* @throws InvalidPasswordException When the password is invalid
 */
function verify_password(string $password): bool;
```

For this exercise this function will always throw an exception but unfortunately the exception message contains the password in plain text!

To pass this exercise you will need to call the `verify_password` function with the password provided, handle the exception and output `"Given password is invalid, please try again"`. 

PHP 8 allows you to handle the exception without capturing the exception itself which will ensure this message is not leaked further.

### The advantages of non capturing catches

* No unused variables
* A clear way to show you don't want to make use of the exception itself
* Is compatible with Catching Multiple Exception Types / Union Types 

----------------------------------------------------------------------
## HINTS

Documentation on the non-capturing catches feature is sparse without examples, so the RFC has the most amount of detail:
[https://wiki.php.net/rfc/non-capturing_catches]()
