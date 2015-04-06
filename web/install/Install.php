<?php

/**
 * Description of Install
 *
 * @author Fadi Amoudi
 */
class Install {
	protected $dbh;
	protected $host;
	protected $dbname;
	protected $user;
	protected $pass;
	protected $admname;
	protected $admmail;
	protected $theme;


	public function selectDB() {
		$this->host = (string)$_POST['host'];
		$this->dbname = (string)$_POST['dbname'];
		$this->user = (string)$_POST['user'];
		$this->pass = (string)$_POST['pass'];
		$this->admuser = (string)$_POST['admuser'];
		$this->admmail = (string)$_POST['admmail'];
		$this->theme = (string)$_POST['theme'];

		if ( empty($this->host) ) {
			return '<p class="alert alert-danger">Entrez la ressource de la base de données (host)</p>';
		}
		if ( empty($this->user) ) {
			return '<p class="alert alert-danger">Entrez l\'utilisateur de la base de données</p>';
		}
		if ( empty($this->dbname) ) {
			return '<p class="alert alert-danger">Entrez le nom de la base de données</p>';
		}

		try{
			$this->dbh = new \PDO("mysql:host={$this->host}", $this->user, $this->pass);
			$this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
			$dbname = str_replace("`","``",$this->dbname);
			$query= $this->dbh->query("SELECT IF(EXISTS (SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'), 'Yes','No')");
			$result= $query->fetch();

			if($result[0]=="Yes") {
				return false;
				exit;
			} else {
				$this->dbh->query("CREATE DATABASE IF NOT EXISTS $dbname");
				$this->dbh->query("use $dbname");
				return true;
			}	
		}
		catch(PDOException $ex){
			return '<p class="alert alert-danger">Connexion à la base de données impossible !</p>';
		}
	}
	
	public function installer($pathyml, $pathsql)
	{
		$data = $_POST;

		// Modification du fichier parameters.yml
		$fyml = fopen($pathyml, 'a');
		$param = file_get_contents('parameters.model.yml');
		$param = str_replace("{{host_name}}", $data['host'], $param);
		$param = str_replace("{{db_name}}", $data['dbname'], $param);
		$param = str_replace("{{user_name}}", $data['user'], $param);
		$param = str_replace("{{user_pass}}", $data['pass'], $param);
		if ($fyml !== false) {
		    ftruncate($fyml, 0);
		}
		fwrite($fyml, $param);
		fclose($fyml);
		
		// Modification et execution du fichier SQL
		$fsql = fopen($pathsql, 'a');
		$sql = file_get_contents('c0symfony.model.sql');
		$sql = str_replace("{{admuser}}", $data['admuser'], $sql);
		$sql = str_replace("{{admmail}}", $data['admmail'], $sql);
		if ($fsql !== false) {
		    ftruncate($fsql, 0);
		}
		fwrite($fsql, $sql);
		fclose($fsql);

		try {
			$this->dbh->exec($sql);
			return true;
		}
		catch(PDOException $ex){
			return false;
		}
	}
}