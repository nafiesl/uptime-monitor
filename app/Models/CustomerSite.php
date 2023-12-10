<?php

namespace App\Models;

use App\Models\MonitoringLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSite extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'is_active', 'owner_id'];

    public function latestLogs()
    {
        return $this->hasMany(MonitoringLog::class)->latest();
    }

    public function monitoringLogs()
    {
        return $this->hasMany(MonitoringLog::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id')->withDefault(['name' => 'n/a']);
    }
}
