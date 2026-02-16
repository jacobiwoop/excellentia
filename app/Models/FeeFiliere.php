<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FeeFiliere extends Pivot
{
    protected $table = 'fee_filiere';
    protected $fillable = ['montant'];
}