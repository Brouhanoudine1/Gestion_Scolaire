<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleves extends Model
{
    protected $fillable = ['nom', 'prenom', 'id_class'];
}
