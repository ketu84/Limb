<?php

   require_once __DIR__ . '/../vo/chatVO.php';
   require_once __DIR__ . '/../vo/grupoVO.php';
    
    class ChatDAO{
        private $log;
        
        public function __construct(){
            $this->log = Logger::getLogger('com.hotelpene.limbBot.dao.ChatDAO');
        }
        
        public function select($chatid){
            $this->log->debug("Select chat: ".$chatid);
            
            $sql_query_chat = " SELECT nombre
                                FROM CHATS 
                                WHERE chat_id = $chatid";
            $sql_query_grupo = "SELECT G.id as id, G.nombre, G.url_api, G.url_web
                                FROM GRUPOS G
                                INNER JOIN CHATS_GRUPO CG ON CG.grupo_id = G.id and CG.chat_id = $chatid";
            try {
                $db = Database::getInstance();
                $dbCon = $db->getConnection();
                
                $stmt   = $dbCon->query($sql_query_chat);
                $chat  = $stmt->fetch();
                
                $stmt_grupo   = $dbCon->query($sql_query_grupo);
                $arrGrupo  = $stmt_grupo->fetchAll(PDO::FETCH_CLASS, "grupoVO");
                
                $chatVo = new ChatVO();
                $chatVo->id=$chatid;
                $chatVo->nombre=$chat['nombre'];
                $chatVo->arrGrupo=$arrGrupo;
                
                $dbCon = null;
             
                return $chatVo;
            }catch(PDOException $e) {
                $this->log->error($e->getTraceAsString());
            }
        }
        
        public function selectGruposChat($chatid){
            $this->log->debug("Select grupos chat");
            
            $sql_query_grupo = "SELECT G.id, G.nombre, G.url_api, G.url_web 
                                FROM GRUPOS G
                                INNER JOIN CHATS_GRUPO CG ON CG.grupo_id = G.id and CG.chat_id = $chatid";
            try {
                $db = Database::getInstance();
                $dbCon = $db->getConnection();
                
                $stmt_grupo   = $dbCon->query($sql_query_grupo);
                $arrGrupo  = $stmt_grupo->fetchAll(PDO::FETCH_CLASS, "grupoVO");
             
                return $arrGrupo;
            }catch(PDOException $e) {
                $this->log->error($e->getTraceAsString());
            }
        }
        
        public function selectGrupoPorNombre($nomGrupo){
            $this->log->debug("Select grupo por nombre");
            
            $sql_query_grupo = "SELECT id, nombre, url_api, url_web 
                                FROM GRUPOS
                                WHERE nombre= '$nomGrupo'";
            try {
                $db = Database::getInstance();
                $dbCon = $db->getConnection();
                
                $stmt_grupo= $dbCon->query($sql_query_grupo);
                $arrGrupo  = $stmt_grupo->fetchAll(PDO::FETCH_CLASS, "grupoVO");
             
                return $arrGrupo;
            }catch(PDOException $e) {
                $this->log->error($e->getTraceAsString());
            }
        }
    }
    
?>