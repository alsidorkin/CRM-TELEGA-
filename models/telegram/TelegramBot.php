<?php 
namespace models\telegram;

use models\users\User;
use models\telegram\CommandHandler;

class TelegramBot{

    private $botApiKey;

    public function __construct($botApiKey){
        $this->botApiKey=$botApiKey;

    }

// метод для отправки сообщения в чат с указанным ID и текстом 
public function sendTelegramMessage($chatId, $text){
    // формирование URL для запроса к API телеграм 
    $url = "https://api.telegram.org/bot{$this->botApiKey}/sendMessage";
    // формирование данных для POST запроса
    $postData =[
        'chat_id' => $chatId,
        'text' => $text
    ];


    // инициализация сеанс cURL 

    $ch= curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // выполнение запроса cURL 
    $response= curl_exec($ch);
    curl_close($ch);

    // декодируем строку и отдаем через return 
    return json_decode($response, true);

}


// метод для обработки входящих обновлений от Telegram
public function handleUpdate($update){
// если сообщения нет остановить обработку 
// tte($update);
if (!isset($update['message'])){
    return;
}

// получаем данные из сообщения 
$message =$update['message'];
$chatId = $message['chat']['id'];
$text = $message['text'];
$username = $message['from']['username'];

$userModel = new User();
$commandHandler = new CommandHandler();
// tte($text);

try{
    // получить текущее состояние пользователя
    $userState = $userModel->getUsersState($chatId);
    $currentState = $userState ? $userState['state'] : '';
    $user_id = $userState ? $userState['user_id'] : null;

// Обрабатываем команды и текстовые сообщения
switch ($text) {
    // Если команда /start, вызываем обработчик handleStartCommand и устанавливаем состояние пользователя на 'email'
    case '/start':
        $response = $commandHandler->handleStartCommand($chatId);
        $userModel->setUserState($chatId, 'start'); 
        break;
    // Если команда /email, вызываем обработчик handleEmailCommand и устанавливаем состояние пользователя на 'email'
    case '/email':
        $response = $commandHandler->handleEmailCommand($chatId);
        $userModel->setUserState($chatId, 'email');
        break;
    // Если команда /help, вызываем обработчик handleHelpCommand
    case '/help':
        $response = $commandHandler->handleHelpCommand($chatId);
        break;
    // Если другая команда или текстовое сообщение, вызываем обработчик handleMessage с передачей параметров
    default:
        $response = $this->handleMessage($text, $currentState, $chatId, $userModel, $user_id, $username);
   }
} catch(\Exception $e){
error_log("Error: " . $e->getMessage() . "\n", 3, 'logs/error.log'); 
$response = 'Произошла ошибка. Пожалуйста, попробуйте еще раз.';
}

$this->sendTelegramMessage($chatId, $response);
}




private function handleMessage($text, $currentState, $chatId, $userModel, $user_id, $username){
    
if ($currentState === 'email') {
$user = $userModel->getUserByEmail($text);

if ($user) {
    $user_id = $user['id'];
    $response = 'Теперь введите код OTP, ...';
    $userModel->setUserState($chatId, 'otp', $user_id);
} else {
    $response = 'Пользователь с таким email не найден. ...';
}
} elseif ($currentState === 'otp' && preg_match('/^\d{7}$/', $text)) {
$otpCode = intval($text);
$otpInfo = $userModel->getOtpInfoByUserIdAndCode($user_id, $otpCode);

if ($otpInfo) {
    $userModel->createUserTelegram($user_id, $chatId, $username);
    $response = 'Ваш код подтвержден, и ваши аккаунты связаны!!!';
    $userModel->setUserState($chatId, ''); // Очищаем состояние
} else {
    $response = 'Введенный код неверен или ...';
}
} else {
$response = 'Я не понимаю вашу команду. ' . $currentState;
}
return $response;
  }
}