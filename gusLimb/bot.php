<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/properties.php';

Logger::configure(__DIR__ .'/../config.xml');

// Fetch a logger, it will inherit settings from the root logger
$log = Logger::getLogger('gusLimbLogger');
$endpoint = "https://api.telegram.org/bot".$TOKEN."/";

$log->debug("Comienza la ejecución");

$rawMsg = file_get_contents('php://input');
$log->debug("Mensaje: ".$rawMsg);

$message = json_decode($rawMsg, true);


if(is_null($message)){
    return;
}

//Si no hay chat_id, salimos
if(!isset($message["message"]["chat"]["id"])) return;

$chatid= $message['message']['chat']['id'];


//Si se recibe un documento, se responde con el Id de este.
if(isset($message["message"]["document"])){
    $documentId = $message['message']['document']['file_id'];
    $text="Recibido documento con id: ".$documentId;
    enviarTexto($text,$chatid, false);
    return;
}

//Si se recibe una foto, se responde con el Id de esta
if(isset($message["message"]["photo"])){
    $documentId = $message['message']['photo'][0]['file_id'];
    $text="Recibida foto con id: ".$documentId;
    enviarTexto($text,$chatid, false);
    return;
}

//Si se recibe un sticket, se responde con el Id de este
if(isset($message["message"]["sticker"])){
    $documentId = $message['message']['sticker']['file_id'];
    $text="Recibido sticker con id: ".$documentId;
    enviarTexto($text,$chatid, false);
    return;
}



if(!isset($message["message"]["text"])) return;


$command= $message['message']['text'];

if($command=='/clasificacion'){
    enviarAccionChat('typing',$chatid);

    $text='Clasificación de la última fase en curso:'.PHP_EOL.PHP_EOL;

    $json = file_get_contents('http://hotelpene.com/gusLimb/pages/api.php?q=clasificacion');
    $obj = json_decode($json);

    foreach($obj as $valor) {
        $text=$text.$valor->pos.'.- '.$valor->nombre.': '.$valor->neto.'€'.PHP_EOL;
    }
    enviarTexto($text,$chatid, false);
}

if($command=='/prox_jornada'){
    enviarAccionChat('typing',$chatid);

    $text='';
    $json = file_get_contents('http://hotelpene.com/gusLimb/pages/api.php?q=prox_jornada');

    $obj = json_decode($json);
    $fecha='';

    $idPartido=-1;
    foreach($obj as $valor) {
        $fecha=$valor->fecha;
        if($idPartido!=$valor->id){
            $text=$text.PHP_EOL;
            $idPartido=$valor->id;
            $text=$text.substr($valor->hora,0,5).' '.$valor->local_c.' vs '.$valor->visitante_c.'=>'.$valor->apostante;
        }else{
            $text=$text.', '.$valor->apostante;
        }
    }

    $fecha= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
    $text='Próxima jornada '.$fecha.': '.PHP_EOL.$text;
    enviarTexto($text,$chatid, false);
}

if($command=='/apuestas'){
    enviarAccionChat('typing',$chatid);

    $text='';
    $json = file_get_contents('http://hotelpene.com/gusLimb/pages/api.php?q=apuestas');

    $obj = json_decode($json);
    $fecha='';

    $idPartido=-1;
    $apostante='';

    $emoji_star= unichr(0x1F538);
    $emoji_guion= unichr(0x2796);
    $emoji_cara=unichr(0x1F633);
    $emoji_ok=unichr(0x2705);
    $emoji_mal=unichr(0x274C);

    foreach($obj as $valor) {
        if($idPartido!=$valor->partido){
            $idPartido=$valor->partido;
            $fecha=$valor->fecha;
            $text=$text.PHP_EOL.$emoji_star.$valor->local_c.' vs '.$valor->visitante_c.$emoji_star.PHP_EOL;
        }
        if($apostante!=$valor->apostante){
            $apostante=$valor->apostante;
            $text=$text.$emoji_cara.$valor->apostante.PHP_EOL;
        }
        $iconoApuesta=$emoji_guion;
        //1 acertada
        if($valor->acertada=="1"){
            $iconoApuesta=$iconoApuesta.$emoji_ok;
        }else if($valor->acertada=="2"){
             $iconoApuesta=$iconoApuesta.$emoji_mal;
        }
        $text=$text.$iconoApuesta.$valor->apuesta.':'.$valor->apostado.'@'.$valor->cotizacion.PHP_EOL;
    }

    $fecha= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
    $text='Apuestas '.$fecha.': '.PHP_EOL.PHP_EOL.$text;
    enviarTexto($text,$chatid, false);
}

if($command=='/web'){
    $text='http://hotelpene.com/gusLimb';
    enviarTexto($text,$chatid, false);
}

if($command=='/donaSemen'){
    $emoji_mujer=unichr(0x1F64B);
    $emoji_semen=unichr(0x1F4A6);

    $text=$emoji_semen.$emoji_mujer;
    enviarTexto($text,$chatid, false);
}

if($command=='/bravo'){
    enviarDoc('BQADBAADuAADmEw-AAFENNvXv3KlQgI',$chatid);
}

if($command=='/quieroMiPenaltito'){
    enviarDoc('BQADBAADtwADmEw-AAHFpvL_faHg5QI',$chatid);
}

if($command=='/cuantoHaPerdidoRiojas'){
    enviarTexto('jajaja, pues todo pringaos',$chatid, false);
}
    
if($command=='/Gus'){
    enviarFoto('AgADBAADKrExG6uCfgABZugFvbiTwBWpaHIwAAQIkbE_6Ksrx8Q2AQABAg',$chatid);
}

if($command=='/TeLaComiste'){
    enviarFoto('AgADBAADK7ExG6uCfgAB9rTpspMp9VRGYGkwAAS8GdFc47A_whSFAQABAg',$chatid);
}

if($command=='/Vicenwin'){
    enviarFoto('AgADBAADLLExG6uCfgAB0UBRGzF7sb96C2swAAS7hCl_X6wqS9ByAQABAg',$chatid);
}

if($command=='/FatSpanishWaiter'){
    enviarDoc('BQADBAADMAEAAquCfgABhqhRqhpC5agC',$chatid);
}

if($command=='/cuantoHaGanadoCas'){
    enviarTexto('Se lo está llevando crudo',$chatid, false);
    enviarFoto('AgADBAADLbExG6uCfgABO7d46OcKzQkVuo8wAATDrhyVPZbKfktbAAIC',$chatid);
}

if($command=='/mandaHuevos'){
    enviarTexto('Marchando una de huevo!!!',$chatid, false);
    enviarFoto('AgADBAADLrExG6uCfgABahD-W03Mf1dPVnEwAAS5SQ1buL1RJM85AQABAg',$chatid);
}


/*Comandos de pruebas para desarrolladores*/

if(isset($message["message"]["chat"]["type"]) && $message["message"]["chat"]["type"]=="private"){

     $log->debug("mensaje privado");

    if(substr($command, 0, strlen('/pruebaTexto')) === '/pruebaTexto'){
        $param = substr($command, strlen('/pruebaTexto')+1);
        enviarTexto($param,$chatid, true);
    }

    if(substr($command, 0, strlen('/pruebaFoto')) === '/pruebaFoto'){
        $param = substr($command, strlen('/pruebaFoto')+1);
        enviarFoto($param,$chatid);
    }

    if(substr($command, 0, strlen('/pruebaDoc')) === '/pruebaDoc'){
        $param = substr($command, strlen('/pruebaDoc')+1);
        enviarDoc($param,$chatid);
    }

    if(substr($command, 0, strlen('/pruebaSticker')) === '/pruebaSticker'){
        $param = substr($command, strlen('/pruebaSticker')+1);
        enviarSticker($param,$chatid);
    }
}

$log->debug("Fin de la ejecución");


//Función para convertir EMOJIS
function unichr($i) {
    return iconv('UCS-4LE', 'UTF-8', pack('V', $i));
}

function enviarTexto($text, $chatid,$markdown){
    global $TOKEN;
    global $log;
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'text' => $text
            ];


        if($markdown){
            $log->debug("Activado markdown");
            $data["parse_mode"]="Markdown";
        }

        enviarMensaje('/sendMessage', $data);
        
    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarFoto($docId, $chatid){
    global $TOKEN;
    global $log;

    $log->debug("Enviando foro con id: ".$docId);
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'photo' => $docId
            ];

        enviarMensaje('/sendPhoto', $data);

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarDoc($docId, $chatid){
    global $TOKEN;
    global $log;

    $log->debug("Enviando doc con id: ".$docId);
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'document' => $docId
            ];

        enviarMensaje('/sendDocument', $data);

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarSticker($docId, $chatid){
    global $TOKEN;
    global $log;

    $log->debug("Enviando Sticker con id: ".$docId);
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'sticker' => $docId
            ];

        enviarMensaje('/sendSticker', $data);

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarAccionChat($action, $chatid){
    global $TOKEN;
    try {
         $data= [
                'chat_id' => (int) $chatid,
                'action' => $action
            ];

        enviarMensaje('/sendChatAction', $data);

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarMensaje($accion, $data){
    global $TOKEN;
    global $log;
    try {       

        $options = [
                CURLOPT_URL => 'https://api.telegram.org/bot'.$TOKEN. $accion,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => null,
                CURLOPT_POSTFIELDS => null
            ];

        if ($data) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        $curl = curl_init();

        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);

        $log->debug("Respuesta Telegram: ".$result);

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}




?>
