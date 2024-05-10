<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSiteType extends Model
{
    use HasFactory;

    public function getHtmlSlugAttribute()
    {
        if (isset($this->attributes['slug'])) {
            $slug = $this->attributes['slug'];
            return "<span class='badge bg-primary'>$slug</span>";
        }
    }
}
