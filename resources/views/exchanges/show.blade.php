@extends('layouts.app')

@section('title', 'Детали обмена')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Детали обмена</h1>
                    <p class="text-muted mb-0">Статус: 
                        @switch($exchange->status)
                            @case('pending')
                                <span class="badge bg-warning">Ожидает ответа</span>
                                @break
                            @case('accepted')
                                <span class="badge bg-success">Принят</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger">Отклонен</span>
                                @break
                            @case('completed')
                                <span class="badge bg-primary">Завершен</span>
                                @break
                        @endswitch
                    </p>
                </div>
                <div>
                    <a href="{{ route('exchanges.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Назад к списку
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- Предлагаемый предмет -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="h5 mb-3">Предлагаемый предмет</h2>
                            <div>
                                <h3 class="h6 mb-1">{{ $exchange->offeredItem->title }}</h3>
                                <p class="text-muted small mb-2">{{ $exchange->offeredItem->description }}</p>
                                <span class="badge bg-primary">
                                    {{ $exchange->offeredItem->condition }}
                                </span>
                                <p class="text-muted small mt-2 mb-0">
                                    Владелец: {{ $exchange->offeredItem->user->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Запрашиваемый предмет -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="h5 mb-3">Запрашиваемый предмет</h2>
                            <div>
                                <h3 class="h6 mb-1">{{ $exchange->item->title }}</h3>
                                <p class="text-muted small mb-2">{{ $exchange->item->description }}</p>
                                <span class="badge bg-primary">
                                    {{ $exchange->item->condition }}
                                </span>
                                <p class="text-muted small mt-2 mb-0">
                                    Владелец: {{ $exchange->item->user->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($exchange->message)
                <div class="card mt-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Сообщение</h2>
                        <p class="mb-0">{{ $exchange->message }}</p>
                    </div>
                </div>
            @endif

            @if($exchange->status === 'pending' && auth()->id() === $exchange->item->user_id)
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <form action="{{ route('exchanges.update', $exchange) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-2"></i>Отклонить
                        </button>
                    </form>
                    <form action="{{ route('exchanges.update', $exchange) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Принять
                        </button>
                    </form>
                </div>
            @endif

            @if($exchange->status === 'accepted')
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('messages.exchange', $exchange) }}" class="btn btn-primary">
                        <i class="fas fa-comments me-2"></i>Открыть чат
                    </a>
                    @if(auth()->id() === $exchange->item->user_id || auth()->id() === $exchange->offeredItem->user_id)
                        <form action="{{ route('exchanges.update', $exchange) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-double me-2"></i>Завершить обмен
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 