<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchCommandTest extends TestCase
{
    use WithFaker;

    /**
     * Tests accessability of downloading resource.
     *
     * @return void
     */
    public function testFetchResourceCommand()
    {
        $this->artisan('fetch:resource', [
            'urls' => [$this->faker->imageUrl(200, 200)]
        ])->assertExitCode(0);
    }
}
