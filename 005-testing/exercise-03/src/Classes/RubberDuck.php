<?php

namespace App\Classes;

class RubberDuck extends Duck
{
	public function __construct()
	{
		$this->flyBehavior   = new FlyNoWay();
		$this->quackBehavior = new Squeak();
	}
	
	public function display()
	{
		echo "I'm a rubber ducky\n";
	}
}
