

<?php $__env->startSection('title', 'Мои обмены'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="h4 mb-0"><i class="fas fa-exchange-alt me-2 text-primary"></i>Мои обмены</h1>
                        <div class="text-muted small">Управление вашими предложениями обмена</div>
                    </div>
                    <a href="<?php echo e(route('items.index')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-search me-1"></i>Найти предмет для обмена
                    </a>
                </div>
                <div class="card-body">
                    <?php
                        $outgoing = $exchanges->filter(fn($e) => $e->user_id == auth()->id());
                        $incoming = $exchanges->filter(fn($e) => $e->item->user_id == auth()->id() && $e->user_id != auth()->id());
                    ?>

                    <h2 class="h5 mt-3 mb-3 text-primary"><i class="fas fa-inbox me-2"></i>Входящие обмены</h2>
                    <div class="row g-3 mb-4">
                        <?php $__empty_1 = true; $__currentLoopData = $incoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exchange): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-md-6">
                                <div class="card h-100 border-primary border-2">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <?php if($exchange->offeredItem->images && count($exchange->offeredItem->images) > 0): ?>
                                                <img src="<?php echo e(Storage::url($exchange->offeredItem->images[0])); ?>" alt="<?php echo e($exchange->offeredItem->title); ?>" class="rounded me-3" style="width: 64px; height: 64px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 64px; height: 64px;">
                                                    <i class="fas fa-image text-secondary fa-2x"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <a href="<?php echo e(route('exchanges.show', $exchange)); ?>" class="fw-bold text-decoration-none text-primary"><?php echo e($exchange->offeredItem->title); ?></a>
                                                <div class="text-muted small">Вам предложили обмен на: <span class="fw-semibold"><?php echo e($exchange->item->title); ?></span></div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge <?php if($exchange->status === 'pending'): ?> bg-warning text-dark <?php elseif($exchange->status === 'accepted'): ?> bg-success <?php elseif($exchange->status === 'rejected'): ?> bg-danger <?php else: ?> bg-secondary <?php endif; ?>">
                                                <?php if($exchange->status === 'pending'): ?> Ожидает вашего решения <?php elseif($exchange->status === 'accepted'): ?> Принято <?php elseif($exchange->status === 'rejected'): ?> Отклонено <?php else: ?> Завершено <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small"><i class="fas fa-user me-1"></i><?php echo e($exchange->user->name); ?></div>
                                            <div class="text-muted small"><i class="fas fa-clock me-1"></i><?php echo e($exchange->created_at->diffForHumans()); ?></div>
                                        </div>
                                        <?php if($exchange->status === 'pending'): ?>
                                            <div class="mt-3 d-flex gap-2">
                                                <form action="<?php echo e(route('exchanges.accept', $exchange)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i>Принять</button>
                                                </form>
                                                <form action="<?php echo e(route('exchanges.reject', $exchange)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times me-1"></i>Отклонить</button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12 text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <div>Нет входящих обменов</div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h2 class="h5 mt-4 mb-3 text-primary"><i class="fas fa-paper-plane me-2"></i>Исходящие обмены</h2>
                    <div class="row g-3">
                        <?php $__empty_1 = true; $__currentLoopData = $outgoing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exchange): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <?php if($exchange->offeredItem->images && count($exchange->offeredItem->images) > 0): ?>
                                                <img src="<?php echo e(Storage::url($exchange->offeredItem->images[0])); ?>" alt="<?php echo e($exchange->offeredItem->title); ?>" class="rounded me-3" style="width: 64px; height: 64px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 64px; height: 64px;">
                                                    <i class="fas fa-image text-secondary fa-2x"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <a href="<?php echo e(route('exchanges.show', $exchange)); ?>" class="fw-bold text-decoration-none text-primary"><?php echo e($exchange->offeredItem->title); ?></a>
                                                <div class="text-muted small">Вы предложили обмен на: <span class="fw-semibold"><?php echo e($exchange->item->title); ?></span></div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge <?php if($exchange->status === 'pending'): ?> bg-warning text-dark <?php elseif($exchange->status === 'accepted'): ?> bg-success <?php elseif($exchange->status === 'rejected'): ?> bg-danger <?php else: ?> bg-secondary <?php endif; ?>">
                                                <?php if($exchange->status === 'pending'): ?> Ожидает ответа <?php elseif($exchange->status === 'accepted'): ?> Принято <?php elseif($exchange->status === 'rejected'): ?> Отклонено <?php else: ?> Завершено <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small"><i class="fas fa-user me-1"></i><?php echo e($exchange->item->user->name); ?></div>
                                            <div class="text-muted small"><i class="fas fa-clock me-1"></i><?php echo e($exchange->created_at->diffForHumans()); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12 text-center text-muted py-4">
                                <i class="fas fa-paper-plane fa-2x mb-2"></i>
                                <div>Нет исходящих обменов</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="d-flex justify-content-center">
                        <?php echo e($exchanges->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /srv/users/nxxyreps/pyhjavd-m2/resources/views/exchanges/index.blade.php ENDPATH**/ ?>