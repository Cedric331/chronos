<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ville',
        'departement',
        'code_postal',
        'adresse',
        'complement_adresse',
        'abonne_freebox',
        'abonne_mobile',
    ];

    public function dates (): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Date::class, 'collaborateur_dates');
    }

    public function collaborateurs (): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Collaborateur::class, 'collaborateur_dates');
    }
}