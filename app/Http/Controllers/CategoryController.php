<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('children');
        
        // Получаем все подкатегории (включая вложенные)
        $subcategoryIds = $this->getAllSubcategoryIds($category);
        
        // Добавляем ID текущей категории
        $categoryIds = array_merge([$category->id], $subcategoryIds);
        
        // Загружаем предметы из текущей категории и всех подкатегорий
        $items = Item::whereIn('category_id', $categoryIds)
            ->with('user')
            ->latest()
            ->paginate(12);
            
        return view('categories.show', compact('category', 'items'));
    }

    /**
     * Рекурсивно получает ID всех подкатегорий
     */
    private function getAllSubcategoryIds(Category $category)
    {
        $ids = [];
        
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllSubcategoryIds($child));
        }
        
        return $ids;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has items
        if ($category->items()->exists()) {
            return redirect()->back()
                ->with('error', 'Невозможно удалить категорию, так как в ней есть предметы');
        }

        // Check if category has children
        if ($category->children()->exists()) {
            return redirect()->back()
                ->with('error', 'Невозможно удалить категорию, так как у нее есть подкатегории');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно удалена');
    }
}
