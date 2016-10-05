<?php
    class ApostarCMD{
        
        private $log;
        
        function __construct() {
            $this->log = Logger::getLogger('com.hotelpene.limbBot.ApostarCMD');
        }
        
        public function apostar($endpoint, $request, $currentCMD){
            $apostarCMDDAO = new ApostarCMDDAO();
            $resultApostarCMD = $apostarCMDDAO->select($request->get_chat_id());
            
            if(!isset($resultApostarCMD['partido'])){
                $this->log->debug("No hay partido");
                return $this->updatePartido($endpoint, $request);
                
            }else if(!isset($resultApostarCMD['importe'])){
                $this->log->debug("Ya hay partido, pero no hay importe");
                return $this->updateImporte($endpoint, $request, $resultApostarCMD);
               
            }else if(!isset($resultApostarCMD['descrip'])){
               $this->log->debug("Ya hay partido e importe, no hay descrip");
               return $this->updateDescrip($endpoint, $request, $resultApostarCMD, $currentCMD);
               
            }else{
                $this->log->debug("Aqui no debería llegar nuenca.");
            }
        }
        
        private function updatePartido($endpoint, $request){
            $this->log->debug("update partido");
            
            $arrText=preg_split('/ | /',$request->get_text());
            if(count($arrText)>0 && is_numeric($arrText[0])){
                $partido = $arrText[0];
                $cmd = new ApostarCMDVO();
                $cmd->chat_id=$request->get_chat_id();
                $cmd->partido=$partido;
                $apostarCMDDAO = new ApostarCMDDAO();
                $result = $apostarCMDDAO->update($cmd);
                
                //se bora el teclado.
                $object = new stdClass();
                $object->hide_keyboard =true;
                return Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), '¿Cuanto quieres apostar?', json_encode($object));
                
            }else{
                 $this->log->debug("No se puede obtener el partido.");
            }
        }
        
        private function updateImporte($endpoint, $request, $resultApostarCMD){
            $this->log->debug("update importe");
            $importe=$request->get_text();
            $importe=str_replace(',','.',$importe);
            if(is_numeric($importe)){
                $this->log->debug("Es un número.");
                
                $cmd = new ApostarCMDVO();
                $cmd->chat_id=$request->get_chat_id();
                $cmd->partido=$resultApostarCMD['partido'];
                $cmd->importe = $importe;
                $apostarCMDDAO = new ApostarCMDDAO();
                $result = $apostarCMDDAO->update($cmd);
                
                //Se pide la descripción
                $text='Vale, dime tú apuesta';
                return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
                
                
            }else{
                $this->log->debug("Error no es un número");
                $text='El importe de la apuesta debe ser un número, '.Utils::getInsultoSingular();
                return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
            }
        }
        
        private function updateDescrip($endpoint, $request, $resultApostarCMD, $currentCMD){
            
            $cmd = new ApostarCMDVO();
            $cmd->chat_id=$request->get_chat_id();
            $cmd->partido=$resultApostarCMD['partido'];
            $cmd->importe = $resultApostarCMD['importe'];
            $cmd->descrip=$request->get_text();
            $apostarCMDDAO = new ApostarCMDDAO();
            $result = $apostarCMDDAO->update($cmd);
            
            $this->log->debug("Ya tengo todo para hacer la apuesta");
            
            
            //Llamar api apostar
            $result = $this->crearApuesta($endpoint, $request, $cmd, $currentCMD);
            
            //Se borra el comando actual
            $currentCMDDAO = new CurrentCMDDAO();
            $currentCMD = $currentCMDDAO->delete($request->get_chat_id());

            if($result==null){
                $text='Apuesta creada correctamente.'.PHP_EOL.'Puedes verla con el comando /mispartidos';
                return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
            }
            $emoji_alert=Utils::convert_emoji(0x26A0);
            $text=$emoji_alert.'*Error:*'.PHP_EOL.$result;
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
            
        }
        
        private function crearApuesta($endpoint, $request, $cmd, $currentCMD){
            $grupo=$currentCMD['grupo'];
            if($grupo==0){
                $urlApi = CHAMPIONSLIMB_URL_API;
            }else{
                $urlApi = GUSLIMB_URL_API;
            }
            
            $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
            $tokenUsuario = json_decode($jsonTokenUser, true);

            //Si hay token de usuario del chat, se invoca el comando con el token
            if($tokenUsuario[0]['token']){
                $idUsuario = $tokenUsuario[0]['id'];
                
                $data = array("token" => $tokenUsuario[0]['token'],
                    "idpartido"=>$cmd->partido,
                    "idapostante"=>$idUsuario,
                    "importe"=>$cmd->importe,
                    "desc"=>$cmd->descrip
                        );
                $data_string = json_encode($data);                                                                                   
                
                
                //Esto se podría meter en el utils
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL,$urlApi.'/apuestas/partido');
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                                                                                                                                     
                $result = curl_exec($curl);
                $apuesta = json_decode($result);
                if(isset($apuesta->error)){
                    return $apuesta->error->text;
                }
                return null;
            }
            
        }
    }
?>
        