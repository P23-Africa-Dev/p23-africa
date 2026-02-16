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
        'annual_revenue',
        'offer_value',
        'goals',
        'selected_outcome',
        'b2b_interest',
        'status', // approved, declined, pending
        'tier' // silver, gold, platinum
    ];

    /**
     * Valid tier values
     */
    public const TIERS = ['silver', 'gold', 'platinum'];

    /**
     * Get tier display name with proper casing
     */
    public function getTierDisplayAttribute()
    {
        return $this->tier ? ucfirst($this->tier) : null;
    }

    /**
     * Get the name attribute (alias for full_name for backward compatibility)
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * Set the name attribute (alias for full_name for backward compatibility)
     */
    public function setNameAttribute($value)
    {
        $this->attributes['full_name'] = $value;
    }
}
