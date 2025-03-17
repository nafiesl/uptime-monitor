<?php

namespace App\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringLog extends Model
{
    use HasFactory;

    protected $fillable = ['site_id', 'url', 'response_time', 'status_code', 'response_message'];

    public function site()
    {
        return $this->belongsTo(Site::class)->withDefault(['name' => 'n/a']);
    }
}
