<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'date', 'status', 'student_id', 'formateur_id', 'marked_by_role'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }
}