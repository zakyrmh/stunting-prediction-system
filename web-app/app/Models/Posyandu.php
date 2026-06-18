<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'address', 'village', 'district', 'city'])]
class Posyandu extends Model
{
    use HasFactory;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Children::class);
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }
}
