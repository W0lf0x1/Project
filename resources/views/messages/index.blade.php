@extends('layouts.app')

@section('title', 'Мои сообщения')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Мои сообщения</h1>
                    <p class="text-muted mb-0">Управление вашими сообщениями</p>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($messages as $message)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">
                                    <a href="{{ route('messages.exchange', $message->exchange) }}" class="text-decoration-none">
                                        {{ $message->exchange->offeredItem->title }} ↔ {{ $message->exchange->item->title }}
                                    </a>
                                </h5>
                                <p class="mb-1">{{ Str::limit($message->content, 100) }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $message->user->name }}
                                    <i class="fas fa-clock ms-2 me-1"></i>{{ $message->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('messages.exchange', $message->exchange) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Просмотреть
                                </a>
                                @if($message->user_id === auth()->id())
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $message->id }}">
                                        <i class="fas fa-trash me-1"></i>Удалить
                                    </button>
                                @endif
                            </div>
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
                    </div>
                @empty
                    <div class="list-group-item text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">У вас пока нет сообщений</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="card-footer bg-white">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection 