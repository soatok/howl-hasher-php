<?php
declare(strict_types=1);
namespace Soatok\Howlhasher\Tests\Hash;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\HowlHasher\HowlHasher;

#[CoversClass('Soatok\HowlHasher\Hash\BLAKE2b')]
class BLAKE2bTest extends TestCase
{
    private HowlHasher $api;

    public function setUp(): void
    {
        $this->api = new HowlHasher('phpunit');
        parent::setUp();
    }

    public function testTranscript(): void
    {
        /* Simple concatenation fails: */
        $alice = $this->api->BLAKE2b()
            ->update('apple')
            ->update('boy')
            ->digest();
        $bob = $this->api->BLAKE2b()
            ->update('appleboy')
            ->digest();
        $this->assertNotSame($alice, $bob);

        /* Even trying to collide with the inner length fails: */
        $chuck = $this->api->BLAKE2b()
            ->update("apple" . pack('J', 3) . "bob")
            ->digest();
        $this->assertNotSame($alice, $chuck);
        $this->assertNotSame($bob, $chuck);
    }
}
