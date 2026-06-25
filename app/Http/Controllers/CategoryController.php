<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Mostra o formulário de criação
    public function create()
    {
        return view('categories.create');
    }

    // Salva a categoria no banco
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        // SE o pedido vier do modal (AJAX), devolvemos os dados em JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Categoria criada com sucesso!',
                'category' => $category
            ], 201);
        }

        // Se for um cadastro tradicional por outra tela, redireciona normal
        return redirect()->route('dashboard')->with('status', 'Categoria criada com sucesso!');
    }
}
