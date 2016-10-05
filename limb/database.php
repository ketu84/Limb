<?php

    class Database {
        private $log;
        
    	private $_connection;
    	
    	private static $_instance; //The single instance
    	private $_username = BD_USER;
    	private $_password = BD_PASSWORD;
    	private $_database = BD_NAME;
    	
    	
    
    	private function __construct() {
            $this->log = Logger::getLogger('com.hotelpene.limbBot.Database');
    		try {
    		    $this->_connection =  new PDO('mysql:host=localhost;dbname='.$this->_database, $this->_username, $this->_password,  array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
    		    $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		} catch(PDOException $e) {
                $this->log->error('Error al establecer la coneción a BD'. $e->getTraceAsString());
            }
            $this->log->debug('Conexión a BD establecida');
    	}
    	
    	public static function getInstance() {
    		if(!self::$_instance) { // If no instance then make one
    			self::$_instance = new self();
    		}
    		
    		return self::$_instance;
    	}
     
        public function getConnection() {
		    return $this->_connection;
	    }

    }
?>
