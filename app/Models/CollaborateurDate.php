<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CollaborateurDate extends Pivot
{
    protected $table = 'collaborateur_dates';

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'horaire',
        'hub_id',
        'collaborateur_id',
        'evenements',
        'date_id'
    ];
}
