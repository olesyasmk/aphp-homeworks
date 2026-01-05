<?php

namespace App\Classes;

use App\Interfaces\FlyBehavior;

class FlyWithWings implements FlyBehavior
{
	public function fly()
	{
		echo "I'm flying!!\n";
	}
}
