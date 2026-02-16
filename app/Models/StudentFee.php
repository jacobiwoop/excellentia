<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $fillable = [
        'student_id',
        'fee_id',
        'montant_total',
        'montant_reduction',
        'montant_paye',
        'statut',
        'site_id',
        'promotion_id',
        'reduction_motif',
        
    ];
    protected $casts = [
    'date_paiement' => 'datetime',
];

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Ajoutez ces méthodes au modèle StudentFee
// Dans le modèle StudentFee
public function getMontantApresReductionAttribute()
{
    return max(0, $this->montant_total - $this->montant_reduction);
}

public function getTotalPayeAttribute()
{
    return $this->payments->sum('montant');
}

public function getResteAPayerAttribute()
{
    return max(0, $this->montant_apres_reduction - $this->total_paye);
}

public function updateStatut()
{
    if ($this->reste_a_payer <= 0) {
        $this->statut = 'payé';
    } elseif ($this->total_paye > 0) {
        $this->statut = 'partiellement_payé';
    } else {
        $this->statut = 'non_payé';
    }
    
    $this->save();
}
}
