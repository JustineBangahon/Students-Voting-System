<?php
class Config{
	public function __construct(){

	}
	function getConnection(){
       return new mysqli("localhost", "root", "", "a_voting_system");
    }
}
?>