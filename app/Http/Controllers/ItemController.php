<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['category', 'user'])->latest()->paginate(12);
        $categories = Category::all();
        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:new,like_new,good,fair',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');
                $images[] = $path;
            }
        }

        $item = Item::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'condition' => $validated['condition'],
            'images' => $images,
            'is_available' => true
        ]);

        return redirect()->route('items.show', $item)
            ->with('success', 'Предмет успешно добавлен!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['category', 'user']);
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        if (request()->ajax()) {
            return response()->json([
                'title' => $item->title,
                'description' => $item->description,
                'category_id' => $item->category_id,
                'condition' => $item->condition,
                'images' => $item->images
            ]);
        }

        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:new,like_new,good,fair',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
        if (!is_array($images)) {
            $images = [];
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');
                $images[] = $path;
            }
        }

        $item->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'condition' => $validated['condition'],
            'images' => $images
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('items.show', $item)
            ->with('success', 'Предмет успешно обновлен!');
    }

    public function removeImage(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $imagePath = $request->input('image_path');
        $images = is_array($item->images) ? $item->images : json_decode($item->images, true);

        if (in_array($imagePath, $images)) {
            // Удаляем файл из хранилища
            Storage::disk('public')->delete($imagePath);

            // Удаляем изображение из массива
            $images = array_diff($images, [$imagePath]);
            $images = array_values($images); // Переиндексируем массив

            // Обновляем запись в базе данных
            $item->update(['images' => $images]);

            return redirect()->back()->with('success', 'Изображение успешно удалено.');
        }

        return redirect()->back()->with('error', 'Изображение не найдено.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // Check if the item can be deleted
        if (!$item->is_available) {
            return redirect()->back()
                ->with('error', 'Невозможно удалить предмет, так как он уже участвует в обмене');
        }

        // Check if the user is authorized to delete the item
        if (auth()->id() !== $item->user_id) {
            return redirect()->back()
                ->with('error', 'У вас нет прав для удаления этого предмета');
        }

        // Delete images
        if ($item->images) {
            $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
            if ($images && is_array($images)) {
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Предмет успешно удален');
    }

    public function category(Category $category)
    {
        $items = $category->items()->with(['user'])->latest()->paginate(12);
        return view('items.category', compact('category', 'items'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $category = $request->get('category');
        $condition = $request->get('condition');

        $itemsQuery = Item::with(['category', 'user']);

        if ($query) {
            $itemsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($category) {
            $itemsQuery->where('category_id', $category);
        }

        if ($condition) {
            $itemsQuery->where('condition', $condition);
        }

        $items = $itemsQuery->latest()->paginate(12);
        $categories = Category::all();

        return view('items.index', compact('items', 'categories', 'query', 'category', 'condition'));
    }
}
