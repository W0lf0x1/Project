@extends('layouts.app')

@section('title', 'Мои обмены')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="h4 mb-0"><i class="fas fa-exchange-alt me-2 text-primary"></i>Мои обмены</h1>
                        <div class="text-muted small">Управление вашими предложениями обмена</div>
                    </div>
                    <a href="{{ route('items.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-search me-1"></i>Найти предмет для обмена
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $outgoing = $exchanges->filter(fn($e) => $e->user_id == auth()->id());
                        $incoming = $exchanges->filter(fn($e) => $e->item->user_id == auth()->id() && $e->user_id != auth()->id());
                    @endphp

                    <h2 class="h5 mt-3 mb-3 text-primary"><i class="fas fa-inbox me-2"></i>Входящие обмены</h2>
                    <div class="row g-3 mb-4">
                        @forelse($incoming as $exchange)
                            <div class="col-md-6">
                                <div class="card h-100 border-primary border-2">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            @if($exchange->offeredItem->images && count($exchange->offeredItem->images) > 0)
                                                <img src="{{ Storage::url($exchange->offeredItem->images[0]) }}" alt="{{ $exchange->offeredItem->title }}" class="rounded me-3" style="width: 64px; height: 64px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 64px; height: 64px;">
                                                    <i class="fas fa-image text-secondary fa-2x"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('exchanges.show', $exchange) }}" class="fw-bold text-decoration-none text-primary">{{ $exchange->offeredItem->title }}</a>
                                                <div class="text-muted small">Вам предложили обмен на: <span class="fw-semibold">{{ $exchange->item->title }}</span></div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge @if($exchange->status === 'pending') bg-warning text-dark @elseif($exchange->status === 'accepted') bg-success @elseif($exchange->status === 'rejected') bg-danger @else bg-secondary @endif">
                                                @if($exchange->status === 'pending') Ожидает вашего решения @elseif($exchange->status === 'accepted') Принято @elseif($exchange->status === 'rejected') Отклонено @else Завершено @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small"><i class="fas fa-user me-1"></i>{{ $exchange->user->name }}</div>
                                            <div class="text-muted small"><i class="fas fa-clock me-1"></i>{{ $exchange->created_at->diffForHumans() }}</div>
                                        </div>
                                        @if($exchange->status === 'pending')
                                            <div class="mt-3 d-flex gap-2">
                                                <form action="{{ route('exchanges.accept', $exchange) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i>Принять</button>
                                                </form>
                                                <form action="{{ route('exchanges.reject', $exchange) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times me-1"></i>Отклонить</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <div>Нет входящих обменов</div>
                            </div>
                        @endforelse
                    </div>

                    <h2 class="h5 mt-4 mb-3 text-primary"><i class="fas fa-paper-plane me-2"></i>Исходящие обмены</h2>
                    <div class="row g-3">
                        @forelse($outgoing as $exchange)
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            @if($exchange->offeredItem->images && count($exchange->offeredItem->images) > 0)
                                                <img src="{{ Storage::url($exchange->offeredItem->images[0]) }}" alt="{{ $exchange->offeredItem->title }}" class="rounded me-3" style="width: 64px; height: 64px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 64px; height: 64px;">
                                                    <i class="fas fa-image text-secondary fa-2x"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('exchanges.show', $exchange) }}" class="fw-bold text-decoration-none text-primary">{{ $exchange->offeredItem->title }}</a>
                                                <div class="text-muted small">Вы предложили обмен на: <span class="fw-semibold">{{ $exchange->item->title }}</span></div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge @if($exchange->status === 'pending') bg-warning text-dark @elseif($exchange->status === 'accepted') bg-success @elseif($exchange->status === 'rejected') bg-danger @else bg-secondary @endif">
                                                @if($exchange->status === 'pending') Ожидает ответа @elseif($exchange->status === 'accepted') Принято @elseif($exchange->status === 'rejected') Отклонено @else Завершено @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small"><i class="fas fa-user me-1"></i>{{ $exchange->item->user->name }}</div>
                                            <div class="text-muted small"><i class="fas fa-clock me-1"></i>{{ $exchange->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-4">
                                <i class="fas fa-paper-plane fa-2x mb-2"></i>
                                <div>Нет исходящих обменов</div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="d-flex justify-content-center">
                        {{ $exchanges->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 