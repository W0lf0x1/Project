@extends('layouts.app')

@section('title', 'Сообщения обмена')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Сообщения обмена</h1>
                    <p class="text-muted mb-0">{{ $exchange->offeredItem->title }} ↔ {{ $exchange->item->title }}</p>
                </div>
                <a href="{{ route('exchanges.show', $exchange) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Назад к обмену
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="messages-container mb-4" style="max-height: 500px; overflow-y: auto;">
                @foreach($messages as $message)
                    <div class="message mb-3 {{ $message->user_id === auth()->id() ? 'text-end' : '' }}">
                        <div class="d-flex {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="message-content p-3 rounded {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 70%;">
                                <p class="mb-1">{{ $message->content }}</p>
                                <small class="{{ $message->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                    <i class="fas fa-user me-1"></i>{{ $message->user->name }}
                                    <i class="fas fa-clock ms-2 me-1"></i>{{ $message->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @if($message->user_id === auth()->id())
                                <div class="ms-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $message->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $message->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $message->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $message->id }}">Подтверждение удаления</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Вы уверены, что хотите удалить это сообщение?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                <form action="{{ route('messages.destroy', $message) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Удалить</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('messages.store', $exchange) }}" method="POST">
                @csrf
                <div class="input-group">
                    <textarea name="content" class="form-control" placeholder="Введите ваше сообщение..." rows="2" required></textarea>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Scroll to bottom of messages container
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.messages-container');
        container.scrollTop = container.scrollHeight;
    });
</script>
@endsection 