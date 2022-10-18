<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoursFerie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'annee',
        'date',
        'name',
        'hub_id'
    ];

    public function collaborateurs (): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Collaborateur::class, 'collaborateur_jours_feries', 'collaborateur_id', 'jours_ferie_id');
    }
}
