<?php
declare(strict_types=1);
namespace Soatok\HowlHasher;

use Soatok\HowlHasher\Hash\BLAKE2b;
use Soatok\HowlHasher\Hash\Sha256;
use Soatok\HowlHasher\Hash\Sha384;
use Soatok\HowlHasher\Hash\Sha512;

class HowlHasher
{
    /**
     * @param string $context - Application name or other context
     * @param ?string $key - Key
     */
    public function __construct(
        protected string $context,
        protected ?string $key = null
    ) {}

    /**
     * @throws HowlException
     */
    public function blake2b(?string $key = null, int $outputLength = 32): BLAKE2b
    {
        $key = $key ?? $this->key;
        $hash = (new BLAKE2b())
            ->withContext($this->context)
            ->withOutputLength($outputLength);
        if ($key) {
            return $hash->withKey(new Key($key));
        }
        return $hash;
    }

    public function sha256(?string $key = null): Sha256
    {
        $key = $key ?? $this->key;
        $hash = (new Sha256())->withContext($this->context);
        if ($key) {
            return $hash->withKey(new Key($key));
        }
        return $hash;
    }

    public function sha384(?string $key = null): Sha384
    {
        $key = $key ?? $this->key;
        $hash = (new Sha384())->withContext($this->context);
        if ($key) {
            return $hash->withKey(new Key($key));
        }
        return $hash;
    }

    public function sha512(?string $key = null): Sha512
    {
        $key = $key ?? $this->key;
        $hash = (new Sha512())->withContext($this->context);
        if ($key) {
            return $hash->withKey(new Key($key));
        }
        return $hash;
    }
}
