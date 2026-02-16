<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['nom', 'filiere_id'];

    public function assignations()
    {
        return $this->hasMany(Assignation::class);
    }
   public function filieres()
{
    return $this->belongsToMany(Filiere::class, 'filiere_subject');
}


    
public function programmes()
    {
        return $this->hasMany(Programme::class);
    }
}
