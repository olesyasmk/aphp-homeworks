<?php

namespace App\Classes;

use App\Interfaces\FlyBehavior;

class FlyNoWay implements FlyBehavior
{
	public function fly()
	{
		echo "I can't fly\n";
	}
}
