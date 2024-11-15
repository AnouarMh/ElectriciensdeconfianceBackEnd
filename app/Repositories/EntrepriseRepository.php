<?php

namespace App\Repositories;

use App\Models\Entreprise;
use App\Models\User;

class EntrepriseRepository implements EntrepriseRepositoryInterface
{
    public function create(array $data)
    {
        return Entreprise::create($data);
    }

    public function update($id, array $data)
    {
        $user = Entreprise::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function find($id)
    {
        return Entreprise::findOrFail($id);
    }
    
}