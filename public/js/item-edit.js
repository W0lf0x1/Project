document.addEventListener('DOMContentLoaded', function() {
    const editItemModal = new bootstrap.Modal(document.getElementById('editItemModal'));
    const editItemForm = document.getElementById('editItemForm');
    const saveItemChanges = document.getElementById('saveItemChanges');

    // Обработчик открытия модального окна
    document.querySelectorAll('.edit-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            loadItemData(itemId);
            editItemForm.action = `/items/${itemId}`;
            editItemModal.show();
        });
    });

    // Загрузка данных предмета
    function loadItemData(itemId) {
        fetch(`/items/${itemId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('edit_category_id').value = data.category_id;
                document.getElementById('edit_condition').value = data.condition;

                // Отображение текущих изображений
                const currentImagesContainer = document.getElementById('currentImages');
                currentImagesContainer.innerHTML = '';
                
                const images = JSON.parse(data.images);
                images.forEach(image => {
                    const col = document.createElement('div');
                    col.className = 'col-4';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${image}" class="img-fluid rounded" alt="Item image">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                    onclick="removeImage(this, '${image}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    currentImagesContainer.appendChild(col);
                });
            });
    }

    // Обработчик сохранения изменений
    saveItemChanges.addEventListener('click', function() {
        const formData = new FormData(editItemForm);
        
        fetch(editItemForm.action, {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                editItemModal.hide();
                location.reload(); // Обновляем страницу для отображения изменений
            } else {
                alert('Произошла ошибка при сохранении изменений');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при сохранении изменений');
        });
    });

    // Функция удаления изображения
    window.removeImage = function(button, imagePath) {
        if (confirm('Вы уверены, что хотите удалить это изображение?')) {
            const itemId = editItemForm.action.split('/').pop();
            fetch(`/items/${itemId}/remove-image`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ image: imagePath })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.closest('.col-4').remove();
                }
            });
        }
    };
}); 