<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'document_number',
        'names',
        'last_names',
        'phone',
        'email',
        'address',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
