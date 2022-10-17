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
        'droit_update',
        'import_horodatage'
    ];

    public function dates (): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Date::class, 'collaborateur_dates');
    }

    public function collaborateurs (): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Collaborateur::class, 'collaborateur_dates');
    }

    public function members (): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'hub_id');
    }

    public function horodatage (): ?string
    {
            return $this->import_horodatage ? date('d/m/Y \à H\hi', strtotime($this->import_horodatage)) : null;
    }
}
