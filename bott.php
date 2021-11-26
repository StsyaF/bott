<?php
$confirmationToken = '96b5498e';
$secretKey = 'gjlze89551';    
// Функция отправляющая сообщения
function vk_msg_send($peer_id, $text){
    
    $request_params = array(
      'message' => $text,
      'attachment' => $attachment,
      'peer_id' => $peer_id,
      'access_token' => '15a309f6d672f5a14bd50cd496784b3d18db09c82fa0f2a2e5e5de13e6ed0fe32522394698f74fec729f0',
      'v' => '5.89'
    );
    
    $get_params = http_build_query($request_params); 
    file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);
}
$data = json_decode(file_get_contents('php://input')); // Получаем данные с ВК
if(strcmp($data->secret, $secretKey) !== 0 && strcmp($data->type, 'confirmation') !== 0) {
    return;
}
switch ($data->type) {  
    case 'confirmation': 
        echo $confirmationToken; // Если ВК запрашивает подтверждение, то выводим код подтверждения 
    break;  
        
    case 'message_new':
        // Если событие нового сообщения, то получаем его текст
        $message_text = $data->object->text;
        $peer_id = $data->object->peer_id;
        
        $message_text = mb_strtolower($message_text, 'UTF-8'); // Переводим текст к нижнему регистру
        
        // Если сообщение содержит подстроку привет, отправляем сообщение
        if(strpos($message_text, "Дилюк, привет") !== false){
            vk_msg_send($peer_id, "Привет!");
        }
        
        echo 'ok'; // Обязательно уведомляем сервер, что сообщение получено, текстом ok
    break;
}
?>