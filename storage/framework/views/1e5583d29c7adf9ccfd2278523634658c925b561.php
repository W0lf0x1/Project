

<?php $__env->startSection('title', 'Предложить обмен'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1 class="h3 mb-0">Предложить обмен</h1>
            <p class="text-muted mb-0">Выберите предмет, который хотите предложить для обмена</p>
        </div>

        <div class="card-body">
            <form action="<?php echo e(route('exchanges.store', $item)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="requested_item_id" value="<?php echo e($item->id); ?>">

                <!-- Запрашиваемый предмет -->
                <div class="mb-4">
                    <h2 class="h5 mb-3">Запрашиваемый предмет</h2>
                    <div class="bg-light p-3 rounded">
                        <div>
                            <h3 class="h6 mb-2"><?php echo e($item->title); ?></h3>
                            <p class="text-muted small mb-2"><?php echo e($item->description); ?></p>
                            <span class="badge bg-primary"><?php echo e($item->condition); ?></span>
                            <p class="text-muted small mt-2 mb-0">
                                Владелец: <?php echo e($item->user->name); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Выбор предмета для обмена -->
                <div class="mb-4">
                    <h2 class="h5 mb-3">Ваш предмет для обмена</h2>
                    <div class="row g-3">
                        <?php $__empty_1 = true; $__currentLoopData = $userItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="offered_item_id" id="item<?php echo e($userItem->id); ?>" value="<?php echo e($userItem->id); ?>" required>
                                            <label class="form-check-label" for="item<?php echo e($userItem->id); ?>">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <?php
                                                            $itemImages = is_string($userItem->images) ? json_decode($userItem->images, true) : $userItem->images;
                                                        ?>
                                                        <?php if($itemImages && is_array($itemImages) && count($itemImages) > 0): ?>
                                                            <img src="<?php echo e(url($itemImages[0])); ?>" alt="<?php echo e($userItem->title); ?>" class="rounded" style="width: 96px; height: 96px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="rounded bg-secondary d-flex align-items-center justify-content-center" style="width: 96px; height: 96px;">
                                                                <i class="fas fa-image text-white fa-2x"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h3 class="h6 mb-2"><?php echo e($userItem->title); ?></h3>
                                                        <p class="text-muted small mb-2"><?php echo e($userItem->description); ?></p>
                                                        <span class="badge bg-primary"><?php echo e($userItem->condition); ?></span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    У вас нет доступных предметов для обмена. <a href="<?php echo e(route('items.create')); ?>" class="alert-link">Добавьте новый предмет</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Сообщение -->
                <div class="mb-4">
                    <label for="message" class="form-label">Сообщение владельцу</label>
                    <textarea class="form-control" id="message" name="message" rows="3" placeholder="Напишите, почему вы хотите обменяться этим предметом..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('items.show', $item)); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Отмена
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-exchange-alt me-2"></i>Предложить обмен
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /srv/users/nxxyreps/pyhjavd-m2/resources/views/exchanges/create.blade.php ENDPATH**/ ?>