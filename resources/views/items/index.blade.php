@extends('layouts.app')

@section('title', 'Предметы для обмена')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="card mb-4 border-0 bg-white">
        <div class="card-body p-5 text-center">
            <h1 class="display-4 fw-bold mb-3 text-primary">Добро пожаловать на GivnGet</h1>
            <p class="lead mb-4 text-secondary">Платформа для обмена предметами между пользователями</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('items.create') }}" class="btn btn-primary-custom btn-custom btn-icon">
                    <i class="fas fa-plus me-2"></i>Добавить предмет
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary-custom btn-custom btn-icon">
                    <i class="fas fa-th-large me-2"></i>Категории
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form action="{{ url('search') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="query" class="form-control border-start-0" placeholder="Поиск предметов..." value="{{ request('query') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Все категории</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="condition" class="form-select">
                        <option value="">Любое состояние</option>
                        <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>Новое</option>
                        <option value="excellent" {{ request('condition') == 'excellent' ? 'selected' : '' }}>Отличное</option>
                        <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Хорошее</option>
                        <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Удовлетворительное</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary-custom btn-custom btn-icon">
                        <i class="fas fa-filter me-2"></i>Применить фильтры
                    </button>
                    @if(request('query') || request('category') || request('condition'))
                        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary-custom btn-custom btn-icon ms-2">
                            <i class="fas fa-times me-2"></i>Сбросить
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Items List -->
    <div class="card border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h1 class="h3 mb-0">Доступные предметы</h1>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group">
                    <button id="gridViewBtn" class="btn btn-outline-secondary-custom btn-custom btn-icon active">
                        <i class="fas fa-th-large"></i> Сетка
                    </button>
                    <button id="listViewBtn" class="btn btn-outline-secondary-custom btn-custom btn-icon">
                        <i class="fas fa-list"></i> Список
                    </button>
                </div>
                <a href="{{ route('items.create') }}" class="btn btn-primary-custom btn-custom btn-icon">
                    <i class="fas fa-plus me-2"></i>Добавить предмет
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Grid View -->
            <div id="gridView" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 p-4">
                @forelse($items as $item)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                @php
                                    $itemImages = is_array($item->images) ? $item->images : json_decode($item->images, true);
                                    $hasImage = $itemImages && is_array($itemImages) && count($itemImages) > 0;
                                @endphp
                                @if($hasImage)
                                    <img src="{{ asset('public/storage/' . $itemImages[0]) }}" alt="{{ $item->title }}" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <span class="position-absolute top-0 end-0 m-2 badge bg-success">{{ $item->condition }}</span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-dark">{{ $item->title }}</h5>
                                <p class="card-text text-muted">
                                    <i class="fas fa-tag me-1"></i>{{ $item->category->name }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="ms-2 text-muted">{{ $item->user->name }}</span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $item->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-flex gap-2 justify-content-end">
                                    @if(auth()->id() !== $item->user_id)
                                        <a href="{{ route('items.show', $item) }}" class="btn btn-primary d-flex align-items-center justify-content-center mx-auto" style="width: 90%; margin: 0 auto;">
                                            <i class="fas fa-eye me-2"></i>Подробнее
                                        </a>
                                    @else
                                        <div class="d-flex gap-2 flex-row-reverse">
                                            <div class="btn-group">
                                                <a href="{{ route('items.edit', $item) }}" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center gap-1">
                                                    <i class="fas fa-edit"></i> <span>Редактировать</span>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <a href="{{ route('items.show', $item) }}" class="btn btn-primary d-flex align-items-center justify-content-center w-100">
                                                <i class="fas fa-eye me-2"></i>Подробнее
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">Подтверждение удаления</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Вы уверены, что хотите удалить предмет "{{ $item->title }}"?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                    <form action="{{ route('items.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Пока нет доступных предметов для обмена</h4>
                        <p class="text-muted">Будьте первым, кто добавит предмет для обмена!</p>
                        @auth
                            <a href="{{ route('items.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Добавить предмет
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>
            
            <!-- List View -->
            <div id="listView" class="d-none p-4">
                @forelse($items as $item)
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-3">
                                @php
                                    $itemImages = is_array($item->images) ? $item->images : json_decode($item->images, true);
                                    $hasImage = $itemImages && is_array($itemImages) && count($itemImages) > 0;
                                @endphp
                                @if($hasImage)
                                    <img src="{{ asset('public/storage/' . $itemImages[0]) }}" alt="{{ $item->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title text-dark">{{ $item->title }}</h5>
                                        <span class="badge bg-success">{{ $item->condition }}</span>
                                    </div>
                                    <p class="card-text text-muted">
                                        <i class="fas fa-tag me-1"></i>{{ $item->category->name }}
                                    </p>
                                    <p class="card-text">{{ Str::limit($item->description, 150) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="ms-2 text-muted">{{ $item->user->name }}</span>
                                        </div>
                                        <div>
                                            <small class="text-muted me-3">
                                                <i class="fas fa-clock me-1"></i>{{ $item->created_at->diffForHumans() }}
                                            </small>
                                            <div class="d-flex gap-2">
                                                @if(auth()->id() !== $item->user_id)
                                                    <a href="{{ route('items.show', $item) }}" class="btn btn-primary d-flex align-items-center justify-content-center mx-auto" style="width: 90%; margin: 0 auto;">
                                                        <i class="fas fa-eye me-2"></i>Подробнее
                                                    </a>
                                                @else
                                                    <div class="d-flex gap-2 flex-row-reverse">
                                                        <div class="btn-group">
                                                            <a href="{{ route('items.edit', $item) }}" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center gap-1">
                                                                <i class="fas fa-edit"></i> <span>Редактировать</span>
                                                            </a>
                                                            <button type="button" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <a href="{{ route('items.show', $item) }}" class="btn btn-primary d-flex align-items-center justify-content-center w-100">
                                                            <i class="fas fa-eye me-2"></i>Подробнее
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Пока нет доступных предметов для обмена</h4>
                        <p class="text-muted">Будьте первым, кто добавит предмет для обмена!</p>
                        @auth
                            <a href="{{ route('items.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Добавить предмет
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        @if($items->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $items->previousPageUrl() }}" aria-label="Previous">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @endif
                        
                        @foreach($items->getUrlRange(max($items->currentPage() - 2, 1), min($items->currentPage() + 2, $items->lastPage())) as $page => $url)
                            <li class="page-item {{ $page == $items->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        
                        @if($items->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $items->nextPageUrl() }}" aria-label="Next">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            <div class="text-center text-muted small mt-2">
                Показано {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} из {{ $items->total() }} результатов
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const gridViewBtn = $('#gridViewBtn');
    const listViewBtn = $('#listViewBtn');
    const gridView = $('#gridView');
    const listView = $('#listView');

    function toggleView(view) {
        if (view === 'grid') {
            gridView.removeClass('d-none');
            listView.addClass('d-none');
            gridViewBtn.addClass('active');
            listViewBtn.removeClass('active');
            localStorage.setItem('itemsView', 'grid');
        } else {
            gridView.addClass('d-none');
            listView.removeClass('d-none');
            gridViewBtn.removeClass('active');
            listViewBtn.addClass('active');
            localStorage.setItem('itemsView', 'list');
        }
    }

    gridViewBtn.on('click', function() {
        toggleView('grid');
    });

    listViewBtn.on('click', function() {
        toggleView('list');
    });

    // Загрузка сохраненного вида
    const savedView = localStorage.getItem('itemsView');
    if (savedView) {
        toggleView(savedView);
    }
});
</script>

<script src="{{ asset('js/item-edit.js') }}"></script>
@endpush

@include('items._edit-modal')
@endsection 