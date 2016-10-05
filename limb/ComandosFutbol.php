<?php
    class ComandosFutbol{
        
        static $logStatic;
        private $log;
        
        static function ejecutar($func,$endpoint, $request){
            $logStatic = Logger::getLogger('com.hotelpene.limbBot.ComandosFutbol');
            $logStatic->debug("Comienza Comandos Futbol");
            
            $command = new ComandosFutbol();
            if(method_exists($command,$func)){
                return $command->$func($endpoint, $request);
            }
        }
                
        public function __construct(){
            $this->log = Logger::getLogger('com.hotelpene.limbBot.ComandosFutbol');
        }
        
        
        private function encuentros($endpoint, $request){
            return $this->resultados($endpoint, $request);
        }

        private function partidos($endpoint, $request){
            return $this->resultados($endpoint, $request);
        }

        private function resultados($endpoint, $request){

            $this->log->debug("Resultados");
            $time = microtime(true);
		
			$response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            $response->send();
            
            $params = $request->get_command_params();
            $numparams = count($params);

            $fecha = date('Y-m-d');
			$competicion = null;			
			if($numparams > 0 && $numparams <=2){
			    
			    if(Utils::IsDate($params[0])){
			        $fecha = $params[0];
			    }else if(is_string($params[0])){
			        $competicion = $params[0];
			    }
			    
                if($numparams == 2 && Utils::IsDate($params[1])){
			        $fecha=$params[1];
                }else if($numparams == 2 && is_string($params[1])){
			        $competicion = $params[1];
                }
			}
			$idCompeticion = 440; // por defecto, la champions
			switch(strtolower($competicion)){
                case 'inglaterra': 
                    $idCompeticion = 426; 
                    break;
                case 'inglaterra2': 
                    $idCompeticion = 427; 
                    break;
                case 'alemania': 
                    $idCompeticion = 430; 
                    break;
                case 'holanda': 
                    $idCompeticion = 433; 
                    break;
                case 'españa': 
                    $idCompeticion = 436; 
                    break;
                case 'españa2': 
                    $idCompeticion = 437; 
                    break;
                case 'francia':
                    $idCompeticion = 434; 
                    break;
                case 'francia2': 
                    $idCompeticion = 435; 
                    break;
                case 'italia': 
                    $idCompeticion = 438; 
                    break;
                case 'portugal': 
                    $idCompeticion = 439; 
                    break;
                default: 
                    $competicion = 'champions';
			}
			
			$competicion = str_replace('2', ' 2', ucfirst($competicion));
			$fechaFormateada = date('d/m/Y', strtotime($fecha));
			
			$apiurl = 'http://api.football-data.org/v1/competitions/'.$idCompeticion.'/fixtures';
            $this->log->debug("URL ".$apiurl);
			$content = file_get_contents($apiurl);
			$json = json_decode($content, true);
			foreach($json['fixtures'] as $item) {
				if(strpos($item['date'],$fecha) !== false){
					$estado = $item['status'];
					switch($estado) {
						case "IN_PLAY":
							$text.=$item['homeTeamName'].' '.$item['result']['goalsHomeTeam'].' - '.$item['result']['goalsAwayTeam'].' '.$item['awayTeamName'].' (En juego)'.PHP_EOL;
							break;
						case "TIMED":
                        case "SCHEDULED":
						    $date = new DateTime($item['date'], new DateTimeZone('UTC'));
						    $date->setTimezone(new DateTimeZone('Europe/Madrid'));
							$text.=$item['homeTeamName'].' - '.$item['awayTeamName'].' ('.$date->format('H:i').')'.PHP_EOL;
							break;
						case "FINISHED":
							$text.=$item['homeTeamName'].' '.$item['result']['goalsHomeTeam'].' - '.$item['result']['goalsAwayTeam'].' '.$item['awayTeamName'].PHP_EOL;
							break;
					}
				}
			}

			if(isset($text))
			    $text ='*Resultados '.$competicion.' ('.$fechaFormateada.'):*'.PHP_EOL.$text;
			else 
			    $text ='*No hay partidos para '.$competicion.' ('.$fechaFormateada.')*';
			
			$response = new Response($endpoint, $request->get_chat_id(), Response::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            
		    $this->log->debug("Fin Resultados (".(microtime(true)-$time)." s): ");
            return $response;
		}
    }
?>