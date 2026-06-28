<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    // Mostra o formulário de registo de palestrantes
    public function create()
    {
        // Trava de segurança: Apenas promotores institucionais
        if (!auth()->user()->is_promoter) {
            abort(403, 'Acesso restrito: Apenas promotores podem registar palestrantes.');
        }

        return view('speakers.create');
    }

    // Processa os dados e guarda no banco
    public function store(Request $request)
    {
        if (!auth()->user()->is_promoter) {
            abort(403);
        }

        // Validação rigorosa: Todos os campos obrigatórios
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:speakers,email'],
            'phone' => ['required', 'string', 'max:20'],
            'expertise_area' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:1500'],
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Foto obrigatória!
        ]);

        // Processa o upload da imagem
        $photoPath = $request->file('profile_photo')->store('speakers/photos', 'public');

        // Regista o palestrante na base de dados
        Speaker::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'expertise_area' => $validated['expertise_area'],
            'institution' => $validated['institution'],
            'bio' => $validated['bio'],
            'profile_photo_path' => $photoPath,
        ]);

        // Retorna para o painel de controle com uma mensagem de sucesso
        return redirect()->route('dashboard')->with('status', 'Palestrante registado com sucesso no sistema!');
    }

    public function storeAjax(Request $request)
    {
        if (!auth()->user()->is_promoter) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:speakers,email'],
            'phone' => ['required', 'string', 'max:20'],
            'expertise_area' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:1500'],
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $photoPath = $request->file('profile_photo')->store('speakers/photos', 'public');
        $validated['profile_photo_path'] = $photoPath;

        $speaker = Speaker::create($validated);

        // Devolve apenas os dados essenciais para atualizar a caixa de seleção na interface
        return response()->json([
            'id' => $speaker->id,
            'name' => $speaker->name,
            'institution' => $speaker->institution,
        ]);
    }
}
