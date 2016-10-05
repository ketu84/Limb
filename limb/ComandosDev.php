<?php
    class ComandosDev{
        
        static function ejecutar($func,$endpoint, $request){
            if(method_exists('ComandosDev',$func)){
                $commandDev = new ComandosDev();
                return $commandDev->$func($endpoint, $request);
            }
            return null;
        }
        
        private function pruebatexto($endpoint, $request){
            $params = $request->get_command_params();
            
            $texto = '';
            $i =0;
            foreach ($params as &$valor) {
                if($i!=0) $texto.=' ';
                $texto.=$valor;
                $i++;
            }
            
            //Se genera el objeto con la respuesta
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$texto;
            $response->markdown=true;
        
            return $response;
        }
        
        private function pruebadoc($endpoint, $request){
            $params = $request->get_command_params();
            
            if(count($params)>0){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_DOC);
                $response->file_id=$params[0];
            
                return $response;
            }else{
                echo "Sin parámetros";
                return false;
            }
        }
        
        private function pruebafoto($endpoint, $request){
            $params = $request->get_command_params();
            
            if(count($params)>0){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_PHOTO);
                $response->file_id=$params[0];
            
                return $response;
            }else{
                echo "Sin parámetros";
                return false;
            }
        }
        
        private function pruebasticker($endpoint, $request){
            $params = $request->get_command_params();
            
            if(count($params)>0){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_STICKER);
                $response->file_id=$params[0];
            
                return $response;
            }else{
                echo "Sin parámetros";
                return false;
            }
        }
        
        private function pruebavoice($endpoint, $request){
            $params = $request->get_command_params();
            
            if(count($params)>0){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_VOICE);
                $response->file_id=$params[0];
            
                return $response;
            }else{
                echo "Sin parámetros";
                return false;
            }
        }
        
        private function pruebaaudio($endpoint, $request){
            $params = $request->get_command_params();
            
            if(count($params)>0){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_AUDIO);
                $response->file_id=$params[0];
            
                return $response;
            }else{
                echo "Sin parámetros";
                return false;
            }
        }
        
        private function pruebavideo($endpoint, $request){
            $params = $request->get_command_params();
            
            if(count($params)>0){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_VIDEO);
                $response->file_id=$params[0];
            
                return $response;
            }else{
                echo "Sin parámetros";
                return false;
            }
        }
        
              
        private function cama($endpoint, $request){
            $object = new stdClass();
            //ReplyKeyboardMarkup
            $object->keyboard = [['/web ano'],['/web culo']];
            $object->resize_keyboard=true;
            $object->one_time_keyboard=true;
            return Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), 'ReplyKeyboardMarkup', json_encode($object));
        }
        private function cama2($endpoint, $request){
            //InlineKeyboardMarkup
            $InlineKeyboardButton=new stdClass();
            $InlineKeyboardButton->text='ChampionsLimb';
            //$InlineKeyboardButton->url='google.es';
            $InlineKeyboardButton->callback_data='/web ChampionsLimb';
            
            $InlineKeyboardButton2=new stdClass();
            $InlineKeyboardButton2->text='GusLimb';
            //$InlineKeyboardButton->url='google.es';
            $InlineKeyboardButton2->callback_data='/web GusLimb';
            
            $InlineKeyboardButton3=new stdClass();
            $InlineKeyboardButton3->text='Culo Limb';
            //$InlineKeyboardButton->url='google.es';
            $InlineKeyboardButton3->callback_data='/web GusLimb';
            
            $inline_keyboard = new stdClass();
            $arr = Array($InlineKeyboardButton, $InlineKeyboardButton2, $InlineKeyboardButton3);
            $inline_keyboard->inline_keyboard = [$arr];
            
            return Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), 'InlineKeyboardMarkup', json_encode($inline_keyboard));
        }
        
        private function cama3($endpoint, $request){
            $object = new stdClass();
            //ReplyKeyboardHide
            $object->hide_keyboard =true;
            return Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), 'oculto teclado - ReplyKeyboardHide', json_encode($object));
        }
        
        private function cama4($endpoint, $request){
            //force_reply
             $object = new stdClass();
            //ForceReply
            $object->force_reply =true;
            return Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), 'ForceReply', json_encode($object));
        
        }
        
        private function selectcurrent($endpoint, $request){
            
            $currentCMDDAO = new CurrentCMDDAO();
            $result = $currentCMDDAO->select($request->get_chat_id());
            $text='Chatid: '.$result['chat_id'];
            $text.='. CMD: '.$result['cmd'];
            $text.=' fec_actividad: '.$result['fec_actividad'];
            $text.=' grupo: '.$result['grupo'];
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            return $response;
        
        }
        
         private function insertcurrent($endpoint, $request){
            
            $currentCMDDAO = new CurrentCMDDAO();
            
            $cmd = new CurrentCMDVO();
            $cmd->chat_id=$request->get_chat_id();
            $cmd->cmd='mear';
            $cmd->grupo=1;
            
            $result = $currentCMDDAO->insert($cmd);
            //var_dump($result);
            $text='Insertado --Chatid: '.$result->chat_id;
            $text.='. CMD: '.$result->cmd;
            $text.=' fec_actividad: '.$result->fec_actividad;
            $text.=' grupo: '.$result->grupo;
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            return $response;
        
        }
        
         private function updatecurrent($endpoint, $request){
            
            $currentCMDDAO = new CurrentCMDDAO();
            $result = $currentCMDDAO->update($request->get_chat_id());
            if($result){
                $text='actualizado con exito';
            }else{
                $text='Error al actualizar';
            }
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            return $response;
        
        }
        
         private function deletecurrent($endpoint, $request){
            
            $currentCMDDAO = new CurrentCMDDAO();
            $result = $currentCMDDAO->delete($request->get_chat_id());
            $text='';
            if($result){
                $text='Borrando con exito';
            }else{
                $text='Error al borrar';
            }
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            return $response;
        
        }
        
        private function selectapostar($endpoint, $request){
            
            $apostarCMDDAO = new ApostarCMDDAO();
            $result = $apostarCMDDAO->select($request->get_chat_id());
            $text='Chatid: '.$result['chat_id'];
            $text.='. descrip: '.$result['descrip'];
            $text.=' importe: '.$result['importe'];
            $text.=' partido: '.$result['partido'];
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            return $response;
        
        }
        
         private function insertapostar($endpoint, $request){
            
            $apostarCMDDAO = new ApostarCMDDAO();
            
            $cmd = new ApostarCMDVO();
            $cmd->chat_id=$request->get_chat_id();
            $cmd->descrip='ambos marcan';
            $cmd->importe=1.34;
            $cmd->partido=25;
            
            $result = $apostarCMDDAO->insert($cmd);
            //var_dump($result);
            $text='Insertado --Chatid: '.$result->chat_id;
            $text.='. descrip: '.$result->descrip;
            $text.=' importe: '.$result->importe;
            $text.=' partido: '.$result->partido;
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            return $response;
        
        }
        
         private function updateapostar($endpoint, $request){
            
            $cmd = new ApostarCMDVO();
            $cmd->chat_id=$request->get_chat_id();
            $cmd->descrip='ambos marcan por los cojones';
            $cmd->importe=2.34;
            $cmd->partido=27;
            
            
            $apostarCMDDAO = new ApostarCMDDAO();
            $result = $apostarCMDDAO->update($cmd);
            if($result){
                $text='actualizado con exito';
            }else{
                $text='Error al actualizar';
            }
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            return $response;
        
        }
        
         private function deleteapostar($endpoint, $request){
            
            $apostarCMDDAO = new ApostarCMDDAO();
            $result = $apostarCMDDAO->delete($request->get_chat_id());
            $text='';
            if($result){
                $text='Borrando con exito';
            }else{
                $text='Error al borrar';
            }
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            return $response;
        
        }
        
        private function emitirmensaje($endpoint, $request) {
            global $TOKEN;
            $params = $request->get_command_params();
            if(count($params)<3) {

                $texto = 'Uso /emitirmensaje token destino mensaje';
            }
            else if($params[0] != $TOKEN){
                $texto = 'Token invalido';
            }
            else {
                $texto = implode(" ", array_slice($params, 2));
                $chat_id = $params[1];
            }
            $chat_id = isset($chat_id) ? $chat_id : $request->get_chat_id();
            return Response::create_text_response($endpoint, $chat_id, $texto);
        }
        
    }
        
  
?>