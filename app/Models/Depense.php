<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'motif',
        'montant',
        'date_depense',
        'description',
        'justificatif',
        'site_id',
        'formateur_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_depense' => 'date',
        'montant' => 'decimal:2',
    ];

    // Relations
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accesseur pour savoir si c'est un salaire
    public function getIsSalaireAttribute()
    {
        return !is_null($this->formateur_id);
    }

    // Accesseur pour le type de dépense
    public function getTypeAttribute()
    {
        return $this->is_salaire ? 'Salaire Formateur' : 'Autre';
    }
   
}