<?php

namespace App\Models;

use App\Models\CustomerSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringLog extends Model
{
    use HasFactory;

    protected $fillable = ['customer_site_id', 'url', 'response_time', 'status_code'];

    public function customerSite()
    {
        return $this->belongsTo(CustomerSite::class)->withDefault(['name' => 'n/a']);
    }
}
