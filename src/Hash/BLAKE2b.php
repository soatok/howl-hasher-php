<?php
declare(strict_types=1);
namespace Soatok\HowlHasher\Hash;

use Soatok\HowlHasher\HashInterface;
use Soatok\HowlHasher\HowlException;
use Soatok\HowlHasher\Key;
use SodiumException;

class BLAKE2b implements HashInterface
{
    protected int $pieces = 0;
    protected string $context = '';
    protected ?Key $key = null;
    protected ?string $state = null;
    protected int $outputLength = 32;

    public function getKeyLength(): int
    {
        return $this->outputLength;
    }

    /**
     * @throws HowlException
     * @throws SodiumException
     */
    public function digest(bool $rawBinary = false): string
    {
        if (is_null($this->state)) {
            throw new HowlException('This hasher was either never initialized, or was already finalized');
        }
        sodium_crypto_generichash_update($this->state, pack('J', $this->pieces));
        try {
            if (!$rawBinary) {
                return sodium_bin2hex(
                    sodium_crypto_generichash_final($this->state, $this->outputLength)
                );
            }
            return sodium_crypto_generichash_final($this->state, $this->outputLength);
        } finally {
            $this->state = null;
            $this->pieces = 0;
        }
    }

    /**
     * @throws HowlException
     * @throws SodiumException
     */
    public function init(): self
    {
        if (empty($this->context)) {
            throw new HowlException('Context must be provided before initialization');
        }
        $this->state = sodium_crypto_generichash_init(
            $this->key ?? '',
            $this->outputLength
        );
        $length = pack('J', strlen($this->context));
        sodium_crypto_generichash_update($this->state, $length);
        sodium_crypto_generichash_update($this->state, $this->context);
        return $this;
    }

    /**
     * @throws HowlException
     * @throws SodiumException
     */
    public function update(string $piece): HashInterface
    {
        if (is_null($this->state)) {
            $this->init();
        }
        $length = pack('J', strlen($piece));
        sodium_crypto_generichash_update($this->state, $length);
        sodium_crypto_generichash_update($this->state, $piece);
        ++$this->pieces;
        return $this;
    }

    public function withContext(string $context): self
    {
        $self = clone $this;
        $self->pieces = 0;
        $self->state = null;
        $self->context = $context;
        return $self;
    }

    public function withKey(Key $key): self
    {
        $self = clone $this;
        $self->pieces = 0;
        $self->state = null;
        $self->key = $key;
        return $self;
    }

    /**
     * @throws HowlException
     */
    public function withOutputLength(int $length): self
    {
        if ($length < SODIUM_CRYPTO_GENERICHASH_BYTES_MIN) {
            throw new HowlException('Output length must be at least 16');
        }
        if ($length > SODIUM_CRYPTO_GENERICHASH_BYTES_MAX) {
            throw new HowlException('Output length must be at most 64');
        }
        $self = clone $this;
        $self->outputLength = $length;
        $self->pieces = 0;
        $self->state = null;
        return $self;
    }
}
