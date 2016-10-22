<?php
    require_once __DIR__ . '/Utils.php';
    
    require_once __DIR__ . '/ComandosDev.php';
    require_once __DIR__ . '/ComandosLimb.php';
    require_once __DIR__ . '/ComandosFutbol.php';
    require_once __DIR__ . '/ComandosOffTopic.php';
    
    
    class Comandos{
        static $logStatic;
        
        static function ejecutar($endpoint, $request){
            $logStatic = Logger::getLogger('com.hotelpene.limbBot.Comandos');
             
            $func=$request->get_command();
            if($func!=null){
                
                if($request->is_private_chat()){
                    //Sólo se permiten estos comandos desde chats privados
                    
                    if($func=='cancel'){
                        $currentCMDDAO = new CurrentCMDDAO();
                        $currentCMD = $currentCMDDAO->delete($request->get_chat_id());
                        $object = new stdClass();
                        $object->hide_keyboard =true;
                        $response = Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), 'Cancelado', json_encode($object));
                    }else{
                        $response = ComandosDev::ejecutar($func,$endpoint, $request );
                    }
                }
                
                if(!isset($response)){
                    $response = ComandosLimb::ejecutar($func,$endpoint, $request );
                }
                
                if(!isset($response)){
                    $response = ComandosFutbol::ejecutar($func,$endpoint, $request );
                }
                
                if(!isset($response)){
                    $response = ComandosOffTopic::ejecutar($func,$endpoint, $request );
                }
                
                return $response;
                
            }else{
                $logStatic->debug("No es un comando: ".$request->get_text());
                //Buscar si hay algun comando en curso
                $currentCMDDAO = new CurrentCMDDAO();
                $currentCMD = $currentCMDDAO->select($request->get_chat_id());
                
                if($currentCMD['grupo']==null){
                    $grupoDAO = new GrupoDAO();
                    $grupoVO=$grupoDAO->selectByNombre($request->get_text());
                    if($grupoVO!=null){
                        $currentCMDDAO->updateGrupo($request->get_chat_id(), $grupoVO->id);
                    
                        $func = $currentCMD['cmd'];
                        return $response = ComandosLimb::ejecutar($func,$endpoint, $request );
                    } 
                }
                
                if($currentCMD!=null){
                    $logStatic->debug("Hay un comando en marcha: ".$currentCMD['cmd']);
                    
                    if($currentCMD['cmd']=='apostar'){
                        $apostarCMD = new ApostarCMD();
                        return $apostarCMD->apostar($endpoint, $request, $currentCMD );
                    }else{
                        $grupoDAO = new GrupoDAO();
                        $grupoVO=$grupoDAO->selectByNombre($request->get_text());
                        if($grupoVO!=null){
                            $currentCMDDAO->updateGrupo($request->get_chat_id(), $grupoVO->id);
                        
                            $func = $currentCMD['cmd'];
                            return $response = ComandosLimb::ejecutar($func,$endpoint, $request );
                        }
                    }
                    
                }else{
                    $logStatic->debug("NO Hay un comando en marcha.");
                }
            }
            
            return false;
            
        }
        
    }

?>