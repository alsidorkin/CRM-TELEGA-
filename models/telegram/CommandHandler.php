<?php 

namespace models\telegram;

use models\users\User;
use models\todo\tasks\TaskModel;

class CommandHandler{



    // Ниже методы, которые отвечают за обработку команд c телеграма
public function handleHelpCommand($chatId) 
{
return "Список команд:\n/start - начать работу\n/email - ввести email\n/help - вывести справку";
}



public function handleEmailCommand($chatId) 
{
return "Введите email с вашего аккаунта miniCRM...";
}

public function handleStartCommand($chatId) 
{
return "Чтобы иметь возможность пользоваться нашим ботом, вам необходимо провести привязку аккаунта
 телеграм и аккаунта в miniCRM. Для инструкции перейдите по адресу https://greenhouse.hhos.com.ua, 
 авторизуйтесь в системе и перейдите в ваш профайл...";
}
}

