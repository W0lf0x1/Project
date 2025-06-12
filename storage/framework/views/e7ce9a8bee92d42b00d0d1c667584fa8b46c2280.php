

<?php $__env->startSection('title', 'Предметы для обмена'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Hero Section -->
    <div class="card mb-4 border-0 bg-white">
        <div class="card-body p-5 text-center">
            <h1 class="display-4 fw-bold mb-3 text-primary">Добро пожаловать на GivnGet</h1>
            <p class="lead mb-4 text-secondary">Платформа для обмена предметами между пользователями</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?php echo e(route('items.create')); ?>" class="btn btn-primary-custom btn-custom btn-icon">
                    <i class="fas fa-plus me-2"></i>Добавить предмет
                </a>
                <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-outline-secondary-custom btn-custom btn-icon">
                    <i class="fas fa-th-large me-2"></i>Категории
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form action="<?php echo e(url('search')); ?>" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="query" class="form-control border-start-0" placeholder="Поиск предметов..." value="<?php echo e(request('query')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Все категории</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="condition" class="form-select">
                        <option value="">Любое состояние</option>
                        <option value="new" <?php echo e(request('condition') == 'new' ? 'selected' : ''); ?>>Новое</option>
                        <option value="excellent" <?php echo e(request('condition') == 'excellent' ? 'selected' : ''); ?>>Отличное</option>
                        <option value="good" <?php echo e(request('condition') == 'good' ? 'selected' : ''); ?>>Хорошее</option>
                        <option value="fair" <?php echo e(request('condition') == 'fair' ? 'selected' : ''); ?>>Удовлетворительное</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary-custom btn-custom btn-icon">
                        <i class="fas fa-filter me-2"></i>Применить фильтры
                    </button>
                    <?php if(request('query') || request('category') || request('condition')): ?>
                        <a href="<?php echo e(route('items.index')); ?>" class="btn btn-outline-secondary-custom btn-custom btn-icon ms-2">
                            <i class="fas fa-times me-2"></i>Сбросить
                        </a>
                    <?php endif; ?>
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
                <a href="<?php echo e(route('items.create')); ?>" class="btn btn-primary-custom btn-custom btn-icon">
                    <i class="fas fa-plus me-2"></i>Добавить предмет
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Grid View -->
            <div id="gridView" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 p-4">
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                <?php
                                    $itemImages = is_array($item->images) ? $item->images : json_decode($item->images, true);
                                    $hasImage = $itemImages && is_array($itemImages) && count($itemImages) > 0;
                                ?>
                                <?php if($hasImage): ?>
                                    <img src="<?php echo e(asset('public/storage/' . $itemImages[0])); ?>" alt="<?php echo e($item->title); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='<?php echo e(asset('images/no-image.jpg')); ?>';">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="position-absolute top-0 end-0 m-2 badge bg-success"><?php echo e($item->condition); ?></span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-dark"><?php echo e($item->title); ?></h5>
                                <p class="card-text text-muted">
                                    <i class="fas fa-tag me-1"></i><?php echo e($item->category->name); ?>

                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="ms-2 text-muted"><?php echo e($item->user->name); ?></span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i><?php echo e($item->created_at->diffForHumans()); ?>

                                    </small>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-flex gap-2 justify-content-end">
                                    <?php if(auth()->id() !== $item->user_id): ?>
                                        <a href="<?php echo e(route('items.show', $item)); ?>" class="btn btn-primary d-flex align-items-center justify-content-center mx-auto" style="width: 90%; margin: 0 auto;">
                                            <i class="fas fa-eye me-2"></i>Подробнее
                                        </a>
                                    <?php else: ?>
                                        <div class="d-flex gap-2 flex-row-reverse">
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('items.edit', $item)); ?>" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center gap-1">
                                                    <i class="fas fa-edit"></i> <span>Редактировать</span>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($item->id); ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <a href="<?php echo e(route('items.show', $item)); ?>" class="btn btn-primary d-flex align-items-center justify-content-center w-100">
                                                <i class="fas fa-eye me-2"></i>Подробнее
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo e($item->id); ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?php echo e($item->id); ?>">Подтверждение удаления</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Вы уверены, что хотите удалить предмет "<?php echo e($item->title); ?>"?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                    <form action="<?php echo e(route('items.destroy', $item)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Пока нет доступных предметов для обмена</h4>
                        <p class="text-muted">Будьте первым, кто добавит предмет для обмена!</p>
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('items.create')); ?>" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Добавить предмет
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- List View -->
            <div id="listView" class="d-none p-4">
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <?php
                                    $itemImages = is_array($item->images) ? $item->images : json_decode($item->images, true);
                                    $hasImage = $itemImages && is_array($itemImages) && count($itemImages) > 0;
                                ?>
                                <?php if($hasImage): ?>
                                    <img src="<?php echo e(asset('public/storage/' . $itemImages[0])); ?>" alt="<?php echo e($item->title); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title text-dark"><?php echo e($item->title); ?></h5>
                                        <span class="badge bg-success"><?php echo e($item->condition); ?></span>
                                    </div>
                                    <p class="card-text text-muted">
                                        <i class="fas fa-tag me-1"></i><?php echo e($item->category->name); ?>

                                    </p>
                                    <p class="card-text"><?php echo e(Str::limit($item->description, 150)); ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="ms-2 text-muted"><?php echo e($item->user->name); ?></span>
                                        </div>
                                        <div>
                                            <small class="text-muted me-3">
                                                <i class="fas fa-clock me-1"></i><?php echo e($item->created_at->diffForHumans()); ?>

                                            </small>
                                            <div class="d-flex gap-2">
                                                <?php if(auth()->id() !== $item->user_id): ?>
                                                    <a href="<?php echo e(route('items.show', $item)); ?>" class="btn btn-primary d-flex align-items-center justify-content-center mx-auto" style="width: 90%; margin: 0 auto;">
                                                        <i class="fas fa-eye me-2"></i>Подробнее
                                                    </a>
                                                <?php else: ?>
                                                    <div class="d-flex gap-2 flex-row-reverse">
                                                        <div class="btn-group">
                                                            <a href="<?php echo e(route('items.edit', $item)); ?>" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center gap-1">
                                                                <i class="fas fa-edit"></i> <span>Редактировать</span>
                                                            </a>
                                                            <button type="button" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($item->id); ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <a href="<?php echo e(route('items.show', $item)); ?>" class="btn btn-primary d-flex align-items-center justify-content-center w-100">
                                                            <i class="fas fa-eye me-2"></i>Подробнее
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Пока нет доступных предметов для обмена</h4>
                        <p class="text-muted">Будьте первым, кто добавит предмет для обмена!</p>
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('items.create')); ?>" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Добавить предмет
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <?php if($items->onFirstPage()): ?>
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($items->previousPageUrl()); ?>" aria-label="Previous">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php $__currentLoopData = $items->getUrlRange(max($items->currentPage() - 2, 1), min($items->currentPage() + 2, $items->lastPage())); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="page-item <?php echo e($page == $items->currentPage() ? 'active' : ''); ?>">
                                <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if($items->hasMorePages()): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($items->nextPageUrl()); ?>" aria-label="Next">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <div class="text-center text-muted small mt-2">
                Показано <?php echo e($items->firstItem() ?? 0); ?> - <?php echo e($items->lastItem() ?? 0); ?> из <?php echo e($items->total()); ?> результатов
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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

<script src="<?php echo e(asset('js/item-edit.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('items._edit-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /srv/users/nxxyreps/pyhjavd-m2/resources/views/items/index.blade.php ENDPATH**/ ?>