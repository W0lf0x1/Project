@extends('layouts.app')

@section('title', 'Редактировать предмет')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Редактировать предмет</h1>
                <a href="{{ route('items.show', $item) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Назад
                </a>
            </div>
            <p class="text-muted mb-0">Измените информацию о предмете</p>
        </div>

        <div class="card-body">
            <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Название</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" class="form-control" required>
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea id="description" name="description" rows="3" class="form-control" required>{{ old('description', $item->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Категория</label>
                            <select id="category_id" name="category_id" class="form-select" required>
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="condition" class="form-label">Состояние</label>
                            <select id="condition" name="condition" class="form-select" required>
                                <option value="">Выберите состояние</option>
                                <option value="new" {{ old('condition', $item->condition) == 'new' ? 'selected' : '' }}>Новое</option>
                                <option value="like_new" {{ old('condition', $item->condition) == 'like_new' ? 'selected' : '' }}>Отличное</option>
                                <option value="good" {{ old('condition', $item->condition) == 'good' ? 'selected' : '' }}>Хорошее</option>
                                <option value="fair" {{ old('condition', $item->condition) == 'fair' ? 'selected' : '' }}>Удовлетворительное</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Текущие фотографии</label>
                    <div class="row g-3">
                        @php
                            $itemImages = is_array($item->images) ? $item->images : json_decode($item->images, true);
                        @endphp
                        @if($itemImages && is_array($itemImages))
                            @foreach($itemImages as $image)
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <img src="{{ asset('public/storage/' . $image) }}" alt="{{ $item->title }}" class="img-fluid rounded">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="removeImage('{{ $image }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Добавить новые фотографии</label>
                    <div class="border rounded p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                        </div>
                        <div class="mb-2">
                            <label for="images" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Загрузить файлы
                                <input id="images" name="images[]" type="file" class="d-none" multiple accept="image/*">
                            </label>
                        </div>
                        <p class="text-muted mb-0">или перетащите файлы сюда</p>
                        <p class="text-muted small">PNG, JPG, GIF до 10MB</p>
                    </div>
                    <div id="imagePreview" class="row g-3 mt-3"></div>
                    @error('images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('items.show', $item) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Отмена
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('images');
        const imagePreview = document.getElementById('imagePreview');
        const dropZone = document.querySelector('.border.rounded.p-4');

        // Обработка перетаскивания файлов
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-primary');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-primary');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-primary');
            imageInput.files = e.dataTransfer.files;
            handleFiles(imageInput.files);
        });

        // Обработка выбора файлов через input
        imageInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            imagePreview.innerHTML = '';
            
            if (files) {
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-4';
                            
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'position-relative';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-fluid rounded';
                            img.style.width = '100%';
                            img.style.height = '200px';
                            img.style.objectFit = 'cover';
                            
                            imgContainer.appendChild(img);
                            col.appendChild(imgContainer);
                            imagePreview.appendChild(col);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        }
    });

    function removeImage(imagePath) {
        if (confirm('Вы уверены, что хотите удалить это изображение?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("items.removeImage", $item) }}';
            form.innerHTML = `
                @csrf
                @method('DELETE')
                <input type="hidden" name="image_path" value="${imagePath}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection 