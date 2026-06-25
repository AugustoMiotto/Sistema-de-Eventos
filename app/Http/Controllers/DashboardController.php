<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inicia a pesquisa trazendo a categoria do evento e apenas eventos ativos
        $query = Event::with('category')->whereIn('status', ['New', 'In Progress']);

        // 2. Aplica o filtro de Texto (Nome do evento)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 3. Aplica o filtro de Categoria
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 4. Executa a pesquisa ordenando pela data mais próxima e paginando de 9 em 9
        $events = $query->orderBy('start_at', 'asc')->paginate(9);

        // 5. Puxa as categorias para preencher o campo "Select" do filtro
        $categories = Category::all();

        return view('dashboard', compact('events', 'categories'));
    }
}
