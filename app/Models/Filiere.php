<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;
    
    protected $fillable = ['nom', 'code'];

    // ✅ Événement après création d'une filière
    protected static function booted()
    {
        static::created(function ($filiere) {
            // Récupérer les frais d'inscription et de soutenance
            $feeInscription = Fee::where('type', 'inscription')->first();
            $feeSoutenance = Fee::where('type', 'soutenance')->first();

            // Attacher automatiquement avec montants par défaut
            if ($feeInscription) {
                $feeInscription->filieres()->attach($filiere->id, ['montant' => 20000]);
            }

            if ($feeSoutenance) {
                $feeSoutenance->filieres()->attach($filiere->id, ['montant' => 50000]);
            }
        });
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'filiere_subject');
    }

    public function assignations()
    {
        return $this->hasMany(Assignation::class);
    }

    public function fees()
    {
        return $this->belongsToMany(Fee::class, 'fee_filiere')
            ->withPivot('montant')
            ->withTimestamps();
    }
}