<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignation extends Model
{
    protected $fillable = [
        'formateur_id',
        'subject_id',
        'filiere_id',
        'site_id',
        'trimestres', // ✅ AJOUTÉ
    ];
    
    // ✅ AJOUTÉ : Cast automatique JSON ↔ Array
    protected $casts = [
        'trimestres' => 'array',
    ];

    // Relations existantes
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    
    // ✅ AJOUTÉ : Méthodes utilitaires
    
    /**
     * Vérifie si cette assignation est active pour un trimestre donné
     * 
     * @param int $term Le numéro du trimestre (1, 2 ou 3)
     * @return bool
     */
    public function isActiveForTerm($term)
    {
        if (!$this->trimestres || empty($this->trimestres)) {
            return true; // Par défaut tous les trimestres
        }
        
        return in_array($term, $this->trimestres);
    }
    
    /**
     * Retourne une chaîne lisible des trimestres
     * Exemple : "T1, T3" ou "Tous les trimestres"
     * 
     * @return string
     */
    public function getTrimestreDisplayAttribute()
    {
        if (!$this->trimestres || empty($this->trimestres)) {
            return 'Tous les trimestres';
        }
        
        if (count($this->trimestres) === 3) {
            return 'Tous les trimestres';
        }
        
        $terms = array_map(function($t) {
            return "T$t";
        }, $this->trimestres);
        
        return implode(', ', $terms);
    }
}