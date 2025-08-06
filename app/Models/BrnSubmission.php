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
        'linkedin',
        'company_name',
        'country',
        'position',
        'years_of_operation',
        'number_of_employees',
        'goals',
        'selected_outcome',
        'status' // approved, declined, pending
    ];
}
