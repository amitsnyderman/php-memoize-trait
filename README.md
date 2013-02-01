PHP Memoize Trait

## Usage

Prefix any method name with an underscore (`_`) to memoize calls to that method.

```php
$instance = new Object();
$instance->method();  // normal
$instance->_method(); // memoized
```

*Note:* Requires special consideration when your class (or a super class) already implements the magic `__call` method.

## Test

```
% phpunit tests/MemoizeTest.php 
```