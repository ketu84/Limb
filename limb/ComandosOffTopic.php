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
                    Resources::AUD_HE_VISTO_COSAS, 
                    Resources::AUD_SOY_EL_SARGENTO, 
                    Resources::AUD_HUELES_ESO 
                ]);
                return Response::create_audio_response($endpoint, $request->get_chat_id(), $audio_id);
            }
            if (Utils::contiene($comando, ['soplar', 'soplando', 'soplo', 'viento', 'vientos'])) {
                return $this->soplar($endpoint, $request);
            }
            if (Utils::contiene($comando, ['racismo', 'negro', 'pancho', 'panchito', 'raza', 'rumano', 'gitano'])) {
                return $this->racismo($endpoint, $request);
            }
            if (Utils::es($comando, ['fas'])) {
                return $this->cas($endpoint, $request);
            }
            if (Utils::es($comando, ['chulepa', 'chuleta', 'chule'])) {
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
            if (Utils::contiene($comando, ['merla', 'mame'])) {
                return $this->merla($endpoint, $request);
            }
            if (Utils::es($comando, ['var'])) {
                return $this->revision($endpoint, $request);
            }
            if (Utils::es($comando, ['obeso','sebo','boliche'])) {
                return $this->gordo($endpoint, $request);
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
                    return Response::create_audio_response($endpoint, $request->get_chat_id(), Resources::AUD_HOUSTON);
                case 2:
                    return Response::create_audio_response($endpoint, $request->get_chat_id(), Resources::AUD_GRAN_PODER);
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
                        Resources::IMG_CRISTIANO_PULGARES_OK,
                        Resources::IMG_WEAH_PULGAR_OK
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
                Resources::IMG_AGE_PULGARES_AGUA,
                Resources::IMG_AGE_GANGSTER,
                Resources::IMG_AGE_MASCARA_ROJA,
                Resources::IMG_AGE_COPA_OHARAS,
                Resources::IMG_AGE_LENGUA_BUS,
                Resources::GIF_AGE_MONCHI
            ]);
            return Response::create_photo_response($endpoint, $request->get_chat_id(), $file_id);
        }
	    
        private function agevamuypipa($endpoint, $request){
            $file_id = Utils::aleatorio([
                Resources::GIF_AGE_BAILE_RANDOM,
                Resources::GIF_AGE_MINI_QUE_NOS_QUITEN_LO_BAILAO,
                Resources::GIF_AGE_MANO_MONCHICHI
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
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
                    if (rand(0,5) === 2) { $this->chuache($endpoint, $request)->send();}
                    return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_TETAS_AUPA_ATHLETIC);
                    break;
                case 1:
                    if (rand(0,5) === 2) { $this->chuache($endpoint, $request)->send();}
                    return Response::create_video_response($endpoint, $request->get_chat_id(), Resources::VID_TETAS_TIA_DICE_HOLA_GRUPO);
                    break;
                case 2:
                    if (rand(0,5) === 2) { $this->chuache($endpoint, $request)->send();}
                    $file_id=Utils::aleatorio([
                        Resources::GIF_ALIZEE_BAILANDO,
                        Resources::GIF_TETAS_VUELTA_CICLISTA
                    ]);
                    return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
                    break;
                case 3:
                    if (rand(0,5) === 2) { $this->chuache($endpoint, $request)->send();}
                    $file_id= Utils::aleatorio([
                        Resources::IMG_LAWRENCE_TETAS_FILTRADA, 
                        Resources::IMG_LAWRENCE_CULO_FILTRADO
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
            $file_id = Utils::aleatorio([
                Resources::GIF_HOLA_BALCON_SICILIA,
                Resources::GIF_CARRACEDO_HOLA_VERTICAL,
                Resources::GIF_FORREST_GUMP_SALUDANDO,
                Resources::STK_LUCAS_VAZQUEZ
            ]);
            return Response::create_sticker_response($endpoint,  $request->get_chat_id(), $file_id);
        }

        private function baila($endpoint, $request) {
            $file_id = Utils::aleatorio([
                Resources::GIF_MASCARAS_RAVE_NORUEGA, 
                Resources::GIF_FIESTA_HOMO_SICILIA,
                Resources::GIF_RINCON_BAILANDO_BODA,
                Resources::GIF_ARABES_BAILANDO_VENGUE,
                Resources::GIF_RICHY_BAILANDO_MASCARA_PROBOSCIS,
                Resources::GIF_RICHY_BAILE_MALTA_CAMISETA_AMARILLA,
                Resources::GIF_MASCARAS_BAILE_BODA
            ]);
            return Response::create_doc_response($endpoint,  $request->get_chat_id(), $file_id);
        }
        
        private function age($endpoint, $request){
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            return $this->agevapipa($endpoint, $request);
        }

        private function jon($endpoint, $request)
        {
            
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_JON_MASCARILLA
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function ori($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_ORI_BAILE_RANDOM_TRAJE_CAMISA,
                Resources::GIF_ORI_BAILE_AVANZA
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function nano($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_NANO_PINZAS,
                Resources::GIF_NANO_CLEARLY_SOCIALIST,
                Resources::GIF_NANO_ABANICO_BODA
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function iban($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_IBAN_AGE_TAPIA,
                Resources::GIF_IBAN_PEDOS_MANOS
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function luis($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_BARTOL_ALAS_BARCELONA,
                Resources::GIF_BARTOL_BANDERA_EUROPA_VENGUE,
                Resources::GIF_BARTOLMORT
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function tapia($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_SALTO_HUEVO,
                Resources::GIF_OSO_ACORDEON
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function rulo($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function lucho($endpoint, $request)
        {
            
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_CULO_LUCHO_FILETE
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function vicente($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_VICENTE_AGRESION_BUS_BODA
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
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
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_CARRACEDO_HOLA_VERTICAL
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function borja($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function zato($endpoint, $request)
        {
            return $this->_funcion_pendiente($endpoint, $request);
        }

        private function riojas($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_COCACOLAS_PARACA,
                Resources::GIF_RIOJAS_BAILE_SILLAS,
                Resources::GIF_COCACOLAS
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
            
        }

        private function paco($endpoint, $request)
        {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_PACO_CABALLO_LOCO_SILLA
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function cas($endpoint, $request) {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_CAS_PRIMERISIMO_PRIMER_PLANO, 
                Resources::GIF_CAS_EXTRATITANIO_HAWAIIAN,
                Resources::GIF_CAS_CESTO_BAILE,
                Resources::GIF_CAS_BAILE_BANDERAS
            ]);
            return Response::create_doc_response($endpoint,  $request->get_chat_id(), $file_id);
        }

        private function filete($endpoint, $request) {
            if (rand(0,3) === 0) return $this->_funcion_pendiente($endpoint, $request);
            $file_id = Utils::aleatorio([
                Resources::GIF_FILETE_BAMBOLEO_SANSE,
                Resources::GIF_FILETE_ACERCANDOSE_EN_LA_OSCURIDAD,
                Resources::GIF_FILETE_PONIENDOSE_SOMBRERO_BOCA,
                Resources::GIF_FILETE_BAMBOLEO_PISCINA,
                Resources::GIF_FILETE_RODILLAS_PIPO
            ]);
            return Response::create_doc_response($endpoint,  $request->get_chat_id(), $file_id);
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
                Resources::IMG_LA_COSA_TIENE_MIGA,
                Resources::IMG_LA_COSA_NO_SE_PUEDE_DEJAR_A_MEDIAS,
                Resources::IMG_A_VER_COMO_AVANZA_LA_COSA,
                Resources::IMG_ADUANA_COSAS_QUE_IMPORTAN,
                Resources::IMG_CADA_COSA_EN_SU_MOMENTO,
                Resources::IMG_COMO_EL_QUE_NO_QUIERE_LA_COSA,
                Resources::IMG_LA_COSA_ESTA_BAJO_CONTROL,
                Resources::IMG_LA_COSA_NO_ESTA_PARA_BOLLOS,
				Resources::IMG_LA_COSA_PASANDO_CASTANO_OSCURO,
				Resources::IMG_LA_COSA_DECAYENDO,
				Resources::IMG_LA_COSA_ESPADA_Y_PARED,
				Resources::IMG_LA_COSA_FUNCIONAN_ASI,
				Resources::IMG_LA_COSA_NO_ESTA_CLARA,
				Resources::IMG_LA_COSA_NO_ESTA_PARA_JUEGOS,
				Resources::IMG_LA_COSA_NO_ERA_TAN_GRAVE,
				Resources::IMG_LA_COSA_PROMETE,
				Resources::IMG_LA_COSA_NO_PINTA_NADA_BIEN,
				Resources::IMG_LAS_COSAS_CLARAS_CHOCOLATE_ESTESO,
				Resources::IMG_CUENTAME_LAS_COSAS_PELOS_SENALES,
				Resources::IMG_LAS_COSAS_PALACIO_DESPACIO,
				Resources::IMG_LAS_COSAS_NUNCA_ENTIENDO,
				Resources::IMG_LAS_COSAS_REACCIONANDO_TIEMPO,
				Resources::IMG_LA_COSA_TIENE_WASSA,
				Resources::IMG_COSAS_VEREDES_AMIGO_SANCHO,
				Resources::IMG_LA_COSA_ESTA_QUE_ARDE,
				Resources::IMG_LA_COSA_ESTA_QUE_TRINA,
				Resources::IMG_LA_COSA_MANDA_HUEVOS,
				Resources::IMG_LA_COSA_NO_PASO_A_MAYORES,
				Resources::IMG_LA_COSA_NO_SALIO_BIEN,
				Resources::IMG_LA_COSA_SE_NOS_VA_DE_LAS_MANOS,
				Resources::IMG_LA_COSA_SE_PONE_BIEN,
				Resources::IMG_LA_COSA_VA_SOBRE_RUEDAS,
				Resources::IMG_TIENE_TELA_LA_COSA,
				Resources::IMG_LIADO_ENTRE_UN_COSA_Y_OTRAS,
				Resources::IMG_MANDA_HUEVOS_LA_COSA,
				Resources::IMG_UNA_COSA_ES_SEGURA,
				Resources::IMG_UNA_COSA_LLEVA_A_LA_OTRA,
				Resources::IMG_LA_COSA_ESTA_EN_JUEGO,
				Resources::IMG_PODRIA_PASAR_CUALQUIER_COSA,
				Resources::IMG_PINTA_MAL_LA_COSA,
				Resources::IMG_A_VER_COMO_SALE_LA_COSA,
				Resources::IMG_SIMPSONS_LAS_COSAS_Y_ASI_CONTADO,
				Resources::IMG_LA_COSA_ESTA_EN_STAND_BY,
				Resources::IMG_TIENE_HUEVOS_LA_COSA
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
                $response= Response::create_photo_response($endpoint, $request->get_chat_id(), Resources::IMG_A_QUE_TE_DEDICAS_FUNCIONARIO_MAMADA);
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
                Resources::GIF_GREASE_TELL_ME_MORE,
                Resources::GIF_SASHA_CUENTAME_MAS,
                Resources::GIF_MONO_SIENDO_PEINADO
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function siagesi($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_GATO_ACARICIADO);
        }

        private function insultar($endpoint, $request){
            $intro = Utils::aleatorio([
                "Pues veamos,", "Ahora que lo dices", 
                "Me sabe mal, pero", "Lo cierto es que", 
                "Jajaja, vale,", "Por todos es sabido que",
                "Sinceramente,", "Pues está claro que",
                "Creo que"
            ]);
            $humano = Utils::get_humano_random();
            $insulto =  Utils::aleatorio(Resources::INSULTO_DIRECTO);
            $text = "${intro} ${humano} es un ${insulto}.";
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }
        
        private function insultaa($endpoint, $request){
            $text = null;
            $params = $request->get_command_params();
            if(count($params)>0){
                if(rand(0,7) === 3) {
                    return $this->insulto_argentino($endpoint, $request);
                }
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
                    "Buff, qué jodido INÚTIL.",
                    "Menudo ANORMAL, no sabes hacer nada."
                ]);
            }
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }

        private function stopmame($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = Utils::aleatorio([
                "Jajajajaja... Pero ${humano}, ¡¡si eres un puto MAMAO!!",
                "Con lo puto MAMAO que eres, ${humano}. Si es que no te lo crees ni tú.",
                "Pero qué dices, ${humano}. Si vas de merla en merla. ¡¡MAMAO!!",
                "${humano} diciendo que stop mame... ¡¡Stop mame!! Jajaja menudo MAMAO...",
                "Pero ${humano}, con las merlas que te pillas, jajaja, JAJAJA, stop mame dice..."
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }

        private function gordo($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = Utils::aleatorio([
                "${humano}, no sé qué quieres decir, pero hay que tener respeto con los cementerios de canelones.",
                "Hola, ${humano}. Me cuesta entender qué pretendías. Me parece de muy mal gusto querer meterte con los obesos."
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }        

        private function calbo($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $text = Utils::aleatorio([
                "Sí, ${humano}, sí. Este es un buen grupo de CALBOS.",
                "Es absurda la POCA cantidad de PELO que hay por aquí, ${humano}.",
                "Mucho flequillo de carne veo por aquí.",
                "Y venga mondos... ¡¡MONDOS!! Habrá que fletar un viaje a Turquía, ¿eh, ${humano}?"
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }        

        private function soplar($endpoint, $request){
            $file_id = Utils::aleatorio([
                Resources::GIF_PALMERAS_HURACAN,
                Resources::GIF_CULO_SOPLANDO,
                Resources::GIF_RICHY_ME_SUDA_LOS_COJONES,
                Resources::GIF_NICOLAS_CAGE_MELENA_VIENTO
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function racismo($endpoint, $request){
            $file_id = Utils::aleatorio([
                Resources::GIF_NO_RACISMO_BANDERIN_CHAMPIONS,
                Resources::GIF_NANO_LUCHO_CONECTA_NAZI,
                Resources::GIF_NO_RACISMO_CARAS_HITLER
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function elbobo($endpoint, $request){
            return Response::create_doc_response($endpoint, $request->get_chat_id(), Resources::GIF_PEDRO_SANCHEZ_RIENDO);
        }
        
        private function españa($endpoint, $request){
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

        private function gol($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_GOL_INIESTA_FINA_MUNDIAL, 
                Resources::GIF_CRISTIANO_PORTUGAL_SIUUU
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function acierto($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_CARRACEDO_OTRO_PICK, 
                Resources::GIF_MASCARAS_RAVE_NORUEGA, 
                Resources::GIF_CRISTIANO_PORTUGAL_SIUUU,
                Resources::GIF_LLULL_PALMAS_VAMOS, 
                Resources::GIF_MASCARAS_BAILE_BODA,
                Resources::GIF_BAILE_FIESTA,
                Resources::GIF_PIPO_BAILA,
                Resources::GIF_BAILE_SIN_CAMISETA
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function fallo($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_DICAPRIO_MAKE_IT_RAIN,
                Resources::GIF_CRISTIANO_NEGANDO,
                Resources::GIF_CARRITO_HOMELESS,
                Resources::GIF_NIGGA_MAKE_IT_RAIN
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function revision($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_JOAQUIN_VAR
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function resaca($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_FERDINAND_SI_OTRA_RESACA
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }
        
        private function vengue($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_BARTOL_BANDERA_EUROPA_VENGUE,
                Resources::GIF_ARABES_BAILANDO_VENGUE
            ]);
            return Response::create_doc_response($endpoint, $request->get_chat_id(), $file_id);
        }

        private function merla($endpoint, $request){
            $file_id=Utils::aleatorio([
                Resources::GIF_FERDINAND_VENGA_VENGA_OTRA_MERLA,
                Resources::GIF_FERDINAND_NO_OS_HUELE_A_MERLA
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

        private function tarancon($endpoint, $request){
            $text = "Estaba p'ahí en la boda de Tarancón, ";
            $text .= "y estaba allí un paisano con el gorrillo de paja ";
            $text .= "y el cigarrilo allí, y estaba doblao. Estaba así... estaba doblao... ";
            $text .= "Unos calores, un tío gordo, ¿como Manolo el de \"Manos a la obra\"? Igual. ";
            $text .= "Le digo: ". PHP_EOL;
            $text .= "- ¿Qué pasa fenómeno?" . PHP_EOL;
            $text .= "Y dice: " . PHP_EOL;
            $text .= "- ¿De dóóónde eres?" . PHP_EOL;
            $text .= "- De Salamanca." . PHP_EOL;
            $text .= "- Salamancaaaa. Que tú, que el de allí, ";
            $text .= "¡¡hala!! que no estaba muerto.. ¡¡Aaaay!!" . PHP_EOL;
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }

        private function insulto_argentino($endpoint, $request){
            $humano = Utils::get_humano_name($request->get_from_id());
            $humano = strtoupper($humano);
            $text = Utils::aleatorio([
                "IMAGINATE QUE LA APUESTA ES UN REMEDIO CONTRA LA ALOPECIA Y ACIERTALA, ${humano}.",
                "PERO HAZ ALGO PUTO PELADO DE COJONES INÚTIL DE MIERDA DESIERTO DE PELO ANTICIPO DE QUIMIOTERAPIA DEJA DE APOSTAR COMO UN PUTO SUBNORMAL ${humano} QUE SOS UN PUTO CALVO RIDÍCULO HACE ALGO BIEN FORRO DE MIERDA",
                "${humano} GORDO PUTO CALBO CÓMO SE TE OCURRE NO ACERTAR LAS APUESTAS COGOLLO DE ABONO SIN CUELLO ASI NO GANAMOS NADA QUÉ QUERÉS HACER BOTIJO DE GONORREA SI NO SABES APOSTAR A UNOS HIJOS DE REMIL BARCOS DE ESTIÉRCOL PECHOFRÍOS ANDATE A LA CONCHA DE LA LORA",
                "${humano} PUTO CEMENTERIO DE PEINES, FLEQUILLO DE CARNE, MORITE HIJO DE PUTA",
                "${humano} QUE TE PASA EN LA CABEZA, HIJO DE UN SISTEMA SOLAR REBOSANTE DE PUTAS, CABEZA DE RODILLA, SALAME, FORRO, LA CONCHA DE TU HERMANA, METETE EN UN COHETE Y ATERRIZÁ EN UNA GALAXIA DONDE NO SE TE PUEDA VER NI CON UN SATÉLITE, DEDICATE A ESQUILAR OVEJAS CALVO",
                "${humano} MALPARIDO HIJO DE 500MIL PUTAS SIDOSAS, GENOCIDA DE PEINES, TE VAN A SACAR EN LA CAJUELA, SOS UN IMPRESENTABLE MALDITA BOLSA DE SIDA NO ME PODES DAR UNA SOLA ALEGRIA CALVO PELOTUDO ME CAGO EN TUS MUERTOS",
                "${humano} HIJO DE 800 CIVILIZACIONES DE RAMERAS BÍBLICAS. CÓMO CARAJO VAS A FALLAR ESA APUESTA. QUE ALGUIEN VELE A CAJÓN CERRADO A ESE MUERTO CARAJO.",
                "${humano} LA CONCHA DE LA LORA, SOS UN PUTO TETRAPLEJICO. PUTO TARADO CATADOR DE LIQUIDO PRESEMINAL SOS UNA MIERDA, VIOLADOR DE PEINES PAJERO DE BRAZZERS",
                "${humano} TENES EL PECHO MAS HELADO QUE LA CONCHA DE ELSA DE FROZEN HIJO DE LA RECONTRACONCHA MADRE DE UN MONO CON SIFILIS",
                "${humano} SOS UN CÁNCER, SOS PEOR QUE UN CÁNCER. SI AL CÁNCER LE DICEN TENÉS ${humano} SE DEPRIME Y SE SUICIDA PARA NO SUFRIR"
            ]);
            return Response::create_text_response($endpoint, $request->get_chat_id(), $text);
        }

        private function _funcion_pendiente($endpoint, $request) {
            $file_id = Utils::aleatorio([
                Resources::GIF_PERRO_TECLEANDO,
                Resources::GIF_MASCARAS_RAVE_NORUEGA,
                Resources::STK_LUCAS_VAZQUEZ,
                Resources::GIF_PEDRO_SANCHEZ_RIENDO,
                Resources::GIF_RINCON_CORTEN,
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
