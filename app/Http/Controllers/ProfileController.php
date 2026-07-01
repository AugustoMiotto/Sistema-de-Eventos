<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->hasFile('profile_photo')) {
            // Se o usuário já tinha uma foto antiga, apaga ela para não acumular lixo
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Salva a nova foto na pasta 'users/photos' dentro do disco público
            $user->profile_photo_path = $request->file('profile_photo')->store('users/photos', 'public');
        }

        // 🚨 REGRA IFRS GLOBAL DEFINITIVA
        $email = $user->email;
        $isStudent = preg_match('/@aluno\.[a-zA-Z0-9-]+\.ifrs\.edu\.br$/i', $email);
        $isInstitutional = preg_match('/@[a-zA-Z0-9-]+\.ifrs\.edu\.br$/i', $email);

        $canBePromoter = $isInstitutional && !$isStudent;

        if (!$canBePromoter) {
            $user->is_promoter = false;
        } else {
            $user->is_promoter = $request->has('is_promoter');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
