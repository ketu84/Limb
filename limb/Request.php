<?php
    require_once __DIR__ . '/RequestException.php';
    
    class Request{
        
        const TYPE_TEXT = 1;
        const TYPE_DOC = 2;
        const TYPE_PHOTO = 3;
        const TYPE_STICKER = 4;
        const TYPE_AUDIO = 5;
        const TYPE_VIDEO = 6;
        const TYPE_VOICE = 7;
        
        private $chat_id;
        private $chat_type;
        private $is_chat_private=false;
        private $from_id;
        private $message_type;
        private $message_type_desc;
        private $file_id;
        private $text;
        private $command;
        private $command_params;
        
        private $log ;
        
        public function __construct($message, $telegramAPI) {
            if($telegramAPI) {
                $message = json_decode($message, true);
                $this->RequestTelegramAPI($message);
            }
            else {
                $this->RequestPOST($message);
            }
        }
        
        
        private function RequestTelegramAPI($message) {
            
            $log= Logger::getLogger('com.hotelpene.limbBot.bot');
            if(is_null($message)){
                throw new RequestException("El mensaje es nulo");
            }
            
            //Se convierte el mensaje, sea del tipo que sea a mensaje
            if(isset($message["message"]["chat"]["id"])){
                $log->debug("Mensaje normal");
                $message=$message;
            }elseif(isset($message["edited_message"]["chat"]["id"])){
                $log->debug("Mensaje editado");
                $message["message"]=$message["edited_message"];
            }elseif(isset($message["callback_query"]["message"]["chat"]["id"])){
                $log->debug("Mensaje callback query");
                $message["message"]=$message["callback_query"]["message"];
                $message["message"]["text"]=$message["callback_query"]["data"];
                
                
                
                
                
            }
            
           // $log->debug("Mensaje:".$message);
            //var_dump($message);
            
            //Se obtiene el chat_id
            if(isset($message["message"]["chat"]["id"])){
                $this->chat_id=$message["message"]["chat"]["id"];
            }else{
                throw new RequestException("No hay chat_id");
            }
            
            $this->from_id=$message['message']['from']['id'];
            
            if(isset($message['message']['chat']['type'])){
                $this->chat_type=$message['message']['chat']['type'];
                if($this->chat_type=='private'){
                    $this->is_chat_private=true;
                }
            }
            
            if(isset($message["message"]["text"])){
                $this->message_type=self::TYPE_TEXT;
                $this->message_type_desc='texto';
                $this->text=$message["message"]["text"];
                
                //Se obtiene el comando
                if((strpos($this->text,'/') !== false) && (strpos($this->text,'/')==0)){
                    
                    $finCommand=strpos($this->text,'@');
                    
                    if($finCommand===false){
                        $finCommand=strpos($this->text,' ');
                        if($finCommand===false){
                            $finCommand=strlen($this->text);
                        }else{
                            $finCommand=$finCommand-1;
                        }
                    }else{
                        $finCommand=$finCommand-1;
                    }
                    
                    $this->command=strtolower(substr($this->text,1,$finCommand));
                    
                    //Se obtienen los parámetros del comando
                    $this->command_params=explode(' ',$this->text);
                    array_splice($this->command_params, 0,1);
                    
                }
            
                
            }elseif(isset($message["message"]["document"])){
                $this->message_type=self::TYPE_DOC;
                $this->message_type_desc='documento';
                $this->file_id=$message['message']['document']['file_id'];
                
            }elseif(isset($message["message"]["photo"])){
                $this->message_type=self::TYPE_PHOTO;
                $this->message_type_desc='foto';
                $this->file_id=$message['message']['photo'][0]['file_id'];
                
            }elseif(isset($message["message"]["sticker"])){
                $this->message_type=self::TYPE_STICKER;
                $this->message_type_desc='sticket';
                $this->file_id=$message['message']['sticker']['file_id'];
                
            }elseif(isset($message["message"]["voice"])){
                $this->message_type=self::TYPE_VOICE;
                $this->message_type_desc='voz';
                $this->file_id=$message['message']['voice']['file_id'];
                
            }elseif(isset($message["message"]["audio"])){
                $this->message_type=self::TYPE_AUDIO;
                $this->message_type_desc='audio';
                $this->file_id=$message['message']['audio']['file_id'];
                
            }elseif(isset($message["message"]["video"])){
                $this->message_type=self::TYPE_VIDEO;
                $this->message_type_desc='video';
                $this->file_id=$message['message']['video']['file_id'];
            }else{
                 throw new RequestException("Tipo de mensaje no reconocido");
            }
            
        }
        
        
        private function RequestPOST($message) {
            
            $log= Logger::getLogger('com.hotelpene.limbBot.bot');
            if(is_null($message)){
                throw new RequestException("El mensaje es nulo");
            }

            //Se obtiene el chat_id
            if(isset($message["chat_id"])){
                $this->chat_id=$message["chat_id"];
            }else{
                throw new RequestException("No hay chat_id");
            }
            
            $this->from_id=$message["from_id"];
            $this->is_chat_private=false;
            $this->chat_type=='public'; // es public
            
            $this->message_type=self::TYPE_TEXT;
            $this->message_type_desc='texto';
            $this->text=$message["text"];
            
            //Se obtiene el comando
            if((strpos($this->text,'/') !== false) && (strpos($this->text,'/')==0)){
                
                $finCommand=strpos($this->text,'@');
                
                if($finCommand===false){
                    $finCommand=strpos($this->text,' ');
                    if($finCommand===false){
                        $finCommand=strlen($this->text);
                    }else{
                        $finCommand=$finCommand-1;
                    }
                }else{
                    $finCommand=$finCommand-1;
                }
                
                $this->command=strtolower(substr($this->text,1,$finCommand));
                
                //Se obtienen los parámetros del comando
                $this->command_params=explode(' ',$this->text);
                array_splice($this->command_params, 0,1);
                
                
            }            
        }
        
        
        public function is_private_chat(){
            return $this->is_chat_private;
        }
        
        public function get_chat_id(){
            return $this->chat_id;    
        }
        
        public function get_message_type(){
            return $this->message_type;
        }
        
        public function get_message_type_desc(){
            return $this->message_type_desc;
        }
        
        public function get_file_id(){
            return $this->file_id;
        }
        
        public function get_command(){
            return $this->command;
        }
        
        public function get_command_params(){
            return $this->command_params;
        }
        
        public function set_command_params($params){
            $this->command_params=$params;
        }
        
        public function get_from_id(){
            return $this->from_id;
        }
        
        public function get_text(){
            return $this->text;
        }
        
        public function to_string(){
            $result='';
            $result.= 'chat_id: '.$this->chat_id.PHP_EOL;
            $result.= 'chat_type: '.$this->chat_type.PHP_EOL;
            $result.= 'is_chat_private: '.$this->is_chat_private.PHP_EOL;
            $result.= 'from_id: '.$this->from_id.PHP_EOL;
            $result.= 'message_type: '.$this->message_type.PHP_EOL;
            $result.= 'file_id: '.$this->file_id.PHP_EOL;
            $result.= 'text: '.$this->text.PHP_EOL;
            $result.= 'command: '.$this->command.PHP_EOL;
            
            if($this->command_params!=null && $this->command_params!==false){
                 $result.= 'command_params: ';
                foreach ($this->command_params as &$valor) {
                     $result.= $valor.', ';
                }
            }
            return $result;        
        }
    }

?>