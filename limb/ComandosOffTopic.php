<?php
    //ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

    require_once __DIR__ . '/Resources.php';

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
            $comando = $request->get_command();
            if (strpos($comando,'puta') !== false) {
                return $this->insultarAMadre($endpoint, $request, 'puta');
            }
            if (strpos($comando,'gorda') !== false) {
                return $this->insultarAMadre($endpoint, $request, 'gorda');
            }
            if (strpos($comando,'tetas') !== false) {
                return $this->insultarAMadre($endpoint, $request, 'puta, que te las enseñe ella');
            }
            if (strpos($comando,'chupa') !== false) {
                return $this->insultarAMadre($endpoint, $request, 'puta, que te la chupe ella por cinco duros');
            }
            if (Utils::contiene($comando, ['cabron', 'cabrón'])) {
                return $this->insultarAHumano($endpoint, $request, 'cabrón');
            }
            if (Utils::contiene($comando, ['puto', 'maricon', 'maricón'])) {
                return $this->insultarAHumano($endpoint, $request, 'un puto maricón de mierda');
            }
            if (strpos($comando,'subnormal') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'subnormal');
            }
            if (strpos($comando,'gilipollas') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'un puto gilipollas');
            }
            if (strpos($comando,'socialista') !== false) {
                return $this->insultarAHumano($endpoint, $request, 'socialista');
            }
            if (strpos($comando,'podemita') !== false) {
                return Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_IGLESIAS_SE_HA_IDO_LA_CASTA_YA);
            }
            if (strpos($comando,'coleta') !== false) {
                return Response::create_sticker_response($endpoint, $request->get_chat_id(), Resources::STK_FRANCO_CONMIGO_NO_PASABA);
            }
            if (strpos($comando,'bot') !== false && strpos($comando,'noches') !== false) {
                return Response::create_audio_response($endpoint, $request->get_chat_id(), Resources::AUD_BUENAS_NOCHES_Y_BUENA_SUERTE);
            }
            if (strpos($comando,'bot') !== false) {
                $audio_id=Utils::aleatorio([
                    'BQADBAADfwEAAphMPgABb7GsrVt547oC', 
                    'BQADBAADfgEAAphMPgAB-cXIHgEea4kC',
                    'BQADBAADfQEAAphMPgABsVDRcZCRdwMC'
                ]);
                return Response::create_audio_response($endpoint, $request->get_chat_id(), $audio_id);
            }
            if (Utils::contiene($comando, ['soplar', 'soplando', 'soplo', 'viento', 'vientos'])) {
                return $this->soplar($endpoint, $request);
            }
            if (Utils::contiene($comando, ['racismo', 'negro', 'pancho', 'panchito', 'raza'])) {
                return $this->racismo($endpoint, $request);
            }
            if (Utils::es($comando, ['fas'])) {
                return $this->cas($endpoint, $request);
            }
            if (Utils::es($comando, ['chulepa', 'chuleta'])) {
                return $this->filete($endpoint, $request);
            }
            if (Utils::es($comando, ['tits', 'tetas'])) {
                return $this->chuache($endpoint, $request);
            }
            if (Utils::contiene($comando, ['tetas', 'mamellas', 'domingas', 'lolas', 'peras', 'melones', 'pechos', 'senos', 'mamas'])) {
                if (rand(0, 5) === 1) {
                    return $this->chuache($endpoint, $request);
                } else {
                    return $this->enfermo($endpoint, $request);
                }
            }
            if (Utils::contiene($comando, ['pene', 'pito', 'nabo', 'cipote', 'cimbrel', 'semen'])) {
                return $this->esloquetegustaeh($endpoint, $request);
            }
            if (Utils::es($comando, ['baile', 'bailar'])) {
                return $this->baila($endpoint, $request);
            }
            if (Utils::es($comando, ['mierda', 'hez'])) {
                return $this->hez($endpoint, $request);
            }
            if (Utils::es($comando, ['sortea', 'sortear'])) {
                return $this->sorteo($endpoint, $request);
            }
            if (Utils::es($comando, ['calvo', 'mondo', 'turquia', 'turquía', 'ankara'])) {
                return $this->calbo($endpoint, $request);
            }
            if (Utils::contiene($comando, ['bobo'])) {
                return $this->elbobo($endpoint, $request);
            }
            if (Utils::contiene($comando, ['penaldo', 'cristiano'])) {
                return $this->quieroMiPenaltito($endpoint, $request);
            }
            if (Utils::es($comando, ['cuentanosmas'])) {
                return $this->cuentamemas($endpoint, $request);
            }
            
            //El comando no existe
            $index = rand(0,2);

            switch($index) {
                case 0: 
                    return $this->no_implementada($endpoint, $request);
                case 1:
                    $audio_id='BQADBAADjwEAAphMPgABSEw32ygsbFIC';
                    return Response::create_audio_response($endpoint, $request->get_chat_id(), $audio_id);
                case 2:
                    $audio_id='BQADBAADiwEAAphMPgABaFOwoeYdAUkC';
                    return Response::create_audio_response($endpoint, $request->get_chat_id(), $audio_id);
            }
        }
         
        private function donaSemen($endpoint, $request){
            $emoji_mujer=Utils::convert_emoji(0x1F64B);
            $emoji_semen=Utils::convert_emoji(0x1F4A6);
        
            $text=$emoji_semen.$emoji_mujer;
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function bravo($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_APLAUSO_CIUDADANO_KANE);
        }
        
        private function quieroMiPenaltito($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_MI_PENALTITO_CRISTIANO);
        }
        
        private function enfermo($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = "${humano} eres un enfermo.";
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function esloquetegustaeh($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = "Es lo que te gusta, ${humano}, ¿eh?";
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function gus($endpoint, $request){
            $index = rand(0,2);

            switch($index) {
                case 0: 
    	            $file_id = Utils::aleatorio([
                        'AgADBAADKrExG6uCfgABZugFvbiTwBWpaHIwAAQIkbE_6Ksrx8Q2AQABAg', 
                        'AgADBAAD3KkxG5sPmAABKqtTAAHWZbY3NQWLMAAEmP0iZZyVDtfYMAEAAQI'
                    ]);
                    return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
                case 1:
                    $file_id = Utils::aleatorio([
                        Resources::GIF_APLAUSO_MARIANO, 
                        Resources::GIF_BAILE_BARCO_SICILIA
                    ]);
                    return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
                case 2:
                    return $this->baila($endpoint, $request);
            }
        }
        
        private function nogus($endpoint, $request){
            return Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_BARTOL_NO_GUS);
        }
        
        private function holaketu($endpoint, $request){
            return Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_KETU_NAVAS_HOLA_KETU);
        }
        
        private function holaage($endpoint, $request){
            return Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_AGE_IKER_HOLA_AGE);
        }
        
        private function valetio($endpoint, $request){
            return Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_KETU_VALE_TIO);
        }
        
        private function agevapipa($endpoint, $request){
            $file_id = Utils::aleatorio([
                'AgADBAADsLExG6uCfgAB11V67VOSyHQhd4wwAATniw8DzMJf0yJcAQABAg', 
                'AgADBAADsbExG6uCfgABdK4Br7b7bjPOCnEwAASIQJwfY4Wa6v7QAQABAg', 
                'AgADBAADsrExG6uCfgABcOiowovh9m2J83AwAAQXyvtk28XB_s7RAQABAg', 
                'AgADBAADu7ExG6uCfgABxjIl6YqoTCSzMIswAAT06vz4TKuqGnVhAQABAg', 
                'AgADBAADyqoxG3lazgABhV1-CGUlYW4fA3EwAASKHnfeO00-ih3XAQABAg'
            ]);
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
	    
        private function agevamuypipa($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_AGE_BAILE_RANDOM);
        }
        
        private function telacomiste($endpoint, $request){
            return Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_ADMIRAL_ACKBAR_TRAP);
        }
        
        private function vicenwin($endpoint, $request){
            return Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_KETU_HA_APOSTADO_VICENTE_YA);
        }
        
        private function fatSpanishWaiter($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_BENITEZ_FAT_SPANISH_WAITER_SOMBRERO);
        }
        
        private function cuantoHaGanadoCas($endpoint, $request){
            $response= Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_CAS_GANGSTER_PURO);
            $text='Se lo está llevando crudo';
            $response->caption=$text;
            return $response;
        }

        private function chuache($endpoint, $request) {
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_SCHWARZENEGGER_TITS);
        }
        
        private function aupa($endpoint, $request){
            $hour = (new DateTime(null, new DateTimezone('Europe/Madrid')))->format('H');
            $day = (new DateTime(null, new DateTimezone('Europe/Madrid')))->format('N');
            if (($hour >= 8 && $hour < 18 && $day < 5) || ($hour >= 8 && $hour < 15 && $day == 5)) {
                $humano = Utils::get_humano_name($request->get_from_id());
                $web = Utils::aleatorio(Resources::WEBS_PORNO);
                $text = Utils::aleatorio([
                    "No son horas, ${humano}. eres un puto pajero.",
                    "${humano}, ¿a estas horas ya andas con las pajas?",
                    "A ver, ${humano}, eres un pajero pero te voy a hacer un favor: ${web}"
                ]);
                return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
            } 
            
            $index = rand(0,4);
            
            switch($index){
                case 0:
                    if (rand(0,5) === 2) { ($this->chuache($endpoint, $request))->send(); }
                    $file_id='BQADBAADOAAECiQB3V1ov-88-qgC';
                    return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
                case 1:
                    if (rand(0,5) === 2) { ($this->chuache($endpoint, $request))->send(); }
                    $file_id='BAADBAAD-gADq4J-AAGsDCkH3vElRwI';
                    return Response::create_video_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
                case 2:
                    if (rand(0,5) === 2) { ($this->chuache($endpoint, $request))->send(); }
                    $file_id=Utils::aleatorio([
                        'BQADBAADOgEAAquCfgABXRORytopeMsC',
                        Resources::GIF_TETAS_VUELTA_CICLISTA
                    ]);
                    return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
                case 3:
                    if (rand(0,5) === 2) { ($this->chuache($endpoint, $request))->send(); }
                    $file_id= Utils::aleatorio([
                        'AgADBAADyrExG6uCfgAByJa4e096PDrUuqYwAAT4WookikriBOAAIC', 
                        'AgADBAADzLExG6uCfgABl0UQFLI2ny9wvY8wAATndl-8tzyDq9zyAAIC', 
                        'AgADBAADyLExG6uCfgABiop-lux6czIfQYswAASIWlelkQEKZedyAQABAg', 
                        'AgADBAADzLExG6uCfgABl0UQFLI2ny9wvY8wAATndl-8tzyDq9zyAAIC'
                    ]);
                    return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
                default:
                    $humano = Utils::get_humano_name($request->get_from_id());
                    $web = Utils::aleatorio(Resources::WEBS_PORNO);
                    $text = Utils::aleatorio([
                        "${humano}. eres un pajero.",
                        "Venías a por tetas, ¿eh, ${humano}? Pues te jodes, pajero.",
                        "A ver ${humano}, eres un jodido pajero, pero te voy a hacer un favor: ${web}"
                    ]);
                    return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
                    break;
            }
        }
        
        private function hola($endpoint, $request){
            return Response::create_sticker_response($endpoint,  $request->get_chat_id(), Resources::STK_LUCAS_VAZQUEZ);
        }

        private function baila($endpoint, $request) {
            $file_id = Utils::aleatorio([
                Resources::GIF_MASCARAS_RAVE_NORUEGA, 
                Resources::GIF_FIESTA_HOMO_SICILIA,
                Resources::GIF_MASCARAS_BAILE_BODA
            ]);
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $file_id);
        }
        
        private function age($endpoint, $request){
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            return $this->agevapipa($endpoint, $request);
        }

        private function jon($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function ori($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_ORI_BAILE_RANDOM_TRAJE_CAMISA
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function nano($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_NANO_PINZAS,
                Resources::GIF_NANO_ABANICO_BODA
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function iban($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function luis($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_BARTOL_BANDERA_EUROPA_VENGUE
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function tapia($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function rulo($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function lucho($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function vicente($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_VICENTE_AGRESION_BUS_BODA
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function ketu($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function matute($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function carracedo($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function borja($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function zato($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function rio($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function paco($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_PACO_CABALLO_LOCO_SILLA
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function cas($endpoint, $request) {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_CAS_PRIMERISIMO_PRIMER_PLANO, 
                Resources::GIF_CAS_EXTRATITANIO_HAWAIIAN
            ]);
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $file_id);
        }

        private function filete($endpoint, $request) {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_FILETE_BAMBOLEO_SANSE, 
                Resources::GIF_FILETE_BAMBOLEO_PISCINA
            ]);
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $file_id);
        }
        
        private function hez($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_ERNESTO_SEVILLA_VAYA_MIERDA);
        }
        
        private function sorteo($endpoint, $request){
            $response = Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_THEODEN_NO_TIENES_PODER);
            $text='¿Quieres dejar de molestar?';
            $response->caption=$text;
            return $response;
        }
        
        private function comovalacosa($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = "${humano}, que ¿cómo va la cosa?";
            $response = Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
            $response->send();
            $file_id = Utils::aleatorio([
                'AgADBAADg7ExG6uCfgABVKJWZutMk03lxWkwAAQyyYLPq-povk7xAQABAg', 
                'AgADBAADhLExG6uCfgABkiwjXOKfo3Y0tY8wAATxS8uU6Iu9fg_YAAIC', 
                'AgADBAADhbExG6uCfgABesAVy65s2vanA3EwAARisRvtaQadn3nPAQABAg', 
                'AgADBAADhrExG6uCfgAB3q5XSN1HRuPNcHEwAAQ8lK5YSKm_w5a7AQABAg', 
                'AgADBAADh7ExG6uCfgABSfzQtoQa7aKTLqIwAATDO6GIuvcGBeg1AAIC', 
                'AgADBAADiLExG6uCfgABMQbc_pgPqPEuMHEwAAQuty0bwqN55XO6AQABAg', 
                'AgADBAADibExG6uCfgABugz1SCDyHuHhQ6IwAASYuB7xHlsFZ_MzAAIC', 
                'AgADBAADirExG6uCfgAB1vISgyQDxyMLcHIwAARCgZ4gG5cWjY60AQABAg', 
                'AgADBAADi7ExG6uCfgAB5CYClvguvRQhUoswAASJGt4QohAzC_haAQABAg', 
                'AgADBAADjLExG6uCfgAB_kzIyeo7UVgJUIwwAAQgu3fC_vtzdy5XAQABAg', 
                'AgADBAADjbExG6uCfgABZjphYymr8F9KS6YwAAQtofZMgNgMBis2AAIC', 
                'AgADBAADjrExG6uCfgABpuGgz4haMOsRYHEwAAS14RfJ-IZpZVS6AQABAg', 
                'AgADBAADj7ExG6uCfgABg5r1ekTLJ7btW48wAAQiyna7QZONdmRfAAIC', 
                'AgADBAADkLExG6uCfgABt5tNR8GfsWy5WqYwAAQxj7W6UPHflX41AAIC', 
                'AgADBAADkrExG6uCfgABAZlES-gMg-rz1IwwAAR6gfrHOimI75nZAAIC', 
                'AgADBAADk7ExG6uCfgABks1B4XJt5Bd4yGkwAAR8ECGfpgFKyJHzAQABAg', 
                'AgADBAADkbExG6uCfgABs04ElacKtmyn7mowAAR-idLveeqFxb7uAQABAg', 
                'AgADBAADlLExG6uCfgABzgujxVkGl8VLxWkwAASzi-RJNjW4yqHwAQABAg', 
                'AgADBAADlbExG6uCfgABadtPItKyx57k5XAwAAS9J_8HXRkqQRLQAQABAg', 
                'AgADBAADlrExG6uCfgABjtQZ-hLnpBAVD2swAASmM_StGK3AQIn2AQABAg', 
                'AgADBAADl7ExG6uCfgABa7lJExDqn6KxRnEwAASoJxtLytnEBk-4AQABAg', 
                'AgADBAADmLExG6uCfgAB2yvN68F1E3qQRaYwAAQJZvnWM0fs6_w1AAIC', 
                'AgADBAADmbExG6uCfgABb5BeDjZLP5DRyIowAASpPPFqZl6L139eAQABAg', 
                'AgADBAADmrExG6uCfgABG62c8ayCCvvRuI8wAATi1YEVNMomD0_bAAIC', 
                'AgADBAADm7ExG6uCfgABNGIZUIZX9JYyKHEwAAQ62JlAJ5p_0SW7AQABAg', 
                'AgADBAADnLExG6uCfgABQwGrUWBMJKVxlY8wAATL374qLly_A79fAAIC', 
                'AgADBAADnbExG6uCfgABW7Uip_ShXhXb43IwAAQkAnw1HQVB9tG9AQABAg', 
                'AgADBAADnrExG6uCfgABTotSza9d6Gz4nWkwAAQqdc8JQP14YL_1AQABAg', 
                'AgADBAADn7ExG6uCfgABojpB_BgE_JdeaHEwAARtUZIZWh77avy7AQABAg', 
                'AgADBAADoLExG6uCfgAB9v9babjpowU56HAwAAR7W19v2yek8-nQAQABAg', 
                'AgADBAADobExG6uCfgABE_MSvMoXM01HGXEwAARypH9DLukNyIC5AQABAg', 
                'AgADBAADorExG6uCfgABI9r34SFG4BhdU4swAAR3m_Jwt8ywoDVZAQABAg', 
                'AgADBAADq7ExG6uCfgABzHgzg4sczRsbrmkwAAQQKX9ZrfI9Bt_vAQABAg', 
                'AgADBAADrLExG6uCfgABSiS6rwhMIAF6u6YwAAS4qmUoULYrgiw1AAIC', 
                'AgADBAADo7ExG6uCfgABwiLcKZVclAyyV4wwAARQA7M4L17jmhJbAQABAg', 
                'AgADBAADrbExG6uCfgABUKunpEocMOJImI8wAATALTCBXb0Oe51cAAIC', 
                'AgADBAADrrExG6uCfgABer9D-9C0RO7z54wwAASP4IgsgI0kN-1dAAIC', 
                'AgADBAADpLExG6uCfgABVG9smyxlg6CoxoowAATIlHSMiAxe1AdaAQABAg', 
                'AgADBAADpbExG6uCfgABxbSa0X7VyL_KSHEwAAQT8mSD18ayE5u3AQABAg', 
                'AgADBAADp7ExG6uCfgABiDzmyccrxFtOto8wAARBseWhiEnjE7XZAAIC', 
                'AgADBAADprExG6uCfgABPNfI6LRiOSdeR3EwAATSf-1vm3K-fKK3AQABAg', 
                'AgADBAADqLExG6uCfgABxlU7MV-ghMAJ0GkwAAQQUlgVQloPLen2AQABAg', 
                'AgADBAADqbExG6uCfgABgbxqAAG0uafSOkymMAAEgwovT5OEHKmkNQACAg', 
                'AgADBAADqrExG6uCfgABS17nrpTZLMlxWqYwAASRlfGd2854Zm81AAIC'
            ]);
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function no_implementada($endpoint, $request){
            $text = "¿Pero qué dices? ¿Qué intentas?" . PHP_EOL;
            if($request->get_from_id() == ID_PACO) {
                $text .= "¡¡¡Pacooooooooooooooooooo!!!" . PHP_EOL;
            }
            $humano = Utils::get_humano_name($request->get_from_id());
            $insulto1 = Utils::aleatorio(Recurso::INSULTO_DIRECTO_2);
            $insulto2 = Utils::getInsultoSingular();
            $text .= "${insulto1} ${humano}. ${insulto2}.";
            if($humano=='Ario'){
                $file_id='AgADBAADLKkxG4jtnAABsbSkFxkCLImgn2kwAARwTik8oQSyGj3nAQABAg';
                $response= Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
                $response->send();
            }
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);        		
        }
         
        private function insultarAMadre($endpoint, $request, $insulto){
            if ($insulto == null) {
                $insulto = Utils::aleatorio(Resource::INSULTO_MADRE);
            }
            $humano = Utils::get_humano_name($request->get_from_id());
            $text  = "${humano}, tu madre sí que es ${insulto}.";
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function insultarAHumano($endpoint, $request, $insulto){
            if ($insulto == null) {
                $insulto = Utils::getInsultoSingular();
            }            
            $humano = Utils::get_humano_name($request->get_from_id());
            $text  = "${humano}, tú sí que eres ${insulto}.";
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }
        
        private function canta($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::AUD_PEM_JOSE_BRETON);
        }

        private function wololo($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::AUD_WOLOLO);
        }
        
        private function cuentamemas($endpoint, $request){
            $file_id = Utils::aleatorio([
                'BQADBAADPQADmw-YAAEhWGbVFye0lQI', 
                'BQADBAADPgADmw-YAAH-FnGmrZjAewI'
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function siagesi($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_GATO_ACARICIADO);
        }

        private function insultar($endpoint, $request){
            $intro = Utils::aleatorio(['Pues veamos,', 'Ahora que lo dices', 'Me sabe mal, pero', 'Lo cierto es que', 
                                        'Jajaja, vale,', 'Por todos es sabido que']);
            $humano = Utils::get_humano_random();
            $insulto =  Utils::aleatorio(Resources::INSULTO_DIRECTO);
            $text = "${intro} ${humano} es un ${insulto}.";
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }
        
        private function insultaa($endpoint, $request){
            $text = null;
            $params = $request->get_command_params();
            if(count($params)>0){
                $humano1 = Utils::get_humano_name($request->get_from_id());
                $humano2 = $params[0];
                $insulto = Utils::aleatorio(Resources::INSULTO_DIRECTO);
                $text = Utils::aleatorio([
                    "${humano1} tienes razón, ${humano2} es un ${insulto}",
                    "Bueno bueno, tal vez ${humano2} sea un ${insulto}, pero tú también tienes lo tuyo, ¿eh ${humano1}?",
                    "Sí, si ${humano2} es un ${insulto}, pero no estás para hablar, ${humano1}."
                ]);
            }else{
                $text = Utils::aleatorio([
                    "A quién, ¿eh? a quién, bobo, el BOBO. Puto retrasado.",
                    "Si es que no sabes ni poner los comandos. Menudo IMBÉCIL. Qué pena das.",
                    "Menudo ANORMAL, no sabes hacer nada."
                ]);
            }
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }

        private function stopmame($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = Utils::aleatorio([
                "Jajajajaja... Pero ${humano}, ¡¡si eres un puto MAMAO!!",
                "Pero ${humano}, con las merlas que te pillas, jajaja, JAJAJA, stop mame dice..."
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }

        private function calbo($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = Utils::aleatorio([
                "Sí, ${humano}, sí. Este es un buen grupo de CALBOS.",
                "Es absurda la POCA cantidad de PELO que hay por aquí, ${humano}.",
                "Y venga mondos... ¡¡MONDOS!! Habrá que fletar un viaje a Turquía, ¿eh, ${humano}?"
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }        

        private function soplar($endpoint, $request){
            $file_id = Utils::aleatorio([
                Resources::GIF_PALMERAS_HURACAN,
                Resources::GIF_NICOLAS_CAGE_MELENA_VIENTO
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function racismo($endpoint, $request){
            $file_id = Utils::aleatorio([
                Resources::GIF_NO_RACISMO_BANDERIN_CHAMPIONS,
                Resources::GIF_NO_RACISMO_CARAS_HITLER
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function elbobo($endpoint, $request, $insulto){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_PEDRO_SANCHEZ_RIENDO);
        }
        
        private function españa($endpoint, $request, $insulto){
            $emoji_e=Utils::convert_emoji(0x1F1EA);
            $emoji_s=Utils::convert_emoji(0x1F1F8);
            $text = $emoji_e.$emoji_s;
            return Response::create_text_response($endpoint,  $request->get_chat_id(), $text);
        }
        
        private function stihl($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::AUD_STIHL);
        }

        private function señor($endpoint, $request){
            $response_doc = Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_GOL_SENOR_CABALLO_BIPEDO);
            $response_doc->send();
            return Response::create_audio_response($endpoint, $request->get_chat_id(), Resources::AUD_GOL_MORSE);
        }
        
        private function acierto($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_CARRACEDO_OTRO_PICK, 
                Resources::GIF_MASCARAS_RAVE_NORUEGA, 
                Resources::GIF_LLULL_PALMAS_VAMOS, 
                Resources::GIF_MASCARAS_BAILE_BODA
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function fallo($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_DICAPRIO_MAKE_IT_RAIN,
                Resources::GIF_NIGGA_MAKE_IT_RAIN
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function var($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_JOAQUIN_VAR
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function tehasexcedido($endpoint, $request){
            $response_doc = Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_JURASSIC_PARK_ENTRADA);
            $response_doc->send();
            return Response::create_audio_response($endpoint, $request->get_chat_id(), Resources::AUD_JURASSIC_PARK);
        }
					       
    	private function melafo($endpoint, $request){
            $file_id=Utils::aleatorio(['BQADBAAD0gAECiQBmf8x2MUKMbsC', 'BQADBAAD3xgAAtwXZAeef-gdoL82-QI']);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }				       

        private function _funcion_pendiente($endpoint, $request) {
            $file_id = Utils::aleatorio([
                Resources::GIF_PERRO_TECLEANDO,
                Resources::GIF_MASCARAS_RAVE_NORUEGA,
                Resources::STK_LUCAS_VAZQUEZ,
                Resources::IMG_THEODEN_NO_TIENES_PODER,
                Resources::GIF_PEDRO_SANCHEZ_RIENDO,
                Resources::GIF_ERNESTO_SEVILLA_VAYA_MIERDA,
                Resources::GIF_GORDO_BAMBOLEANDOSE
            ]);
            $response = Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
            $text = Utils::aleatorio([
                "Calma en Barna, chacho.",
                "Muy buena cama.",
                "¡¡Hala, VENGA!!",
                "¡¡Venga, CHURRAS!!",
                "Vengue, vengue."
            ]);
            $response->caption = $text;
            return $response;
        }
    }
?>
