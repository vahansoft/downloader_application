<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchCommandTest extends TestCase
{
    /**
     * Tests accessability of downloading resource.
     *
     * @return void
     */
    public function testFetchResourceCommand()
    {
        $this->artisan('fetch:resource', [
            'urls' => ['http://ipv4.download.thinkbroadband.com/5MB.zip']
        ])->assertExitCode(0);
    }
}
