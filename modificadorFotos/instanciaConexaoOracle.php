<?php
	require_once 'environmentsBackend.php';
	class DB{
		private static $instance;
		public static function getInstance(){
            $tns = "(DESCRIPTION =
            (ADDRESS_LIST =
            (ADDRESS = (PROTOCOL = TCP)(HOST = " . HOST . ")(PORT = " . PORT . "))
            )
            (CONNECT_DATA =
            (SERVICE_NAME =" . NAME . ")
            )
            )";
			if(!isset(self::$instance)){
				try {
                    self::$instance = new PDO('oci:dbname=' . $tns, USER, PASS);
                    self::$instance->setAttribute( PDO::ATTR_CASE, PDO::CASE_LOWER);
                    self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
			return self::$instance;
		}
				
		public static function prepare($sql){
			return self::getInstance()->prepare($sql);
		}
	}
?>