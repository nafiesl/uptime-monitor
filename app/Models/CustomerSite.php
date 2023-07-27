<?php

namespace App\Models;

use App\Models\MonitoringLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSite extends Model
{
    use HasFactory;

    public function latestLogs()
    {
        return $this->hasMany(MonitoringLog::class)->latest();
    }
}
