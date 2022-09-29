<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeRotation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'hours',
        'hub_id'
    ];

    public function rotations (): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Rotation::class);
    }
}
