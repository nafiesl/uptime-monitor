<?php

namespace App\Models;

use App\Models\MonitoringLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'url', 'vendor_id', 'is_active', 'owner_id', 'check_interval', 'priority_code',
        'warning_threshold', 'down_threshold', 'notify_user_interval', 'last_check_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'notify_user' => 'boolean',
        'last_check_at' => 'datetime',
        'last_notify_user_at' => 'datetime',
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

    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->withDefault(['name' => 'n/a']);
    }

    public function needToCheck(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_check_at) {
            return true;
        }

        if ($this->last_check_at->diffInMinutes() < ($this->check_interval - 1)) {
            return false;
        }

        return true;
    }

    public function canNotifyUser(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_notify_user_at) {
            return true;
        }

        if ($this->last_notify_user_at->diffInMinutes() < ($this->notify_user_interval - 1)) {
            return false;
        }

        return true;
    }

    public function getYAxisMaxAttribute()
    {
        return $this->down_threshold + 2000;
    }

    public function getYAxisTickAmountAttribute()
    {
        return $this->y_axis_max / 1000;
    }
}
