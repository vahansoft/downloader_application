<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Resource;

class FetchRetrieve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:retrieve {ids* : Resource id} {--path= : Retrieve directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command copies choosen ids into path.';

    /**
     * Copies resources of provided ids into path
     *
     * @return void
     */
    public function handle()
    {
        $path = $this->option('path');
        $ids = $this->argument('ids');

        $resources = Resource::whereIn('id', $ids)->get();

        $localStorage = \Storage::disk('resources')->getDriver();
        $clientStorage = \Storage::createLocalDriver(['root' => $path]);

        foreach ($resources as $resource) {
            $fileName = $resource->id . $resource->hash;
            $stream = $localStorage->readStream($fileName);

            $clientStorage->put($resource->name, $stream);
        }
    }
}
