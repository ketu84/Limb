<?php
    class ComandosLimb{
        
        static function ejecutar($func,$endpoint, $request){
            
            //Si el usuario o grupo no está configurado para Limb, se sale de estos comandos
            $urlApi=Utils::get_url_api($request);
            if(is_null($urlApi)){
                return null;
            }
            
            if(method_exists(ComandosLimb,$func)){
                $commandDev = new ComandosLimb();
                return $commandDev->$func($endpoint, $request);
            }
            return null;
        }
        
        private function clasificacion($endpoint, $request){
            $response_chat_typing = Response::create_typing_response($endpoint, $request->get_chat_id());
            $response_chat_typing->send();
            
            $text='*Clasificación de la última fase en curso:*'.PHP_EOL.PHP_EOL;
            
            $urlApi=Utils::get_url_api($request);
            $json = file_get_contents($urlApi . 'clasificacion');
            $obj = json_decode($json);
            
            $emoji_down= Utils::convert_emoji(0x2B06);
            $emoji_up= Utils::convert_emoji(0x2B07);
            
            foreach($obj as $valor) {
                /**TODO Adaptar esto para todas las rondas de clasificación*/
            	if((int)$valor->pos > 8){
                	$text=$text.'*'.$valor->pos.'*.- '.$valor->nombre.': '.$valor->neto.'€ '.$emoji_up.PHP_EOL;
                }else{
                	$text=$text.'*'.$valor->pos.'*.- '.$valor->nombre.': '.$valor->neto.'€'.PHP_EOL;
                }
            }
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            return $response;
        }
        
        private function prox_jornada($endpoint, $request){
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            $urlApi=Utils::get_url_api($request);
            $json = file_get_contents($urlApi . 'prox_jornada');
            $obj = json_decode($json);
            
            $text='';
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
            $text='*Próxima jornada '.$fecha.':* '.PHP_EOL.$text;
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            return $response;
        }
        
        private function apuestas($endpoint, $request){
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            $urlApi=Utils::get_url_api($request);
            $json = file_get_contents($urlApi . 'apuestas');
            $obj = json_decode($json);
            
            $text='';
            $fecha='';
            $idPartido=-1;
            $apostante='';
        
            $emoji_star= Utils::convert_emoji(0x1F538);
            $emoji_guion= Utils::convert_emoji(0x2796);
            $emoji_cara=Utils::convert_emoji(0x1F633);
            $emoji_ok=Utils::convert_emoji(0x2705);
            $emoji_mal=Utils::convert_emoji(0x274C);
        
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
            $text='*Apuestas '.$fecha.': *'.PHP_EOL.PHP_EOL.$text;

            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            return $response;
        }
        
        private function euros($endpoint, $request){
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            $text='*Acumulado:*'.PHP_EOL;
        
            $urlApi=Utils::get_url_api($request);
            $json = file_get_contents($urlApi . 'euros');
            
            echo $json;
            
            $obj = json_decode($json);
            
            $sumatorio = $obj->total;
            $text=$text.$sumatorio.'€'.PHP_EOL;
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            return $response;
        }
        
        private function apostadYa($endpoint, $request){
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            $urlApi=Utils::get_url_api($request);
            $json = file_get_contents($urlApi . 'apostantes');
            $jsonApostantes = json_decode($json);
            $arrayApostantes = array();
            $arrayApostantesYaApostados = array();
    
        	foreach($jsonApostantes as $apostante){
        		$arrayApostantes[$apostante->id] = $apostante->nombre;
        	}
          
            $json = file_get_contents($urlApi . 'apuestas');
            $jsonApuestas = json_decode($json);
            foreach($jsonApuestas as $apuesta){
                if(!in_array($apuesta->apostante,$arrayApostantesYaApostados)){
                    array_push($arrayApostantesYaApostados,$apuesta->apostante);
                }
            }
            
            $json = file_get_contents($urlApi . 'prox_jornada');
            $jsonProxJornada = json_decode($json);

            $mapApostantesPartidos = array();
	
            foreach($jsonProxJornada as $valor) {     
                $idPartido=$valor->id;
                $mapApostantesPartidos[$valor->apostante] =substr($valor->hora,0,5).' '.$valor->local_c.' vs '.$valor->visitante_c;
            }
            
            $emoji_pointing= Utils::convert_emoji(0x1F449);
        	$emoji_r_arrow= Utils::convert_emoji(0x27A1);
            $text='*Faltan por apostar:*'.PHP_EOL;
            foreach($arrayApostantes as $apostante){
                if (!in_array($apostante,$arrayApostantesYaApostados)){
                    $text=$text.$emoji_pointing.$apostante . ' ' .$emoji_r_arrow. ' ' .$mapApostantesPartidos[$apostante].PHP_EOL;
                }
            }
        
            $text=$text.'Apostad ya '.Utils::getInsultoPlural();
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            return $response;
        }
        
        private function web($endpoint, $request){
        	$text=Utils::get_url_web($request);
        	return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
    }
?>
