<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'site_id'
    ];

    protected $hidden = ['password'];

    // Relations pour les rôles classiques (admin, formateur, etc.)
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function filiere()
{
    return $this->belongsTo(\App\Models\Filiere::class);
}

public function attendancesMarked()
{
    return $this->hasMany(Attendance::class, 'formateur_id');
}
    // Pas de lien avec Student !

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Vérifie si l'utilisateur a un des rôles spécifiés
     */
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }
}