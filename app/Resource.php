<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = "resources";

    const STATUSES = [
        "PENDING" => [
            "id" => 1,
            "name" => "Pending"
        ],
        "DOWNLOADING" => [
            "id" => 2,
            "name" => "Downloading"
        ],
        "COMPLETED" => [
            "id" => 3,
            "name" => "Completed"
        ],
        "ERROR" => [
            "id" => 4,
            "name" => "Error"
        ]
    ];

    protected $fillable = [
        "name",
        "hash",
        "mime_type",
        "url",
        "status_id"
    ];

    protected $appends = [
        'status_name'
    ];

    public static function getStatusId(String $name) {
        return self::STATUSES[$name]['id'] ?? 0;
    }

    public function getStatusNameAttribute() {
        return collect(self::STATUSES)->keyBy('id')->get($this->status_id)['name'];
    }

    // private static function getStatuses() {
    //     if (!isset(self::$_statuses)) {
    //         self::$_statuses = collect(self::STATUSES)->keyBy('id');
    //     }

    //     return self::$_statuses;
    // }
}
