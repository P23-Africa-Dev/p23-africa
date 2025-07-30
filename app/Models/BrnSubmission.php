<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrnSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'linkedin',
        'company_name',
        'country',
        'position',
        'years_of_operation',
        'number_of_employees',
        'goals',
        // 'what_you_want_to_gain',
        'status' // approved, declined, pending
    ];
}
