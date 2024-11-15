<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'anniversaire' => 'required|date',
        ]);

        $user = $this->userService->register($validatedData);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Invalid login details'
        ], 401);
    }

    $user = User::where('email', $request['email'])->firstOrFail();

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
}
public function me(Request $request)
{
    $user = $request->user();
    $entreprise = $user->entreprise;

    return response()->json([
        'user' => $user,
        'entreprise' => $entreprise
    ]);
}

public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'anniversaire' => 'nullable|date',
            'logo' => 'nullable|image|max:2048',
            'notification' => 'boolean',
            'reponses_automatiques' => 'boolean',
            'publication_automatique' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $this->userService->updateProfile($user->id, $validatedData);

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function uploadLogo(Request $request)
{
    $request->validate([
        'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $user = $request->user();

    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('user_logos', 'public');
        $user->logo = $logoPath;
        $user->save();

        return response()->json([
            'message' => 'Logo uploaded successfully',
            'logo' => $logoPath
        ]);
    }

    return response()->json(['message' => 'No file uploaded'], 400);
}

//change password de user connecte 
public function changePassword(Request $request)
{
    $user = $request->user();

    $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if (!password_verify($request->current_password, $user->password)) {
        return response()->json(['message' => 'Invalid current password'], 400);
    }

    $user->password = bcrypt($request->password);
    $user->save();

    return response()->json(['message' => 'Password changed successfully']);
}
}