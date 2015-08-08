<?php

class DB{
	public function con(){
		try{
			return new pdo("mysql:host=localhost;dbname=feed", "root", "vertrigo");
		}catch(PDOException $msg){
			var_dump($msg);
		}
	}
}