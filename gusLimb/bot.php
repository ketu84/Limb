<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/properties.php';

Logger::configure(__DIR__ .'/../config.xml');

// Fetch a logger, it will inherit settings from the root logger
$log = Logger::getLogger('gusLimbLogger');

$log->debug("Comienza la ejecución");

//Función para convertir EMOJIS
function unichr($i) {
    return iconv('UCS-4LE', 'UTF-8', pack('V', $i));
}

$rawMsg = file_get_contents('php://input');
$log->debug("Mensaje: ".$rawMsg);

$message = json_decode($rawMsg, true);
//$message = json_decode(file_get_contents('php://input'), true);


if(is_null($message)){
    return;
}

$endpoint = "https://api.telegram.org/bot".$TOKEN."/";

if(!isset($message["message"]["text"]) || !isset($message["message"]["chat"]["id"])) return;

$chatid= $message['message']['chat']['id'];
$command= $message['message']['text'];

if($command=='/clasificacion'){
    enviarEstadoEscribiendo($chatid);

    $text='Clasificación de la última fase en curso:'.PHP_EOL.PHP_EOL;

    $json = file_get_contents('http://hotelpene.com/gusLimb/pages/api.php?q=clasificacion');
    $obj = json_decode($json);

    foreach($obj as $valor) {
        $text=$text.$valor->pos.'.- '.$valor->nombre.': '.$valor->neto.'€'.PHP_EOL;
    }
    enviarTexto($text,$chatid, false);
}

if($command=='/prox_jornada'){
    enviarEstadoEscribiendo($chatid);

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
    enviarEstadoEscribiendo($chatid);

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

if($command=='/prueba'){
    enviarTexto('texto normal _cursiva_ normal *negrita* normal',$chatid, true);
}

function enviarEstadoEscribiendo($chatid){
    global $TOKEN;
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'action' => 'typing'
            ];

        $options = [
                CURLOPT_URL => 'https://api.telegram.org/bot'.$TOKEN. '/sendChatAction',
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

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarTexto($text, $chatid, $markdown){
    global $TOKEN;
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'text' => $text
            ];

        $options = [
                CURLOPT_URL => 'https://api.telegram.org/bot'.$TOKEN. '/sendMessage',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => null,
                CURLOPT_POSTFIELDS => null
            ];

        if($markdown){
            $log->debug("Activado markdown");
            $data["parse_mode"]="Markdown";
        }


        if ($data) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        $curl = curl_init();

        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarDoc($docId, $chatid){
    global $TOKEN;
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'document' => $docId
            ];

        $options = [
                CURLOPT_URL => 'https://api.telegram.org/bot'.$TOKEN. '/sendDocument',
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

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

$log->debug("Fin de la ejecución");

?>
