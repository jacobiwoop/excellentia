<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fee extends Model
{
    /**
     * Les attributs mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['nom', 'description', 'type']; // 'montant' retiré car maintenant dans la pivot

    /**
     * Relation avec les filières (many-to-many via la table pivot fee_filiere)
     */
    
      public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'fee_filiere')
               ->withPivot('montant')
               ->using(FeeFiliere::class); // Optionnel : si tu as un modèle pivot
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    /**
     * Helper: Récupère le montant pour une filière spécifique
     */
 public function getMontantForFiliere($filiereId)
{
    return $this->filieres()->where('filiere_id', $filiereId)->first()?->pivot->montant;
}

    /**
     * Helper: Vérifie si le frais est applicable à une filière
     */
    public function appliesToFiliere(int $filiereId): bool
    {
        return $this->filieres()
            ->where('filiere_id', $filiereId)
            ->exists();
    }
}