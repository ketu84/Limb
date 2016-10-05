<?php

    require_once __DIR__ . '/../vo/apostarCMDVO.php';
    
    class ApostarCMDDAO{
        private $log;
        
        public function __construct(){
            $this->log = Logger::getLogger('com.hotelpene.limbBot.dao.ApostarCMDDAO');
        }
        
        public function select($chatid){
            $this->log->debug("Select apostar cmd: ".$chatid);
            
            $sql_query = "SELECT chat_id, descrip, importe, partido FROM CMD_APOSTAR WHERE chat_id = $chatid";
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
            $this->log->debug("Insertando apostar cmd, chatid: ".$cmd->chat_id);
            
            $db = Database::getInstance();
            $dbCon = $db->getConnection();
            
            try{
                
                $consulta=$dbCon->prepare("INSERT INTO CMD_APOSTAR (chat_id, descrip, importe, partido) VALUES ( :chat_id, :descrip, :importe, :partido)");
                
                $consulta->bindValue(':chat_id', $cmd->chat_id, PDO::PARAM_INT);
                
                if( $cmd->descrip!=null){
                    $consulta->bindValue(':descrip', $cmd->descrip, PDO::PARAM_STR);
                }else{
                    $consulta->bindValue(':descrip', null, PDO::PARAM_STR);
                }
                
                if( $cmd->importe!=null){
                    $consulta->bindValue(':importe', $cmd->importe, PDO::PARAM_INT);
                }else{
                    $consulta->bindValue(':importe', null, PDO::PARAM_INT);
                }
                
                if( $cmd->partido!=null){
                    $consulta->bindValue(':partido', $cmd->partido, PDO::PARAM_INT);
                }else{
                    $consulta->bindValue(':partido', null, PDO::PARAM_INT);
                }
                
                $estado=$consulta->execute();
                
                //$cmd->id=$dbCon->lastInsertId();
                return $cmd;
            }catch(Exception $e){
                $this->log->error($e);
            }
        }
        
        public function update($cmd){
            $this->log->debug("Actualizando apostar cmd, chatid: ".$cmd->chat_id);
            //var_dump($cmd);
            $db = Database::getInstance();
            $dbCon = $db->getConnection();
    
            $consulta=$dbCon->prepare("UPDATE CMD_APOSTAR SET descrip=:descrip, importe=:importe, partido=:partido WHERE chat_id=:chat_id");
            
            $consulta->bindValue(':chat_id', $cmd->chat_id, PDO::PARAM_INT);
            if( $cmd->descrip!=null){
                $consulta->bindValue(':descrip', $cmd->descrip, PDO::PARAM_STR);
            }else{
                $consulta->bindValue(':descrip', null, PDO::PARAM_STR);
            }
            
            if( $cmd->importe!=null){
                $consulta->bindValue(':importe', $cmd->importe, PDO::PARAM_INT);
            }else{
                $consulta->bindValue(':importe', null, PDO::PARAM_INT);
            }
            
            if( $cmd->partido!=null){
                $consulta->bindValue(':partido', $cmd->partido, PDO::PARAM_INT);
            }else{
                $consulta->bindValue(':partido', null, PDO::PARAM_INT);
            }
            $estado=$consulta->execute();
            return true;
        }
        
        public function delete($chatid){
            $this->log->debug("Eliminando apostar cmd: ".$chatid);
            
            $sql_query = "DELETE FROM CMD_APOSTAR WHERE chat_id=$chatid";
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