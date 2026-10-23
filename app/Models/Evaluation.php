<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'id_affectation',
        'titre',
        'date_evaluation',
        'note_max',
    ];

    protected function casts(): array
    {
        return [
            'date_evaluation' => 'date',
            'note_max' => 'decimal:2',
        ];
    }
}
