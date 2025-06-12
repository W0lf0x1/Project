

<?php $__env->startSection('title', 'Категории'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Категории</h1>
                    <p class="text-muted mb-0">Управление категориями предметов</p>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-categories')): ?>
                    <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Добавить категорию
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <?php if($category->icon): ?>
                                    <i class="<?php echo e($category->icon); ?> fa-2x text-secondary me-3"></i>
                                <?php endif; ?>
                                <div>
                                    <h5 class="mb-1">
                                        <a href="<?php echo e(route('categories.show', $category)); ?>" class="text-decoration-none"><?php echo e($category->name); ?></a>
                                    </h5>
                                    <?php if($category->description): ?>
                                        <p class="text-muted mb-0"><?php echo e($category->description); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-categories')): ?>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Редактировать
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($category->id); ?>">
                                        <i class="fas fa-trash me-1"></i>Удалить
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal<?php echo e($category->id); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo e($category->id); ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo e($category->id); ?>">Подтверждение удаления</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Вы уверены, что хотите удалить категорию "<?php echo e($category->name); ?>"?
                                        <?php if($category->children->count() > 0): ?>
                                            <div class="alert alert-warning mt-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Эта категория содержит подкатегории. Удаление невозможно.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                        <?php if($category->children->count() == 0): ?>
                                            <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger">Удалить</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if($category->children->count() > 0): ?>
                            <div class="mt-3 ms-4">
                                <div class="list-group list-group-flush">
                                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="list-group-item border-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <?php if($child->icon): ?>
                                                        <i class="<?php echo e($child->icon); ?> fa-lg text-secondary me-3"></i>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-1">
                                                            <a href="<?php echo e(route('categories.show', $child)); ?>" class="text-decoration-none"><?php echo e($child->name); ?></a>
                                                        </h6>
                                                        <?php if($child->description): ?>
                                                            <p class="text-muted mb-0 small"><?php echo e($child->description); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-categories')): ?>
                                                    <div class="btn-group">
                                                        <a href="<?php echo e(route('categories.edit', $child)); ?>" class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-edit me-1"></i>Редактировать
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($child->id); ?>">
                                                            <i class="fas fa-trash me-1"></i>Удалить
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Delete Confirmation Modal for Child Category -->
                                        <div class="modal fade" id="deleteModal<?php echo e($child->id); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo e($child->id); ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?php echo e($child->id); ?>">Подтверждение удаления</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Вы уверены, что хотите удалить категорию "<?php echo e($child->name); ?>"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                        <form action="<?php echo e(route('categories.destroy', $child)); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger">Удалить</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="list-group-item text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Категории пока не созданы</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /srv/users/nxxyreps/pyhjavd-m2/resources/views/categories/index.blade.php ENDPATH**/ ?>