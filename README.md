SimplifiedPHP
==============

## Internals

SimplifiedPHP compiles a source map of all files, using the following functions. The `$S` variable is the scope at runtime.

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
