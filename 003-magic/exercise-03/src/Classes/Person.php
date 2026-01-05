<?php

namespace App\Classes;

class Person
{
	private $name;
	private $age;
	private $login;
	private $data = [];
	
	
	public function __construct( $name, $age, $login )
	{
		$this->name  = $name;
		$this->age   = $age;
		$this->login = $login;
	}
	
	
	public function __get( $name ) {
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}
		
		return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
	}
	
	public function __set( $name, $value )
	{
		if ( property_exists( $this, $name ) ) {
			$this->$name = $value;
		}
		else {
			$this->data[ $name ] = $value;
		}
	}
	
	public function __sleep()
	{
		return [ 'name', 'age', 'login' ];
	}
	
	public function __wakeup()
	{
		echo "The object has been restored.\n";
	}
	
	public function __toString()
	{
		return sprintf(
			"Person: [Name: %s, Age: %d, Login: %s]\n",
			$this->name,
			$this->age,
			$this->login
		);
	}
}
