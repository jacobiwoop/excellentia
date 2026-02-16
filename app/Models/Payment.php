<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_fee_id',
        'montant',
        'mode_paiement',
        'date_paiement',
        'note'
    ];
    protected $casts = [
    'date_paiement' => 'datetime',
];
    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }
}
