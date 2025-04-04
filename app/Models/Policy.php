<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Policy extends Model
{
    
    protected $fillable = [
        'user_id',
        'policy_number',
        'type',
        'premium_amount',
        'coverage_details',
        'start_date',
        'end_date',
        'status'
    ];




}
