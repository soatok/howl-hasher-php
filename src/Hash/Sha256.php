<?php
declare(strict_types=1);
namespace Soatok\HowlHasher\Hash;

class Sha256 extends BaseHash
{
    public function getHashAlgo(): string
    {
        return 'sha256';
    }

    public function getKeyLength(): int
    {
        return 32;
    }
}
