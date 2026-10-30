<?php

namespace App\Models;

use App\Models\Concerns\HasContentTranslations;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasContentTranslations;

    protected $with = ['contentTranslations'];
    protected $fillable = ['name', 'description'];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
