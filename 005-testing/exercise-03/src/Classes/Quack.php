<?php

namespace App\Classes;

use App\Interfaces\QuackBehavior;

class Quack implements QuackBehavior
{
	public function quack()
	{
		echo "Quack\n";
	}
}
