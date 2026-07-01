<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PromoterController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_promoter) {
            abort(403, 'Acesso restrito: Apenas promotores podem aceder a esta área.');
        }

        // O 'withTrashed()' diz ao Laravel para trazer também os excluídos
        $events = Event::with('category')
            ->where('promoter_id', auth()->id())
            ->withTrashed()
            ->withCount(['users as inscritos_ativos_count' => function($query) {
            $query->whereNull('registrations.deleted_at')
                  ->where('registrations.status', 'Registered');
        }])
            ->orderBy('start_at', 'desc')
            ->paginate(10);

        return view('promoter.events.index', compact('events'));
    }
}
