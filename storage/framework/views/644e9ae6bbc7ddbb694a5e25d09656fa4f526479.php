<!-- Модальное окно редактирования -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Редактировать предмет</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Название</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Описание</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Категория</label>
                        <select class="form-select" id="edit_category_id" name="category_id" required>
                            <option value="">Выберите категорию</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_condition" class="form-label">Состояние</label>
                        <select class="form-select" id="edit_condition" name="condition" required>
                            <option value="">Выберите состояние</option>
                            <option value="new">Новое</option>
                            <option value="like_new">Отличное</option>
                            <option value="good">Хорошее</option>
                            <option value="fair">Удовлетворительное</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Текущие фотографии</label>
                        <div id="currentImages" class="row g-2">
                            <!-- Здесь будут отображаться текущие изображения -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_images" class="form-label">Добавить новые фотографии</label>
                        <input type="file" class="form-control" id="edit_images" name="images[]" multiple accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="saveItemChanges">Сохранить</button>
            </div>
        </div>
    </div>
</div> <?php /**PATH /srv/users/nxxyreps/pyhjavd-m2/resources/views/items/_edit-modal.blade.php ENDPATH**/ ?>