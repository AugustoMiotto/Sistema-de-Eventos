<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Mostra o formulário de criação
    public function create()
    {
        // Trava de segurança: Apenas promotores podem aceder a esta tela
        if (!auth()->user()->is_promoter) {
            abort(403, 'Acesso restrito: Apenas promotores podem criar eventos.');
        }

        $categories = Category::orderBy('name')->get();
        return view('events.create', compact('categories'));
    }

    // Salva o evento no banco
    public function store(Request $request)
    {
        if (!auth()->user()->is_promoter) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:255'],
            'target_audience' => ['required', 'string', 'max:255'],
            'max_slots' => ['required', 'integer', 'min:1'],
            'start_at' => ['required', 'date', 'after:now'], // Evento tem de ser no futuro
            'price' => ['nullable', 'numeric', 'min:0'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Trata o upload da imagem de capa
        $coverPhotoPath = null;
        if ($request->hasFile('cover_photo')) {
            $coverPhotoPath = $request->file('cover_photo')->store('events/covers', 'public');
        }

        // Determina se é gratuito baseado no valor enviado
        $isFree = empty($request->price) || $request->price <= 0;

        Event::create([
            'promoter_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'target_audience' => $validated['target_audience'],
            'max_slots' => $validated['max_slots'],
            'is_free' => $isFree,
            'price' => $isFree ? 0 : $validated['price'],
            'start_at' => $validated['start_at'],
            'cover_photo' => $coverPhotoPath,
            'status' => 'New',
        ]);

        return redirect()->route('dashboard')->with('status', 'Evento publicado com sucesso!');
    }
    // Mostra os detalhes de um evento específico
    public function show(Event $event)
    {
        // Carrega as informações da categoria e do promotor (usuário) ligadas a este evento
        $event->load(['category', 'promoter']);

        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        // Segurança: Garante que apenas o promotor dono do evento pode editá-lo
        if ($event->promoter_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar este evento.');
        }

        $categories = Category::orderBy('name')->get();
        return view('events.edit', compact('event', 'categories'));
    }

    // Salva as alterações do evento
    public function update(Request $request, Event $event)
    {
        if ($event->promoter_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:255'],
            'target_audience' => ['required', 'string', 'max:255'],
            'max_slots' => ['required', 'integer', 'min:1'],
            'start_at' => ['required', 'date'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Trata o upload se uma NOVA foto de capa for enviada
        if ($request->hasFile('cover_photo')) {
            // Apaga a foto antiga do servidor para não acumular lixo eletrônico
            if ($event->cover_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->cover_photo);
            }
            // Salva a nova
            $validated['cover_photo'] = $request->file('cover_photo')->store('events/covers', 'public');
        }

        // Determina se mudou para gratuito ou pago
        $isFree = empty($request->price) || $request->price <= 0;
        $validated['is_free'] = $isFree;
        $validated['price'] = $isFree ? 0 : $request->price;

        // Atualiza o registro no banco
        $event->update($validated);

        return redirect()->route('promoter.events')->with('status', 'Evento atualizado com sucesso!');
    }

    // Exclui o evento (Soft Delete)
    public function destroy(Event $event)
    {
        if ($event->promoter_id !== auth()->id()) {
            abort(403);
        }

        // O Laravel intercepta o delete() e faz o Soft Delete automaticamente
        $event->delete();

        return redirect()->route('promoter.events')->with('status', 'Evento excluído com sucesso!');
    }
}
