<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Exchange;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::with(['exchange', 'user'])
            ->whereHas('exchange', function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereHas('item', function ($q) {
                        $q->where('user_id', auth()->id());
                    });
            })
            ->latest()
            ->paginate(20);

        return view('messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exchange_id' => 'required|exists:exchanges,id',
            'content' => 'required|string'
        ]);

        $exchange = \App\Models\Exchange::findOrFail($validated['exchange_id']);

        // Проверяем, что пользователь участвует в обмене
        if (!in_array(auth()->id(), [$exchange->user_id, $exchange->item->user_id])) {
            return back()->with('error', 'У вас нет прав для отправки сообщений в этом обмене');
        }

        $message = Message::create([
            'user_id' => auth()->id(),
            'exchange_id' => $exchange->id,
            'content' => $validated['content']
        ]);

        return redirect()->route('exchanges.show', $exchange)
            ->with('success', 'Сообщение отправлено');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(Message $message)
    {
        // Check if the user is authorized to delete the message
        if ($message->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'У вас нет прав для удаления этого сообщения');
        }

        $message->delete();

        return redirect()->back()
            ->with('success', 'Сообщение успешно удалено');
    }

    public function exchange(Exchange $exchange)
    {
        // Проверяем, что пользователь участвует в обмене
        if (!in_array(auth()->id(), [$exchange->user_id, $exchange->item->user_id])) {
            return back()->with('error', 'У вас нет прав для просмотра сообщений этого обмена');
        }

        $messages = $exchange->messages()
            ->with('user')
            ->latest()
            ->get();

        return view('messages.exchange', compact('exchange', 'messages'));
    }

    public function markAsRead(Message $message)
    {
        // Проверяем, что пользователь участвует в обмене
        if (!in_array(auth()->id(), [$message->exchange->user_id, $message->exchange->item->user_id])) {
            return back()->with('error', 'У вас нет прав для выполнения этого действия');
        }

        $message->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
