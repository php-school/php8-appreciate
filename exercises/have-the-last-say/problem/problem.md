Create a program that reads a CSV file using `fgetcsv` and change the delimiter argument using named arguments.

The first argument is the file name of the CSV which you should read. 

The CSV file contains two columns separated with `|` (the pipe operator). The first column is `country` and the second column is `capital`. 

You should print each row to the console like the following (with a new line after each row):

```
Country: Austria, Capital: Vienna
```

The list of countries will be picked at random, so the CSV will be different each time your program runs.

When using `fgetcsv` there's a bunch of arguments which change the behaviour of the way the CSV is parsed. In our case we  want to change the `separator` argument, which defaults to a comma `,`. We need it to be the pipe `|` character. 

We don't want to specify the rest of the arguments, so aside from the file pointer which is the first argument, we only want to specify the `separator` argument.

Named arguments are a great way to change argument default values without having to specify all the defaults again. 

For example, if you only want to change the value of the last argument to a function,
you can do so, without specifying all the other arguments:

```php
htmlspecialchars($string, ENT_COMPAT | ENT_HTML, 'UTF-8', false);
```

We only want to change the last argument (double_encode) of the function to false (the default is true). However, we are forced to specify all the other arguments, even though they have not changed from the defaults.

Named arguments allows to write the same, but in a more succinct fashion:

```php
htmlspecialchars($string, double_encode: false);
```

Note: only the values changed from the defaults are specified!

### Advantages of named arguments

* Possible to skip defaults in between the arguments you want to change.
* The code is better documented since the argument label is specified with the value, very useful for booleans.

----------------------------------------------------------------------
## HINTS

You will need to open the file for writing before using `fgetcsv` you can do that using `fopen`.

`fgetcsv` will return *one* line at a time

You will most likely need a loop to process all the data in the file.

You will need to keep reading from the file until it has been fully read. `feof` is your friend here and will inform you whether there is any data left to read.

Documentation on the `fopen` function can be found by pointing your browser here:
[https://www.php.net/manual/en/function.fopen.php]()

Documentation on the `fgetcsv` function can be found by pointing your browser here:
[https://www.php.net/manual/en/function.fgetcsv.php]()

Documentation on the `feof` function can be found by pointing your browser here:
[https://www.php.net/manual/en/function.feof.php]()

----------------------------------------------------------------------
## EXTRA

Although not entirely necessary for a small script, it is good practise to close any open file handles so that other processes can access them, you can use `fclose` for that.
