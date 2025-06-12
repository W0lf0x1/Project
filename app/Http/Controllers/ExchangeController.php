<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Item;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exchanges = Exchange::with(['item', 'offeredItem', 'user'])
            ->where('user_id', auth()->id())
            ->orWhereHas('item', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return view('exchanges.index', compact('exchanges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        \Log::info('Exchange create request parameters:', $request->all());
        
        if (!$request->has('item')) {
            \Log::error('Item parameter is missing in the request');
            return redirect()->route('items.index')->with('error', 'Не указан предмет для обмена');
        }
        
        try {
            $item = Item::findOrFail($request->item);
            \Log::info('Item found:', ['item_id' => $item->id, 'item_title' => $item->title]);
            
            $item->load('user');
            $userItems = Item::where('user_id', auth()->id())
                ->where('id', '!=', $item->id)
                ->where('is_available', true)
                ->get();
            
            \Log::info('User items found:', ['count' => $userItems->count()]);
            
            return view('exchanges.create', compact('item', 'userItems'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Item not found:', ['item_id' => $request->item]);
            return redirect()->route('items.index')->with('error', 'Предмет не найден');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'requested_item_id' => 'required|exists:items,id',
            'offered_item_id' => 'required|exists:items,id',
            'message' => 'nullable|string'
        ]);

        $requestedItem = Item::findOrFail($validated['requested_item_id']);
        $offeredItem = Item::findOrFail($validated['offered_item_id']);

        // Проверяем, что предлагаемый предмет принадлежит текущему пользователю
        if ($offeredItem->user_id !== auth()->id()) {
            return back()->with('error', 'Вы не можете предложить чужой предмет');
        }

        // Проверяем, что предмет доступен для обмена
        if (!$offeredItem->is_available) {
            return back()->with('error', 'Этот предмет уже недоступен для обмена');
        }

        $exchange = Exchange::create([
            'user_id' => auth()->id(),
            'item_id' => $requestedItem->id,
            'offered_item_id' => $offeredItem->id,
            'message' => $validated['message'],
            'status' => 'pending'
        ]);

        return redirect()->route('exchanges.show', $exchange)
            ->with('success', 'Предложение обмена успешно создано');
    }

    /**
     * Display the specified resource.
     */
    public function show(Exchange $exchange)
    {
        $exchange->load(['item', 'offeredItem', 'user', 'messages.user']);
        return view('exchanges.show', compact('exchange'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exchange $exchange)
    {
        // Check if the user is authorized to delete the exchange
        if (!in_array(auth()->id(), [$exchange->user_id, $exchange->item->user_id])) {
            return redirect()->back()
                ->with('error', 'У вас нет прав для удаления этого обмена');
        }

        // Check if the exchange can be deleted
        if ($exchange->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Невозможно удалить обмен, так как он уже обработан');
        }

        $exchange->delete();

        return redirect()->route('exchanges.index')
            ->with('success', 'Обмен успешно удален');
    }

    public function accept(Exchange $exchange)
    {
        // Проверяем, что текущий пользователь является владельцем предмета
        if ($exchange->item->user_id !== auth()->id()) {
            return back()->with('error', 'У вас нет прав для выполнения этого действия');
        }

        // Проверяем, что обмен еще не завершен
        if ($exchange->status !== 'pending') {
            return back()->with('error', 'Этот обмен уже обработан');
        }

        $exchange->update(['status' => 'accepted']);

        // Помечаем оба предмета как недоступные
        $exchange->item->update(['is_available' => false]);
        $exchange->offeredItem->update(['is_available' => false]);

        return redirect()->route('exchanges.show', $exchange)
            ->with('success', 'Обмен успешно принят');
    }

    public function reject(Exchange $exchange)
    {
        // Проверяем, что текущий пользователь является владельцем предмета
        if ($exchange->item->user_id !== auth()->id()) {
            return back()->with('error', 'У вас нет прав для выполнения этого действия');
        }

        // Проверяем, что обмен еще не завершен
        if ($exchange->status !== 'pending') {
            return back()->with('error', 'Этот обмен уже обработан');
        }

        $exchange->update(['status' => 'rejected']);

        return redirect()->route('exchanges.show', $exchange)
            ->with('success', 'Обмен отклонен');
    }

    public function complete(Exchange $exchange)
    {
        // Проверяем, что обмен был принят
        if ($exchange->status !== 'accepted') {
            return back()->with('error', 'Этот обмен еще не принят');
        }

        // Проверяем, что текущий пользователь участвует в обмене
        if (!in_array(auth()->id(), [$exchange->user_id, $exchange->item->user_id])) {
            return back()->with('error', 'У вас нет прав для выполнения этого действия');
        }

        $exchange->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Удаляем оба товара из каталога
        $exchange->item->delete();
        $exchange->offeredItem->delete();

        return redirect()->route('home')->with('success', 'Обмен успешно завершён, предметы удалены!');
    }
}
