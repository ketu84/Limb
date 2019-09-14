<?php
    class Utils{
        
        static function convert_emoji($utf8){
            return iconv('UCS-4LE', 'UTF-8', pack('V', $utf8));
        }
        
        static function quien_ha_perdido_mas($endpoint, $request,$urlApi){
           
            switch ($request->get_from_id()) {
                case ID_AGE:
                case ID_TAPIA:
                case ID_NANO:
                case ID_YONI:
                case ID_CAS: 
                case ID_JAVI:
                case ID_KETU:
                case ID_PACO:
                case ID_BARTOL:
                case ID_VICENTE:
                case ID_IBAN:
                case ID_ZATO:
                case ID_RULO:
                case ID_MATUTE:
                case ID_LUCHO:
                case ID_BORJA:
                case ID_JON:
                case ID_FILETE:
                    $time = microtime(true);
                    
                    $response_chat_typing = Response::create_typing_response($endpoint, $request->get_chat_id());
                    $response_chat_typing->send();
                    
                    //Se obtiene la fase actual
                    $jsonFaseActual = Utils::callApi($request, 'util/faseActual', $urlApi);
                    $faseActual = json_decode($jsonFaseActual);
                    
                    $url='clasificacion/'.$faseActual->id;
                    
                    //Se comprueba si es un chat privado, para obtener el token del usuario
                    if($request->is_private_chat()){
                        $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
                        $tokenUsuario = json_decode($jsonTokenUser, true);
                        //Si hay token de usuario del chat, se invoca el comando con el token
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
                    
                    $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT, $urlApi);
                    $tokenUsuario = json_decode($jsonTokenUser, true);
                    
                    $nombreUsuario='';
                    
                    if($tokenUsuario[0]['nombre']){
                        $nombreUsuario=$tokenUsuario[0]['nombre'];
                    }
                    
                    $i=1;
                    $ganadoUsuario = 0;
                    $ganadoCocacolas = 0;
                    foreach($obj as $valor) {
                        if($valor->nombre=='Rio'){
                            $ganadoCocacolas = floatval($valor->ganancia);
                        }else if($valor->nombre==$nombreUsuario){
                            $ganadoUsuario = floatval($valor->ganancia);
                        }
                        $i++;
                    }
                    
                    if($ganadoCocacolas > $ganadoUsuario){
                        return 1;
                    }elseif($ganadoCocacolas < $ganadoUsuario){
                        return 0;
                    }elseif($ganadoCocacolas == $ganadoUsuario){
                        return 5;
                    }
                        
                    break;
                case ID_RIOJANO:
                    return 2;
                    
                default:
                    return 4;
                    
            }
        }
        
        static function aleatorio($elementos){
	        return $elementos[rand(0,count($elementos)-1)];
        }
        
        static function getInsultoSingular(){
            return self::aleatorio(
                array(
                    '¿Eres idiota?', 
                    '¿Eres bobo?', 
                    '¿Eres falto?', 
                    '¿Eres imbécil?', 
                    'Cómeme un huevo', 
                    '¿Estás beodo?', 
                    'Mierdaseca', 
                    'Hijo de puta',
                    'Gilipipas',
                    'Mascachapas',
                    'Soplanucas',
                    'Muerdealmohadas',
                    'Sodomita',
                    'Aborto',
                    'Anormal',
                    '¿Pero tú sabes quien es tu padre?',
                    'Abanto',
                    'Abrazafarolas',
                    'Adufe',
                    'Alcornoque',
                    'Alfeñique',
                    'Andurriasmo',
                    'Arrastracueros',
                    'Artabán',
                    'Atarre',
                    'Baboso',
                    'Barrabás',
                    'Barriobajero',
                    'Bebecharcos',
                    'Bellaco',
                    'Belloto',
                    'Berzotas',
                    'Besugo',
                    'Bobalicón',
                    'Bocabuzón',
                    'Bocachancla',
                    'Bocallanta',
                    'Boquimuelle',
                    'Borrico',
                    'Botarate',
                    'Brasas',
                    'Cabestro',
                    'Cabezaalberca',
                    'Cabezabuque',
                    'Cachibache',
                    'Cafre',
                    'Cagalindes',
                    'Cagarruta',
                    'Calambuco',
                    'Calamidad',
                    'Caldúo',
                    'Calientahielos',
                    'Calzamonas',
                    'Cansalmas',
                    'Cantamañanas',
                    'Capullo',
                    'Caracaballo',
                    'Caracartón',
                    'Caraculo',
                    'Caraflema',
                    'Carajaula',
                    'Carajote',
                    'Carapapa',
                    'Carapijo',
                    'Cazurro',
                    'Cebollino',
                    'Cenizo',
                    'Cenutrio',
                    'Ceporro',
                    'Cernícalo',
                    'Charrán',
                    'Chiquilicuatre',
                    'Chirimbaina',
                    'Chupacables',
                    'Chupasangre',
                    'Chupóptero',
                    'Cierrabares',
                    'Cipote',
                    'Comebolsas',
                    'Comechapas',
                    'Comeflores',
                    'Comestacas',
                    'Cretino',
                    'Cuerpoescombro',
                    'Culopollo',
                    'Descerebrado',
                    'Desgarracalzas',
                    'Dondiego',
                    'Donnadie',
                    'Echacantos',
                    'Ejarramantas',
                    'Energúmeno',
                    'Esbaratabailes',
                    'Escolimoso',
                    'Escornacabras',
                    'Estulto',
                    'Fanfosquero',
                    'Fantoche',
                    'Fariseo',
                    'Filimincias',
                    'Foligoso',
                    'Fulastre',
                    'Ganapán',
                    'Ganapio',
                    'Gandúl',
                    'Gañán',
                    'Gaznápiro',
                    'Gilipuertas',
                    'Giraesquinas',
                    'Gorrino',
                    'Gorrumino',
                    'Guitarro',
                    'Gurriato',
                    'Habahelá',
                    'Huelegateras',
                    'Huevón',
                    'Lamecharcos',
                    'Lameculos',
                    'Lameplatos',
                    'Lechuguino',
                    'Lerdo',
                    'Letrín',
                    'Lloramigas',
                    'Longanizas',
                    'Lumbreras',
                    'Maganto',
                    'Majadero',
                    'Malasangre',
                    'Malasombra',
                    'Malparido',
                    'Mameluco',
                    'Mamporrero',
                    'Manegueta',
                    'Mangarrán',
                    'Mangurrián',
                    'Mastuerzo',
                    'Matacandiles',
                    'Meapilas',
                    'Melón',
                    'Mendrugo',
                    'Mentecato',
                    'Mequetrefe',
                    'Merluzo',
                    'Metemuertos',
                    'Metijaco',
                    'Mindundi',
                    'Morlaco',
                    'Morroestufa',
                    'Muerdesartenes',
                    'Orate',
                    'Ovejo',
                    'Pagafantas',
                    'Palurdo',
                    'Pamplinas',
                    'Panarra',
                    'Panoli',
                    'Papafrita',
                    'Papanatas',
                    'Papirote',
                    'Paquete',
                    'Pardillo',
                    'Parguela',
                    'Pasmarote',
                    'Pasmasuegras',
                    'Pataliebre',
                    'Patán',
                    'Pavitonto',
                    'Pazguato',
                    'Pecholata',
                    'Pedorro',
                    'Peinabombillas',
                    'Peinaovejas',
                    'Pelagallos',
                    'Pelagambas',
                    'Pelagatos',
                    'Pelatigres',
                    'Pelazarzas',
                    'Pelele',
                    'Pelma',
                    'Percebe',
                    'Perrocostra',
                    'Perroflauta',
                    'Peterete',
                    'Petimetre',
                    'Picapleitos',
                    'Pichabrava',
                    'Pillavispas',
                    'Piltrafa',
                    'Pinchauvas',
                    'Pintamonas',
                    'Piojoso',
                    'Pitañoso',
                    'Pitofloro',
                    'Plomo',
                    'Pocasluces',
                    'Pollopera',
                    'Quitahipos',
                    'Rastrapajo',
                    'Rebañasandías',
                    'Revientabaules',
                    'Ríeleches',
                    'Robaperas',
                    'Sabandija',
                    'Sacamuelas',
                    'Sanguijuela',
                    'Sinentraero',
                    'Sinsustancia',
                    'Sonajas',
                    'Sonso',
                    'Soplagaitas',
                    'Soplaguindas',
                    'Sosco',
                    'Tagarote',
                    'Tarado',
                    'Tarugo',
                    'Tiralevitas',
                    'Tocapelotas',
                    'Tocho',
                    'Tolai',
                    'Tontaco',
                    'Tontucio',
                    'Tordo',
                    'Tragaldabas',
                    'Tuercebotas',
                    'Tunante',
                    'Zamacuco',
                    'Zambombo',
                    'Zampabollos',
                    'Zamugo',
                    'Zángano',
                    'Zarrapastroso',
                    'Zascandil',
                    'Zopenco',
                    'Zoquete',
                    'Zote',
                    'Zullenco',
                    'Zurcefrenillos'
                ));
        }
        
        static function getInsultoPlural(){
            return self::aleatorio(
                array(
                    'idiotas', 
    				'lamenalgas', 
    				'chupaculos', 
    				'hijos de perra', 
    				'bobos', 
    				'cabrones', 
    				'memos', 
    				'imbéciles', 
    				'comehuevos', 
    				'papanatas', 
    				'mentecatos', 
    				'podemitas',
    				'parguelas', 
    				'mierdasecas', 
    				'malnacidos',
    				'borbones',
	    			'retardados',
		    		'hijos de mil putas sifilíticas'));
        }
        
        static function get_humano_name($humanoId){
            switch ($humanoId) {
            	case ID_AGE:
            		$humano = self::aleatorio(array('Ángel', 'Caballero', 'Ario', 'Veleta'));
            		break;
            	case ID_TAPIA:
            		$humano = self::aleatorio(array('Antonio', 'Tapia','Oso'));
            		break;
            	case ID_NANO:
            		$humano=self::aleatorio(array('Nano','Gnomo', 'Reducto'));
            		break;
            	case ID_YONI:
            		$humano = self::aleatorio(array('Yoni', 'Ori'));
            		break;
            	case ID_CAS: 
            		$humano= self::aleatorio(array('Cas', 'Castaña'));
            		break;
            	case ID_JAVI:
            		$humano = self::aleatorio(array('Javi', 'Carracedo', 'Fascista'));
            		break;
            	case ID_KETU:
            		$humano= self::aleatorio(array('Ketu', 'Manuel David'));
            		break;
            	case ID_PACO:
            		$humano = self::aleatorio(array('Paco', 'Ake'));
            		break;
            	case ID_RIOJANO:
            		$humano= self::aleatorio(array('Riojano', 'Gelete', 'Almendro'));
            		break;
            	case ID_BARTOL:
            		$humano = self::aleatorio(array('Luis', 'Bartol'));
            		break;
            	case ID_VICENTE:
            		$humano= self::aleatorio(array('Vicente', 'Comandante'));
            		break;
        	case ID_IBAN:
        		$humano= self::aleatorio(array('Ibán'));
            		break;
            	case ID_ZATO:
            		$humano=self::aleatorio(array('Álvaro', 'Zato', 'Bárbol'));
            		break;
            	case ID_RULO:
            		$humano=self::aleatorio(array('Raúl','Rulo', 'Tomasa'));
            		break;
            	case ID_MATUTE:
            		$humano=self::aleatorio(array('Matute','Doctor'));
            		break;
            	case ID_LUCHO:
            		$humano==self::aleatorio(array('Luciano','Lucho','Luz&Ano'));
            		break;
            	case ID_BORJA:
            		$humano=self::aleatorio(array('Borja','Barbudo','Barba Humana'));
            		break;
		case ID_JON:
            		$humano=self::aleatorio(array('Jon','Vasco'));
            		break;
		case ID_FILETE:
            		$humano=self::aleatorio(array('Filete','Chuleta'));
            		break;
            }
            
            return $humano;
        }

        static function callApi($request, $url, $urlApi){
            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL,$urlApi. $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
            $result = curl_exec($curl);
        
            curl_close($curl);
        
            return $result;
        }
        
        
        static function urlTokenUsuario($request){
            $finUrl='';
            
            //Se comprueba si es un chat privado, para obtener el token del usuario
            if($request->is_private_chat()){
                $jsonTokenUser = Utils::callApi($request, 'tokenusuario/'.$request->get_chat_id().'?token='.TOKEN_API_BOT);
                $tokenUsuario = json_decode($jsonTokenUser, true);
                //var_dump($tokenUsuario);
                //Si hay token de usuario del chat, se invoca el comando con el token
                if($tokenUsuario[0]['token']){
                    $finUrl='?token='.$tokenUsuario[0]['token'];
                }
            }
            return $finUrl;
        }
        
        static function pregunta_grupo($endpoint,$request){
            $chatDao = new ChatDAO();
            $arrGrupos = $chatDao->selectGruposChat($request->get_chat_id());
            
            $arrButtons=Array();
            foreach($arrGrupos as $grupo){
                array_push($arrButtons, [$grupo->nombre]);
            }
            
            $inline_keyboard = new stdClass();
            $inline_keyboard->keyboard = $arrButtons;
            $inline_keyboard->resize_keyboard=true;
            $inline_keyboard->one_time_keyboard=true;
            
            return Response::create_text_replymarkup_response($endpoint,  $request->get_chat_id(), 'Dime el grupo, pringao!', json_encode($inline_keyboard));
        } 
       
        
        public static function IsDate($date) {
            return (strtotime($date) !== false);
        }
            
    }
    
?>
