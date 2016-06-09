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
            
            //Se obtiene la fase actual
            $jsonFaseActual = file_get_contents($urlApi . 'util/faseActual');
            $faseActual = json_decode($jsonFaseActual);
            
            $json = file_get_contents($urlApi . 'clasificacion'.$faseActual[0]->id);
            $obj = json_decode($json);

            if(sizeof($obj)>0 && property_exists($obj[0],'error')){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
                $response->text=$obj[0]->error->text;
                $response->markdown=true;
                return $response;
            }
            
            
            $emoji_down= Utils::convert_emoji(0x2B06);
            $emoji_up= Utils::convert_emoji(0x2B07);
            
            $i=1;
            foreach($obj as $valor) {
            	$text=$text.'*'.$i.'*.- '.$valor->nombre.': '.number_format((float)$valor->ganancia,2).'€ '.PHP_EOL;
            	$i++;
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
            
            //Se obtiene la fecha del proximo partido
            $jsonFecha = file_get_contents($urlApi . 'util/fechaProxPartido/');
            $fecha = json_decode($jsonFecha);
            
            $json = file_get_contents($urlApi . 'partidos/fecha/'.$fecha->fecha);
            $obj = json_decode($json);
            
            $text='';
            $fecha='';
            
            foreach($obj as $valor) {
                $fecha=$valor->fecha;
                    $text=$text.PHP_EOL;
                    $idPartido=$valor->id;
                    //Apostantes
                    $apostantes='';
                    $idx = 0;
                    foreach($valor->usuarios as $usu) {
                        if($idx>0){
                            $apostantes.=', '.$usu->nombre;
                        }else{
                            $apostantes.=$usu->nombre;
                        }
                        $idx++;
                    }
                    $text=$text.substr($valor->hora,0,5).' '.$valor->local->nombre_corto.' vs '.$valor->visitante->nombre_corto.'=>'.$apostantes;
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
            
            //Se obtiene la fecha del proximo partido
            $jsonFecha = file_get_contents($urlApi . 'util/fechaProxPartido/');
            $fecha = json_decode($jsonFecha);
            
            $jsonPartidos = file_get_contents($urlApi . 'partidos/fecha/'.$fecha->fecha);
            $partidos = json_decode($jsonPartidos);
            
            
            $text='';
            $idPartido=-1;
            $apostante='';
        
            $emoji_star= Utils::convert_emoji(0x1F538);
            $emoji_guion= Utils::convert_emoji(0x2796);
            $emoji_cara=Utils::convert_emoji(0x1F633);
            $emoji_ok=Utils::convert_emoji(0x2705);
            $emoji_mal=Utils::convert_emoji(0x274C);
            $emoji_tijeras=Utils::convert_emoji(0x2702);
            $emoji_cerdo=Utils::convert_emoji(0x1F416);
            
        
            foreach($partidos as $partido) {
                //apuestas/partido/104
                $text.=PHP_EOL.$emoji_star.$partido->local->nombre_corto.' vs '.$partido->visitante->nombre_corto.$emoji_star.PHP_EOL;
                
                $jsonApuestas = file_get_contents($urlApi . 'apuestas/partido/'.$partido->id);
                $apuestas = json_decode($jsonApuestas);
                foreach($apuestas as $apuesta) {
                    
                    if($apostante!=$apuesta->apostante->id){
                        $text.=$emoji_cara.$apuesta->apostante->nombre.PHP_EOL;
                        $apostante=$apuesta->apostante->id;
                    }
                    
                    $iconoApuesta=$emoji_guion;
                    //1 acertada
                    if($apuesta->acertada=="1"){
                        $iconoApuesta=$iconoApuesta.$emoji_ok;
                    }else if($apuesta->acertada=="2"){
                        $iconoApuesta=$iconoApuesta.$emoji_mal;
                    }else if($apuesta->acertada=="4"){
                        $iconoApuesta=$iconoApuesta.$emoji_tijeras;
                    }else if($apuesta->acertada=="3"){
                        $iconoApuesta=$iconoApuesta.$emoji_cerdo;
                    }
                    $text.=$iconoApuesta.$apuesta->desc.':'.$apuesta->importe.'@'.$apuesta->cuota.PHP_EOL;
                }
            }
        
            $fechaTit= substr($fecha->fecha,8,2).'/'.substr($fecha->fecha,5,2).'/'.substr($fecha->fecha,0,4);
            //$fechaTit= $fecha->fecha;
            $text='*Apuestas '.$fechaTit.': *'.PHP_EOL.PHP_EOL.$text;

            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            return $response;
        }
        
        private function euros($endpoint, $request){
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            $text='*Euros:*'.PHP_EOL;
        
            $urlApi=Utils::get_url_api($request);
            $json = file_get_contents($urlApi . 'util/euros');
            $obj = json_decode($json);
            
            
            if(is_array($obj) && property_exists($obj[0],'error')){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
                $response->text=$obj[0]->error->text;
                $response->markdown=true;
                return $response;
            }

            $text.='Apostado: '.$obj->jugado.'€'.PHP_EOL;
            $text.='Ganado: '.$obj->ganancia.'€'.PHP_EOL;
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            return $response;
        }
        
        private function apostadYa($endpoint, $request){
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            $emoji_pointing= Utils::convert_emoji(0x1F449);
        	$emoji_r_arrow= Utils::convert_emoji(0x27A1);
        
            $urlApi=Utils::get_url_api($request);
            //Se obtiene la fase actual
            $jsonFaseActual = file_get_contents($urlApi . 'util/faseActual');
            $faseActual = json_decode($jsonFaseActual);
            $max_apostable = $faseActual[0]->importe;
            
            
            //Se obtiene la fecha del proximo partido
            $jsonFecha = file_get_contents($urlApi . 'util/fechaProxPartido/');
            $fecha = json_decode($jsonFecha);
            //Partidos de esa fecha
            $jsonPartidos = file_get_contents($urlApi . 'partidos/fecha/'.$fecha->fecha);
            $partidos = json_decode($jsonPartidos);
            
            $text='*Faltan por apostar:*'.PHP_EOL;
            foreach($partidos as $partido){
                $text.=PHP_EOL.substr($partido->fecha,8,2).'/'.substr($partido->fecha,5,2).'/'.substr($partido->fecha,0,4).' *'.$partido->local->nombre_corto.' vs '.$partido->visitante->nombre_corto.'* '.substr($partido->hora,0,5).PHP_EOL;
                
                //Apostado en ese partido
                $jsonApostantes = file_get_contents($urlApi . '/util/apostadoApostantePartido/'.$partido->id);
                $apostantes = json_decode($jsonApostantes);
                 
                foreach($apostantes as $apostante){
                    if($apostante->apostado<$max_apostable){
                         $text.=$emoji_pointing.$apostante->nombre . PHP_EOL;
                    }
                }
            }
        
            $text.='Apostad ya '.Utils::getInsultoPlural();
            
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
