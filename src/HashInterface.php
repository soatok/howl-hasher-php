<?php
declare(strict_types=1);
namespace Soatok\HowlHasher;

interface HashInterface
{
    public function getKeyLength(): int;
    public function digest(bool $rawBinary = false): string;
    public function update(string $piece): self;
    public function withContext(string $context): self;
    public function withKey(Key $key): self;
}
