<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public const TYPE_SUPER_ADMIN = 'Super Admin';
    public const TYPE_ADMIN = 'Admin';
    public const STATUS_ACTIVE = '1';
    public const STATUS_INACTIVE = '0';

    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * Method users
     *
     * @return void
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id');
    }
}
