<?php $__env->startSection('title', $item->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('items.index')); ?>" class="text-decoration-none">Предметы</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('categories.show', $item->category)); ?>" class="text-decoration-none"><?php echo e($item->category->name); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo e($item->title); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Левая колонка с изображениями -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <?php
                        $itemImages = is_array($item->images) ? $item->images : json_decode($item->images, true);
                    ?>
                    <?php if($itemImages && is_array($itemImages) && count($itemImages) > 0): ?>
                        <div id="itemCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php $__currentLoopData = $itemImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                        <img src="<?php echo e(asset('public/storage/' . $image)); ?>" class="d-block w-100" alt="<?php echo e($item->title); ?>" style="height: 400px; object-fit: cover;">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php if(count($itemImages) > 1): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#itemCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#itemCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Правая колонка с информацией -->
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="h2 mb-0"><?php echo e($item->title); ?></h1>
                        <?php if(auth()->id() === $item->user_id): ?>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?php echo e(route('items.edit', $item)); ?>">
                                            <i class="fas fa-edit me-2"></i>Редактировать
                                        </a>
                                    </li>
                                    <li>
                                        <form action="<?php echo e(route('items.destroy', $item)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены, что хотите удалить этот предмет?')">
                                                <i class="fas fa-trash me-2"></i>Удалить
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="text-muted"><?php echo e($item->user->name); ?></span>
                        <span class="mx-2 text-muted">•</span>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i><?php echo e($item->created_at->diffForHumans()); ?>

                        </small>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-2">Описание</h5>
                        <p class="text-muted"><?php echo e($item->description); ?></p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-2">Категория</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-tag me-2"></i><?php echo e($item->category->name); ?>

                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-2">Состояние</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-2"></i><?php echo e($item->condition); ?>

                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <span class="badge <?php echo e($item->is_available ? 'bg-success' : 'bg-danger'); ?> me-2">
                            <?php echo e($item->is_available ? 'Доступен для обмена' : 'Недоступен для обмена'); ?>

                        </span>
                    </div>

                    <?php if(auth()->check() && auth()->id() !== $item->user_id && $item->is_available): ?>
                        <div class="d-grid">
                            <a href="<?php echo e(route('exchanges.create')); ?>?item=<?php echo e($item->id); ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-exchange-alt me-2"></i>Предложить обмен
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Похожие товары -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Похожие товары</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php $__currentLoopData = $item->category->items()->where('id', '!=', $item->id)->take(4)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similarItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col">
                        <div class="card h-100 border-0">
                            <div class="position-relative">
                                <?php
                                    $similarImages = is_array($similarItem->images) ? $similarItem->images : json_decode($similarItem->images, true);
                                ?>
                                <?php if($similarImages && is_array($similarImages) && count($similarImages) > 0): ?>
                                    <img src="<?php echo e(asset('public/storage/' . $similarImages[0])); ?>" alt="<?php echo e($similarItem->title); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="position-absolute top-0 end-0 m-2 badge bg-success"><?php echo e($similarItem->condition); ?></span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($similarItem->title); ?></h5>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="ms-2 text-muted small"><?php echo e($similarItem->user->name); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="<?php echo e(route('items.show', $similarItem)); ?>" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-eye me-2"></i>Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Простой скрипт для переключения изображений
    document.addEventListener('DOMContentLoaded', function() {
        const mainImage = document.querySelector('.position-relative img');
        const thumbnails = document.querySelectorAll('.d-flex.mt-3 img');
        
        if (mainImage && thumbnails.length > 0) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    mainImage.src = this.src;
                });
            });
        }
    });
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /srv/users/nxxyreps/pyhjavd-m2/resources/views/items/show.blade.php ENDPATH**/ ?>