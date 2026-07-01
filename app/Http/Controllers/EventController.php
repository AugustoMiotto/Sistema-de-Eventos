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
        $speakers = \App\Models\Speaker::orderBy('name')->get();
        return view('events.create', compact('categories', 'speakers'));
    }

    // Salva o evento no banco
   // Salva o evento no banco
    public function store(Request $request)
    {
        if (!auth()->user()->is_promoter) {
            abort(403);
        }

        $validated = $request->validate([
    // 1. ARRAY DE REGRAS
    'name' => ['required', 'string', 'max:255'],
    'description' => ['nullable', 'string'],
    'category_id' => ['required', 'exists:categories,id'],
    'speaker_ids' => ['nullable', 'array'],
    'speaker_ids.*' => ['exists:speakers,id'],
    'location' => ['required', 'string', 'max:255'],
    'target_audience' => ['required', 'string', 'max:255'],
    'max_slots' => ['required', 'integer', 'min:1', 'max:999999'],
    'start_at' => ['required', 'date', 'after:today'],
    'price' => ['nullable', 'numeric', 'min:0'],
    'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            // ARRAY DE MENSAGENS CUSTOMIZADAS
            'start_at.after' => 'O campo data de início deve ser uma data de amanhã em diante',
        ]);

        // Trata o upload da imagem de capa
        $coverPhotoPath = null;
        if ($request->hasFile('cover_photo')) {
            $coverPhotoPath = $request->file('cover_photo')->store('events/covers', 'public');
        }

        // Determina se é gratuito baseado no valor enviado
        $isFree = empty($request->price) || $request->price <= 0;

        // 1. PRIMEIRO: Cria o evento e guarda na variável $event
        $event = Event::create([
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

        // 2. DEPOIS: Com o evento criado (já com ID), vincula os palestrantes!
        if (!empty($request->speaker_ids)) {
            $event->speakers()->sync($request->speaker_ids);
        }

        return redirect()->route('dashboard')->with('status', 'Evento publicado com sucesso!');
    }

    // Mostra os detalhes de um evento específico
    public function show(Event $event)
    {
        // Carrega as informações da categoria, do promotor e palestrante ligadas a este evento
        $event->load(['category', 'promoter', 'speakers']);


        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
{
    if ($event->promoter_id !== auth()->id()) {
        abort(403, 'Você não tem permissão para editar este evento.');
    }
    $event->load('speakers');

    $categories = Category::orderBy('name')->get();
    $speakers = \App\Models\Speaker::orderBy('name')->get();

    return view('events.edit', compact('event', 'categories', 'speakers'));
}

    // Salva as alterações do evento
    public function update(Request $request, Event $event)
    {
        if ($event->promoter_id !== auth()->id()) {
            abort(403);
        }

        if ($event->start_at->isPast() && !$event->start_at->isToday()) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['start_at' => 'Não é possível editar um evento cuja data de início já passou.']);
    }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'speaker_ids' => ['nullable', 'array'], // Validação dos palestrantes
            'speaker_ids.*' => ['exists:speakers,id'],
            'location' => ['required', 'string', 'max:255'],
            'target_audience' => ['required', 'string', 'max:255'],
            'max_slots' => ['required', 'integer', 'min:1','max:999999'],
            'start_at' => ['required', 'date', 'after_or_equal:today'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ],[
        'start_at.after_or_equal' => 'A nova data de início deve ser de hoje em diante.',
    ]);

        if ($request->hasFile('cover_photo')) {
            if ($event->cover_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->cover_photo);
            }
            $validated['cover_photo'] = $request->file('cover_photo')->store('events/covers', 'public');
        }

        $isFree = empty($request->price) || $request->price <= 0;
        $validated['is_free'] = $isFree;
        $validated['price'] = $isFree ? 0 : $request->price;

        $event->update($validated);

        // ATUALIZAÇÃO DOS PALESTRANTES
        // Se vier vazio, o sync([]) remove todos. Se vier IDs, ele atualiza!
        $event->speakers()->sync($request->input('speaker_ids', []));

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
