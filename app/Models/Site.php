<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = ['nom', 'code'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
{
    return $this->hasMany(Student::class);
}

// app/Models/Site.php
public function admins()
{
    return $this->hasMany(User::class)->where('role', 'admin_site');
}

public function formateurs()
{
    return $this->hasMany(User::class)->where('role', 'formateur');
}

}
