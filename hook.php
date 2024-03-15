<?php 


require_once 'config.php';
require_once 'autoload.php';
require_once 'functions.php';
require_once 'models/TelegramBot.php';

use models\Check;
use models\users\User;
use models\TelegramBot;



$botApiKey='TELEGRAM_BOT_API_KEY';
$telegramBot= new TelegramBot($botApiKey);  

// Получаем содержимое запроса
$content = file_get_contents("php://input");
// Преобразуем JSON в массив
//$json = json_decode($content, true);


$update=json_decode($content,true);
// tte($update);
$telegramBot->handleUpdate($update);




// $content= file_get_contents('php://input');
// // Проверяем, что пришли JSON данные
// if ($json !== null) {
//     // Обработка JSON данных
//     var_dump($json);
//     http_response_code(200);
// } else {
//     // Ошибка - некорректный формат данных
//     http_response_code(400);
// }



// require_once 'vendor/autoload.php';

// use Telegram\Bot\Api;

// $botApiKey='7023271445:AAHznQlbbbNFwB9Dl4x5Jnf9IvewXot77Kk';

// $botUsername='@newkindBot';

// $telegram =new Api($botApiKey);

// $update =$telegram->getWebhookUpdates();

// $chatId= $update->getMessage()->getChat()->getId();
// $text=$update->getMessage()->getText();
// $username=$update->getMessage()->getFrom()->getUsername();

// // создаем папку logs, если она еще не создана для наших логов

// if (!file_exists('logs')){
//    mkdir('logs' , 0755,true);
// }

// // халаем имя файла с текущим годом и месяцем

// $logFileName= sprintf('logs/%s_telegram_bot_messages.log', date('Y_m'));

// // записываем лог с информацией об обращении

// $logMessage= sprintf(
// "[%s] User: %s (ID: %d) send message: %s\n",
// date('Y-m-d H:i:s'),
// $username,
// $chatId,
// $text
// ); 

// error_log($logMessage, 3, $logFileName);

// switch($text){

//     case '/start':
//         $response= ' Добро пожаловать '.$username.' в наш телеграмм-бот : ' . $botUsername;
//         break;

//      case '/validate':   
//         $response= ' Вы проходите валидацию,введите в ответ код сгенерированный в вашем аккаунте CRM системы:';
//         break;
//      case '/nikitos':   
//         $response= ' Привет никита!наруби дров и занеси!!!!:';
//         break;

//      default:
//      $response= 'Я не понимаю вашу команду!!!';  

// }

// $telegram->sendMessage([
// 'chat_id'=>$chatId,
// 'text'=>$response
// ]);
