<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'crm_currency',
    ];

    /**
     * Returns the ISO 4217 currency code for this org's CRM, defaulting to USD.
     */
    public function getCrmCurrency(): string
    {
        return $this->crm_currency ?? 'USD';
    }

    /**
     * Returns the currency symbol for display (e.g. '$', '€', '£').
     */
    public function getCrmCurrencySymbol(): string
    {
        return static::currencySymbol($this->getCrmCurrency());
    }

    /**
     * Map of ISO currency code → symbol.
     */
    public static function currencySymbol(string $code): string
    {
        return static::getCurrencyList()[$code]['symbol'] ?? $code;
    }

    /**
     * Full list of supported currencies for CRM display.
     */
    public static function getCurrencyList(): array
    {
        return [
            'USD' => ['name' => 'US Dollar',            'symbol' => '$'],
            'EUR' => ['name' => 'Euro',                  'symbol' => '€'],
            'GBP' => ['name' => 'British Pound',         'symbol' => '£'],
            'NGN' => ['name' => 'Nigerian Naira',        'symbol' => '₦'],
            'GHS' => ['name' => 'Ghanaian Cedi',         'symbol' => '₵'],
            'KES' => ['name' => 'Kenyan Shilling',       'symbol' => 'KSh'],
            'ZAR' => ['name' => 'South African Rand',    'symbol' => 'R'],
            'EGP' => ['name' => 'Egyptian Pound',        'symbol' => 'E£'],
            'TZS' => ['name' => 'Tanzanian Shilling',    'symbol' => 'TSh'],
            'UGX' => ['name' => 'Ugandan Shilling',      'symbol' => 'USh'],
            'RWF' => ['name' => 'Rwandan Franc',         'symbol' => 'Fr'],
            'ETB' => ['name' => 'Ethiopian Birr',        'symbol' => 'Br'],
            'XOF' => ['name' => 'West African CFA Franc', 'symbol' => 'CFA'],
            'MAD' => ['name' => 'Moroccan Dirham',       'symbol' => 'MAD'],
            'AED' => ['name' => 'UAE Dirham',            'symbol' => 'AED'],
            'CAD' => ['name' => 'Canadian Dollar',       'symbol' => 'CA$'],
            'AUD' => ['name' => 'Australian Dollar',     'symbol' => 'A$'],
            'INR' => ['name' => 'Indian Rupee',          'symbol' => '₹'],
            'JPY' => ['name' => 'Japanese Yen',          'symbol' => '¥'],
            'CNY' => ['name' => 'Chinese Yuan',          'symbol' => '¥'],
        ];
    }

    /**
     * Auto-seed default lead statuses when a new organization is created.
     */
    protected static function booted(): void
    {
        static::created(function (Organization $organization) {
            LeadStatus::seedDefaults($organization->id);
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function leadStatuses()
    {
        return $this->hasMany(LeadStatus::class);
    }
}
