<?php
declare(strict_types=1);
namespace Soatok\HowlHasher;

use Exception;
use SensitiveParameter;

class Key
{
    public function __construct(
        #[SensitiveParameter]
        private string $bytes
    ) {}

    /**
     * @param HashInterface|int|null $spec
     * @return self
     * @throws Exception
     */
    public static function generate(HashInterface|int|null $spec = null): self
    {
        if (is_null($spec)) {
            return new self(random_bytes(32));
        }
        if (is_int($spec)) {
            return new self(random_bytes($spec));
        }
        return new self(random_bytes($spec->getKeyLength()));
    }

    public function __destruct()
    {
        // Best effort to erase
        try {
            sodium_memzero($this->bytes);
        } catch (\Throwable) {}
    }

    public function bytes(): string
    {
        return $this->bytes;
    }
}
