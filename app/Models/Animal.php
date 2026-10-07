<?php

namespace App\Models;

use App\Enums\Species;
use Database\Factories\AnimalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['shelter_id', 'name', 'species', 'birth_date', 'description', 'adopted_at'])]
class Animal extends Model
{
    /** @use HasFactory<AnimalFactory> */
    use HasFactory;

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * L'âge en années révolues, ou null si la date de naissance est inconnue.
     */
    public function age(): ?int
    {
        if ($this->birth_date === null) {
            return null;
        }

        return (int) $this->birth_date->diffInYears(today());
    }

    public function isAdopted(): bool
    {
        return $this->adopted_at !== null;
    }

    protected function casts(): array
    {
        return [
            'species' => Species::class,
            'birth_date' => 'date',
            'adopted_at' => 'date',
        ];
    }
}
