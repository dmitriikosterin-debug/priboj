<?php
// Конфигурация
const ACCESS_TOKEN = 'vk1.a.Dufx89bZWB16Z1iltRmaORy5H4bVE4AKDIGFnK0CtmZtuNkXCIsTBjlpD_oKvZ4qzvLXTvIiU06g0TMVTSXJE6syP_QT0ITR_MzV-nDc55NWM85UrV5ghCTnBI0iTrVe_j1KedQUBwqQvYwufhkGZgUM67qulaXtKliw5ouqvF_ybvHYCYWAihaciJaZyvfwriItSqhzJQ5BG48evQgLBA';
const CONFIRMATION_TOKEN = 'da252871';
const SECRET_KEY = 'aa15klfcbz52';
const GROUP_ID = '232836641';

// Обработка входящих данных
$data = json_decode(file_get_contents('php://input'), true);

// Проверка секретного ключа (если настроен)
if (isset($data['secret']) && $data['secret'] !== SECRET_KEY) {
    http_response_code(403);
    exit('Invalid secret key');
}

// Обработка подтверждения сервера
if ($data['type'] === 'confirmation') {
    exit(CONFIRMATION_TOKEN);
}

// Проверка типа события
if ($data['type'] === 'message_new') {
    $message = $data['object']['message'];
    $user_id = $message['from_id'];
    $text = $message['text'];
    
    // Обработка сообщения
    processMessage($user_id, $text);
}

// Ответ серверу VK
exit('ok');

/**
 * Обработка сообщений от пользователя
 */
function processMessage($user_id, $text) {
    // Приветственное сообщение
    if (in_array(strtolower($text), ['начать', 'старт', 'start'])) {
        sendMessage($user_id, 'Здравствуйте, это бот', getMainKeyboard());
        return;
    }
    
    // Главное меню
    if ($text === 'Назад') {
        sendMessage($user_id, 'Возвращаемся в главное меню', getMainKeyboard());
        return;
    }
    
    // Обработка основных кнопок
    switch ($text) {
        case 'Вариант 1':
            sendMessage($user_id, 'Вы выбрали Вариант 1. Выберите подвариант:', getSubKeyboard1());
            break;
            
        case 'Вариант 2':
            sendMessage($user_id, 'Вы выбрали Вариант 2. Выберите подвариант:', getSubKeyboard2());
            break;
            
        case 'Вариант 3':
            sendMessage($user_id, 'Вы выбрали Вариант 3. Выберите подвариант:', getSubKeyboard3());
            break;
            
        // Обработка подвариантов
        case 'Подвариант 1.1':
            sendMessage($user_id, 'Вы выбрали Подвариант 1.1', getSubKeyboard1());
            break;
            
        case 'Подвариант 1.2':
            sendMessage($user_id, 'Вы выбрали Подвариант 1.2', getSubKeyboard1());
            break;
            
        case 'Подвариант 2.1':
            sendMessage($user_id, 'Вы выбрали Подвариант 2.1', getSubKeyboard2());
            break;
            
        case 'Подвариант 2.2':
            sendMessage($user_id, 'Вы выбрали Подвариант 2.2', getSubKeyboard2());
            break;
            
        case 'Подвариант 3.1':
            sendMessage($user_id, 'Вы выбрали Подвариант 3.1', getSubKeyboard3());
            break;
            
        case 'Подвариант 3.2':
            sendMessage($user_id, 'Вы выбрали Подвариант 3.2', getSubKeyboard3());
            break;
            
        default:
            sendMessage($user_id, 'Используйте кнопки для навигации', getMainKeyboard());
            break;
    }
}

/**
 * Отправка сообщения через VK API
 */
function sendMessage($user_id, $message, $keyboard = null) {
    $params = [
        'user_id' => $user_id,
        'message' => $message,
        'access_token' => ACCESS_TOKEN,
        'v' => '5.131',
        'random_id' => rand(1, 1000000)
    ];
    
    if ($keyboard) {
        $params['keyboard'] = json_encode($keyboard);
    }
    
    $url = 'https://api.vk.com/method/messages.send?' . http_build_query($params);
    file_get_contents($url);
}

/**
 * Клавиатура главного меню
 */
function getMainKeyboard() {
    return [
        'one_time' => false,
        'buttons' => [
            [
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Вариант 1'
                    ],
                    'color' => 'primary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Вариант 2'
                    ],
                    'color' => 'primary'
                ]
            ],
            [
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Вариант 3'
                    ],
                    'color' => 'primary'
                ]
            ]
        ]
    ];
}

/**
 * Клавиатура для варианта 1
 */
function getSubKeyboard1() {
    return [
        'one_time' => false,
        'buttons' => [
            [
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Подвариант 1.1'
                    ],
                    'color' => 'secondary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Подвариант 1.2'
                    ],
                    'color' => 'secondary'
                ]
            ],
            [
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Назад'
                    ],
                    'color' => 'negative'
                ]
            ]
        ]
    ];
}

/**
 * Клавиатура для варианта 2
 */
function getSubKeyboard2() {
    return [
        'one_time' => false,
        'buttons' => [
            [
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Подвариант 2.1'
                    ],
                    'color' => 'secondary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Подвариант 2.2'
                    ],
                    'color' => 'secondary'
                ]
            ],
            [
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Назад'
                    ],
                    'color' => 'negative'
                ]
            ]
        ]
    ];
}

/**
 * Клавиатура для варианта 3
 */
function getSubKeyboard3() {
    return [
        'one_time' => false,
        'buttons' => [
            [
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Подвариант 3.1'
                    ],
                    'color' => 'secondary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Подвариант 3.2'
                    ],
                    'color' => 'secondary'
                ]
            ],
            [
                [
                    'action' => [
                        'type' => 'text',
                        'label' => 'Назад'
                    ],
                    'color' => 'negative'
                ]
            ]
        ]
    ];
}
?>
