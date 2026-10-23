<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    protected $fillable = ['id_user', 'id_matiere', 'id_classe'];
}
