<?php

namespace Tests\Unit;

use App\Services\TournamentService;
use PHPUnit\Framework\TestCase;

class IsPowerOf2Test extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(TournamentService::isPowerOf2(2));
        $this->assertTrue(TournamentService::isPowerOf2(4));
        $this->assertTrue(TournamentService::isPowerOf2(8));
        $this->assertTrue(TournamentService::isPowerOf2(16));
        $this->assertTrue(TournamentService::isPowerOf2(32));
        $this->assertTrue(TournamentService::isPowerOf2(64));
        $this->assertTrue(TournamentService::isPowerOf2(128));

        $this->assertFalse(TournamentService::isPowerOf2(3));
        $this->assertFalse(TournamentService::isPowerOf2(6));
        $this->assertFalse(TournamentService::isPowerOf2(10));
        $this->assertFalse(TournamentService::isPowerOf2(12));
    }
}
