# Howl Hasher (PHP)

Secure hashing library. Features:

1. Safely implement transcript hashing (working over multi-part data) without canonicalization attacks.
2. Built-in domain separation.
3. Support multiple hash functions (including BLAKE2).
4. Support for keyed modes and extensible outputs.

## Installation

```
composer require soatok/howl-hasher
```

## Usage

Raw hashing:

```php
use Soatok\HowlHasher\HowlHasher;

// Initialize a factory with a context name
$factory = (new HowlHasher('my application name goes here'));

// Get a sha256 hasher:
$sha256 = $factory->sha256();

// You can now call update() on this object to get a canonical hash: 
$result = $sha256
    ->update('some application specific data')
    ->update('some more data')
    ->update('third piece of data')
    ->digest();
```

Keyed hashing:

```php
use Soatok\HowlHasher\HowlHasher;
use Soatok\HowlHasher\Key;

$key = Key::generate();

$option1 = (new HowlHasher('my application name goes here', $key));
$keyed1 = $option1->sha256();

$option2 = (new HowlHasher('my application name goes here'));
$keyed2 = $option2->sha256($key);
```
