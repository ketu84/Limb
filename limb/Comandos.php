<?php
    require_once __DIR__ . '/Utils.php';
    
    require_once __DIR__ . '/ComandosDev.php';
    require_once __DIR__ . '/ComandosLimb.php';
    require_once __DIR__ . '/ComandosOffTopic.php';
    
    
    class Comandos{
        
        static function ejecutar($endpoint, $request){
            $func=$request->get_command();
            if($func!=null){
                
                if($request->is_private_chat()){
                    //Sólo se permiten estos comandos desde chats privados
                    $response = ComandosDev::ejecutar($func,$endpoint, $request );
                }
                
                if(!isset($response)){
                    $response = ComandosLimb::ejecutar($func,$endpoint, $request );
                }
                
                if(!isset($response)){
                    $response = ComandosOffTopic::ejecutar($func,$endpoint, $request );
                }
                
                return $response;
                
            }
            return false;
            
        }
        
    }

?>