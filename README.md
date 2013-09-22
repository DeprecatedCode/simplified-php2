SimplifiedPHP
==============

## Internals

SimplifiedPHP compiles a source map of all files, using the following functions.

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
