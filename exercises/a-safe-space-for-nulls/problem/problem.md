Create a program that exports a `User` object instance to a CSV file using the null safe operator to access it's member properties.

You will have a variable named `$user` available in your PHP script. It is placed there automatically each time your program runs. It is populated with random data.

Sometimes, properties won't have a value set and will be null.

With the null safe operator it is possible to access variables like so:

```php
$capitalPopulation = $country?->capital?->population;
```

If the `$capital` property is null, the variable `$capitalPopulation` will also be null. Previously, without the null safe operator, this would be achieved like so:

```php
$capitalPopulation = null;
if ($city->capital !== null) {
    $capitalPopulation = $country->capital->population;
}
```

The `User` class, for which the `$user` variable holds an instance of, has the following signature:

```php
class User
{
    public string $firstName;
    public string $lastName;
    public ?int $age = null;
    public ?Address $address = null;
}

class Address
{
    public int $number;
    public string $addressLine1;
    public ?string $addressLine2 = null;
}
```

Note also the `Address` class which the property `$user->address` may be an instance of, or it may be null.

Export the `$user` data to a CSV with the following columns:

`First Name`, `Last Name`, `Age`, `House num`, `Addr 1`, `Addr 2`

 * The CSV should be comma delimited.
 * The columns should read exactly as above, any mistake will trigger a failure.
 * There should be one row for the column headers and one for the data.
 * Any properties which are null on the user should be printed as empty fields in the CSV.
 * The file should be named `users.csv` and exist next to your submission file (eg in the same directory).
 
And finally, the most important part, all properties which may be `NULL` should be accessed using the null safe operator!

### Advantages of the null safe operator

* Much less code for simple operations where null is a valid value.
* If the operator is part of a chain anything to the right of the null will not be executed, the statements will be short-circuited.
* Can be used on methods where null coalescing cannot `$user->getCreatedAt()->format() ?? null` where `getCreatedAt()` could return null or a `\DateTime` instance.

----------------------------------------------------------------------
## HINTS

Remember your program will be passed no arguments. There will be a `User` object populated for you under the variable `$user`. It is available at the beginning of your script.

Documentation on the Null Safe Operator can be found by pointing your browser here:
[https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.nullsafe]()

----------------------------------------------------------------------
## EXTRA

We have not given any hints regarding writing to a CSV file, as we are not testing you on that. How you achieve that (`fputcsv`, `file_put_contents`, etc) is up to you.

Therefore, it is up to you to figure out how to write a CSV if you don't already know :)

Okay... just one hint: Check back over the exercise "Have the Last Say". You might find some pointers there!