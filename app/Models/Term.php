<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable = ['name', 'code', 'order'];

    /**
     * Matières liées à ce trimestre via la table pivot filiere_subject
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'filiere_subject')
                    ->withPivot('filiere_id')
                    ->withTimestamps();
    }

    /**
     * Filieres liées à ce trimestre via la table pivot filiere_subject
     */
    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'filiere_subject')
                    ->withPivot('subject_id')
                    ->withTimestamps();
    }
}
