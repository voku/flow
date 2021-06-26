# Array Dot

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)

## Description

Array Dot is a set of functions that allows to manipulate PHP arrays using custom **dot notation** known
from [JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Property_accessors).
This implementation brings some custom syntax which will be explained bellow.

## Available Functions

```
array_dot_get(array $array, string $path) : mixed;
array_dot_exists(array $array, string $path) : bool;
```

### Dot Notation - Basic Syntax

```
$array = [
    'foo' => [
        'bar' => [
            'baz' => 1000
        ]
    ]
];

$value = array_dot_get('foo.bar.baz'); // 1000
```

In above example `foo.bar.baz` is path which also supports integer keys. For exmaple
`foo.0.baz`.

`foo`, `bar`, `baz` represents single steps (keys) of path. 


### Dot Notation - Custom Operators

- `?` - nullsafe
- `*` - wildcard
- `?*` - nullsafe wildcar


### Dot Notation - Custom Syntax

- `{}` - multipath

#### Nullsafe Operator - ? 

Dot notation is strict by default, which means that if any step of path is not present,
function will throw exception. 

This behavior can be changed by `?` nullsafe operator. 

```
$array = [
    'foo' => [
        'bar' => [
            'baz' => 1000
        ]
    ]
];

$value = array_dot_get('foo.bar.nothing'); // InvalidPathException
$value = array_dot_get('foo.bar.?nothing'); // null
```

Nullsafe does not need to be used with the last step of path.

```
$array = [
    'foo' => [
        'fii' => [
            'oop' => 1000
        ]
    ]
];

$value = array_dot_get('foo.?bar.nothing'); // null
```

#### Wildcard Operator - *

Wildcard operator allows to access all paths in nested arrays.

```
$array = [
    'users' => [
        [
            'id' => 1
        ],
        [
            'id' => 2
        ],
    ]
];

$value = array_dot_get('foo.*.id'); // [1, 2]
```

#### Nullsafe Wildcard Operator - ?*

Nullsafe Wildcard operator allows to access all paths in nested arrays for non symetric
collections.

```
$array = [
    'users' => [
        [
            'id' => 1,
            'name' => 'John'
        ],
        [
            'id' => 2
        ],
    ]
];

$value = array_dot_get('foo.*.name'); // ['John']
```

#### Multipath Syntax - {}

Get only selected keys from nested array

```
$array = [
    'users' => [
        [
            'id' => 1,
            'name' => 'John',
            'status' => 'active',
        ],
        [
            'id' => 2,
            'name' => 'Mikel',
            'status' => 'active',
            'role' => 'ADMIN'
        ],
    ]
];

$value = array_dot_get('foo.*.{id,?role}'); // [[1, null], [2, 'ADMIN']]
```


## Development

In order to install dependencies please, launch following commands:

```bash
composer install
composer install --working-dir ./tools
```

## Run Tests

In order to execute full test suite, please launch following command:

```bash
composer build
```

It's recommended to use [pcov](https://pecl.php.net/package/pcov) for code coverage however you can also use
xdebug by setting `XDEBUG_MODE=coverage` env variable.
