<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    protected $fillable = [
        'nom', 'adresse', 'zone_intervention', 'site_web', 'taille', 'description'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}