<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit();
}

// Ваш сайт
$site = 'example.сайт';
// Тема письма
$subject = 'Заявка с сайта '.$site.'!';
// От кого
$from = 'form@'.$site;
// Кому
$to = 'info@'.$site;

//В переменную $token нужно вставить id вашего бота
$token = "6527581740:AAEc3Z_1vwP5aOY6iOBUdCqGYm9XRMWwyv8";
//В chat_id вставляем id группы
$chat_id = "-4154188856";

var_dump($_POST);
if ( ! empty( $_POST ) ) {
    $name  = htmlspecialchars($_POST['name']);
    $phone  = htmlspecialchars($_POST['phone']);
    $question  = htmlspecialchars($_POST['question']);

    // Тело сообщения для отправки по почте
    $message = "Имя клиента: $name \r\n";
    $message .= "Телефон клиента: $phone \r\n";
    $message .= "Сообщение: $question";

    //Тело сообщения для отправки в телеграмм
    $txt = "Имя клиента: $name %0A";
    $txt .= "Телефон клиента: $phone %0A";
    $txt .= "Сообщение: $question";

    try {
        $headers = 'From: form@'.$site."\r\n".
                'X-Mailer: PHP/' . phpversion();

        //Передаем сообщение по почте
		$mail = mail( $to, $subject, $message, $headers );

        //Передаем сообщение в телеграмм
        $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}","r");

        if ( $mail && $sendToTelegram ) {
            echo json_encode('Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.');
        } else {
            echo json_encode('Ошибка отправки!');
        }

        die();

    } catch (Exception $e) {
        echo json_encode("Ошибка: $e->getMessage()");
    }
} else {
    echo json_encode("Тело сообщения пустое");
}
?>