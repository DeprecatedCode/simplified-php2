SimplifiedPHP [Deprecated]
==============

## This project is no longer maintained. However, I have taken the best of SimplifiedPHP and incorporated it into the <a href="https://github.com/NateFerrero/zoo">Zoo</a> project.</p>

Simplified PHP is a new approach to creating a programming language that works with the widespread distribution, ease of installation, and reliability of PHP, while avoiding its overly complicated syntax and nuances. A minimalistic approach has been taken, and features will only be added if they are deemed essential for the purpose of this project.

As with PHP, there are two main data structures in SimplifiedPHP: the Object and the Array, each with their own limitations and feature sets.

# Objects

An Object is delimited with curly braces, and optional commas for multiple items per line. Object variables must be assigned as key: value pairs.

### Example:

    a: {name: "Dan", age: 20}

    b: {name: "Dan"
         age: 20}

    a = b

# Objects as Functions

Objects can contain statements to be executed at a later time. Expressions may be provided with default arguments, if any arguments are required, to avoid errors when a variable is undefined.

### Example:

    add: {a + b}{a: 0, b: 0}

    4 = add{b: 4}()

    6 = add{b: 3, a: 3}()

You may curry arguments and provide the remaining arguments at any time. Attempting to provide the same arguments twice will override the earlier provided argument(s).

### Example:

    add: {a + b} {a: 0, b: 0}

    add2: add {a: 2}

    7 = add2{b: 5}()

It's perfectly fine to curry all of the arguments. When you want to invoke the function, just pass it an empty group `()`.

### Example:

    add: {a + b}{a: 0, b: 0}

    add6and4: add{a: 6, b: 4}

    10 = add6and4()

# Adding Properties to an Object

Object properties can be set after an object is created with the `obj::var value` setter syntax. In the following example, no default arguments are needed. An error would be raised if joe had no name variable in scope. Also note that the name and occupation variables are not inside the string.

### Example:

    joe: {name: "Joe Swanson", occupation: "Police Officer"}
    
    joe::greeting {"Hi, my name is " name " and I'm a " occupation "."}
    
    "Hi, my name is Joe Swanson and I'm a Police Officer." = (joe.greeting())

    
If you have an object and want to avoid deferring calculation until it's used, follow the object with the literal `$`. This evaluates the object instead of assigning the object to the variable.

### Example:

    steve: {        name: "Steve"
                     age: 34
            ageIn10Years: {"In 10 years, " name " will be " (age + 10)}$ }
    
    "In 10 years, Steve will be 44" = steve.ageIn10Years

# Loops

An object can be applied to an array that uses the variable `it` for the current iteration value, and `key` for the index.

### Example:

    count: [1..10] @ {it + 3} .join " "

    count == "4 5 6 7 8 9 10 11 12 13"
    
    names: [{name: "Bob"}, {name: "Jim"}] @ {it.name} @ {it.replace{"o": "i", "i": "a"}}
    
    names = {0: "Bib", 1: "Jam"}
    
    fruits: ["Apple", "Pear", "Orange", "Carrot", "Potato", "Nectarine"][0..2, 5] @ {it.substr[0..2]}
    
    fruits = {0: "App", 1: "Pea", 2: "Ora", 3: "Nec"} 
    
# Conditional Matching

To perform matching, specify the variable you want to match on and follow with a `?`. A literal `*` is the catchall condition and should be used last.

### Example:

    temp: [20, 32, 45, 60, 72] @ {
        it ?
           <= 32: "Freezing"
            < 60: "Cold"
            < 70: "Cool"
            < 80: "Perfect"
               *: "Hot"
    }
    
    temp = ["Freezing", "Freezing", "Cold", "Cool", "Perfect"]


## Internals

SimplifiedPHP compiles a source map of all files, using the following functions. The `$S` variable is the scope at runtime, and `$P` refers to parent scope.

### New Object

```php
n($P, $line, $column);
```

### New Array

```php
a($P, $line, $column);
```

### New Group

```php
g($P, $line, $column);
```

### Value

```php
v($S, $value, $line, $column);
```

### Identifier

```php
i($S, $name, $line, $column);
```

### Operator

```php
o($S, $name, $line, $column);
```

### Break

```php
b($S, $noop, $line, $column);
```
