<?php
declare(strict_types=1);
namespace Soatok\HowlHasher\Hash;

use HashContext;
use Soatok\HowlHasher\HashInterface;
use Soatok\HowlHasher\HowlException;
use Soatok\HowlHasher\Key;

abstract class BaseHash implements HashInterface
{
    protected int $pieces = 0;
    protected string $context = '';
    protected ?Key $key = null;
    protected ?HashContext $state = null;
    abstract public function getHashAlgo(): string;

    public function __construct(string $context = '', ?Key $key = null)
    {
        $this->context = $context;
        $this->key = $key;
    }

    /**
     * @throws HowlException
     */
    public function init(): self
    {
        if (empty($this->context)) {
            throw new HowlException('Context must be provided before initialization');
        }
        if (is_null($this->key)) {
            $this->state = hash_init($this->getHashAlgo());
        } else {
            $this->state = hash_init($this->getHashAlgo(), HASH_HMAC, $this->key->bytes());
        }
        $length = pack('J', strlen($this->context));
        hash_update($this->state, $length);
        hash_update($this->state, $this->context);
        return $this;
    }

    /**
     * @throws HowlException
     */
    public function update(string $piece): static
    {
        if (is_null($this->state)) {
            $this->init();
        }
        $length = pack('J', strlen($piece));
        hash_update($this->state, $length);
        hash_update($this->state, $piece);
        ++$this->pieces;
        return $this;
    }

    /**
     * @throws HowlException
     */
    public function digest(bool $rawBinary = false): string
    {
        if (is_null($this->state)) {
            throw new HowlException('This hasher was either never initialized, or was already finalized');
        }
        try {
            hash_update($this->state, pack('J', $this->pieces));
            return hash_final($this->state, $rawBinary);
        } finally {
            unset($this->state);
            $this->pieces = 0;
        }
    }

    public function withContext(string $context): self
    {
        $self = clone $this;
        $self->context = $context;
        $self->pieces = 0;
        $self->state = null;
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
}
