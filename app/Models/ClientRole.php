<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ClientRole
 */
class ClientRole extends Model
{
    use HasFactory;

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
        return $this->hasMany(User::class, 'id', 'client_role_id');
    }
    
}
