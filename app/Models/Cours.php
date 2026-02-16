<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'fichier_path',
        'video_path',
        'assignation_id',
        'promotion_id',
        'formateur_id',
    ];

    public function assignation()
    {
        return $this->belongsTo(Assignation::class);
    }
    public function getFilieresAttribute()
    {
        // Version corrigée pour relation "assignation" (singulier)
        if ($this->assignation && $this->assignation->relationLoaded('filiere')) {
            return $this->assignation->filiere->nom ?? 'Aucune filière associée';
        }

        // Fallback sécurisé
        return 'Filières non chargées'; // Ou une requête alternative si nécessaire
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }
}
