<?php
declare(strict_types=1);
namespace Soatok\HowlHasher\Hash;

class Sha384 extends BaseHash
{
    public function getHashAlgo(): string
    {
        return 'sha384';
    }

    public function getKeyLength(): int
    {
        return 48;
    }
}
