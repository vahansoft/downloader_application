<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Resource;

class FetchRetrieveCommandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFetchRetrieveCommand()
    {
        $resource = $this->getRetrievableResource();

        $this->artisan('fetch:retrieve', [
            'ids' => [$resource->id],
            '--path' => './'
        ])
            ->assertExitCode(0);
    }

    protected function getRetrievableResource()
    {
        return Resource::where('status_id', Resource::STATUSES['COMPLETED']['id'])->orderBy('id', 'desc')->first();
    }
}
