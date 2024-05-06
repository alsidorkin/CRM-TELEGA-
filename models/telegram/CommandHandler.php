<?php 

namespace models\telegram;

use models\users\User;
use models\todo\tasks\TaskModel;

class CommandHandler{



    // Ниже методы, которые отвечают за обработку команд c телеграма
public function handleHelpCommand($chatId) 
{
return "Список команд:\n/start - начать работу\n/addaccount - привязка телеграма\n/task - статус задач";
}



public function handleEmailCommand($chatId) 
{
return "Введите email с вашего аккаунта miniCRM...";
}

public function handleStartCommand($chatId) 
{
return "Чтобы иметь возможность пользоваться нашим ботом, вам необходимо провести привязку аккаунта
 телеграм и аккаунта в miniCRM. Для инструкции перейдите по адресу https://greenhouse.hhos.com.ua, 
 авторизуйтесь в системе и перейдите в ваш профайл...\n/help - список команд";
}
public function handleTaskCommand($chatId) 
{

    $userModel=new User();
    $userTelegram=$userModel-> getUserByTelegramChatId($chatId);
    $user_id=$userTelegram['user_id'];

    $taskModel=new TaskModel();
    $tasks=$taskModel->getTasksCountAndStatusByUserId($user_id);
    $tasks= json_encode($tasks);
    $tasks= json_decode($tasks, true);
    $obj=$tasks[0];

    $userTelegram=$obj['telegram_username'];
    $all_tasks=$obj['all_tasks'];
    $completed=$obj['completed'];
    $expired=$obj['expired'];
    $opened=$obj['opened'];

$text= "🖐Привет:  <b>$userTelegram</b> 
📜Всего задач:  <b>$all_tasks</b> 
📌Закрытых:  <b>$completed</b>
❗️ Просроченных: <b>$expired</b>
🗒 Открытых: <b>$opened</b>";

         return $text;
}
}

