<?php declare(strict_types=1);

return [
    // ***
    // Common | other
    // ***

    // status & order status
    'work' => 'Активный',
    'delete' => 'Удалён',
    'moderate' => 'Модерация',
    'block' => 'Заблокирован',
    'new' => 'Новый',
    'process' => 'В работе',
    'payment' => 'Оплачен',
    'ready' => 'Готов',
    'complete' => 'Выполнен',
    'cancel' => 'Отменён',

    // attributes
    'string' => 'Строка',
    'integer' => 'Целое',
    'float' => 'Дробное',
    'boolean' => 'Булево',

    // product type
    'product' => 'Продукт',
    'service' => 'Услуга',

    // api access
    'key' => 'Только ключи',
    'user' => 'Пользователи и ключи',

    // user auth by
    'username' => 'Логин',
    'email' => 'E-Mail',
    'phone' => 'Телефон',

    // user without group
    'WITHOUT_GROUP' => 'Без группы',

    // newsletters
    'all' => 'Всем',
    'users' => 'Пользователи',
    'subscribers' => 'Подписчики',

    // boolean
    'yes' => 'Да',
    'no' => 'Нет',
    'off' => 'Выключена',
    'on' => 'Включена',

    // sorts by
    'title' => 'Заголовок',
    'price' => 'Цена',
    'stock' => 'Наличие',
    'date' => 'Дата',
    'DESC' => 'По убыванию',
    'ASC' => 'По возрастанию',

    // content type
    'html' => 'HTML',
    'text' => 'Текст',

    // ***
    // Exceptions
    // ***

    // exists
    'EXCEPTION_TITLE_ALREADY_EXISTS' => 'Такое наименование уже занято',
    'EXCEPTION_ADDRESS_ALREADY_EXISTS' => 'Такой адрес уже занят',
    'EXCEPTION_FILE_ALREADY_EXISTS' => 'Такой файл уже занят',
    'EXCEPTION_PARAMETER_ALREADY_EXISTS' => 'Такой параметр уже занят',
    'EXCEPTION_EMAIL_ALREADY_EXISTS' => 'Такой E-Mail уже занят',
    'EXCEPTION_PHONE_ALREADY_EXISTS' => 'Такой телефон уже занят',
    'EXCEPTION_USERNAME_ALREADY_EXISTS' => 'Такой логин уже занят',

    // missing
    'EXCEPTION_TITLE_MISSING' => 'Наименование отсутствует',
    'EXCEPTION_EMAIL_MISSING' => 'E-Mail отсутствует',
    'EXCEPTION_MESSAGE_MISSING' => 'Сообщение отсутствует',
    'EXCEPTION_NAME_MISSING' => 'Имя отсутствует',
    'EXCEPTION_USER_UUID_MISSING' => 'UUID пользователя отсутствует',
    'EXCEPTION_ACTION_VALUE_MISSING' => 'Отсутствует значение действий',
    'EXCEPTION_UNIQUE_MISSING' => 'Отсутствует уникальное значение',

    // not found
    'EXCEPTION_ATTRIBUTE_NOT_FOUND' => 'Атрибут не найден',
    'EXCEPTION_CATEGORY_NOT_FOUND' => 'Категория не найдена',
    'EXCEPTION_MEASURE_NOT_FOUND' => 'Размер не найден',
    'EXCEPTION_ORDER_NOT_FOUND' => 'Заказ не найден',
    'EXCEPTION_PRODUCT_NOT_FOUND' => 'Продукт не найден',
    'EXCEPTION_RELATION_NOT_FOUND' => 'Связь не найдена',
    'EXCEPTION_FILE_NOT_FOUND' => 'Файл не найден',
    'EXCEPTION_FORM_NOT_FOUND' => 'Форма не найдена',
    'EXCEPTION_FORM_DATA_NOT_FOUND' => 'Данные формы не найдены',
    'EXCEPTION_ENTRY_NOT_FOUND' => 'Запись не найдена',
    'EXCEPTION_NOTIFICATION_NOT_FOUND' => 'Уведомление не найдено',
    'EXCEPTION_PAGE_NOT_FOUND' => 'Страница не найдена',
    'EXCEPTION_PARAMETER_NOT_FOUND' => 'Параметр не найдена',
    'EXCEPTION_PUBLICATION_NOT_FOUND' => 'Публикация не найдена',
    'EXCEPTION_TASK_NOT_FOUND' => 'Задача не найдена',
    'EXCEPTION_USER_NOT_FOUND' => 'Пользователь не найден',
    'EXCEPTION_USER_INTEGRATION_NOT_FOUND' => 'Интеграция пользователя не найдена',
    'EXCEPTION_USER_GROUP_NOT_FOUND' => 'Группа пользователей не найдена',

    // other
    'EXCEPTION_EMAIL_BANNED' => 'Такой домен использовать нельзя',
    'EXCEPTION_WRONG_EMAIL' => 'Неверный формат E-Mail',
    'EXCEPTION_WRONG_PHONE' => 'Неверный формат телефона',
    'EXCEPTION_WRONG_IP' => 'Неверный формат IP адреса',
    'EXCEPTION_WRONG_CODE' => 'Неверный код',
    'EXCEPTION_WRONG_CODE_TIMEOUT' => 'Обновить код авторизации можно раз в 10 минут',
    'EXCEPTION_WRONG_PASSWORD' => 'Неверный пароль',
];