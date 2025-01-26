<?php
declare(strict_types=1);
namespace Soatok\HowlHasher\Hash;

class Sha512 extends BaseHash
{
    public function getHashAlgo(): string
    {
        return 'sha512';
    }

    public function getKeyLength(): int
    {
        return 64;
    }
}
