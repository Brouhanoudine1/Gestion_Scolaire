<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emploi extends Model
{
    protected $fillable = [
        'id_teacher_assignement',
        'jour',
        'heure_de_debut',
        'heure_fin'
    ];
}
