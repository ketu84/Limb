<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/properties.php';
require_once $VENDOR_AUTOLOAD_PATH;
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/RequestException.php';
require_once __DIR__ . '/Comandos.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/dao/currentCMDDAO.php';
require_once __DIR__ . '/dao/apostarCMDDAO.php';
require_once __DIR__ . '/dao/chatDAO.php';
require_once __DIR__ . '/dao/grupoDAO.php';
require_once __DIR__ . '/ApostarCMD.php';

Logger::configure(__DIR__ .'/../config.xml');
$log = Logger::getLogger('com.hotelpene.limbBot.bot');

$log->debug("Comienza la ejecución del bot sorteo");
//$log->debug("Cabeceras: ".print_r(getallheaders(), true));


if(isset($_POST["texto"]) && isset($_POST["chat"]) && isset($_POST["token"])){
    $texto =$_POST["texto"];
    $chat =$_POST["chat"];
    $token =$_POST["token"];
    $aviso_admin =$_POST["aviso_admin"];
    
    if($token == $TOKEN){
        
        if($aviso_admin==1){
            
            $log->info('Enviando notificación a los administradores');
                    
            $response = Response::create_text_response($endpoint, $chat, $texto);
            $resultado = $response->send();
            $result = json_decode($resultado, true);
            if($result["ok"]){
                $log->info('Notificación enviada correctamente');
                echo '{"error": false}';
            }else{
                $log->error('Error al enviar la respuesta. ErroCode: '.$result["error_code"] . '. description: '.$result["description"]);
                echo '{"error":true, "desc":"'.$result["description"].'"}';
            }  
        }else{
            $log->debug("enviando gif");
            $file_id='CgADBAADWwIAAvbxKFOg2mQnmjb4lAI';
            $response= Response::create_doc_response($endpoint, $chat, $file_id);
            $resultado = $response->send();
            $result = json_decode($resultado, true);
            if($result["ok"]){
                //{"ok":true,
                    //"result":{"message_id":1717,"from":{"id":138747506,"is_bot":true,"first_name":"limBot","username":"guslimb_bot"},"chat":{"id":4082840,"first_name":"Antonio","username":"sgtoleos","type":"private"},"date":1508086073,"text":"prueba sorteo"}}
                $msg_id=$result["result"]["message_id"];
                
                sleep(3);
                
                $responseDel = Response::create_delete_response($endpoint, $chat, $msg_id);
                $resultadoDel = $responseDel->send();
                $resultDel = json_decode($resultadoDel, true);
                
                if($resultDel["ok"]){
                    $log->info('Respuesta borrado enviada correctamente');
                    
                    $response = Response::create_text_response($endpoint, $chat, $texto);
                    $resultado = $response->send();
                    $result = json_decode($resultado, true);
                    
                    if($result["ok"]){
                        $log->info('Respuesta sorteo enviada correctamente');
                        echo '{"error": false}';
                    }else{
                        $log->error('Borrado: Error al enviar la respuesta. ErroCode: '.$result["error_code"] . '. description: '.$result["description"]);
                        echo '{"error":true, "desc":"'.$result["description"].'"}';
                    }
                    
                }else{
                    $log->error('Borrado: Error al enviar la respuesta. ErroCode: '.$result["error_code"] . '. description: '.$result["description"]);
                    echo '{"error":true, "desc":"'.$result["description"].'"}';
                }
                
            }else{
                 $log->error('Error al enviar la respuesta. ErroCode: '.$result["error_code"] . '. description: '.$result["description"]);
                 echo '{"error":true, "desc":"'.$result["description"].'"}';
            }
        }
        
        
        
        
        
        //echo '{"error": false}';
    }else{
        echo '{"error":true, "desc":"No autorizado"}';
    }
    
}else{
    echo '{"error":true, "desc":"Faltan parametros"}';
}


?>
