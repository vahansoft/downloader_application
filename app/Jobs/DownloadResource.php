<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Resource;

class DownloadResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $resource;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $name = substr($url, strrpos($url, '/') + 1);

        $this->resource = Resource::create([
            'name' => $name,
            'url' => $url,
            'status_id' => Resource::getStatusId('PENDING'),
            'hash' => bin2hex(random_bytes(6))
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);

        $fileName = $this->resource->id . $this->resource->hash;
        $url = $this->resource->url;

        $this->resource->update([
            'status_id' => Resource::getStatusId('DOWNLOADING')
        ]);

        $context = stream_context_create([], [
            'notification' => [$this, 'progress']
        ]);

        $resource = fopen($url, 'r', null, $context);

        \Storage::disk('resources')->put($fileName, $resource);

        $this->resource->update([
            'status_id' => Resource::getStatusId('COMPLETED'),
            'file_size' => $this->resource->received_bytes
        ]);
    }

    public function failed()
    {
        $this->resource->update([
            'status_id' => Resource::getStatusId('ERROR')
        ]);
    }


    public function progress($notificationCode, $severity, $message, $messageCode, $bytesTransferred, $fileSize)
    {
        $lastUpdateTime = strtotime($this->resource->updated_at);
        $diffSeconds = time() - $lastUpdateTime;

        switch($notificationCode) {
            case STREAM_NOTIFY_AUTH_REQUIRED: {
                throw new \Exception('Authorization is required');
            }
            case STREAM_NOTIFY_FAILURE: {
                throw new \Exception('Failure');
                break;
            }

            case STREAM_NOTIFY_FILE_SIZE_IS: {
                $this->resource->file_size = $fileSize;
                break;
            }

            case STREAM_NOTIFY_MIME_TYPE_IS: {
                $this->resource->mime_type = $message;
                break;
            }

            case STREAM_NOTIFY_PROGRESS: {
                $this->resource->received_bytes = $bytesTransferred;
                break;
            }
        }

        if ($diffSeconds > 2) {
            $this->resource->save();
        }
    }
}
