@extends('layouts.app')

@section('title', 'Добавить категорию')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Добавить категорию</h1>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary-custom btn-custom btn-icon">
                    <i class="fas fa-arrow-left"></i> Назад
                </a>
            </div>
            <p class="text-muted mb-0">Создайте новую категорию для предметов</p>
        </div>

        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="name" class="form-label">Название</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="parent_id" class="form-label">Родительская категория</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                            <option value="">Нет</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="icon" class="form-label">Иконка</label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon') }}" placeholder="fas fa-folder">
                        <div class="form-text">Используйте классы Font Awesome (например, fas fa-folder)</div>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary-custom btn-custom btn-icon">
                        <i class="fas fa-times me-2"></i>Отмена
                    </a>
                    <button type="submit" class="btn btn-primary-custom btn-custom btn-icon">
                        <i class="fas fa-save me-2"></i>Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 