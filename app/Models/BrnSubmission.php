<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrnSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'country_code',
        'linkedin',
        'company_name',
        'reg_number',
        'website',
        'year_established',
        'country',
        'position',
        'industry',
        'categories',
        'years_of_operation',
        'number_of_employees',
        'offer_value',
        'goals',
        'selected_outcome',
        'b2b_interest',
        'status' // approved, declined, pending
    ];
}
