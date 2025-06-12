// Функция для валидации числовых полей
function validateNumericInput(input) {
    // Разрешаем только цифры и запятую
    input.value = input.value.replace(/[^\d,]/g, '');
    
    // Проверяем формат числа
    const isValid = /^\d*[,]?\d*$/.test(input.value);
    
    // Добавляем или удаляем класс ошибки
    if (!isValid) {
        input.classList.add('is-invalid');
        // Создаем или обновляем сообщение об ошибке
        let feedback = input.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.insertBefore(feedback, input.nextSibling);
        }
        feedback.textContent = 'Введите только цифры и запятую';
    } else {
        input.classList.remove('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }
    
    return isValid;
}

// Функция для валидации email
function validateEmail(input) {
    const email = input.value;
    const isValid = email.includes('@');
    
    if (!isValid) {
        input.classList.add('is-invalid');
        // Создаем или обновляем сообщение об ошибке
        let feedback = input.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.insertBefore(feedback, input.nextSibling);
        }
        feedback.textContent = 'Введите корректный адрес электронной почты';
    } else {
        input.classList.remove('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }
    
    return isValid;
}

// Функция для инициализации маски телефона
function initPhoneMask(input) {
    // Маска для российского телефона: 7XXXXXXXXXX
    const mask = '7_________';
    let value = input.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        if (value[0] !== '7') {
            value = '7' + value.substring(1);
        }
    }
    
    let result = '';
    let i = 0;
    
    for (let char of mask) {
        if (char === '_' && i < value.length) {
            result += value[i];
            i++;
        } else {
            result += char;
        }
    }
    
    input.value = result;
    
    // Проверяем валидность
    const isValid = result.length === mask.length && !result.includes('_');
    
    if (!isValid) {
        input.classList.add('is-invalid');
        // Создаем или обновляем сообщение об ошибке
        let feedback = input.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.insertBefore(feedback, input.nextSibling);
        }
        feedback.textContent = 'Введите телефон в формате 7XXXXXXXXXX';
    } else {
        input.classList.remove('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }
    
    return isValid;
}

// Функция для проверки всей формы
function validateForm(form) {
    let isValid = true;
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Проверяем все поля
    form.querySelectorAll('input').forEach(input => {
        if (input.type === 'number' || input.classList.contains('numeric')) {
            isValid = validateNumericInput(input) && isValid;
        } else if (input.type === 'email') {
            isValid = validateEmail(input) && isValid;
        } else if (input.classList.contains('phone')) {
            isValid = initPhoneMask(input) && isValid;
        }
    });
    
    // Блокируем или разблокируем кнопку отправки
    submitButton.disabled = !isValid;
    
    return isValid;
}

// Инициализация валидации при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Находим все формы на странице
    document.querySelectorAll('form').forEach(form => {
        // Добавляем обработчики событий для полей ввода
        form.querySelectorAll('input').forEach(input => {
            if (input.type === 'number' || input.classList.contains('numeric')) {
                input.addEventListener('input', () => validateNumericInput(input));
            } else if (input.type === 'email') {
                input.addEventListener('input', () => validateEmail(input));
            } else if (input.classList.contains('phone')) {
                input.addEventListener('input', () => initPhoneMask(input));
            }
        });
        
        // Добавляем обработчик отправки формы
        form.addEventListener('submit', (e) => {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
}); 