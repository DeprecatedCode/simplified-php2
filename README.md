SimplifiedPHP
==============

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

### New File

```php
f($P, $line, $column);
```

### Value (String or Number)

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
