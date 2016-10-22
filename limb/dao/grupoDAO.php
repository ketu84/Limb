<?php

   require_once __DIR__ . '/../vo/grupoVO.php';
    
    class GrupoDAO{
        private $log;
        
        public function __construct(){
            $this->log = Logger::getLogger('com.hotelpene.limbBot.dao.GrupoDAO');
        }
        
        public function select($id){
            $this->log->debug("Select grupo: ".$id);
            
            $sql_query_grupo = "SELECT G.id as id, G.nombre, G.url_api, G.url_web
                                FROM GRUPOS G
                                WHERE G.id = $id";
            try {
                $db = Database::getInstance();
                $dbCon = $db->getConnection();
                
             
                $stmt_grupo   = $dbCon->query($sql_query_grupo);
                $arrGrupo  = $stmt_grupo->fetchAll(PDO::FETCH_CLASS, "grupoVO");
                
                $dbCon = null;
                if(count($arrGrupo)>0){
                    return $arrGrupo[0];
                }
                return null;
            }catch(PDOException $e) {
                $this->log->error($e->getTraceAsString());
            }
        }
        
        public function selectByNombre($nombre){
            $this->log->debug("Select grupo: ".$nombre);
            
            $sql_query_grupo = "SELECT G.id as id, G.nombre, G.url_api, G.url_web
                                FROM GRUPOS G
                                WHERE G.nombre = '$nombre'";
            try {
                $db = Database::getInstance();
                $dbCon = $db->getConnection();
                
             
                $stmt_grupo   = $dbCon->query($sql_query_grupo);
                $arrGrupo  = $stmt_grupo->fetchAll(PDO::FETCH_CLASS, "grupoVO");
                
                $dbCon = null;
                if(count($arrGrupo)>0){
                    return $arrGrupo[0];
                }
                return null;
            }catch(PDOException $e) {
                $this->log->error($e->getTraceAsString());
            }
        }
        
    }
    
?>