<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLive extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'meeting_id',
        'date_debut',
        'duree_minutes',
        'is_active',
        'formateur_id',
        'promotion_id',
        'filiere_id',
        'site_id',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
