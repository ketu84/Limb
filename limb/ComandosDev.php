<?php
    class ComandosDev{
        
        static function ejecutar($func,$endpoint, $request){
            if(method_exists(ComandosDev,$func)){
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
    }
        
  
?>