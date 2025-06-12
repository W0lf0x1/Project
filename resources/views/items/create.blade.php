@extends('layouts.app')

@section('title', 'Добавить предмет')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-plus-circle me-2"></i>Добавить новый предмет
                        </h5>
                        <a href="{{ route('items.index') }}" class="btn btn-secondary-custom btn-custom btn-icon">
                            <i class="fas fa-arrow-left"></i> Назад
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label">Название</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Описание</label>
                            <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Категория</label>
                                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Выберите категорию</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="condition" class="form-label">Состояние</label>
                                <select id="condition" name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                                    <option value="">Выберите состояние</option>
                                    <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Новое</option>
                                    <option value="like_new" {{ old('condition') == 'like_new' ? 'selected' : '' }}>Отличное</option>
                                    <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Хорошее</option>
                                    <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Удовлетворительное</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Фотографии</label>
                            <div class="border rounded p-4 text-center bg-light">
                                <div class="mb-3">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                                </div>
                                <div class="mb-2">
                                    <label for="images" class="btn btn-outline-primary-custom btn-custom btn-icon">
                                        <i class="fas fa-upload me-2"></i>Выбрать файлы
                                    </label>
                                    <input id="images" name="images[]" type="file" class="d-none" multiple accept="image/*">
                                </div>
                                <p class="text-muted small mb-0">
                                    Перетащите файлы сюда или нажмите для выбора<br>
                                    <span class="text-muted">PNG, JPG, GIF до 2MB</span>
                                </p>
                            </div>
                            @error('images')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('items.index') }}" class="btn btn-outline-secondary-custom btn-custom btn-icon">
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
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('images');
        const imagePreview = document.getElementById('imagePreview');

        imageInput.addEventListener('change', function() {
            imagePreview.innerHTML = '';
            
            if (this.files) {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.width = '150px';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        imagePreview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>
@endpush
@endsection 