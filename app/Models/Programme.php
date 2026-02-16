<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $fillable = [
        'date_seance', 'heure_debut', 'heure_fin',
        'subject_id', 'titre_custom', 'description',
        'formateur_id', 'site_id', 'recurrence', 'date_fin_recurrence'
    ];

    protected $casts = [
        'date_seance' => 'date:Y-m-d',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    // Relation plusieurs filières via table pivot
    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'filiere_programme', 'programme_id', 'filiere_id')
                    ->withTimestamps();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
