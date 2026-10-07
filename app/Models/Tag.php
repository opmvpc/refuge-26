<?php

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Un trait de caractère : « Calme », « Joueur », « Ok enfants »…
 * La classe s'appelle Tag parce que trait est un mot réservé de PHP.
 */
#[Fillable(['name'])]
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    public function animals(): BelongsToMany
    {
        return $this->belongsToMany(Animal::class);
    }
}
