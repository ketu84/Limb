<?php
    //ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

    class ComandosOffTopic{
        
        static $logStatic;
        private $log;
        
        static function ejecutar($func,$endpoint, $request){
            $logStatic = Logger::getLogger('com.hotelpene.limbBot.ComandosOffTopic');
            $logStatic->debug("Comienza OffTopic");
            
            $command = new ComandosOffTopic();
            if(method_exists($command,$func)){
                return $command->$func($endpoint, $request);
            }else{
                return $command->por_defecto($endpoint, $request);
            }
        }
        
        public function __construct(){
            $this->log = Logger::getLogger('com.hotelpene.limbBot.ComandosOffTopic');
        }
        
        private function por_defecto ($endpoint, $request){
            $this->log->debug("Comando por defecto");
            
            if (strpos($request->get_command(),'puta') !== false) {
                return $this->insultarAMadre($endpoint, $request, 'puta');
            }
            if (strpos($request->get_command(),'gorda') !== false) {
                return $this->insultarAMadre($endpoint, $request, 'gorda');
            }
            if (strpos($request->get_command(),'tetas') !== false) {
                return $this->insultarAMadre($endpoint, $request, 'puta, que te las enseñe ella');
            }
            if (strpos($request->get_command(),'chupa') !== false) {
                return $this->insultarAMadre($endpoint, $request, 'puta, que te la chupe ella por cinco duros');
            }
            if (strpos($request->get_command(),'cabron') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'cabron');
            }
            if (strpos($request->get_command(),'puto') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'un puto maricon de mierda');
            }
            if (strpos($request->get_command(),'maricon') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'un puto maricon de mierda');
            }
            if (strpos($request->get_command(),'subnormal') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'subnormal');
            }
            if (strpos($request->get_command(),'gilipollas') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'un puto gilipollas');
            }
            if (strpos($request->get_command(),'socialista') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'socialista');
            }
            
            if (strpos($request->get_command(),'podemita') !== false) {
                $file_id = 'AgADBAADtrExG6uCfgAB-HBYDek-QkN_mo8wAARqlUj5CBNq9idfAAIC';
                return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
            }
            
            if (strpos($request->get_command(),'coleta') !== false) {
                $file_id = 'BQADBAAD7AAD-WxHAtGrH8UmWiiXAg';
                return Response::create_sticker_response($endpoint, $request->get_chat_id(), $file_id);
            }
            
            return $this->insultar($endpoint, $request);
        }
       
         
        private function donaSemen($endpoint, $request){
            $emoji_mujer=Utils::convert_emoji(0x1F64B);
            $emoji_semen=Utils::convert_emoji(0x1F4A6);
        
            $text=$emoji_semen.$emoji_mujer;
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function bravo($endpoint, $request){
            $file_id='BQADBAADuAADmEw-AAFENNvXv3KlQgI';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function quieroMiPenaltito($endpoint, $request){
            $file_id='BQADBAADtwADmEw-AAHFpvL_faHg5QI';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
       private function cuantoHaPerdidoRiojas($endpoint, $request,$urlApi){
            $result= Utils::quien_ha_perdido_mas($endpoint, $request,$urlApi);
            
            switch($result){
                case 0:
                    $humano = Utils::get_humano_name($request->get_from_id());
                    $text= $text. $humano. '. De momento llevas más pasta que el cocacolitas, sigue así';
                    return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
                    break;
                case 1:
                    $humano = Utils::get_humano_name($request->get_from_id());
                    $insulto = Utils::aleatorio(['Maldito', 'Jodido', 'Estúpido', 'Condenado', 'Retrasado', 'Podemita']);
                    $text = $text. $insulto.' '.$humano.'. Si has perdido más pasta tú ';
                    return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
                    break;
                case 2:
                    $text= 'Pero mira que eres bobo gelete... No sabes ni lo que has perdido. Eres el Pedro Sánchez de las apuestas.';
                    return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
                    break;
                case 4:
                    $text= 'No se ni quién eres, mejor que ni me hables muerto de hambre.';
                    return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
                    break;
                default:
                    $text='Ni más ni menos que tú, parguela';
                    return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
                    break;
            }            
           
        }
        
        
        private function tetas($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function mamellas($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function domingas($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function lolas($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function peras($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function melones($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function pechos($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function senos($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function mamas($endpoint, $request){
            return $this->enfermo($endpoint, $request);
        }
        
        private function enfermo($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text= $humano . ' eres un enfermo';
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function pene($endpoint, $request){
            return $this->eslo1uetegustaeh($endpoint, $request);
        }
        
        private function pito($endpoint, $request){
            return $this->eslo1uetegustaeh($endpoint, $request);
        }
        
        private function nabo($endpoint, $request){
            return $this->eslo1uetegustaeh($endpoint, $request);
        }
        
        private function cipote($endpoint, $request){
            return $this->eslo1uetegustaeh($endpoint, $request);
        }
        
        private function cimbrel($endpoint, $request){
            return $this->eslo1uetegustaeh($endpoint, $request);
        }
        
        private function semen($endpoint, $request){
            return $this->eslo1uetegustaeh($endpoint, $request);
        }
        
        private function eslo1uetegustaeh($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text='Es lo que te gusta, '.$humano.', ¿eh?';
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function gus($endpoint, $request){
            $index = rand(0,1);

            switch($index) {
                case 0: 
    	            $file_id = Utils::aleatorio(array('AgADBAADKrExG6uCfgABZugFvbiTwBWpaHIwAAQIkbE_6Ksrx8Q2AQABAg', 'AgADBAAD3KkxG5sPmAABKqtTAAHWZbY3NQWLMAAEmP0iZZyVDtfYMAEAAQI'));
                    return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
                case 1:
                    $file_id = Utils::aleatorio(array('BQADBAADVwIAAmCIgQABk85Zb6xxMZwC'));
                    return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
            }
        }
        
        private function nogus($endpoint, $request){
            $file_id = 'AgADBAADr7ExG6uCfgABRKEQrm8ULhfcco8wAAQ5D9K2nU6X0IFfAAIC';
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function holaketu($endpoint, $request){
            $file_id = 'AgADBAADtbExG6uCfgABLrwSmy2LSv8U1IwwAAQ_de836O5RLh3YAAIC';
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function holaage($endpoint, $request){
            $file_id = 'AgADBAADtLExG6uCfgABPI6QTh8Q4fpHQnEwAARy8nYUUSRw2Lq2AQABAg';
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function valetio($endpoint, $request){
            $file_id = 'AgADBAADs7ExG6uCfgABNelLZA68mblL0owwAATnURFjObRacZTZAAIC';
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function agevapipa($endpoint, $request){
            $file_id = Utils::aleatorio(array('AgADBAADsLExG6uCfgAB11V67VOSyHQhd4wwAATniw8DzMJf0yJcAQABAg', 'AgADBAADsbExG6uCfgABdK4Br7b7bjPOCnEwAASIQJwfY4Wa6v7QAQABAg', 'AgADBAADsrExG6uCfgABcOiowovh9m2J83AwAAQXyvtk28XB_s7RAQABAg', 'AgADBAADu7ExG6uCfgABxjIl6YqoTCSzMIswAAT06vz4TKuqGnVhAQABAg', 'AgADBAADyqoxG3lazgABhV1-CGUlYW4fA3EwAASKHnfeO00-ih3XAQABAg'));
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
	    
        private function agevamuypipa($endpoint, $request){
         $file_id='BQADBAADWAEAAphMPgAB-oBIaV81Y04C';
         return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function telacomiste($endpoint, $request){
            $file_id = 'AgADBAADK7ExG6uCfgAB9rTpspMp9VRGYGkwAAS8GdFc47A_whSFAQABAg';
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function vicenwin($endpoint, $request){
            $file_id = 'AgADBAADLLExG6uCfgAB0UBRGzF7sb96C2swAAS7hCl_X6wqS9ByAQABAg';
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function fatSpanishWaiter($endpoint, $request){
            $file_id='BQADBAADMAEAAquCfgABhqhRqhpC5agC';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function cuantoHaGanadoCas($endpoint, $request){
            $file_id = 'AgADBAADLbExG6uCfgABO7d46OcKzQkVuo8wAATDrhyVPZbKfktbAAIC';
            $response= Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
            $text='Se lo está llevando crudo';
            $response->caption=$text;
            return $response;
        }
        
        private function aupa($endpoint, $request){
            $index = rand(0,4);

            switch($index){
                case 0:
                    $file_id='BQADBAADOAAECiQB3V1ov-88-qgC';
                    return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
                case 1:
                    $file_id='BAADBAAD-gADq4J-AAGsDCkH3vElRwI';
                    return Response::create_video_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
                case 2:
                    $file_id='BQADBAADOgEAAquCfgABXRORytopeMsC';
                    return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
		case 3:
                    $file_id= Utils::aleatorio(array('AgADBAADyrExG6uCfgAByJa4e096PDrUuqYwAAT4WookikriBOAAIC', 'AgADBAADzLExG6uCfgABl0UQFLI2ny9wvY8wAATndl-8tzyDq9zyAAIC', 'AgADBAADyLExG6uCfgABiop-lux6czIfQYswAASIWlelkQEKZedyAQABAg', 'AgADBAADzLExG6uCfgABl0UQFLI2ny9wvY8wAATndl-8tzyDq9zyAAIC'));
                    return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
                default:
                    $humano = Utils::get_humano_name($request->get_from_id());
                    $text= $humano.' eres un pajero.';
                    return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
                    break;
            }
        }
        
        private function mierda($endpoint, $request){
            return $this->hez($endpoint, $request);
        }
        
        private function hez($endpoint, $request){
            $file_id='BQADBAADMgADmw-YAAE4pcdXZXF0FgI';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function sorteo($endpoint, $request){
            $file_id = 'AgADBAADKqkxG5hMPgABj_DlPCsJq_QnvI8wAATxeRhugBnP6DH_AAIC';
            $response = Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
            $text='¿Quieres dejar de molestar?';
            $response->caption=$text;
            return $response;
        }
        
        private function comovalacosa($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text=$humano.', que cómo va la cosa?';
            $response =  Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
            $response->send();
            
            $file_id = Utils::aleatorio(array('AgADBAADg7ExG6uCfgABVKJWZutMk03lxWkwAAQyyYLPq-povk7xAQABAg', 'AgADBAADhLExG6uCfgABkiwjXOKfo3Y0tY8wAATxS8uU6Iu9fg_YAAIC', 'AgADBAADhbExG6uCfgABesAVy65s2vanA3EwAARisRvtaQadn3nPAQABAg', 'AgADBAADhrExG6uCfgAB3q5XSN1HRuPNcHEwAAQ8lK5YSKm_w5a7AQABAg', 'AgADBAADh7ExG6uCfgABSfzQtoQa7aKTLqIwAATDO6GIuvcGBeg1AAIC', 'AgADBAADiLExG6uCfgABMQbc_pgPqPEuMHEwAAQuty0bwqN55XO6AQABAg', 'AgADBAADibExG6uCfgABugz1SCDyHuHhQ6IwAASYuB7xHlsFZ_MzAAIC', 'AgADBAADirExG6uCfgAB1vISgyQDxyMLcHIwAARCgZ4gG5cWjY60AQABAg', 'AgADBAADi7ExG6uCfgAB5CYClvguvRQhUoswAASJGt4QohAzC_haAQABAg', 'AgADBAADjLExG6uCfgAB_kzIyeo7UVgJUIwwAAQgu3fC_vtzdy5XAQABAg', 'AgADBAADjbExG6uCfgABZjphYymr8F9KS6YwAAQtofZMgNgMBis2AAIC', 'AgADBAADjrExG6uCfgABpuGgz4haMOsRYHEwAAS14RfJ-IZpZVS6AQABAg', 'AgADBAADj7ExG6uCfgABg5r1ekTLJ7btW48wAAQiyna7QZONdmRfAAIC', 'AgADBAADkLExG6uCfgABt5tNR8GfsWy5WqYwAAQxj7W6UPHflX41AAIC', 'AgADBAADkrExG6uCfgABAZlES-gMg-rz1IwwAAR6gfrHOimI75nZAAIC', 'AgADBAADk7ExG6uCfgABks1B4XJt5Bd4yGkwAAR8ECGfpgFKyJHzAQABAg', 'AgADBAADkbExG6uCfgABs04ElacKtmyn7mowAAR-idLveeqFxb7uAQABAg', 'AgADBAADlLExG6uCfgABzgujxVkGl8VLxWkwAASzi-RJNjW4yqHwAQABAg', 'AgADBAADlbExG6uCfgABadtPItKyx57k5XAwAAS9J_8HXRkqQRLQAQABAg', 'AgADBAADlrExG6uCfgABjtQZ-hLnpBAVD2swAASmM_StGK3AQIn2AQABAg', 'AgADBAADl7ExG6uCfgABa7lJExDqn6KxRnEwAASoJxtLytnEBk-4AQABAg', 'AgADBAADmLExG6uCfgAB2yvN68F1E3qQRaYwAAQJZvnWM0fs6_w1AAIC', 'AgADBAADmbExG6uCfgABb5BeDjZLP5DRyIowAASpPPFqZl6L139eAQABAg', 'AgADBAADmrExG6uCfgABG62c8ayCCvvRuI8wAATi1YEVNMomD0_bAAIC', 'AgADBAADm7ExG6uCfgABNGIZUIZX9JYyKHEwAAQ62JlAJ5p_0SW7AQABAg', 'AgADBAADnLExG6uCfgABQwGrUWBMJKVxlY8wAATL374qLly_A79fAAIC', 'AgADBAADnbExG6uCfgABW7Uip_ShXhXb43IwAAQkAnw1HQVB9tG9AQABAg', 'AgADBAADnrExG6uCfgABTotSza9d6Gz4nWkwAAQqdc8JQP14YL_1AQABAg', 'AgADBAADn7ExG6uCfgABojpB_BgE_JdeaHEwAARtUZIZWh77avy7AQABAg', 'AgADBAADoLExG6uCfgAB9v9babjpowU56HAwAAR7W19v2yek8-nQAQABAg', 'AgADBAADobExG6uCfgABE_MSvMoXM01HGXEwAARypH9DLukNyIC5AQABAg', 'AgADBAADorExG6uCfgABI9r34SFG4BhdU4swAAR3m_Jwt8ywoDVZAQABAg', 'AgADBAADq7ExG6uCfgABzHgzg4sczRsbrmkwAAQQKX9ZrfI9Bt_vAQABAg', 'AgADBAADrLExG6uCfgABSiS6rwhMIAF6u6YwAAS4qmUoULYrgiw1AAIC', 'AgADBAADo7ExG6uCfgABwiLcKZVclAyyV4wwAARQA7M4L17jmhJbAQABAg', 'AgADBAADrbExG6uCfgABUKunpEocMOJImI8wAATALTCBXb0Oe51cAAIC', 'AgADBAADrrExG6uCfgABer9D-9C0RO7z54wwAASP4IgsgI0kN-1dAAIC', 'AgADBAADpLExG6uCfgABVG9smyxlg6CoxoowAATIlHSMiAxe1AdaAQABAg', 'AgADBAADpbExG6uCfgABxbSa0X7VyL_KSHEwAAQT8mSD18ayE5u3AQABAg', 'AgADBAADp7ExG6uCfgABiDzmyccrxFtOto8wAARBseWhiEnjE7XZAAIC', 'AgADBAADprExG6uCfgABPNfI6LRiOSdeR3EwAATSf-1vm3K-fKK3AQABAg', 'AgADBAADqLExG6uCfgABxlU7MV-ghMAJ0GkwAAQQUlgVQloPLen2AQABAg', 'AgADBAADqbExG6uCfgABgbxqAAG0uafSOkymMAAEgwovT5OEHKmkNQACAg', 'AgADBAADqrExG6uCfgABS17nrpTZLMlxWqYwAASRlfGd2854Zm81AAIC'));
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function insultar($endpoint, $request){
            $text = 'Función no implementada. ';
            
            if($request->get_from_id()!=null){
                if($request->get_from_id()==ID_PACO){
                    $text = $text.'¡¡¡Pacooooooooooooooooooo!!! ';
                } else {
                    $humano = Utils::get_humano_name($request->get_from_id());
                    $insulto = Utils::aleatorio(['Maldito', 'Jodido', 'Estúpido', 'Condenado', 'Retrasado', 'Podemita']);
                    $text = $text. $insulto.' '.$humano.'. ';
                }
            }
            
            $insulto = Utils::getInsultoSingular();
            $text .= $insulto;
            if($humano=='Ario'){
                $file_id='AgADBAADLKkxG4jtnAABsbSkFxkCLImgn2kwAARwTik8oQSyGj3nAQABAg';
                $response= Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
                $response->send();
            }
            
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);        		
        }
         
        private function insultarAMadre($endpoint, $request, $insulto){
            $text = 'Tu madre si que es '.$insulto;
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function insultarAHumano($endpoint, $request, $insulto){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = $humano.',tu si que eres '.$insulto;
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }
        
        private function canta($endpoint, $request){
            $file_id='BQADBAADawEAAquCfgABAhruCPned4AC';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function wololo($endpoint, $request){
            $file_id='BQADBAADOQADmw-YAAEVi-CBIwOYXQI';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
		
        private function cuentanosmas($endpoint, $request){
            return $this->cuentamemas($endpoint, $request);
        }
        
        private function cuentamemas($endpoint, $request){
            $file_id = Utils::aleatorio(array('BQADBAADPQADmw-YAAEhWGbVFye0lQI', 'BQADBAADPgADmw-YAAH-FnGmrZjAewI'));
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function siagesi($endpoint, $request){
            $file_id='BQADBAAD6gADmEw-AAGGLIPvh6gpqwI';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function insultaa($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            
            $params = $request->get_command_params();
            if(count($params)>0){
        	    $text = $humano;
        	    $text .= ' tienes razón, ';
        	    $text .= $params[0];
        	    $text .= ' es un ';
        	    $text .=  Utils::aleatorio(['jodido perturbado', 'estúpido', 'retrasado', 'podemita', 'pederasta', 
                                                'enfermo', 'hijo de puta', 'maricón', 'sodomita', 'gilipollas', 'subnormal', 
                                                'aborto', 'judio', 'bebedor de semen', 'soplanucas', 'abrazafarolas',
                                                'baboso', 'caraculo', 'mascachapas', 'cuerpoescombro', 'zurcefrenillos',
                                                'cabronazo' ]);
            }else{
                $text = 'A quién, eh? a quién, bobo, el Bobo. Puto retrasado';
            }
            
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
            
        }
        
        private function elbobo($endpoint, $request, $insulto){
            $file_id='BQADBAADUQAECiQBwXY8yQTw4psC';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
            
        }
        
        private function españa($endpoint, $request, $insulto){
            $emoji_e=Utils::convert_emoji(0x1F1EA);
            $emoji_s=Utils::convert_emoji(0x1F1F8);
            $text = $emoji_e.$emoji_s;
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function stihl($endpoint, $request){
            $file_id='BQADBAADOgEAAphMPgABZZKRawyaaBwC';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function señor($endpoint, $request){
            $file_id='BQADBAADGwEAAphMPgABI26EAcZ0dg0C';
            $response_doc = Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
            $response_doc->send();
            
            $audio_id='BQADBAADnAADmw-YAAGj6L0TKXyxjAI';
            return Response::create_audio_response($endpoint, $request->get_chat_id(), $audio_id);
        }
        
        private function acierto($endpoint, $request){
            $file_id='BQADBAADOQEAAphMPgABREm9f5CcR-kC';
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function tehasexcedido($endpoint, $request){
            $file_id='BQADBAADJR0AAsseZAf3QgvdLNg82AI';
            $response_doc = Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
            $response_doc->send();
            
            $audio_id='BQADBAADVAEAAphMPgABNc-CEJQck9oC';
            return Response::create_audio_response($endpoint, $request->get_chat_id(), $audio_id);
        }
					       
	private function melafo($endpoint, $request){
            $file_id=Utils::aleatorio(['BQADBAAD0gAECiQBmf8x2MUKMbsC', 'BQADBAAD3xgAAtwXZAeef-gdoL82-QI']);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }				       
					       
        
        
    }
?>
