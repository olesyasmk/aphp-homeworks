<?php

namespace App\Classes;

use App\Interfaces\FlyBehavior;
use App\Interfaces\QuackBehavior;

abstract class Duck
{
	protected FlyBehavior   $flyBehavior;
	protected QuackBehavior $quackBehavior;
	
	public function __construct() {}
	
	abstract public function display();
	
	public function performFly()
	{
		$this->flyBehavior->fly();
	}
	
	public function performQuack()
	{
		$this->quackBehavior->quack();
	}
	
	public function swim()
	{
		echo "All ducks float, even decoys!\n";
	}
	
	public function setFlyBehavior( FlyBehavior $fb )
	{
		$this->flyBehavior = $fb;
	}
	
	public function setQuackBehavior( QuackBehavior $qb )
	{
		$this->quackBehavior = $qb;
	}
}
