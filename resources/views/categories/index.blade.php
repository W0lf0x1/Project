@extends('layouts.app')

@section('title', 'Категории')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Категории</h1>
                    <p class="text-muted mb-0">Управление категориями предметов</p>
                </div>
                @can('manage-categories')
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Добавить категорию
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($categories as $category)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} fa-2x text-secondary me-3"></i>
                                @endif
                                <div>
                                    <h5 class="mb-1">
                                        <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">{{ $category->name }}</a>
                                    </h5>
                                    @if($category->description)
                                        <p class="text-muted mb-0">{{ $category->description }}</p>
                                    @endif
                                </div>
                            </div>
                            @can('manage-categories')
                                <div class="btn-group">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Редактировать
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                        <i class="fas fa-trash me-1"></i>Удалить
                                    </button>
                                </div>
                            @endcan
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $category->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $category->id }}">Подтверждение удаления</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Вы уверены, что хотите удалить категорию "{{ $category->name }}"?
                                        @if($category->children->count() > 0)
                                            <div class="alert alert-warning mt-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Эта категория содержит подкатегории. Удаление невозможно.
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                        @if($category->children->count() == 0)
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Удалить</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($category->children->count() > 0)
                            <div class="mt-3 ms-4">
                                <div class="list-group list-group-flush">
                                    @foreach($category->children as $child)
                                        <div class="list-group-item border-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }} fa-lg text-secondary me-3"></i>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('categories.show', $child) }}" class="text-decoration-none">{{ $child->name }}</a>
                                                        </h6>
                                                        @if($child->description)
                                                            <p class="text-muted mb-0 small">{{ $child->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @can('manage-categories')
                                                    <div class="btn-group">
                                                        <a href="{{ route('categories.edit', $child) }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-edit me-1"></i>Редактировать
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $child->id }}">
                                                            <i class="fas fa-trash me-1"></i>Удалить
                                                        </button>
                                                    </div>
                                                @endcan
                                            </div>
                                        </div>

                                        <!-- Delete Confirmation Modal for Child Category -->
                                        <div class="modal fade" id="deleteModal{{ $child->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $child->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $child->id }}">Подтверждение удаления</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Вы уверены, что хотите удалить категорию "{{ $child->name }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                        <form action="{{ route('categories.destroy', $child) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Удалить</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="list-group-item text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Категории пока не созданы</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 