<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CmsPage
 */
class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_title',
        'page_content',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
