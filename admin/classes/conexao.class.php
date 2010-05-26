<?php
	class Conexao extends mysqli
	{
		private $host = 'localhost';
		private $user = 'root';
		private $pass = '';
		private $db = 'hton';
		
		public function __construct()
		{
			return parent::__construct($this->host, $this->user, $this->pass, $this->db);
		}
	}
?>