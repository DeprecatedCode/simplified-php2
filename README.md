SimplifiedPHP
==============

## Internals

SimplifiedPHP compiles a source map of all files, using the following functions. The `$S` variable is the scope at runtime.

### String

```php
s($S, $value, $line, $column);
```

### Number

```php
n($S, $value, $line, $column);
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
