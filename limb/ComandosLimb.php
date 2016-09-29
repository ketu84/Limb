<?php
    class ComandosLimb{
        
        private $log;
        
        static function ejecutar($func,$endpoint, $request){
            
            //Si el usuario o grupo no está configurado para Limb, se sale de estos comandos
            $urlApi=Utils::get_url_api($request);
            if(is_null($urlApi)){
                return null;
            }
            
            if(method_exists('ComandosLimb',$func)){
                $command = new ComandosLimb();
                
                $grupo = '';
                
                $params = $request->get_command_params();
                if(count($params)==0){
                    $grupoAux = Utils::get_grupo($endpoint, $request, $func);
                    if($grupoAux instanceof Response)   {
                        return $grupoAux;
                    }else{
                        $grupo=$grupoAux;
                    }
                    
                }else{
                    $grupo=$params[0];
                }
                
                if($grupo=='ChampionsLimb'){
                    $urlApi = CHAMPIONSLIMB_URL_API;
                }else{
                    $urlApi = GUSLIMB_URL_API;
                }
                
                return $command->$func($endpoint, $request, $urlApi);
            }
            return null;
        }
        
        
        function __construct() {
            $this->log = Logger::getLogger('com.hotelpene.limbBot.ComandosLimb');
        }
        
        
        private function clasificacion($endpoint, $request,$urlApi){
            $this->log->debug("Obteniedo clasificacion");
            $time = microtime(true);
            
            $response_chat_typing = Response::create_typing_response($endpoint, $request->get_chat_id());
            $response_chat_typing->send();
            
            $text='*Clasificación de la última fase en curso:*'.PHP_EOL.PHP_EOL;
            
            //Se obtiene la fase actual
            $jsonFaseActual = Utils::callApi($request, 'util/faseActual', $urlApi);
            $faseActual = json_decode($jsonFaseActual);
            
            $url='clasificacion/'.$faseActual->id;
            
            //Se comprueba si es un chat privado, para obtener el token del usuario
            if($request->is_private_chat()){
                $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
                $tokenUsuario = json_decode($jsonTokenUser, true);
                //var_dump($tokenUsuario);
                //Si hay token de usuario del chat, se invoca el comando con el token
               // $objeto = $tokenUsuario[0];
                if($tokenUsuario[0]['token']){
                    $url='clasificacion/'.$faseActual->id.'?token='.$tokenUsuario[0]['token'];
                }
            }
                        

            $json = Utils::callApi($request, $url, $urlApi);
            $obj = json_decode($json);

            if(sizeof($obj)>0 && property_exists($obj[0],'error')){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
                $response->text=$obj[0]->error->text;
                $response->markdown=true;
                return $response;
            }
            
            
            $emoji_down= Utils::convert_emoji(0x2B06);
            $emoji_up= Utils::convert_emoji(0x2B07);
            $emoji_balon= Utils::convert_emoji(0x26BD);
            $emoji_dinero= Utils::convert_emoji(0x1F4B0);
            $emoji_yield= Utils::convert_emoji(0x1F4A5);
            
            $i=1;
            foreach($obj as $valor) {
                $jugado = 0 + floatval($valor->jugado);
                $ganado = 0 + floatval($valor->ganancia);
                $yield = ($ganado/$jugado)*100;
            	$text=$text.'*'.$i.'.'.$valor->nombre.'*'.$emoji_dinero.number_format((float)$valor->ganancia,2).'€'.$emoji_yield.round($yield,2).'%'.$emoji_balon.$valor->num_partidos.PHP_EOL;
            	$i++;
            }
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            
            $this->log->debug("Fin Obteniedo Clasificación (".(microtime(true)-$time)." s): ");
            return $response;
        }
        
        private function clasificacionJornada($endpoint, $request,$urlApi){
            $this->log->debug("Obteniedo clasificacion de jornada");
            $time = microtime(true);
            
            $response_chat_typing = Response::create_typing_response($endpoint, $request->get_chat_id());
            $response_chat_typing->send();
            
            $text='*Clasificación de la última Jornada en curso:*'.PHP_EOL.PHP_EOL;
            
            //Se obtiene la fase actual
            $jsonFaseActual = Utils::callApi($request, 'util/faseActual', $urlApi);
            $faseActual = json_decode($jsonFaseActual);
            
            $url='clasificacionfasetipo/'.$faseActual->id.'/tipofase/'.$faseActual->tipo->id;
            
            //Se comprueba si es un chat privado, para obtener el token del usuario
            if($request->is_private_chat()){
                $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
                $tokenUsuario = json_decode($jsonTokenUser, true);
                //var_dump($tokenUsuario);
                //Si hay token de usuario del chat, se invoca el comando con el token
               // $objeto = $tokenUsuario[0];
                if($tokenUsuario[0]['token']){
                    $url.='?token='.$tokenUsuario[0]['token'];
                }
            }
                        

            $json = Utils::callApi($request, $url, $urlApi);
            $obj = json_decode($json);

            if(sizeof($obj)>0 && property_exists($obj[0],'error')){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
                $response->text=$obj[0]->error->text;
                $response->markdown=true;
                return $response;
            }
            
            
            $emoji_down= Utils::convert_emoji(0x2B06);
            $emoji_up= Utils::convert_emoji(0x2B07);
            $emoji_balon= Utils::convert_emoji(0x26BD);
            $emoji_dinero= Utils::convert_emoji(0x1F4B0);
            $emoji_yield= Utils::convert_emoji(0x1F4A5);
            
            $i=1;
            foreach($obj as $valor) {
                $jugado = 0 + floatval($valor->jugado);
                $ganado = 0 + floatval($valor->ganancia);
                $yield = ($ganado/$jugado)*100;
            	$text=$text.'*'.$i.'.'.$valor->nombre.'*'.$emoji_dinero.number_format((float)$valor->ganancia,2).'€'.$emoji_yield.round($yield,2).'%'.$emoji_balon.$valor->num_partidos.PHP_EOL;
            	$i++;
            }
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            
            $this->log->debug("Fin Obteniedo Clasificación fase y jornada (".(microtime(true)-$time)." s): ");
            return $response;
        }
        
        private function prox_jornada($endpoint, $request, $urlApi){
            $this->log->debug("Obteniedo Próxima jornada");
            $time = microtime(true);
            
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            //Se obtiene la fecha del proximo partido
            $jsonFecha = Utils::callApi($request,'util/fechaProxPartido', $urlApi);
            $fecha = json_decode($jsonFecha);
            
            $json = Utils::callApi($request, 'partidos/fecha/'.$fecha->fecha, $urlApi);
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
                    $text=$text.substr($valor->hora,0,5).' '.$valor->local->nombre_corto.' vs '.$valor->visitante->nombre_corto;
                    if($apostantes!=null){
                        $text.='=>'.$apostantes;   
                    }
            }
        
            if($fecha==null){
                $text='*No hay próxima jornada* '.PHP_EOL;
            }else{
                $fecha= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
                $text='*Próxima jornada '.$fecha.':* '.PHP_EOL.$text;
            }
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            
            $this->log->debug("Fin Obteniedo Próxima jornada (".(microtime(true)-$time)." s): ");
            return $response;
        }
        
        private function apuestas($endpoint, $request, $urlApi){
            $this->log->debug("Obteniedo apuestas");
            $time = microtime(true);
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            //Se obtiene la fecha del proximo partido
            $jsonFecha = Utils::callApi($request, 'util/fechaProxPartido/', $urlApi);
            $fecha = json_decode($jsonFecha);
            
            
            //Se obtienen los partidos de HOY. Si no hay, se obtienen los de la próxima jornada
            $fechaHoy = date('Y-m-d');
            $jsonPartidos = Utils::callApi($request, 'partidos/fecha/'.$fechaHoy, $urlApi);
            $partidos = json_decode($jsonPartidos);

            if(sizeof($partidos)==0){
                //Se obtiene la fecha del proximo partido
                $jsonFecha = Utils::callApi($request, 'util/fechaProxPartido/', $urlApi);
                $fecha = json_decode($jsonFecha);
                $jsonPartidos = Utils::callApi($request, 'partidos/fecha/'.$fecha->fecha, $urlApi);
                $partidos = json_decode($jsonPartidos);

            }
            
            
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
            
        
            $finUrl='';
            
            //Se comprueba si es un chat privado, para obtener el token del usuario
            if($request->is_private_chat()){
                $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
                $tokenUsuario = json_decode($jsonTokenUser, true);
                //var_dump($tokenUsuario);
                //Si hay token de usuario del chat, se invoca el comando con el token
                if($tokenUsuario[0]['token']){
                    $finUrl='?token='.$tokenUsuario[0]['token'];
                }
            }
        
            foreach($partidos as $partido) {
                //apuestas/partido/104
                $text.=PHP_EOL.$emoji_star.$partido->local->nombre_corto.' vs '.$partido->visitante->nombre_corto.$emoji_star.PHP_EOL;
                
                $jsonApuestas = Utils::callApi($request, 'apuestas/partido/'.$partido->id.$finUrl, $urlApi);
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
                    
                    $text.=$iconoApuesta.$apuesta->desc.':'.$apuesta->importe;
                        if($apuesta->cuota==0){
                            $text.='€'.PHP_EOL;
                        }else{
                            $text.='@'.$apuesta->cuota.PHP_EOL;
                        }
                }
            }
        
            if(sizeof($partidos)>0){
                $fechaTit= substr($fecha->fecha,8,2).'/'.substr($fecha->fecha,5,2).'/'.substr($fecha->fecha,0,4);
                //$fechaTit= $fecha->fecha;
                $text='*Apuestas '.$fechaTit.': *'.PHP_EOL.$text;
            }else{
                $text='*No hay próximos partidos*'.PHP_EOL;
            }
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            
            $this->log->debug("Fin Obteniedo apuestas (".(microtime(true)-$time)." s): ");
            return $response;
        }
        
        private function euros($endpoint, $request, $urlApi){
            $this->log->debug("Obteniedo euros");
            $time = microtime(true);
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            $text='*Euros:*'.PHP_EOL;
        
        
            $url='';
             //Se comprueba si es un chat privado, para obtener el token del usuario
            if($request->is_private_chat()){
                $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
                $tokenUsuario = json_decode($jsonTokenUser, true);
                //var_dump($tokenUsuario);
                //Si hay token de usuario del chat, se invoca el comando con el token
                if($tokenUsuario[0]['token']){
                    $url='?token='.$tokenUsuario[0]['token'];
                }
            }
        
            $json = Utils::callApi($request, 'util/euros'.$url, $urlApi);
            $obj = json_decode($json);
            
            if(is_array($obj) && property_exists($obj[0],'error')){
                $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
                $response->text=$obj[0]->error->text;
                $response->markdown=true;
                return $response;
            }
            $jugado = 0 + floatval($obj->jugado);
            $ganado = 0 + floatval($obj->ganancia);
            if($jugado==0){
                $yield=0;
            }else{
                $yield = ($ganado/$jugado)*100;
            }
            $text.='Apostado: '.round($jugado,2).'€'.PHP_EOL;
            $text.='Ganado: '.round($ganado,2).'€'.PHP_EOL;
            $text.='Yield: '.round($yield,2).'%'.PHP_EOL;
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            
            $this->log->debug("Fin Obteniedo euros (".(microtime(true)-$time)." s): ");
            return $response;
        }
		
		private function resultados($endpoint, $request, $urlApi){
            $this->log->debug("Resultados");
            $time = microtime(true);
		
			$response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
            
            $params = $request->get_command_params();
            $numparams = count($params);
			
			$fecha = ($numparams == 0) ? date('Y-m-d') : date('Y-m-d', strtotime($params[0]));
			$fechaFormateada = date('d/m/Y', strtotime($fecha));
			$text='*Resultados ('.$fechaFormateada.'):*'.PHP_EOL;
			$apiurl = 'http://api.football-data.org/v1/competitions/440/fixtures';
			$content = file_get_contents($apiurl);
			$json = json_decode($content, true);
			foreach($json['fixtures'] as $item) 
			{
				if(strpos($item['date'],$fecha) !== false)
				{
					$estado = $item['status'];
					switch($estado) {
						case "IN_PLAY":
							$text.=$item['homeTeamName'].' '.$item['result']['goalsHomeTeam'].' - '.$item['result']['goalsAwayTeam'].' '.$item['awayTeamName'].' (En juego)'.PHP_EOL;
							break;
						case "TIMED":
							$text.=$item['homeTeamName'].' - '.$item['awayTeamName'].PHP_EOL;
							break;
						case "FINISHED":
							$text.=$item['homeTeamName'].' '.$item['result']['goalsHomeTeam'].' - '.$item['result']['goalsAwayTeam'].' '.$item['awayTeamName'].PHP_EOL;
							break;
					}
				}
			}
			$response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
	
		    $this->log->debug("Fin Resultados (".(microtime(true)-$time)." s): ");
            return $response;
		}
        
        private function apostadYa($endpoint, $request, $urlApi){
            $this->log->debug("Obteniedo apostadYa");
            $time = microtime(true);
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
        
            $emoji_pointing= Utils::convert_emoji(0x1F449);
        	$emoji_r_arrow= Utils::convert_emoji(0x27A1);
        
            //Se obtiene la fase actual
            $jsonFaseActual = Utils::callApi($request, 'util/faseActual', $urlApi);
            $faseActual = json_decode($jsonFaseActual);
            $max_apostable = floatval($faseActual->importe);
            
            //Se obtiene la fecha del proximo partido
            $jsonFecha = Utils::callApi($request, 'util/fechaProxPartido/', $urlApi);
            $fecha = json_decode($jsonFecha);
            
            //Partidos de esa fecha
            $jsonPartidos = Utils::callApi($request, 'partidos/fecha/'.$fecha->fecha, $urlApi);
            $partidos = json_decode($jsonPartidos);

            //$text='*Faltan por apostar:*'.PHP_EOL;
            setlocale(LC_ALL,"es_ES");
            $text =  '*'.strftime("%d %b",strtotime($fecha->fecha)).' - Faltan por apostar:*'.PHP_EOL.PHP_EOL;
            
            $insultar=false;
            foreach($partidos as $partido){
                
                //Apostado en ese partido
                $jsonApostantes = Utils::callApi($request, '/util/apostadoApostantePartido/'.$partido->id, $urlApi);
                $apostantes = json_decode($jsonApostantes);
                
                $arrApostantes = $partido->usuarios;
                
                foreach($apostantes as $apostante){
                    if($apostante->apostado==$max_apostable){
                        $i=0;
                        foreach($arrApostantes as $apost){        
                            if($apost->id ==$apostante->idapostante){
                                array_splice($arrApostantes,$i,1);
                                continue;
                            }
                            $i++;
                        }
                    }
                }
                
                if(sizeof($arrApostantes)>0){
                     $text.=' *'.$partido->local->nombre_corto.' vs '.$partido->visitante->nombre_corto.'* '.substr($partido->hora,0,5).PHP_EOL;
                    $insultar=true;
                    foreach($arrApostantes as $apost){  
                        $text.=$emoji_pointing.$apost->nombre . PHP_EOL;
                    }
                }
            }
            if($insultar){
                $text.=PHP_EOL.'Apostad ya '.Utils::getInsultoPlural();
            }else{
                $text.="Han apostado todos". PHP_EOL;
            }
            
            $response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            
            $this->log->debug("Fin Obteniedo apostadYa (".(microtime(true)-$time)." s): ");
            return $response;
        }
        
        private function web($endpoint, $request){
            
            $grupo = '';
            
            $params = $request->get_command_params();
            if(count($params)==0){
                $grupoAux = Utils::get_grupo($endpoint, $request, 'web');
                if($grupoAux instanceof Response)   {
                    return $grupoAux;
                }else{
                    $grupo=$grupoAux;
                }
                
            }else{
                $grupo=$params[0];
            }
            
            if($grupo=='ChampionsLimb'){
                $text = CHAMPIONSLIMB_URL;
            }else{
                $text = GUSLIMB_URL;
            }
            
            $object = new stdClass();
            $object->hide_keyboard =true;
            return Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), $text, json_encode($object));
            
        }
        
        private function mispartidos($endpoint, $request, $urlApi){
            
            //Se comprueba si es un chat privado, para obtener el token del usuario
            if($request->is_private_chat()){
                $text = self::sendMisPartidos($request, $urlApi);
            }else{
                $text = 'Esto solo se puede usar en privado, motherfucker!!';
            }
            
            if($text==''){
                $text='No tienes partidos pendientes';
            }
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        
        private function sendMisPartidos($request, $urlApi){
            $text='';
            
            $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
            $tokenUsuario = json_decode($jsonTokenUser, true);

            //Si hay token de usuario del chat, se invoca el comando con el token
            if($tokenUsuario[0]['token']){
                $idUsuario = $tokenUsuario[0]['id'];
                
                $finUrl='?token='.$tokenUsuario[0]['token'];
                $jsonPartidos = Utils::callApi($request, 'partidos/usuario/'.$tokenUsuario[0]['token'], $urlApi);
                $partidos = json_decode($jsonPartidos);
                
                $fechaaux='';
                
                $emoji_star= Utils::convert_emoji(0x1F538);
                $emoji_guion= Utils::convert_emoji(0x2796);
                $emoji_cara=Utils::convert_emoji(0x1F633);
                $emoji_ok=Utils::convert_emoji(0x2705);
                $emoji_mal=Utils::convert_emoji(0x274C);
                $emoji_tijeras=Utils::convert_emoji(0x2702);
                $emoji_cerdo=Utils::convert_emoji(0x1F416);
            
                foreach($partidos as $part){  
                    setlocale(LC_ALL,"es_ES");
                    $fecha = strftime("%d %b %Y",strtotime($part->fecha));
                    if($fecha != $fechaaux){
                        $text.=PHP_EOL.'`      '.$fecha.' `'.PHP_EOL;
                        $fechaaux=$fecha;
                    }
                    $text.='*'.substr($part->hora,0,5).': '.$part->local->nombre_corto.' vs '.$part->visitante->nombre_corto.'*'.PHP_EOL;
                    
                    
                    /*Se obtienen las apuestas*/
                    $jsonApuestasPartidos = Utils::callApi($request, 'apuestas/partido/'.$part->id.'/'.$idUsuario.$finUrl, $urlApi);
                    $apuestas = json_decode($jsonApuestasPartidos);
                    
                    foreach($apuestas as $apuesta) {

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
                        
                        $text.=$iconoApuesta.$apuesta->desc.':'.$apuesta->importe;
                        if($apuesta->cuota==0){
                            $text.='€'.PHP_EOL;
                        }else{
                            $text.='@'.$apuesta->cuota.PHP_EOL;
                        }
                    }
                    
                }
            }
            return $text;
        }
        
        private function apostar($endpoint, $request, $urlApi){
            //Se comprueba si es un chat privado, para obtener el token del usuario
            if($request->is_private_chat()){

                $params = $request->get_command_params();
                switch (count($params)) {
                    case 1: //Hay Grupo
                        return self::preguntarPartido($endpoint, $request, $urlApi);
                        break;
                    case 2: //Hay partido
                        self::preguntarImporte($endpoint, $request, $urlApi);
                        break;
                    case 3: //Hay importe
                        break;
                }



                
                
            }else{
                $text = 'Esto solo se puede usar en privado, nigga!!';
            }
            
            if($text==''){
                $text='No tienes partidos pendientes';
            }
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        
        private function preguntarPartido($endpoint, $request, $urlApi){
            $this->log->debug("preguntar partidos ");
            $params = $request->get_command_params();
            $text='';
            
            $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
            $tokenUsuario = json_decode($jsonTokenUser, true);

            //Si hay token de usuario del chat, se invoca el comando con el token
            if($tokenUsuario[0]['token']){
                $finUrl='?token='.$tokenUsuario[0]['token'];
                $jsonPartidos = Utils::callApi($request, 'partidos/usuario/'.$tokenUsuario[0]['token'], $urlApi);
                $partidos = json_decode($jsonPartidos);
                $text='¿A que partido quieres apostar?'.PHP_EOL.PHP_EOL;
                $fechaaux='';
                $arr = Array();
                foreach($partidos as $part){  
                    setlocale(LC_ALL,"es_ES");
                    $fecha = strftime("%d %b %Y",strtotime($part->fecha));
                    if($fecha != $fechaaux){
                        $text.='*'.$fecha.'*'.PHP_EOL;
                        $fechaaux=$fecha;
                    }
                    $text.='*     '.substr($part->hora,0,5).': *'.$part->local->nombre_corto.' vs '.$part->visitante->nombre_corto.PHP_EOL;
                    
                    $InlineKeyboardButton=new stdClass();
                    $InlineKeyboardButton->text=substr($part->hora,0,5).': '.$part->local->nombre_corto.' vs '.$part->visitante->nombre_corto;
                    $InlineKeyboardButton->callback_data='/apostar '.$params[0].' '.$part->id;
                    
                    array_push($arr, $InlineKeyboardButton);
                    
                }
                $inline_keyboard = new stdClass();
                $inline_keyboard->inline_keyboard = [$arr];
                
                return Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), $text, json_encode($inline_keyboard));
            }
        }
        
        private function preguntarImporte($endpoint, $request, $urlApi){
            $this->log->debug("preguntar importe ");
        }
    }
?>
