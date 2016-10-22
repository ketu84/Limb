<?php

    require_once __DIR__ . '/../vo/currentCMDVO.php';
    
    class CurrentCMDDAO{
        private $log;
        
        public function __construct(){
            $this->log = Logger::getLogger('com.hotelpene.limbBot.dao.CurrentCMDDAO');
        }
        
        public function select($chatid){
            $this->log->debug("Select currnet cmd: ".$chatid);
            
            $sql_query = "SELECT chat_id, cmd, fec_actividad, grupo FROM CMD_CURRENT WHERE chat_id = $chatid";
            try {
                $db = Database::getInstance();
                $dbCon = $db->getConnection();
                $stmt   = $dbCon->query($sql_query);
                $currentCMD  = $stmt->fetch();
                $dbCon = null;
             
                return $currentCMD;
            }catch(PDOException $e) {
                $this->log->error($e->getTraceAsString());
            }
        }
        
        public function insert($cmd){
            $this->log->debug("Insertando current cmd, chatid: ".$cmd->chat_id);
            
            $db = Database::getInstance();
            $dbCon = $db->getConnection();
            
            try{
                
                $consulta=$dbCon->prepare("INSERT INTO CMD_CURRENT (chat_id, cmd, fec_actividad, grupo) VALUES ( :chat_id, :cmd, now(), :grupo)");
                
                $consulta->bindValue(':chat_id', $cmd->chat_id, PDO::PARAM_INT);
                $consulta->bindValue(':cmd', $cmd->cmd, PDO::PARAM_STR);
                $consulta->bindValue(':grupo', $cmd->grupo, PDO::PARAM_INT);
                
                $estado=$consulta->execute();
                
                //$cmd->id=$dbCon->lastInsertId();
                return $cmd;
            }catch(Exception $e){
                $this->log->error($e);
            }
        }
        
        public function update($chatid){
            $this->log->debug("Actualizando current cmd, chatid: ".$chatid);
            
            $db = Database::getInstance();
            $dbCon = $db->getConnection();
    
            $consulta=$dbCon->prepare("UPDATE CMD_CURRENT SET fec_actividad=now() WHERE chat_id=:chat_id");
            $estado=$consulta->execute(
                array(
                    'chat_id'=> $chatid
                    )
                );
            return true;
        }
        
        public function updateGrupo($chatid, $grupo){
            $this->log->debug("Actualizando grupo cmd, grupo: ".$grupo);
            
            $db = Database::getInstance();
            $dbCon = $db->getConnection();
    
            $consulta=$dbCon->prepare("UPDATE CMD_CURRENT SET grupo=:grupo WHERE chat_id=:chat_id");
            $estado=$consulta->execute(
                array(
                    'grupo'=>$grupo,
                    'chat_id'=> $chatid
                    )
                );
            return true;
        }
        
        public function delete($chatid){
            $this->log->debug("Eliminando current cmd: ".$chatid);
            
            $sql_query = "DELETE FROM CMD_CURRENT WHERE chat_id=$chatid";
            try {
                $db = Database::getInstance();
                $dbCon = $db->getConnection();
                
                $consulta   = $dbCon->prepare($sql_query);
                $consulta->execute();
                $dbCon = null;
                
                if ($consulta->rowCount() == 1){
                    return true;
                }
                return false;
             
            }catch(PDOException $e) {
                $this->log->error($e->getTraceAsString());
            }    
        }
        
    }
    
?>