<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FieldSafetyNotice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'udi_number',
        'product_name',
        'notice_description',
        'attachment_type',
        'upload_file',
        'status',
        'created_at',
        'client_id',
        'product_id',
        'thumbnail'
    ];

    /**
     * appends
     *
     * @var array
     */
    protected $appends = [
        'upload_file_url',
        'thumbnail_url',
    ];

    /**
     * Method getUploadFileUrlAttribute
     *
     * @return void
     */
    public function getUploadFileUrlAttribute()
    {
        if (filter_var($this->upload_file, FILTER_VALIDATE_URL)) {
            $url = $this->upload_file;
        } else {
            $url = getImageUrl($this->upload_file, '', false);
        }

        return $url;
    }
    
    /**
     * Method getThumbnailUrlAttribute
     *
     * @return void
     */
    public function getThumbnailUrlAttribute()
    {
        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            $url = $this->thumbnail;
        } else {
            $url = ($this->thumbnail != '') ? getImageUrl($this->thumbnail) : $this->thumbnail;
        }

        return $url;
    }

    /**
     * Method user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Method product
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
