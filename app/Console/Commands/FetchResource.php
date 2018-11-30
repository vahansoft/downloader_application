<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\DownloadResource;

class FetchResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:resource {urls*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command pushes provided urls in queue for future download';

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
        $urls = $this->argument('urls');

        foreach ($urls as $url) {
            \Queue::push(new DownloadResource($url));
        }

        $this->line('Your resources placed on queue and should be downloaded soon.');
    }
}
