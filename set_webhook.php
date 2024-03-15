<?php


// Указываем токен вашего бота
$botToken = '7023271445:AAHznQlbbbNFwB9Dl4x5Jnf9IvewXot77Kk';

// Указываем URL-адрес, на который будут поступать обновления
$webhookUrl = 'http://crm-for-telegram/hook.php';

// Формируем URL для установки вебхука
$apiUrl = 'https://api.telegram.org/bot' . $botToken . '/setWebhook?url=' . urlencode($webhookUrl);
// echo $apiUrl;
// Отправляем запрос на установку вебхука
$response = file_get_contents($apiUrl);

// Парсим ответ
$result = json_decode($response, true);

// Проверяем успешность запроса
if ($result && $result['ok']) {
    echo "Webhook has been set successfully!";
} else {
    echo "Failed to set webhook: " . $result['description'];
}