<?php

    class Response{
        
        const TYPE_TEXT = 1;
        const TYPE_DOC = 2;
        const TYPE_PHOTO = 3;
        const TYPE_STICKER = 4;
        const TYPE_AUDIO = 5;
        const TYPE_VIDEO = 6;
        const TYPE_VOICE = 7;
        const TYPE_CHAT_ACTION = 8;
        const TYPE_KEYBOARD = 9;
        const TYPE_DELETE = 10;
        
        private $chat_id;
        private $endpoint;
        private $type;
        
        public $text;
        public $markdown=false;
        public $file_id;
        public $chat_action;
        public $caption;
        public $reply_markup;
        public $message_id;
        
        /**
         * Crea un objeto Response de tipo Text, con el markdown activado.
        */
        static function create_text_response($endpoint, $chat_id, $text){
            $response = new Response($endpoint, $chat_id, self::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            return $response;
        }
        
        /**
         * Crea un objeto Response de tipo Text, con el markdown activado.
        */
        static function create_text_replymarkup_response($endpoint, $chat_id, $text, $reply_markup){
            $response = new Response($endpoint, $chat_id, self::TYPE_TEXT);
            $response->text=$text;
            $response->markdown=true;
            $response->reply_markup=$reply_markup;
            return $response;
        }
        
        /**
         * Crea un objeto Response de tipo Document.
        */
        static function create_doc_response($endpoint, $chat_id, $file_id){
            $response = new Response($endpoint, $chat_id, self::TYPE_DOC);
            $response->file_id=$file_id;
            return $response;
        }
        
         /**
         * Crea un objeto Response de tipo Photo.
        */
        static function create_photo_response($endpoint, $chat_id, $file_id){
            $response = new Response($endpoint, $chat_id, self::TYPE_PHOTO);
            $response->file_id=$file_id;
            return $response;
        }
        
         /**
         * Crea un objeto Response de tipo Sticker.
        */
        static function create_sticker_response($endpoint, $chat_id, $file_id){
            $response = new Response($endpoint, $chat_id, self::TYPE_STICKER);
            $response->file_id=$file_id;
            return $response;
        }
        
         /**
         * Crea un objeto Response de tipo Audio.
        */
        static function create_audio_response($endpoint, $chat_id, $file_id){
            $response = new Response($endpoint, $chat_id, self::TYPE_AUDIO);
            $response->file_id=$file_id;
            return $response;
        }
        
         /**
         * Crea un objeto Response de tipo Video.
        */
        static function create_video_response($endpoint, $chat_id, $file_id){
            $response = new Response($endpoint, $chat_id, self::TYPE_VIDEO);
            $response->file_id=$file_id;
            return $response;
        }
        
        /**
         * Crea un objeto Response de tipo Voice.
        */
        static function create_voice_response($endpoint, $chat_id, $file_id){
            $response = new Response($endpoint, $chat_id, self::TYPE_VOICE);
            $response->file_id=$file_id;
            return $response;
        }
        
         /**
         * Crea un objeto Response de tipo Chat Action typing.
        */
        static function create_typing_response($endpoint, $chat_id){
            $response = new Response($endpoint, $chat_id, self::TYPE_CHAT_ACTION);
            $response->chat_action='typing';
            return $response;
        }
        
        /**
         * Crea un objeto Response de tipo Delete Message.
        */
        static function create_delete_response($endpoint, $chat_id, $message_id){
            $response = new Response($endpoint, $chat_id, self::TYPE_DELETE);
            $response->message_id=$message_id;
            return $response;
        }
        
        public function __construct($endpoint, $chat_id, $type){
            $this->endpoint=$endpoint;
            $this->chat_id=$chat_id;
            $this->type=$type;
        }
        
        
        public function send(){
            //var_dump($this);
            $data= [
                'chat_id' => (int) $this->chat_id
            ];
            
            $accion='';
            switch ($this->type) {
	            case self::TYPE_TEXT:
	                $data["text"]=$this->text;
	                if($this->markdown){
	                    $data["parse_mode"]="Markdown";
	                }
	                if($this->reply_markup){
	                    $data["reply_markup"]=$this->reply_markup;
	                }
	                
	                $accion='/sendMessage';
	                break;
                case self::TYPE_DOC:
	                $data["document"]=$this->file_id;
	                $accion='/sendDocument';
	                break;
                case self::TYPE_PHOTO:
	                $data["photo"]=$this->file_id;
	                if(!is_null($this->caption)){
	                    $data["caption"]=$this->caption;
	                }
	                $accion='/sendPhoto';
	                break;
                case self::TYPE_STICKER:
	                $data["sticker"]=$this->file_id;
	                $accion='/sendSticker';
	                break;
                case self::TYPE_AUDIO:
	                $data["audio"]=$this->file_id;
	                $accion='/sendAudio';
	                break;
                case self::TYPE_VIDEO:
	                $data["video"]=$this->file_id;
	                $accion='/sendVideo';
	                break;
                case self::TYPE_VOICE:
	                $data["voice"]=$this->file_id;
	                $accion='/sendVoice';
	                break;
                case self::TYPE_CHAT_ACTION:
                    $data["action"]=$this->chat_action;
                    $accion='/sendChatAction';
	                break;
	            case self::TYPE_DELETE:
	                $data["message_id"]=$this->message_id;
                    $accion='/deleteMessage';
	                break;
	                
            }
            
            
            try {       
            
                $options = [
                    CURLOPT_URL => $this->endpoint. $accion,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => null,
                    CURLOPT_POSTFIELDS => null
                ];
            
                if ($data) {
                    $options[CURLOPT_POST] = true;
                    $options[CURLOPT_POSTFIELDS] = $data;
                }
            
               // var_dump($data);
                $curl = curl_init();
                curl_setopt_array($curl, $options);
                $result = curl_exec($curl);
                return $result;
            
            } catch (Exception $e) {
                syslog(LOG_ERR, '[' . getmypid() . '] ERROR Exception al enviar el mensaje: ' . $e–>getMessage());
            }
            
        }
        
        public function to_string(){
            $result='';
            $result.= 'chat_id: '.$this->chat_id.PHP_EOL;
            $result.= 'endpoint: '.substr($this->endpoint,0,30).'...'.PHP_EOL;
            $result.= 'type: '.$this->type.PHP_EOL;
            $result.= 'text: '.$this->text.PHP_EOL;
            $result.= 'markdown: '.$this->markdown.PHP_EOL;
            $result.= 'file_id: '.$this->file_id.PHP_EOL;
            $result.= 'chat_action: '.$this->chat_action.PHP_EOL;
            
            if( property_exists($this, 'command_params') &&  $this->command_params!=null && $this->command_params!==false){
                 $result.= 'command_params: ';
                foreach ($this->command_params as &$valor) {
                     $result.= $valor.', ';
                }
            }
            return $result;
        }
        
    }
    
?>