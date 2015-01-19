<?php
	class Database {
		private $host = "your_db_host";
		private $user = "your_db_username";
		private $pass = "your_db_password";
		private $db   = "your_database";
		
		private $dbh;
		private $error;
		
		private $stmt;
		
		function __construct() {
			$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db;
			
			$options = array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);
			
			try {
				$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
			}
			
			catch(PDOException $e) {
				$this->error = $e->getMessage();
				echo $this->error;
			}
		}
		
		function query($query) {
			$this->stmt = $this->dbh->prepare($query);
		}
		
		function bind($param, $value, $type = null) {
			if (is_null($type)) {
			  switch (true) {
			    case is_int($value):
			      $type = PDO::PARAM_INT;
			      break;
			    case is_bool($value):
			      $type = PDO::PARAM_BOOL;
			      break;
			    case is_null($value):
			      $type = PDO::PARAM_NULL;
			      break;
			    default:
			      $type = PDO::PARAM_STR;
			  }
		   }
		   
		   $this->stmt->bindValue($param, $value, $type);

		}
		
		function execute() {
			return $this->stmt->execute();
		}
		
		function resultset() {
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function single() {
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_ASSOC);
		}
		
		function rowCount() {
			return $this->stmt->rowCount();
		}
		
		function lastInsertId() {
			return $this->dbh->lastInsertId();
		}
		
		function beginTransaction() {
			return $this->dbh->beginTransaction();
		}
		
		function endTransaction() {
			return $this->dbh->commit();
		}
		
		function cancelTransaction() {
			return $this->dbh->rollBack();
		}
		
		function debugDumpParams() {
			return $this->stmt->debugDumpParams();
		}
	}