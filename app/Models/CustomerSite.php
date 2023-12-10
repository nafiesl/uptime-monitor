<?php

namespace App\Models;

use App\Models\MonitoringLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'url', 'is_active', 'owner_id', 'check_periode', 'priority_code',
        'warning_threshold', 'down_threshold', 'notify_user', 'last_check_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'notify_user' => 'boolean',
        'last_check_at' => 'datetime',
    ];

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
