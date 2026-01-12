<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCard extends Model
{
    use HasFactory, SoftDeletes;

    const DEFAULT_CARD_ACTIVE = 1;
    const DEFAULT_CARD_INACTIVE = 0;

    protected $fillable = [
        'user_id',
        'card_id',
        'card_type',
        'card_number',
        'card_holder_name',
        'exp_month',
        'exp_year',
        'ifsc_code',
        'iban_number',
        'srn_number',
        'gtin_number',
        'paypal_id',
        'is_default',
    ];
}
