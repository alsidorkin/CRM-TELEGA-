<?php 


require_once 'config.php';
require_once 'autoload.php';
require_once 'functions.php';
require_once 'models/telegram/TelegramBot.php';

use models\Check;
use models\users\User;
use models\telegram\TelegramBot;



$botApiKey=TELEGRAM_BOT_API_KEY;
$telegramBot= new TelegramBot($botApiKey);  

// Получаем содержимое запроса
$content = file_get_contents("php://input");
// Преобразуем JSON в массив

$update=json_decode($content,true);

$telegramBot->handleUpdate($update);
