<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recette extends Model
{
    use HasFactory;

    protected $fillable = [
        'motif',
        'montant',
        'date_recette',
        'description',
        'justificatif',
        'site_id',
        'student_fee_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_recette' => 'date',
        'montant' => 'decimal:2',
    ];

    // Relations
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accesseur pour savoir si c'est une recette automatique (scolarité)
    public function getIsAutomatiqueAttribute()
    {
        return !is_null($this->student_fee_id);
    }

    // Accesseur pour le type de recette
    public function getTypeAttribute()
    {
        return $this->is_automatique ? 'Scolarité' : 'Autre';
    }
}