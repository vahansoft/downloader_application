<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Resource;

class FetchHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows previously downloaded resources.';

    protected $headers = ['Id', 'Name', 'File Size', 'Received Bytes', 'Created At', 'Status'];

    /**
     * Displayes fetched resources history
     *
     * @return mixed
     */
    public function handle()
    {
        $resources = $this->getResources();

        $this->displayResources($resources);
    }

    /**
     * Retrieves existing resources from database
     *
     * @param  array  $jobs
     * @return void
     */
    protected function getResources() {
        return Resource::select(['id', 'name', 'status_id', 'file_size', 'received_bytes', 'created_at'])
            ->orderBy('id', 'desc')
            ->get()
            ->makeHidden('status_id')
            ->toArray();
    }

    /**
     * Display the list of resources in the console.
     *
     * @param  array  $jobs
     * @return void
     */
    protected function displayResources(Array $items)
    {
        $this->table($this->headers, $items);
    }
}
