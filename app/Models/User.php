<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'phone',
        'anniversaire',
        'check_update',
        'color_travaille',
        'color_conge',
        'color_repos',
        'color_technicien',
        'color_texte',
        'hub_id',
        'collaborateur_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isCoordinateur (): bool
    {
        return $this->hasRole(['Coordinateur', 'Responsable', 'Administrateur']);
    }

    public function isResponsable (): bool
    {
        return $this->hasRole(['Responsable', 'Administrateur']);
    }

    public function isVolant (): bool
    {
        return $this->hasRole(['Volant', 'Responsable', 'Administrateur']);
    }

    public function isAdmin (): bool
    {
        return $this->hasRole('Administrateur');
    }
}
