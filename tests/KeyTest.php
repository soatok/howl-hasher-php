<?php
declare(strict_types=1);
namespace Soatok\Howlhasher\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Soatok\HowlHasher\Hash\BLAKE2b;
use Soatok\HowlHasher\Hash\Sha256;
use Soatok\HowlHasher\Hash\Sha384;
use Soatok\HowlHasher\Hash\Sha512;
use Soatok\HowlHasher\HashInterface;
use Soatok\HowlHasher\Key;

#[CoversClass('Soatok\HowlHasher\Key')]
class KeyTest extends TestCase
{
    public static function generateProvider(): array
    {
        return [
            'null' => [32, null],
            'blake2b' => [32, new BLAKE2b()],
            'blake2b-40' => [40, (new BLAKE2b())->withOutputLength(40)],
            'sha256' => [32, new Sha256()],
            'sha384' => [48, new Sha384()],
            'sha512' => [64, new Sha512()],
            '100' => [100, 100],
        ];
    }

    #[DataProvider('generateProvider')]
    public function testGenerate(int $expectedLength, HashInterface|int|null $spec = null): void
    {
        $key = Key::generate($spec);
        $this->assertSame($expectedLength, strlen($key->bytes()));
    }
}
