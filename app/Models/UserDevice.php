<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    public const CERTIFICATION_DEV = 'development';

    public const TYPE_WEB = 'web';

    public const TYPE_ANDROID = 'android';

    public const TYPE_IOS = 'ios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'device_id', 'device_type', 'device_version',
    ];

    /**
     * Get the user that owns the user device.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
