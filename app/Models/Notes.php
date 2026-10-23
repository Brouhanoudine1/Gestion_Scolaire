<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $fillable = [
        'id_evaluation',
        'id_eleve',
        'id_mat',
        'id_teacher',
        'notes',
        'decision',
    ];
}
