<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class Student extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable; // Trait pour gérer l'auth facilement

    protected $fillable = [
        'nom_prenom',
        'matricule',
        'telephone',
        'email',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'site_id',
        'filiere_id',
        'promotion_id',
        'statut',
        'photo'
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    // Relations
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Méthodes pour l'authentification (obligatoires)
    public function getAuthIdentifierName()
    {
        return 'matricule'; // On utilise le matricule comme identifiant
    }

    public function getAuthPassword()
    {
        return null; // Pas de mot de passe
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }
}
