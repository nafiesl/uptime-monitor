<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'creator_id'];

    public function getTitleLinkAttribute()
    {
        return link_to_route('vendors.show', $this->name, [$this], [
            'title' => __(
                'app.show_detail_title',
                ['title' => $this->name, 'type' => __('vendor.vendor')]
            ),
        ]);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
