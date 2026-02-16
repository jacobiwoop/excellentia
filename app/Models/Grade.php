<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'student_id',
        'subject_id', 
        'filiere_id',
        'site_id',
        'formateur_id',
        'assignation_id',
        'interro1',
        'interro2',
        'interro3',
        'devoir',
        'moy_interro',
        'moy_finale',
        'term'
    ];

    /**
     * Les attributs qui devraient être typecast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'interro1' => 'float',
        'interro2' => 'float',
        'interro3' => 'float',
        'devoir' => 'float',
        'moy_interro' => 'float',
        'moy_finale' => 'float',
    ];

    /**
     * Relation avec le modèle Student.
     */
 public function student(): BelongsTo
{
    return $this->belongsTo(Student::class, 'student_id');
}
    /**
     * Relation avec le modèle Subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relation avec le modèle Filiere.
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    /**
     * Relation avec le modèle Site.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Relation avec le modèle User (formateur).
     */
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    /**
     * Relation avec le modèle Assignation.
     */
    public function assignation(): BelongsTo
    {
        return $this->belongsTo(Assignation::class);
    }
}