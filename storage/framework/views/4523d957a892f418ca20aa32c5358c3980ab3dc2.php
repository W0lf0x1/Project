

<?php $__env->startSection('title', $category->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="d-flex align-items-center">
                        <?php if($category->icon): ?>
                            <i class="<?php echo e($category->icon); ?> fa-2x text-secondary me-3"></i>
                        <?php endif; ?>
                        <h1 class="h3 mb-0"><?php echo e($category->name); ?></h1>
                    </div>
                    <?php if($category->description): ?>
                        <p class="text-muted mb-0"><?php echo e($category->description); ?></p>
                    <?php endif; ?>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-categories')): ?>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-outline-secondary-custom btn-custom btn-icon">
                            <i class="fas fa-edit me-2"></i>Редактировать
                        </a>
                        <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-danger-custom btn-custom btn-icon" onclick="return confirm('Вы уверены, что хотите удалить эту категорию?')">
                                <i class="fas fa-trash me-2"></i>Удалить
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($category->children->count() > 0): ?>
            <div class="card-body border-top">
                <h2 class="h5 mb-4">Подкатегории</h2>
                <div class="row g-4">
                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <?php if($child->icon): ?>
                                            <i class="<?php echo e($child->icon); ?> fa-lg text-secondary me-3"></i>
                                        <?php endif; ?>
                                        <div>
                                            <h3 class="h6 mb-1">
                                                <a href="<?php echo e(route('categories.show', $child)); ?>" class="text-decoration-none"><?php echo e($child->name); ?></a>
                                            </h3>
                                            <?php if($child->description): ?>
                                                <p class="text-muted mb-0 small"><?php echo e($child->description); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card-body border-top">
            <h2 class="h5 mb-4">Предметы в категории</h2>
            <?php if($category->children->count() > 0): ?>
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Показаны предметы из категории "<?php echo e($category->name); ?>" и всех её подкатегорий.
                </div>
            <?php endif; ?>
            <div class="row g-4">
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <?php if($item->images): ?>
                                            <?php
                                                $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
                                            ?>
                                            <?php if(is_array($images) && count($images) > 0): ?>
                                                <img src="<?php echo e(asset('public/storage/' . $images[0])); ?>" alt="<?php echo e($item->title); ?>" class="rounded" style="width: 64px; height: 64px; object-fit: cover;" onerror="this.onerror=null; this.src='<?php echo e(asset('images/no-image.jpg')); ?>';">
                                            <?php else: ?>
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                                    <i class="fas fa-image text-secondary fa-2x"></i>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                                <i class="fas fa-image text-secondary fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="h6 mb-1">
                                            <a href="<?php echo e(route('items.show', $item)); ?>" class="text-decoration-none"><?php echo e($item->title); ?></a>
                                        </h3>
                                        <p class="text-muted small mb-2"><?php echo e(Str::limit($item->description, 100)); ?></p>
                                        <span class="badge bg-success"><?php echo e($item->condition); ?></span>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex justify-content-between text-muted small">
                                    <span>
                                        <i class="fas fa-user me-1"></i>
                                        <?php echo e($item->user->name); ?>

                                    </span>
                                    <span>
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo e($item->created_at->diffForHumans()); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p>В этой категории пока нет предметов</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($items->hasPages()): ?>
                <div class="mt-4">
                    <?php echo e($items->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /srv/users/nxxyreps/pyhjavd-m2/resources/views/categories/show.blade.php ENDPATH**/ ?>