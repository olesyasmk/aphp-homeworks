<?php

namespace App\Classes;

use App\Interfaces\QuackBehavior;

class Squeak implements QuackBehavior
{
	public function quack()
	{
		echo "Squeak\n";
	}
}
