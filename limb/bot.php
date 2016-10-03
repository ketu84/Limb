<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/RequestException.php';
require_once __DIR__ . '/properties.php';
require_once __DIR__ . '/Comandos.php';

Logger::configure(__DIR__ .'/../config.xml');
$log = Logger::getLogger('com.hotelpene.limbBot.bot');

$log->debug("Comienza la ejecución");
//$log->debug("Cabeceras: ".print_r(getallheaders(), true));

$telegramAPI = !isset($_POST["token"]) || $_POST["token"]!=$TOKEN;
$rawMsg = $telegramAPI ? file_get_contents('php://input') : $_POST;
$log->debug("Mensaje: ".$telegramAPI ? $rawMsg : print_r($rawMsg, true));

try{
    //Se procesa el mensaje recibido
    $request = new Request($rawMsg, $telegramAPI);
    $log->debug('Request: '.$request->to_string());
    
}catch(RequestException $e){
    $log->error("Error al procesar el Request. ",$e);
    
    //Se termina la ejecución
    return;
}


if($request->get_message_type()==Request::TYPE_TEXT){
    $response = Comandos::ejecutar($endpoint, $request);
    if($response != false){
        $log->debug('Response: '.$response->to_string());

        $resultado = $response->send();

        $result = json_decode($resultado, true);
        if($result["ok"]){
            $log->info('Respuesta enviada correctamente');
        }else{
            $log->error('Error al enviar la respuesta. ErroCode: '.$result["error_code"] . '. description: '.$result["description"]);
        }
    }else{
        $log->error('Error al ejecutar el comando. '.$request->to_string());
    }
}else{
    if($request->is_private_chat()){
        //El bot responderá a mensajes que no se sean de texto, solo desde chats privados
        $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
        $response->text='Recibido mensaje de tipo *'.$request->get_message_type_desc().'* con id: '.$request->get_file_id();
        $response->markdown=true;
        $resultado = $response->send();

        $result = json_decode($resultado, true);
        if($result["ok"]){
            $log->info('Respuesta enviada correctamente');
        }else{
            $log->error('Error al enviar la respuesta. ErroCode: '.$result["error_code"] . '. description: '.$result["description"]);
        }
        
    }else{
        $log->info("Mensaje de tipo: ".$request->get_message_type_desc()." enviado desde un chat no privado. No se responde.");
    }
}
    


?>
