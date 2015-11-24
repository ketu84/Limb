<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/properties.php';

Logger::configure(__DIR__ .'/../config.xml');
$log = Logger::getLogger('botLogger');

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
$humano=null;
switch ($message['message']['from']['id']) {
	case ID_AGE:
		$humano = aleatorio(array('Ángel', 'Caballero', 'Ario', 'Veleta'));
		break;
	case ID_TAPIA:
		$humano = aleatorio(array('Antonio', 'Tapia'));
		break;
	case ID_NANO:
		$humano="Nano";
		break;
	case ID_YONI:
		$humano = aleatorio(array('Yoni', 'Ori'));
		break;
	case ID_CAS: 
		$humano= aleatorio(array('Cas', 'Castaña'));
		break;
	case ID_JAVI:
		$humano = aleatorio(array('Javi', 'Carracedo', 'Fascista'));
		break;
	case ID_KETU:
		$humano= aleatorio(array('Ketu', 'Manuel David'));
		break;
	case ID_PACO:
		$humano = aleatorio(array('Paco', 'Ake'));
		break;
	case ID_RIOJANO:
		$humano= aleatorio(array('Riojano', 'Gelete', 'Almendro'));
		break;
	case ID_BARTOL:
		$humano = aleatorio(array('Luis', 'Bartol'));
		break;
	case ID_VICENTE:
		$humano= aleatorio(array('Vicente', 'Comandante'));
		break;
	case ID_ZATO:
		$humano="Álvaro";
		break;
	case ID_RULO:
		$humano="Raúl";
		break;
	case ID_MATUTE:
		$humano="Matute";
		break;
	case ID_LUCHO:
		$humano="Luciano";
		break;
	case ID_BORJA:
		$humano="Borja";
		break;
}

//Si se trata de un grupo
if($message['message']['chat']['type']=='group'){
    if($chatid==GUSLIMB_GROUPID){
        $log = Logger::getLogger(GUSLIMB_LOGGER);
        $urlApi=GUSLIMB_URL_API;
        $urlWeb=GUSLIMB_URL;
    }else if($chatid==CHAMPIONSLIMB_GROUPID){
        $log = Logger::getLogger(CHAMPIONSLIMB_LOGGER);
        $urlApi=CHAMPIONSLIMB_URL_API;
        $urlWeb=CHAMPIONSLIMB_URL;
    }else{
        return;
    }
}else{
    switch ($chatid) {
        case ID_AGE:
        case ID_TAPIA:
        case ID_NANO:
        case ID_YONI:
        case ID_CAS: 
        case ID_JAVI:
        case ID_KETU:
        case ID_PACO:
        case ID_RIOJANO:
        case ID_BARTOL:
        case ID_VICENTE:
            $log = Logger::getLogger(GUSLIMB_LOGGER);
            $urlApi=GUSLIMB_URL_API;
            $urlWeb=GUSLIMB_URL;
            break;
        default:
            $log = Logger::getLogger(CHAMPIONSLIMB_LOGGER);
            $urlApi=CHAMPIONSLIMB_URL_API;
            $urlWeb=CHAMPIONSLIMB_URL;
            break;
    }
}
$log->debug("Mensaje: ".$rawMsg);
$log->debug('Establecido api: '.$urlApi);



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

//Si se recibe un Audio de Voz, se responde con el Id de este
if(isset($message["message"]["voice"])){
    $documentId = $message['message']['voice']['file_id'];
    $text="Recibido Audio de Voz con id: ".$documentId;
    enviarTexto($text,$chatid, false);
    return;
}

//Si se recibe un Audio , se responde con el Id de este
if(isset($message["message"]["audio"])){
    $documentId = $message['message']['audio']['file_id'];
    $text="Recibido Audio con id: ".$documentId;
    enviarTexto($text,$chatid, false);
    return;
}

//Si se recibe un Video , se responde con el Id de este
if(isset($message["message"]["video"])){
    $documentId = $message['message']['video']['file_id'];
    $text="Recibido Video con id: ".$documentId;
    enviarTexto($text,$chatid, false);
    return;
}

if(!isset($message["message"]["text"])) return;

$command= $message['message']['text'];
$test = isset($message["message"]["chat"]["type"]) && $message["message"]["chat"]["type"]=="private";

/*Comandos de pruebas para desarrolladores*/

if($test){

     $log->debug("mensaje privado");

    if(substr($command, 0, strlen('/pruebaTexto')) === '/pruebaTexto'){
        $param = substr($command, strlen('/pruebaTexto')+1);
        enviarTexto($param,$chatid, true);
        return;
    }

    if(substr($command, 0, strlen('/pruebaFoto')) === '/pruebaFoto'){
        $param = substr($command, strlen('/pruebaFoto')+1);
        enviarFoto($param,$chatid);
        return;
    }

    if(substr($command, 0, strlen('/pruebaDoc')) === '/pruebaDoc'){
        $param = substr($command, strlen('/pruebaDoc')+1);
        enviarDoc($param,$chatid);
        return;
    }

    if(substr($command, 0, strlen('/pruebaSticker')) === '/pruebaSticker'){
        $param = substr($command, strlen('/pruebaSticker')+1);
        enviarSticker($param,$chatid);
        return;
    }
    
    if(substr($command, 0, strlen('/pruebaVoice')) === '/pruebaVoice'){
        $param = substr($command, strlen('/pruebaVoice')+1);
        enviarVoice($param,$chatid);
        return;
    }
    
    if(substr($command, 0, strlen('/pruebaAudio')) === '/pruebaAudio'){
        $param = substr($command, strlen('/pruebaAudio')+1);
        enviarAudio($param,$chatid);
        return;
    }
    
    if(substr($command, 0, strlen('/pruebaVideo')) === '/pruebaVideo'){
        $param = substr($command, strlen('/pruebaVideo')+1);
        enviarVideo($param,$chatid);
        return;
    }
}

$command=strtolower($command);

switch ($command) {
	case '/clasificacion':
		clasificacion($chatid, $urlApi, $log);
		break;
	case '/prox_jornada':
		proxima_jornada($chatid, $urlApi);
		break;
	case '/apuestas':
		apuestas($chatid, $urlApi);
		break;
	case '/euros':
		euros($chatid, $urlApi);
		break;
	case '/web':
		web($urlWeb, $chatid);
		break;
	case '/donasemen':
		donaSemen($chatid);
		break;
	case '/bravo':
		bravo($chatid);
		break;
	case '/quieromipenaltito':
		quieroMiPenaltito($chatid);
		break;
	case '/cuantohaperdidoriojas':
		cuantoHaPerdidoRiojas($chatid);
		break;
	case '/gus':
		gus($chatid);
		break;
	case '/telacomiste':
		telacomiste($chatid);
		break;	
	case '/vicenwin':
		vicenwin($chatid);
		break;	
	case '/fatspanishwaiter':
		fatSpanishWaiter($chatid);
		break;	
	case '/cuantohaganadocas':
		cuantoHaGanadoCas($chatid);
		break;
	case '/aupa':
		aupa($chatid);
        	break;
	case '/tetas':
	case '/mamellas':
	case '/domingas':
	case '/lolas':
	case '/peras':
	case '/melones':
	case '/pechos':
	case '/senos':
	case '/mamas':
		enfermo($chatid, $humano);
        	break;	
	case '/pene':
	case '/pito':
	case '/nabo':
	case '/cipote':
	case '/cimbrel':
	case '/semen':
		esLoQueTeGustaEh($chatid, $humano);
		break;
	case '/hez':
	case '/mierda':
		hez($chatid);
        	break;
	case '/sorteo':
		sorteo($chatid);
	    	break;
	case '/comovalacosa':
		lacosa($chatid, $humano);
		break;
	case '/nogus':
		nogus($chatid);
	        break;
	case '/holaage':
		holaage($chatid);
	        break;
	case '/holaketu':
		holaketu($chatid);
		break;
	case '/valetio':
		valetio($chatid);
		break;
	case '/agevapipa':
		agevapipa("$chatid");
		break;
	case '/cantar':
		cantar("$chatid");
		break;
        default:
		if (strpos($command,'puta') !== false) {
		    insultarAMadre($chatid, $humano, 'puta');
		    break;
		}
		if (strpos($command,'gorda') !== false) {
		    insultarAMadre($chatid, $humano, 'gorda');
		    break;
		}
		if (strpos($command,'cabron') !== false) {
		    insultarAHumano($chatid, $humano, 'cabron');
		    break;
		}
		if (strpos($command,'subnormal') !== false) {
		    insultarAHumano($chatid, $humano, 'subnormal');
		    break;
		}
		if (strpos($command,'gilipollas') !== false) {
		    insultarAHumano($chatid, $humano, 'gilipollas');
		    break;
		}
		if (strpos($command,'socialista') !== false) {
		    insultarAHumano($chatid, $humano, 'socialista');
		    break;
		}
		if (strpos($command,'podemita') !== false) {
		   enviarFoto('AgADBAADtrExG6uCfgAB-HBYDek-QkN_mo8wAARqlUj5CBNq9idfAAIC', $chatId);
		   break;
		}
		if (strpos($command,'coleta') !== false) {
		   enviarFoto('AgADBAADtrExG6uCfgAB-HBYDek-QkN_mo8wAARqlUj5CBNq9idfAAIC', $chatId);
		   break;
		}
		else {
		insultar($chatid, $humano);
		break;
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

    $log->debug("Enviando foto con id: ".$docId);
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

function enviarAudio($docId, $chatid){
    global $TOKEN;
    global $log;

    $log->debug("Enviando Audio con id: ".$docId);
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'audio' => $docId
            ];

        enviarMensaje('/sendAudio', $data);

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarVoice($docId, $chatid){
    global $TOKEN;
    global $log;

    $log->debug("Enviando Voice con id: ".$docId);
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'voice' => $docId
            ];

        enviarMensaje('/sendVoice', $data);

    } catch (Exception $e) {
        syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
        exit(1);
    }
}

function enviarVideo($docId, $chatid){
    global $TOKEN;
    global $log;

    $log->debug("Enviando Video con id: ".$docId);
    try {
        $data= [
                'chat_id' => (int) $chatid,
                'video' => $docId
            ];

        enviarMensaje('/sendVideo', $data);

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
    global $endpoint;
    try {       

        $options = [
                CURLOPT_URL => $endpoint. $accion,
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

function clasificacion($chatid, $urlApi, $log){
    global $log;
    $log->debug('clasificacion');
    enviarAccionChat('typing',$chatid);

    $text='Clasificación de la última fase en curso:'.PHP_EOL.PHP_EOL;

    $log->debug($urlApi . 'clasificacion');
    $json = file_get_contents($urlApi . 'clasificacion');
    $obj = json_decode($json);

    foreach($obj as $valor) {
        $text=$text.$valor->pos.'.- '.$valor->nombre.': '.$valor->neto.'€'.PHP_EOL;
    }
    enviarTexto($text,$chatid, false);
}

function proxima_jornada($chatid, $urlApi){
    enviarAccionChat('typing',$chatid);

    $text='';
    $json = file_get_contents($urlApi . 'prox_jornada');

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

function apuestas($chatid, $urlApi){
    enviarAccionChat('typing',$chatid);

    $text='';
    $json = file_get_contents($urlApi . 'apuestas');

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

function euros($chatid, $urlApi){
    global $log;
    $log->debug('euros');
    enviarAccionChat('typing',$chatid);

    $text='Acumulado:'.PHP_EOL;

    $log->debug($urlApi . 'clasificacion');
    $json = file_get_contents($urlApi . 'clasificacion');
    $obj = json_decode($json);
    
    $sumatorio = 0;
    foreach($obj as $valor) {
        $sumatorio += $valor->neto;
    }
    $text=$text.$sumatorio.'€'.PHP_EOL;
    
    enviarTexto($text,$chatid, false);
}

function web($urlWeb, $chatid){
	$text=$urlWeb;
    enviarTexto($text,$chatid, false);
}

function donaSemen($chatid){
	$emoji_mujer=unichr(0x1F64B);
    $emoji_semen=unichr(0x1F4A6);

    $text=$emoji_semen.$emoji_mujer;
    enviarTexto($text,$chatid, false);
}

function bravo($chatid){
	 enviarDoc('BQADBAADuAADmEw-AAFENNvXv3KlQgI',$chatid);
}

function quieroMiPenaltito($chatid){
	 enviarDoc('BQADBAADtwADmEw-AAHFpvL_faHg5QI',$chatid);
}

function cuantoHaPerdidoRiojas($chatid){
	 enviarTexto('jajaja, pues todo pringaos',$chatid, false);
}

function enfermo($chatid, $humano){
	 enviarTexto($humano.' eres un enfermo',$chatid, false);
}

function esLoQueTeGustaEh($chatid, $humano){
	 enviarTexto('Es lo que te gusta, '.$humano.', ¿eh?',$chatid, false);
}

function gus($chatid){
	  $gus = aleatorio(array('AgADBAADKrExG6uCfgABZugFvbiTwBWpaHIwAAQIkbE_6Ksrx8Q2AQABAg', 'AgADBAAD3KkxG5sPmAABKqtTAAHWZbY3NQWLMAAEmP0iZZyVDtfYMAEAAQI'));
	  enviarFoto($gus,$chatid);
}

function nogus($chatid){
	  enviarFoto('AgADBAADr7ExG6uCfgABRKEQrm8ULhfcco8wAAQ5D9K2nU6X0IFfAAIC',$chatid);
}

function holaketu($chatid){
	  enviarFoto('AgADBAADtbExG6uCfgABLrwSmy2LSv8U1IwwAAQ_de836O5RLh3YAAIC',$chatid);
}

function holaage($chatid){
	  enviarFoto('AgADBAADtLExG6uCfgABPI6QTh8Q4fpHQnEwAARy8nYUUSRw2Lq2AQABAg',$chatid);
}

function valetio($chatid){
	  enviarFoto('AgADBAADs7ExG6uCfgABNelLZA68mblL0owwAATnURFjObRacZTZAAIC',$chatid);
}

function agevapipa($chatid){
	  $agepipa = aleatorio(array('AgADBAADsLExG6uCfgAB11V67VOSyHQhd4wwAATniw8DzMJf0yJcAQABAg', 'AgADBAADsbExG6uCfgABdK4Br7b7bjPOCnEwAASIQJwfY4Wa6v7QAQABAg', 'AgADBAADsrExG6uCfgABcOiowovh9m2J83AwAAQXyvtk28XB_s7RAQABAg'));
	  enviarFoto($agepipa,$chatid);
}

function telacomiste($chatid){
	  enviarFoto('AgADBAADK7ExG6uCfgAB9rTpspMp9VRGYGkwAAS8GdFc47A_whSFAQABAg',$chatid);
}

function vicenwin($chatid){
	  enviarFoto('AgADBAADLLExG6uCfgAB0UBRGzF7sb96C2swAAS7hCl_X6wqS9ByAQABAg',$chatid);
}

function fatSpanishWaiter($chatid){
	  enviarDoc('BQADBAADMAEAAquCfgABhqhRqhpC5agC',$chatid);
}

function cuantoHaGanadoCas($chatid){
	enviarTexto('Se lo está llevando crudo',$chatid, false);
	enviarFoto('AgADBAADLbExG6uCfgABO7d46OcKzQkVuo8wAATDrhyVPZbKfktbAAIC',$chatid);
}

function aupa($chatid){
	$index = rand(0,2);
	if($index == 0)
		enviarDoc('BQADBAADOAAECiQB3V1ov-88-qgC',$chatid);
	if($index == 1) 
		enviarTexto($humano.' eres un pajero.',$chatid, false);	
	if($index == 2)
		enviarDoc('BQADBAADOgEAAquCfgABXRORytopeMsC', $chatid);
}

function hez($chatid){
    enviarDoc('BQADBAADMgADmw-YAAE4pcdXZXF0FgI',$chatid);
}

function sorteo($chatid){
    enviarTexto('¿Quieres dejar de molestar?',$chatid, false);
    enviarFoto('BQADBAADOQEAAquCfgABPSV-6BCH3vYC', $chatid);
}

function lacosa($chatid, $humano){
	  enviarTexto($humano.', que cómo va la cosa?', $chatid, false);
	  $foto = aleatorio(array('AgADBAADg7ExG6uCfgABVKJWZutMk03lxWkwAAQyyYLPq-povk7xAQABAg', 'AgADBAADhLExG6uCfgABkiwjXOKfo3Y0tY8wAATxS8uU6Iu9fg_YAAIC', 'AgADBAADhbExG6uCfgABesAVy65s2vanA3EwAARisRvtaQadn3nPAQABAg', 'AgADBAADhrExG6uCfgAB3q5XSN1HRuPNcHEwAAQ8lK5YSKm_w5a7AQABAg', 'AgADBAADh7ExG6uCfgABSfzQtoQa7aKTLqIwAATDO6GIuvcGBeg1AAIC', 'AgADBAADiLExG6uCfgABMQbc_pgPqPEuMHEwAAQuty0bwqN55XO6AQABAg', 'AgADBAADibExG6uCfgABugz1SCDyHuHhQ6IwAASYuB7xHlsFZ_MzAAIC', 'AgADBAADirExG6uCfgAB1vISgyQDxyMLcHIwAARCgZ4gG5cWjY60AQABAg', 'AgADBAADi7ExG6uCfgAB5CYClvguvRQhUoswAASJGt4QohAzC_haAQABAg', 'AgADBAADjLExG6uCfgAB_kzIyeo7UVgJUIwwAAQgu3fC_vtzdy5XAQABAg', 'AgADBAADjbExG6uCfgABZjphYymr8F9KS6YwAAQtofZMgNgMBis2AAIC', 'AgADBAADjrExG6uCfgABpuGgz4haMOsRYHEwAAS14RfJ-IZpZVS6AQABAg', 'AgADBAADj7ExG6uCfgABg5r1ekTLJ7btW48wAAQiyna7QZONdmRfAAIC', 'AgADBAADkLExG6uCfgABt5tNR8GfsWy5WqYwAAQxj7W6UPHflX41AAIC', 'AgADBAADkrExG6uCfgABAZlES-gMg-rz1IwwAAR6gfrHOimI75nZAAIC', 'AgADBAADk7ExG6uCfgABks1B4XJt5Bd4yGkwAAR8ECGfpgFKyJHzAQABAg', 'AgADBAADkbExG6uCfgABs04ElacKtmyn7mowAAR-idLveeqFxb7uAQABAg', 'AgADBAADlLExG6uCfgABzgujxVkGl8VLxWkwAASzi-RJNjW4yqHwAQABAg', 'AgADBAADlbExG6uCfgABadtPItKyx57k5XAwAAS9J_8HXRkqQRLQAQABAg', 'AgADBAADlrExG6uCfgABjtQZ-hLnpBAVD2swAASmM_StGK3AQIn2AQABAg', 'AgADBAADl7ExG6uCfgABa7lJExDqn6KxRnEwAASoJxtLytnEBk-4AQABAg', 'AgADBAADmLExG6uCfgAB2yvN68F1E3qQRaYwAAQJZvnWM0fs6_w1AAIC', 'AgADBAADmbExG6uCfgABb5BeDjZLP5DRyIowAASpPPFqZl6L139eAQABAg', 'AgADBAADmrExG6uCfgABG62c8ayCCvvRuI8wAATi1YEVNMomD0_bAAIC', 'AgADBAADm7ExG6uCfgABNGIZUIZX9JYyKHEwAAQ62JlAJ5p_0SW7AQABAg', 'AgADBAADnLExG6uCfgABQwGrUWBMJKVxlY8wAATL374qLly_A79fAAIC', 'AgADBAADnbExG6uCfgABW7Uip_ShXhXb43IwAAQkAnw1HQVB9tG9AQABAg', 'AgADBAADnrExG6uCfgABTotSza9d6Gz4nWkwAAQqdc8JQP14YL_1AQABAg', 'AgADBAADn7ExG6uCfgABojpB_BgE_JdeaHEwAARtUZIZWh77avy7AQABAg', 'AgADBAADoLExG6uCfgAB9v9babjpowU56HAwAAR7W19v2yek8-nQAQABAg', 'AgADBAADobExG6uCfgABE_MSvMoXM01HGXEwAARypH9DLukNyIC5AQABAg', 'AgADBAADorExG6uCfgABI9r34SFG4BhdU4swAAR3m_Jwt8ywoDVZAQABAg', 'AgADBAADq7ExG6uCfgABzHgzg4sczRsbrmkwAAQQKX9ZrfI9Bt_vAQABAg', 'AgADBAADrLExG6uCfgABSiS6rwhMIAF6u6YwAAS4qmUoULYrgiw1AAIC', 'AgADBAADo7ExG6uCfgABwiLcKZVclAyyV4wwAARQA7M4L17jmhJbAQABAg', 'AgADBAADrbExG6uCfgABUKunpEocMOJImI8wAATALTCBXb0Oe51cAAIC', 'AgADBAADrrExG6uCfgABer9D-9C0RO7z54wwAASP4IgsgI0kN-1dAAIC', 'AgADBAADpLExG6uCfgABVG9smyxlg6CoxoowAATIlHSMiAxe1AdaAQABAg', 'AgADBAADpbExG6uCfgABxbSa0X7VyL_KSHEwAAQT8mSD18ayE5u3AQABAg', 'AgADBAADp7ExG6uCfgABiDzmyccrxFtOto8wAARBseWhiEnjE7XZAAIC', 'AgADBAADprExG6uCfgABPNfI6LRiOSdeR3EwAATSf-1vm3K-fKK3AQABAg', 'AgADBAADqLExG6uCfgABxlU7MV-ghMAJ0GkwAAQQUlgVQloPLen2AQABAg', 'AgADBAADqbExG6uCfgABgbxqAAG0uafSOkymMAAEgwovT5OEHKmkNQACAg', 'AgADBAADqrExG6uCfgABS17nrpTZLMlxWqYwAASRlfGd2854Zm81AAIC'));
	  enviarFoto($foto,$chatid);
}

function insultar($chatid, $humano){
	$text = 'Función no implementada. ';
	if($humano!=null){
		if($humano=='Paco')
			$text = $text.'¡¡¡Pacooooooooooooooooooo!!! ';
		else {
			$insulto = aleatorio(['Maldito', 'Jodido', 'Estúpido', 'Condenado', 'Retrasado', 'Podemita']);
			$text = $text.$insulto.' '.$humano.'. ';
		}
	}
	$insulto = aleatorio(array('¿Eres idiota?', 
	                            '¿Eres bobo?', 
	                            '¿Eres falto?', 
	                            '¿Eres imbécil?', 
	                            'Cómeme un huevo', 
	                            '¿Estás beodo?', 
	                            'Papanatas', 
	                            'Mentecato', 
	                            'Parguela', 
	                            'Mierdaseca', 
	                            'Hijo de puta',
	                            'Gilipipas'));
	$text .= $insulto;
	if($humano=='Ario')
			enviarFoto('AgADBAADLKkxG4jtnAABsbSkFxkCLImgn2kwAARwTik8oQSyGj3nAQABAg', $chatid);
		
	enviarTexto($text,$chatid, false);
}
 
 function insultarAMadre($chatid, $humano, $insulto){
	$text = 'Tu madre si que es ';
	$text .= $insulto;
	enviarTexto($text,$chatid, false);
}
 function insultarAHumano($chatid, $humano, $insulto){
	$text = $humano;
	$text .= ' ,tu si que eres ';
	$text .= $insulto;
	enviarTexto($text,$chatid, false);
}
 function cantar($chatid){
	enviarAudio('BQADBAADawEAAquCfgABAhruCPned4AC',$chatid);
}

function aleatorio($elementos){
	return $elementos[rand(0,count($elementos)-1)];
}

?>
