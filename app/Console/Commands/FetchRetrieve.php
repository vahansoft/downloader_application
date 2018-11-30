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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
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
