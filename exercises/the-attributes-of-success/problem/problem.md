You have been given a few pieces of code (look for `attributes.php`, `deserialize.php` & `the-attributes-of-success.php` in your working directory).

Your entry point is `the-attributes-of-success.php`. This is the file you should edit and work on. The other files should not be modified. However, they are included by `the-attributes-of-success.php`.

You can run and verify your program like so:

```sh
$ {appname} run the-attributes-of-success.php
$ {appname} verify the-attributes-of-success.php
```

Your task is split into two sections. The overall task is to write a class using properties and attributes which describe how to map data to an instance of the class.

The data will be passed to you in a JSON encoded string via the first command line argument. 

The data will represent a product review.

You pass the `JSON` data and the name of your class to a function named `deserialize` which is provided to you in the file  `deserialize.php`. For reference, its signature is:

```php
function deserialize(string $data, string $className): object;
```

It will return to you an instance of `$className` with the data from the `JSON` string `$data` mapped to its properties.

You should dump out the object using `var_dump`.

### Task 1 - Annotate a class with existing attributes

Create a class named `Review` with five public properties which represent the data of the review. The properties should all be `string` types and should be named `comment`, `starRating`, `date`, `id` and `reviewer`.

The class should use the Attribute `Deserialize` so that our `deserialize` function knows that this is a valid class to use.

By default, our `deserialize` function will use the names of the class properties to locate the field value in the `JSON` data.

For example, when passing the following class to our `deserialise` function it would look for the `sku` key in the `JSON` data and set it on the `sku` property.

```php
#[Deserialize]
class Product {
    public string $sku;
}
```

#### Mapping properties

However, the `starRating` key does not exist in the `JSON` data. It exists as `rating`. So here, we need to use the `Map` attribute. By using the `Map` attribute we can tell our `deserialize` function to fetch the star rating from a different key in the `JSON` data.

Use it like so:

```php
#[Deserialize]
class Product {
    #[Map('reference')]
    public string $sku;
}
```

Where `reference` is the key in the `JSON` data you want to use rather than `sku`.

#### Skipping properties

We don't care about the ID value, this relates to a 3rd party system and is not relevant in our code.

Use the `Skip` attribute on the `id` property of our `Review` class to tell our `deserialize` function to skip this piece of data.

### Task 2 - Create your own attribute

By now you should be able to call the `deserialize` function with the `JSON` data and your class name.

When executing your program with

```sh
$ {appname} run the-attributes-of-success.php
```

You should see a dump of your `Review` instance.

Here comes our problem: The reviewers name is not anonymous. We have to comply with strict privacy laws, we cannot display this data without the reviewer's permission.

For now, we will have to obfuscate this data. We can accomplish this using a custom attribute.

#### Create the obfuscate method

Create a method on your `Review` class named `obfuscateReviewer`. It should take a string input, run it through the `md5` function and return it.

#### Create an attribute

Create an attribute named `Obfuscate`. It should have a public property named `key` and it's constructor should assign the first passed in string value to this property.

The attribute must be designated as an attribute by using the `Attribute` attribute (confused much??) and it should target methods only.

Targets designate where an attribute can be used, on classes, methods or properties and so on. For reference, the `Deserialize` attribute can only be used on classes and hence its target is `Attribute::TARGET_CLASS`.

See below for an example of designating a class as an attribute and configuring its target as classes only.

```php
#[Attribute(Attribute::TARGET_CLASS)] //This is how we designate `MyAttribute` as an attribute with its target.
class MyAttribute {
    public function __construct(string $someValue) {
    }
}
```

#### Use the attribute

The last step is to use the attribute on your `obfuscateReviewer` method. We need to tell our `deserialize` function that this method should be called when accessing the `reviewer` key from the `JSON` data.

Use the `Obfuscate` attribute on the method and pass to it the name of the key in the `JSON` data we want the obfuscator to run over, which is the `reviewer` key.

When the `deserialize` function sees a method with the `Obfuscate` attribute it will use the key found in the public property named `key` of the attribute. It will find the value referenced by that key in the `JSON` data and pass it to the obfuscator method. 

Finally, the returned data will be set on the `Review` object instance.

### Dump your object

The last task is to dump your object instance out using the PHP function `var_dump` - we use this output to verify the structure and data in your `Review` instance.    

### The advantages of Attributes

* Add Metadata to classes, methods, properties and arguments and so on.
* They can replace PHP doc blocks, each with custom parsers and rules to a unified standard supported by PHP.
* The data can be introspected using PHP's Reflection API's.
* PHP Core and extensions can provide new behaviour and runtime configuration which is opt-in, such as conditionally declaring functions, deprecating features and so on.

----------------------------------------------------------------------
## HINTS

Documentation on the Attributes feature can be found by pointing your browser here:
[https://www.php.net/manual/en/language.attributes.overview.php]()

Remember, do not edit `attributes.php` or `deserialize.php` - verification will fail if you do. Feel free to read the files to get a better understanding of the deserialization process.

You must call the `deserialize` function and you must use the `var_dump` function to output your deserialized object.

If you want to see the `JSON` data - use `var_dump` to dump it out.

For verification purposes, the order the properties defined in your `Review` class is important. Define them in the same order they are described to you.

## Extra

If you're not sure how to access command line arguments - you should maybe try a different workshop which covers that topic. Try `learnyouphp`.

`json_decode` can fail if it is passed a malformed string. Wrap the decode in a `try\catch` statement and pass the `JSON_THROW_ON_ERROR` flag to `json_decode`'s fourth parameter.