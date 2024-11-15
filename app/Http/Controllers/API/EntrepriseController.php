<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\EntrepriseRepositoryInterface;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    protected $entrepriseRepository;

    public function __construct(EntrepriseRepositoryInterface $entrepriseRepository)
    {
        $this->entrepriseRepository = $entrepriseRepository;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'taille' => 'required|in:independent,small,medium,large',
        ]);

        $user = $request->user();
        $entreprise = $user->entreprise;

        if (!$entreprise) {
            $entreprise = $this->entrepriseRepository->create([
                'taille' => $this->mapTailleToEnum($validatedData['taille']),
            ]);
            $user->entreprise()->associate($entreprise);
            $user->save();
        } else {
            $entreprise->update(['taille' => $validatedData['taille']]);
        }

        return response()->json($entreprise, 201);
    }
    private function mapTailleToEnum($taille)
    {
        $mapping = [
            'independent' => 'indépendant',
            'small' => 'moins de 10 salariés',
            'medium' => 'entre 10 et 20 salariés',
            'large' => 'plus de 20 salariés',
        ];
    
        return $mapping[$taille] ?? $taille;
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'zone_intervention' => 'required|string|max:255',
            'site_web' => 'nullable|url',
            'taille' => 'in:indépendant,moins de 10 salariés,entre 10 et 20 salariés,plus de 20 salariés',
            'description' => 'nullable|string',
        ]);

        $user = $request->user();
        $entreprise = $user->entreprise;

        if (!$entreprise) {
            return response()->json(['message' => 'Entreprise not found'], 404);
        }

        $entreprise = $this->entrepriseRepository->update($entreprise->id, $validatedData);

        return response()->json($entreprise);
    }

 
}