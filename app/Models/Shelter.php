<?php

namespace App\Models;

use Database\Factories\ShelterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'city'])]
class Shelter extends Model
{
    /** @use HasFactory<ShelterFactory> */
    use HasFactory;

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
