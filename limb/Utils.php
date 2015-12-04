<?php
    class Utils{
        
        static function convert_emoji($utf8){
            return iconv('UCS-4LE', 'UTF-8', pack('V', $utf8));
        }
        
        static function get_url_api($request){
           
            switch ($request->get_chat_id()) {
                case ID_AGE:
                case ID_TAPIA:
                case ID_NANO:
                case ID_YONI:
                case ID_CAS: 
                case ID_JAVI:
                case ID_KETU:
                case ID_PACO:
                case ID_RIOJANO:
                case ID_BARTOL:
                case ID_VICENTE:
                case GUSLIMB_GROUPID:
                    return GUSLIMB_URL_API;
                    break;
                
                case CHAMPIONSLIMB_GROUPID:
                    return CHAMPIONSLIMB_URL_API;
                    break;
                default:
                    return null;
                    break;
            }
        }
        
        static function get_url_web($request){
           
            switch ($request->get_chat_id()) {
                case ID_AGE:
                case ID_TAPIA:
                case ID_NANO:
                case ID_YONI:
                case ID_CAS: 
                case ID_JAVI:
                case ID_KETU:
                case ID_PACO:
                case ID_RIOJANO:
                case ID_BARTOL:
                case ID_VICENTE:
                case GUSLIMB_GROUPID:
                    return GUSLIMB_URL;
                    break;
                case CHAMPIONSLIMB_GROUPID:
                    return CHAMPIONSLIMB_URL;
                    break;
                default:
                    return null;
                    break;
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
                    'Papanatas', 
                    'Mentecato', 
                    'Parguela', 
                    'Mierdaseca', 
                    'Hijo de puta',
                    'Gilipipas',
                    'Mascachapas',
                    'Soplanucas',
                    'Muerdealmohadas',
                    'Sodomita',
                    'Aborto',
                    'Anormal'));
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
            }
            
            return $humano;
        }
    }

?>