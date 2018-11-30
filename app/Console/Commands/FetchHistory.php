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
        $resources = $this->getResources();

        $this->displayResources($resources);
    }

    protected function getResources() {
        return Resource::select(['id', 'name', 'status_id', 'file_size', 'received_bytes','created_at'])
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
    protected function displayResources($items)
    {
        $this->table($this->headers, $items);
    }
}
